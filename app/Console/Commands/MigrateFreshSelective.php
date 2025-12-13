<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class MigrateFreshSelective extends Command
{
    protected $signature = 'migrate:fresh-selective';

    protected $description = 'Drop all tables except selected ones, then migrate & seed';

    public function handle()
    {
        $this->info('Running selective fresh migration...');

        // DAFTAR TABEL YANG TIDAK BOLEH DIHAPUS
        // SILAKAN EDIT SESUAI KEBUTUHAN
        $exclude = [
            'provinsi',
            'kota',
            'kecamatan',
            'wilayah_pengiriman',
        ];

        Schema::disableForeignKeyConstraints();

        // Ambil semua table dari database
        $tables = DB::select('SHOW TABLES');
        $keyName = 'Tables_in_' . DB::getDatabaseName();

        foreach ($tables as $table) {
            $name = $table->$keyName;

            if (! in_array($name, $exclude)) {
                $this->warn("Dropping table: {$name}");
                Schema::drop($name);
            } else {
                $this->info("Skipping table: {$name}");
            }
        }

        Schema::enableForeignKeyConstraints();

        // Jalankan migrate
        $this->info("Running migrations...");
        Artisan::call('migrate', [], $this->output);

        // Jalankan seeder
        $this->info("Running seeders...");
        Artisan::call('db:seed', [], $this->output);

        $this->info("Selective fresh migration complete!");
    }
}
