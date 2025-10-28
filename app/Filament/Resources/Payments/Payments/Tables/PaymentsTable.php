<?php

namespace App\Filament\Resources\Payments\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\CreateAction;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                TextColumn::make('order_id')
                    ->label('Order')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR', true)
                    ->sortable(),

                TextColumn::make('method')
                    ->label('Method')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('gateway')
                    ->label('Provider')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'verified' => 'success',
                        'pending'  => 'warning',
                        'failed'   => 'danger',
                        default    => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending'  => 'Pending',
                    'verified' => 'Verified',
                    'failed'   => 'Failed',
                ]),

                SelectFilter::make('gateway')->label('Provider')->options([
                    'manual'   => 'Manual',
                    'midtrans' => 'Midtrans',
                ]),

                TernaryFilter::make('has_proof')
                    ->label('With Proof')
                    ->placeholder('All')
                    ->trueLabel('With proof')
                    ->falseLabel('Without proof')
                    ->queries(
                        true: fn ($q) => $q->whereNotNull('proof_url')->where('proof_url', '!=', ''),
                        false: fn ($q) => $q->whereNull('proof_url')->orWhere('proof_url', ''),
                        blank: fn ($q) => $q
                    ),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),

                Action::make('markPaid')
                    ->label('Mark as Paid')
                    ->visible(fn ($record) => $record->status !== 'verified')
                    ->action(function ($record) {
                        $record->update([
                            'status'  => 'verified',
                            'paid_at' => now(),
                        ]);
                    }),
                    
                Action::make('uploadProof')
                    ->label('Upload Proof')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('proof_url')
                            ->label('Bukti Transfer')
                            ->disk('public')
                            ->directory('payments/proofs')
                            ->visibility('public')
                            ->preserveFilenames(false)
                            ->getUploadedFileNameForStorageUsing(
                                fn ($file) => uniqid() . '.' . $file->getClientOriginalExtension()
                            )
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'proof_url' => $data['proof_url'],
                            'gateway'   => $record->gateway ?: 'manual',
                            // biarin status sesuai kondisi sekarang; atau:
                            // 'status' => $record->status === 'verified' ? 'verified' : 'pending',
                        ]);
                    }),

                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('markPaidBulk')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-check-badge')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            if ($record->status !== 'paid') {
                                $record->update([
                                    'status'  => 'paid',
                                    'paid_at' => now(),
                                ]);
                            }
                        }
                    }),
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->defaultSort('id', 'desc');
    }
}
