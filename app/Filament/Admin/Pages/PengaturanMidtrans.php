<?php

namespace App\Filament\Admin\Pages;

use App\Models\MidtransSetting;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;
use BackedEnum;

class PengaturanMidtrans extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | UnitEnum | null $navigationGroup = 'Pengaturan Sistem';
    protected static ?string $navigationLabel = 'Midtrans Key';
    protected static ?string $title = 'Pengaturan Midtrans';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-key';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = MidtransSetting::first() ?? MidtransSetting::create();

        $this->form->fill([
            'server_key'    => $setting->server_key,
            'client_key'    => $setting->client_key,
            'is_production' => $setting->is_production,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('server_key')
                            ->label('Server Key')
                            ->password()
                            ->revealable()
                            ->required(),

                        TextInput::make('client_key')
                            ->label('Client Key')
                            ->password()
                            ->revealable()
                            ->required(),

                        Toggle::make('is_production')
                            ->label('Gunakan Mode Production?')
                            ->helperText('Jika dimatikan, sistem akan menggunakan Sandbox'),
                    ])
                    ->columnSpanFull()
                    ->extraAttributes(['style' => 'margin-bottom: 2rem;']),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pengaturan Midtrans')
                ->schema([
                    Form::make()
                        ->schema([
                            TextInput::make('server_key')
                                ->label('Server Key')
                                ->password()
                                ->revealable()
                                ->required(),

                            TextInput::make('client_key')
                                ->label('Client Key')
                                ->password()
                                ->revealable()
                                ->required(),

                            Toggle::make('is_production')
                                ->label('Gunakan Mode Production?')
                                ->helperText('Jika dimatikan, sistem akan menggunakan Sandbox'),
                        ])
                        ->statePath('data')
                        ->livewireSubmitHandler('simpan')
                        ->footer([
                            Action::make('simpan')
                                ->label('Simpan')
                                ->color('primary')
                                ->action('simpan'),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public function simpan(): void
    {
        $state = $this->form->getState();

        $setting = MidtransSetting::first() ?? new MidtransSetting();
        $setting->server_key    = $state['server_key'];
        $setting->client_key    = $state['client_key'];
        $setting->is_production = $state['is_production'] ?? false;
        $setting->save();

        Notification::make()
            ->title('Konfigurasi Midtrans berhasil diperbarui.')
            ->success()
            ->send();
    }
}
