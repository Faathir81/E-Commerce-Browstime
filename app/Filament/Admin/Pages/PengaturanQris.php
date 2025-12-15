<?php

namespace App\Filament\Admin\Pages;

use App\Models\QrisSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;

class PengaturanQris extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | UnitEnum | null $navigationGroup = 'Pengaturan Sistem';
    protected static ?string $navigationLabel = 'QRIS Static';
    protected static ?string $title = 'Pengaturan QRIS Static';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-qr-code';

    public ?array $data = [];

    public function mount(): void
    {
        $qrisSetting = QrisSetting::first() ?? QrisSetting::create();
        
        $this->form->fill([
            'gambar_qris' => $qrisSetting->gambar_qris,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
        ->schema([
            Section::make()
                ->schema([
                    FileUpload::make('gambar_qris')
                        ->image()
                        ->disk('public')
                        ->directory('qris')
                        ->visibility('public')
                        ->required(),
                ])
                ->columnSpanFull()
                ->extraAttributes(['style' => 'margin-bottom: 2rem;']),
        ])
        ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pengaturan QRIS')
                ->schema([
                    Form::make()
                        ->schema([
                            FileUpload::make('gambar_qris')
                                ->image()
                                ->disk('public')
                                ->directory('qris')
                                ->visibility('public')
                                ->required(),
                        ])
                        ->statePath('data')
                        ->livewireSubmitHandler('simpan')
                        ->footer([
                            Action::make('simpan')
                                ->label('Simpan')
                                ->action('simpan'),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public function simpan(): void
    {
        $data = $this->form->getState();
        
        $qrisSetting = QrisSetting::first() ?? new QrisSetting();
        $qrisSetting->gambar_qris = $data['gambar_qris'];
        $qrisSetting->save();

        Notification::make()
            ->title('QRIS berhasil diperbarui.')
            ->success()
            ->send();
    }
}
