<?php

namespace App\Filament\Resources\Materials\Tables;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;

use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use App\Models\Material;
use App\Models\MaterialStock;


class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->withSum('stocks as current_stock', 'qty');
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('min_qty')
                    ->label('Min')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        if ($state === null) return '-';
                        $n = (float) $state;

                        // bulat → "5"
                        if (floor($n) == $n) return (string) (int) $n;

                        // pecahan → "4.5" (tanpa ribuan & tanpa nol di belakang)
                        return rtrim(rtrim(number_format($n, 3, '.', ''), '0'), '.');
                    }),

                TextColumn::make('current_stock')
                    ->label('Stock')
                    ->state(fn (Material $record) => $record->current_stock ?? 0)
                    ->formatStateUsing(function ($state /*, Material $record */) {
                        $n = (float) $state;

                        if (floor($n) == $n) return (string) (int) $n;
                        return rtrim(rtrim(number_format($n, 3, '.', ''), '0'), '.');

                        // Kalo mau tempel satuan: return ($s.' '.$record->unit);
                    })
                    ->badge()
                    ->color(fn (Material $r) => ($r->current_stock ?? 0) < $r->min_qty ? 'danger' : 'success'),

                TextColumn::make('unit')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('below_minimum')
                    ->label('Below Minimum')
                    ->query(fn (Builder $q) =>
                        $q->having('current_stock', '<', DB::raw('min_qty'))
                    ),
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),

                // Row Action: Adjust Stock
                Action::make('adjust_stock')
                    ->label('Adjust Stock')
                    ->icon('heroicon-o-adjustments-vertical')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'in'     => 'Increase',
                                'out'    => 'Decrease',
                                'adjust' => 'Adjust (±)',
                            ])
                            ->default('in')
                            ->required(),

                        Forms\Components\Select::make('reason')
                            ->label('Reason')
                            ->options([
                                'usage'      => 'Usage',
                                'expired'    => 'Expired',
                                'damaged'    => 'Damaged',
                                'correction' => 'Correction',
                            ])
                            ->default('usage')
                            ->required(),

                        Forms\Components\TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->required(),

                        Forms\Components\Textarea::make('note')
                            ->rows(2)
                            ->maxLength(191),
                    ])
                    ->action(function (Material $record, array $data) {
                        $qty = (float) $data['qty'];

                        if ($data['type'] === 'in')  $qty =  abs($qty);
                        if ($data['type'] === 'out') $qty = -abs($qty);
                        // 'adjust' dibiarkan sesuai input (+/-)

                        MaterialStock::create([
                            'material_id' => $record->id,
                            'type'        => $data['type'],
                            'reason'      => $data['reason'] ?? 'usage',
                            'qty'         => $qty,
                            'note'        => $data['note'] ?? null,
                        ]);
                    })
                    ->successNotificationTitle('Stock adjusted'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
