<?php

namespace App\Livewire\Checkout;

use App\Models\AlamatPengiriman;
use App\Models\AkunBank;
use App\Models\DetailPesanan;
use App\Models\MetodePembayaran;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\QrisSetting;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\WilayahPengiriman;
use App\Support\OrderNotifier;
use App\Services\RajaOngkirService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.checkout')]
class CheckoutWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;
    public array $steps = [
        ['label' => 'Customer', 'index' => 1],
        ['label' => 'Shipping', 'index' => 2],
        ['label' => 'Payment', 'index' => 3],
        ['label' => 'Confirm', 'index' => 4],
    ];

    public string $nama_penerima = '';
    public string $no_hp = '';
    public ?string $email = null;
    public ?int $wilayah_pengiriman_id = null;
    public string $alamat_lengkap = '';
    public ?string $kode_pos = null;
    public ?string $catatan = '';

    public float $ongkir = 0;
    public int $shipping_fee = 0;
    public ?string $etd = null;
    public ?int $etd_days = null;
    public float $total_berat = 0.0;
    public int $total_weight_gram = 0;

    /** @var array<int, array<string, mixed>> */
    public array $cartItems = [];
    public int $subtotal = 0;
    public int $total = 0;
    public int $totalQuantity = 0;

    public ?string $paymentMethod = null; // transfer | qris | midtrans
    public ?int $akun_bank_id = null;
    public ?int $qris_setting_id = null;
    public ?string $midtrans_order_id = null;

    /** @var array<int, array<string, mixed>> */
    public array $shippingZones = [];
    /** @var array<int, array<string, mixed>> */
    public array $paymentMethods = [];
    /** @var array<int, array<string, mixed>> */
    public array $banks = [];
    /** @var array<int, array<string, mixed>> */
    public array $qrisSettings = [];
    /** @var array<int, array<string, mixed>> */
    public array $provinsis = [];
    /** @var array<int, array<string, mixed>> */
    public array $kotas = [];
    /** @var array<int, array<string, mixed>> */
    public array $kecamatans = [];

    public ?int $provinsi_id = null;
    public ?int $kota_id = null;
    public ?int $kecamatan_id = null;

    public $payment_proof;

    public function mount()
    {
        $this->loadCart();

        if (empty($this->cartItems)) {
            $this->redirectRoute('cart.index');
            return;
        }

        $this->shippingZones = WilayahPengiriman::where('aktif', true)
            ->orderBy('nama')
            ->get()
            ->toArray();

        $this->provinsis = Provinsi::whereIn(
                'id',
                WilayahPengiriman::where('aktif', true)->pluck('provinsi_id')->unique()
            )
            ->orderBy('nama')
            ->get()
            ->toArray();

        $this->paymentMethods = MetodePembayaran::where('aktif', true)
            ->orderBy('id')
            ->get()
            ->toArray();

        $this->banks = AkunBank::where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('nama_bank')
            ->get()
            ->toArray();

        $this->qrisSettings = QrisSetting::query()->get()->toArray();

        $this->prefillCustomer();
        $this->recalculateTotals();

        if ($this->kecamatan_id) {
            $this->calculateShippingFee();
        } elseif ($this->wilayah_pengiriman_id) {
            $this->calculateShipping();
        } else {
            // Ensure cascading dropdowns are hydrated when customer has a saved province/city
            $this->hydrateLocationFromIds();
        }

        // Safety: if province already selected but child lists empty (e.g., after validation), repopulate.
        $this->ensureLocationOptions();
    }

    public function render()
    {
        $this->ensureLocationOptions();
        return view('livewire.checkout.wizard', [
            'stepView' => $this->stepView,
            'stepConfig' => $this->stepConfig,
            'displayCartItems' => $this->displayCartItems,
            'formattedSubtotal' => $this->formattedSubtotal,
            'formattedTotal' => $this->formattedTotal,
            'formattedShippingFee' => $this->formattedShippingFee,
            'provinsis' => $this->provinsis,
            'kotas' => $this->kotas,
            'kecamatans' => $this->kecamatans,
            'stepData' => $this->stepData,
            'selectedZone' => $this->selectedZone,
            'selectedMethod' => $this->selectedMethod,
            'selectedBank' => $this->selectedBank,
        ]);
    }

    public function updatedWilayahPengirimanId(): void
    {
        $this->calculateShipping();
    }

    public function updatedProvinsiId($value): void
    {
        if (! $value) {
            $this->provinsi_id = null;
        }

        $this->kotas = $value
            ? $this->getSupportedKotas((int) $value)
            : [];

        $this->kota_id = null;
        $this->kecamatans = [];
        $this->kecamatan_id = null;
        $this->wilayah_pengiriman_id = null;
        $this->resetShippingState();
        $this->recalculateTotals();
    }

    public function updatedKotaId($value): void
    {
        if (! $value) {
            $this->kota_id = null;
            $this->wilayah_pengiriman_id = null;
            $this->kecamatans = [];
            $this->kecamatan_id = null;
            $this->resetShippingState();
            $this->recalculateTotals();
            return;
        }

        $this->kecamatans = $this->getSupportedKecamatans((int) $value);
        $this->kecamatan_id = null;
        $this->mapWilayahFromKota();
        $this->calculateShipping();
    }

    public function updatedKecamatanId($value): void
    {
        $this->updatedSelectedDistrict($value);
    }

    public function updatedPaymentMethod(): void
    {
        if ($this->paymentMethod !== 'transfer') {
            $this->akun_bank_id = null;
        }
        if ($this->paymentMethod !== 'qris') {
            $this->qris_setting_id = null;
        }
        if ($this->paymentMethod === 'midtrans') {
            $this->payment_proof = null;
        }

        // Clear errors and revalidate dependent fields so UI updates instantly
        $this->resetErrorBag(['akun_bank_id', 'qris_setting_id', 'payment_proof']);

        if ($this->paymentMethod === 'qris' && ! $this->qris_setting_id && ! empty($this->qrisSettings)) {
            $this->qris_setting_id = $this->qrisSettings[0]['id'] ?? null;
        }
    }

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validate($this->stepOneRules());
            $this->calculateShippingFee();
        } elseif ($this->step === 2) {
            $this->validate($this->stepTwoRules());
        } elseif ($this->step === 3) {
            $this->validate($this->stepThreeRules());
        }

        $this->step = min(4, $this->step + 1);
    }

    public function previousStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function calculateShipping(): void
    {
        $this->calculateShippingFee();
    }

    public function placeOrder()
    {
        $this->loadCart();
        if (empty($this->cartItems)) {
            return $this->redirectRoute('cart.index');
        }

        $this->validate(array_merge(
            $this->stepOneRules(),
            $this->stepTwoRules(),
            $this->stepThreeRules()
        ));

        $etaDateTime = $this->computeEtaDateTime();
        $subtotal = $this->subtotal;
        $ongkir = (int) ($this->shipping_fee ?: $this->ongkir);
        $total = $subtotal + $ongkir;

        $pesanan = DB::transaction(function () use ($subtotal, $ongkir, $total, $etaDateTime) {
            $guestEmail = Auth::check() ? null : $this->email;

            $pelanggan = $this->persistCustomerProfile();
            $this->persistShippingAddress($pelanggan);

            $pesanan = Pesanan::create([
                'kode' => $this->generateOrderCode(),
                'user_id' => Auth::id(),
                'guest_email' => $guestEmail,
                'wilayah_pengiriman_id' => $this->wilayah_pengiriman_id,
                'kecamatan_id' => $this->kecamatan_id,
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total' => $total,
                'status' => Pesanan::STATUS_PENDING,
                'eta' => $etaDateTime,
            ]);

            foreach ($this->cartItems as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item['id'],
                    'qty' => $item['quantity'],
                    'harga' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            $metode = $this->paymentMethod ?? 'transfer';
            $midtransOrderId = $metode === 'midtrans'
                ? $this->generateMidtransOrderId($pesanan->kode)
                : null;

            Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'metode' => $metode,
                'akun_bank_id' => $metode === 'transfer' ? $this->akun_bank_id : null,
                'qris_setting_id' => $metode === 'qris' ? $this->qris_setting_id : null,
                'jumlah' => $total,
                'status' => 'pending',
                'midtrans_order_id' => $midtransOrderId,
            ]);

            $pembayaran = $pesanan->pembayaran()->first();
            if ($pembayaran && in_array($metode, ['transfer', 'qris'], true) && $this->payment_proof) {
                $path = $this->payment_proof->store('payment_proofs', 'public');
                $pembayaran->update([
                    'bukti_bayar' => $path,
                    'status' => 'menunggu_verifikasi',
                ]);
                $this->payment_proof = null;
            }

            return $pesanan;
        });

        OrderNotifier::sendOrderCreated($pesanan);

        // TODO: Persist catatan once order notes column available.
        session()->forget('cart');
        $this->dispatch('cartUpdated', 0);

        return $this->redirectRoute('order.success', ['kode' => $pesanan->kode]);
    }

    protected function stepOneRules(): array
    {
        $rules = [
            'nama_penerima' => ['required', 'string', 'min:3'],
            'no_hp' => ['required', 'string', 'min:6'],
            'provinsi_id' => ['required', 'integer', 'exists:provinsi,id'],
            'kota_id' => ['required', 'integer', 'exists:kota,id'],
            'kecamatan_id' => ['required', 'integer', 'exists:kecamatan,id'],
            'alamat_lengkap' => ['required', 'string', 'min:8'],
            'kode_pos' => ['required', 'string', 'min:4', 'max:10'],
            'catatan' => ['nullable', 'string'],
        ];

        $rules['email'] = Auth::check()
            ? ['nullable', 'email']
            : ['required', 'email'];

        return $rules;
    }

    protected function stepTwoRules(): array
    {
        return [
            'kota_id' => ['required', 'exists:kota,id'],
            'kecamatan_id' => ['nullable', 'exists:kecamatan,id'],
            'wilayah_pengiriman_id' => ['required', 'exists:wilayah_pengiriman,id'],
            'ongkir' => ['required', 'numeric', 'min:0'],
            'shipping_fee' => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function stepThreeRules(): array
    {
        $availableCodes = $this->methodCodes();

        $rules = [
            'paymentMethod' => ['required', 'in:' . implode(',', $availableCodes)],
        ];

        if ($this->paymentMethod === 'transfer') {
            $rules['akun_bank_id'] = ['required', 'exists:akun_banks,id'];
        }

        if ($this->paymentMethod === 'qris') {
            $rules['qris_setting_id'] = ['required', 'exists:qris_settings,id'];
        }

        if (in_array($this->paymentMethod, ['transfer', 'qris'], true)) {
            $rules['payment_proof'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];
        }

        return $rules;
    }

    protected function prefillCustomer(): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();
        $pelanggan = Pelanggan::with(['alamatPengiriman' => function ($query) {
            $query->latest();
        }])->where('user_id', $user->id)->first();

        if ($pelanggan) {
            $this->nama_penerima = $pelanggan->nama ?? $this->nama_penerima;
            $this->no_hp = $pelanggan->no_hp ?? $this->no_hp;
            $this->email = $pelanggan->email ?? $this->email;

            /** @var AlamatPengiriman|null $alamat */
            $alamat = $pelanggan->alamatPengiriman->first();
            if ($alamat) {
                $this->alamat_lengkap = $alamat->alamat_lengkap ?? '';
                $this->kode_pos = $alamat->kode_pos ?? null;
                $this->wilayah_pengiriman_id = $alamat->wilayah_pengiriman_id;
                $this->hydrateLocationFromWilayah();
            }
        } else {
            $this->nama_penerima = $user->name ?? $this->nama_penerima;
            $this->email = $user->email ?? $this->email;
        }
    }

    /**
     * Hitung ETA sederhana: gunakan ETD (hari) dari RajaOngkir, default +2 hari dari sekarang.
     */
    protected function computeEtaDateTime(): \Carbon\Carbon
    {
        $days = $this->etd_days ?? $this->parseEtdDays($this->etd) ?? 2;

        return Carbon::now('Asia/Jakarta')
            ->startOfDay()
            ->addDays($days)
            ->setTimezone('UTC');
    }

    /**
     * Ambil angka hari dari string ETD RajaOngkir (mis. "2-3", "2 HARI").
     */
    protected function parseEtdDays(?string $etd): ?int
    {
        if (! $etd) {
            return null;
        }

        // Cari angka pertama saja.
        if (preg_match('/(\\d+)/', $etd, $matches)) {
            $days = (int) $matches[1];
            return $days > 0 ? $days : null;
        }

        return null;
    }

    protected function mapWilayahFromKota(): void
    {
        if (! $this->kota_id) {
            return;
        }

        $zone = WilayahPengiriman::where('kota_id', $this->kota_id)
            ->where('aktif', true)
            ->first();

        if (! $zone) {
            $this->wilayah_pengiriman_id = null;
            $this->resetShippingState();
            $this->addError('kota_id', 'Kota belum didukung.');
            return;
        }

        $this->wilayah_pengiriman_id = $zone->id;
        $this->provinsi_id = $zone->provinsi_id;
    }

    protected function hydrateLocationFromWilayah(): void
    {
        if (! $this->wilayah_pengiriman_id) {
            return;
        }

        $zone = WilayahPengiriman::where('id', $this->wilayah_pengiriman_id)
            ->where('aktif', true)
            ->first();
        if (! $zone) {
            $this->wilayah_pengiriman_id = null;
            $this->resetShippingState();
            return;
        }

        $this->provinsi_id = $zone->provinsi_id;
        $this->kota_id = $zone->kota_id;

        $this->hydrateLocationFromIds();
    }

    protected function hydrateLocationFromIds(): void
    {
        if ($this->provinsi_id) {
            $this->kotas = $this->getSupportedKotas($this->provinsi_id);
        }

        if ($this->kota_id) {
            $this->kecamatans = $this->getSupportedKecamatans($this->kota_id);
        }
    }

    protected function ensureLocationOptions(): void
    {
        if ($this->provinsi_id && empty($this->kotas)) {
            $this->kotas = $this->getSupportedKotas((int) $this->provinsi_id);
        }

        if ($this->kota_id && empty($this->kecamatans)) {
            $this->kecamatans = $this->getSupportedKecamatans((int) $this->kota_id);
        }
    }

    protected function getSupportedKotas(int $provinsiId): array
    {
        $supportedKotaIds = WilayahPengiriman::where('provinsi_id', $provinsiId)
            ->where('aktif', true)
            ->pluck('kota_id')
            ->unique()
            ->toArray();

        return Kota::whereIn('id', $supportedKotaIds)->orderBy('nama')->get()->toArray();
    }

    protected function getSupportedKecamatans(int $kotaId): array
    {
        return Kecamatan::where('kota_id', $kotaId)
            ->orderBy('nama')
            ->get()
            ->toArray();
    }

    protected function persistCustomerProfile(): Pelanggan
    {
        if (Auth::check()) {
            $user = Auth::user();

            $pelanggan = Pelanggan::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $this->nama_penerima,
                    'email' => $this->email ?: $user->email,
                    'no_hp' => $this->no_hp,
                ]
            );

            $pelanggan->update([
                'nama' => $this->nama_penerima,
                'email' => $this->email ?: $user->email,
                'no_hp' => $this->no_hp,
            ]);

            return $pelanggan;
        }

        $email = $this->email ? trim(strtolower($this->email)) : null;
        $pelanggan = $email
            ? Pelanggan::whereRaw('LOWER(email) = ?', [$email])->first()
            : null;

        if ($pelanggan) {
            $pelanggan->update([
                'nama' => $this->nama_penerima,
                'no_hp' => $this->no_hp,
            ]);
            return $pelanggan;
        }

        return Pelanggan::create([
            'nama' => $this->nama_penerima,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
        ]);
    }

    protected function persistShippingAddress(Pelanggan $pelanggan): ?AlamatPengiriman
    {
        if (! $this->wilayah_pengiriman_id) {
            return null;
        }

        return $pelanggan->alamatPengiriman()->create([
            'nama_penerima' => $this->nama_penerima,
            'no_hp' => $this->no_hp,
            'alamat_lengkap' => $this->alamat_lengkap,
            'kode_pos' => $this->kode_pos,
            'wilayah_pengiriman_id' => $this->wilayah_pengiriman_id,
        ]);
    }

    protected function recalculateTotals(): void
    {
        $this->subtotal = collect($this->cartItems)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $effectiveShipping = (int) ($this->shipping_fee ?: $this->ongkir);
        $this->ongkir = $effectiveShipping;
        $this->total = $this->subtotal + $effectiveShipping;
    }

    protected function loadCart(): void
    {
        $sessionCart = session('cart', []);
        if (empty($sessionCart)) {
            $this->cartItems = [];
            $this->subtotal = 0;
            $this->totalQuantity = 0;
            $this->total = 0;
            $this->resetShippingState();
            return;
        }

        $products = Produk::whereIn('id', array_keys($sessionCart))->get()->keyBy('id');

        $items = [];
        foreach ($sessionCart as $productId => $qty) {
            if (! isset($products[$productId])) {
                continue;
            }

            $product = $products[$productId];
            $items[] = [
                'id' => $product->id,
                'name' => $product->nama,
                'price' => (int) $product->harga,
                'quantity' => (int) $qty,
                'image_url' => $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/80x80',
                'production_time' => (int) ($product->waktu_produksi ?? 0),
                'weight_gram' => (int) ($product->berat ?? 0),
            ];
        }

        $this->cartItems = $items;
        $this->subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $this->totalQuantity = collect($items)->sum('quantity');
        $effectiveShipping = (int) ($this->shipping_fee ?: $this->ongkir);
        $this->ongkir = $effectiveShipping;
        $this->total = $this->subtotal + $effectiveShipping;
    }

    protected function generateOrderCode(): string
    {
        do {
            $code = 'ORD-' . strtoupper(Str::random(6));
        } while (Pesanan::where('kode', $code)->exists());

        return $code;
    }

    protected function generateMidtransOrderId(string $kodePesanan): string
    {
        return 'MID-' . $kodePesanan . '-' . strtoupper(Str::random(4));
    }

    protected function methodCodes(): array
    {
        $codes = collect($this->paymentMethods)->pluck('kode')->filter()->values()->all();
        if (empty($codes)) {
            $codes = ['transfer', 'qris', 'midtrans'];
        }

        return $codes;
    }

    protected function calculateCartWeightGram(): int
    {
        $items = collect($this->cartItems);
        $missingWeightIds = $items
            ->filter(fn ($item) => ! isset($item['weight_gram']))
            ->pluck('id')
            ->filter()
            ->unique()
            ->all();

        $weights = [];
        if (! empty($missingWeightIds)) {
            $weights = Produk::whereIn('id', $missingWeightIds)->pluck('berat', 'id')->toArray();
        }

        return $items->sum(function ($item) use ($weights) {
            $qty = (int) ($item['quantity'] ?? 0);
            $weight = (int) ($item['weight_gram'] ?? ($weights[$item['id']] ?? 0));
            return $qty * $weight;
        });
    }

    protected function calculateShippingFee(): void
    {
        $this->mapWilayahFromKota();

        if (! $this->wilayah_pengiriman_id) {
            $this->resetShippingState();
            $this->recalculateTotals();
            return;
        }

        if (! $this->kecamatan_id) {
            $this->resetShippingState();
            $this->recalculateTotals();
            return;
        }

        $this->total_weight_gram = $this->calculateCartWeightGram();
        $this->total_berat = round($this->total_weight_gram / 1000, 2);

        if ($this->total_weight_gram <= 0) {
            $this->resetShippingState();
            $this->addError('ongkir', 'Berat produk belum tersedia.');
            $this->recalculateTotals();
            return;
        }

        $this->resetErrorBag(['ongkir']);
        $courier = config('services.rajaongkir.courier', 'jne');

        try {
            $result = app(RajaOngkirService::class)
                ->calculateDomesticCost((int) $this->kecamatan_id, $this->total_weight_gram, $courier);

            $this->shipping_fee = (int) ($result['cost'] ?? 0);
            $this->etd = $result['etd'] ?? null;
            $this->etd_days = $this->parseEtdDays($this->etd);
        } catch (\Throwable $th) {
            logger()->warning('RajaOngkir cost calculation failed', [
                'error' => $th->getMessage(),
                'destination_subdistrict' => $this->kecamatan_id,
            ]);
            $this->addError('ongkir', 'Gagal menghitung ongkir, silakan coba lagi.');
            $this->resetShippingState();
        }

        $this->recalculateTotals();
    }

    public function updatedSelectedDistrict($value = null): void
    {
        if (! $value) {
            $this->kecamatan_id = null;
            $this->resetShippingState();
            $this->recalculateTotals();
            return;
        }

        $this->kecamatan_id = (int) $value;
        $this->calculateShippingFee();
    }

    protected function resetShippingState(): void
    {
        $this->shipping_fee = 0;
        $this->ongkir = 0;
        $this->etd = null;
        $this->etd_days = null;
        $this->total_berat = 0.0;
        $this->total_weight_gram = 0;
    }

    public function isStepActive(int $index): bool
    {
        return $this->step >= $index;
    }

    public function getStepViewProperty(): string
    {
        return match ($this->step) {
            1 => 'livewire.checkout.steps.step-1',
            2 => 'livewire.checkout.steps.step-2',
            3 => 'livewire.checkout.steps.step-3',
            default => 'livewire.checkout.steps.step-4',
        };
    }

    public function getStepConfigProperty(): array
    {
        return match ($this->step) {
            1 => [
                'submit_action' => 'nextStep',
                'submit_label' => 'Continue to Shipping',
                'show_back' => false,
                'back_action' => null,
                'back_label' => 'Back',
            ],
            2 => [
                'submit_action' => 'nextStep',
                'submit_label' => 'Continue to Payment',
                'show_back' => true,
                'back_action' => 'previousStep',
                'back_label' => 'Back',
            ],
            3 => [
                'submit_action' => 'nextStep',
                'submit_label' => 'Confirm Payment',
                'show_back' => true,
                'back_action' => 'previousStep',
                'back_label' => 'Back',
            ],
            default => [
                'submit_action' => 'placeOrder',
                'submit_label' => 'Place Order',
                'show_back' => true,
                'back_action' => 'previousStep',
                'back_label' => 'Back',
            ],
        };
    }

    public function getDisplayCartItemsProperty(): array
    {
        return collect($this->cartItems)->map(function ($item) {
            $subtotal = (int) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);

            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'image_url' => $item['image_url'] ?? 'https://via.placeholder.com/120x120',
                'price_formatted' => formatRupiah($item['price'] ?? 0, false),
                'subtotal_formatted' => formatRupiah($subtotal, false),
            ];
        })->values()->all();
    }

    public function getFormattedSubtotalProperty(): string
    {
        return formatRupiah($this->subtotal, false);
    }

    public function getFormattedTotalProperty(): string
    {
        return formatRupiah($this->total, false);
    }

    public function getFormattedShippingFeeProperty(): string
    {
        if ($this->shipping_fee > 0 || $this->ongkir > 0) {
            $fee = (int) ($this->shipping_fee ?: $this->ongkir);
            return 'Rp ' . formatRupiah($fee, false);
        }

        return 'Select zone';
    }

    public function getFormattedSubtotalLabelProperty(): string
    {
        return 'Rp ' . formatRupiah($this->subtotal ?? 0, false);
    }

    public function getFormattedOngkirLabelProperty(): string
    {
        return 'Rp ' . formatRupiah(($this->ongkir ?? 0), false);
    }

    public function getFormattedTotalLabelProperty(): string
    {
        return 'Rp ' . formatRupiah($this->total ?? 0, false);
    }

    public function getFormattedTotalWeightProperty(): string
    {
        return $this->total_berat > 0
            ? formatWeight($this->total_berat)
            : 'Pending calculation';
    }

    public function getFormattedEtdProperty(): string
    {
        return $this->etd ?: 'Pending calculation';
    }

    public function getSelectedZoneProperty(): ?array
    {
        return collect($this->shippingZones)->firstWhere('id', $this->wilayah_pengiriman_id) ?: null;
    }

    public function getSelectedZoneNameProperty(): string
    {
        return $this->selectedZone['nama'] ?? 'Not selected';
    }

    public function getSelectedMethodProperty(): ?array
    {
        return collect($this->paymentMethods)->firstWhere('kode', $this->paymentMethod) ?: null;
    }

    public function getSelectedBankProperty(): ?array
    {
        if (! $this->akun_bank_id) {
            return null;
        }

        return collect($this->banks)->firstWhere('id', $this->akun_bank_id) ?: null;
    }

    public function getSelectedQrisProperty(): ?array
    {
        if ($this->qris_setting_id) {
            return collect($this->qrisSettings)->firstWhere('id', $this->qris_setting_id) ?: null;
        }

        return $this->qrisSettings[0] ?? null;
    }

    public function getIsPaymentProofImageProperty(): bool
    {
        if (! $this->payment_proof) {
            return false;
        }

        $ext = strtolower($this->payment_proof->getClientOriginalExtension());
        return in_array($ext, ['jpg', 'jpeg', 'png']);
    }

    public function getPaymentProofNameProperty(): ?string
    {
        return $this->payment_proof?->getClientOriginalName();
    }

    public function getPaymentProofPreviewUrlProperty(): ?string
    {
        return $this->payment_proof ? $this->payment_proof->temporaryUrl() : null;
    }

    public function getFormattedTotalWeightNoteProperty(): string
    {
        return $this->total_berat > 0
            ? 'Based on total weight: ' . formatWeight($this->total_berat)
            : 'Based on total weight: Pending calculation';
    }

    public function getStepDataProperty(): array
    {
        return match ($this->step) {
            1 => [
                'provinsis' => $this->provinsis,
                'kotas' => $this->kotas,
                'kecamatans' => $this->kecamatans,
                'provinsi_id' => $this->provinsi_id,
                'kota_id' => $this->kota_id,
                'kecamatan_id' => $this->kecamatan_id,
                'nama_penerima' => $this->nama_penerima,
                'no_hp' => $this->no_hp,
                'email' => $this->email,
                'alamat_lengkap' => $this->alamat_lengkap,
                'kode_pos' => $this->kode_pos,
                'catatan' => $this->catatan,
            ],
            2 => [
                'shippingZones' => $this->shippingZones,
                'wilayah_pengiriman_id' => $this->wilayah_pengiriman_id,
                'formattedShippingFee' => $this->formattedShippingFee,
                'formattedTotalWeight' => $this->formattedTotalWeight,
                'formattedEtd' => $this->formattedEtd,
                'selectedZoneName' => $this->selectedZoneName,
            ],
            3 => [
                'paymentMethods' => $this->paymentMethods,
                'paymentMethod' => $this->paymentMethod,
                'banks' => $this->banks,
                'qrisSettings' => $this->qrisSettings,
                'payment_proof' => $this->payment_proof,
                'akun_bank_id' => $this->akun_bank_id,
                'qris_setting_id' => $this->qris_setting_id,
                'selectedQris' => $this->selectedQris,
                'isPaymentProofImage' => $this->isPaymentProofImage,
                'paymentProofName' => $this->paymentProofName,
                'paymentProofPreviewUrl' => $this->paymentProofPreviewUrl,
            ],
            default => [
                'shippingZones' => $this->shippingZones,
                'wilayah_pengiriman_id' => $this->wilayah_pengiriman_id,
                'paymentMethods' => $this->paymentMethods,
                'paymentMethod' => $this->paymentMethod,
                'banks' => $this->banks,
                'qrisSettings' => $this->qrisSettings,
                'akun_bank_id' => $this->akun_bank_id,
                'qris_setting_id' => $this->qris_setting_id,
                'nama_penerima' => $this->nama_penerima,
                'no_hp' => $this->no_hp,
                'email' => $this->email,
                'alamat_lengkap' => $this->alamat_lengkap,
                'catatan' => $this->catatan,
                'selectedZone' => $this->selectedZone,
                'selectedMethod' => $this->selectedMethod,
                'selectedBank' => $this->selectedBank,
                'formattedSubtotalLabel' => $this->formattedSubtotalLabel,
                'formattedOngkirLabel' => $this->formattedOngkirLabel,
                'formattedTotalLabel' => $this->formattedTotalLabel,
                'formattedTotalWeightNote' => $this->formattedTotalWeightNote,
                'formattedEtd' => $this->formattedEtd,
                'kode_pos' => $this->kode_pos,
            ],
        };
    }
}
