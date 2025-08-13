<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AbsensiPengunjung;

class CleanAbsensiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:clean {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean absensi data that has no anggota relation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for orphaned absensi data...');

        // Find absensi records that don't have anggota relation
        $orphanedAbsensi = AbsensiPengunjung::whereNotExists(function ($query) {
            $query->select(\DB::raw(1))
                  ->from('anggota')
                  ->whereRaw('anggota.id = absensi_pengunjung.anggota_id');
        })->get();

        if ($orphanedAbsensi->isEmpty()) {
            $this->info('✅ No orphaned absensi data found.');
            return 0;
        }

        $this->warn("⚠️  Found {$orphanedAbsensi->count()} orphaned absensi records:");

        foreach ($orphanedAbsensi as $absensi) {
            $this->line("   - ID: {$absensi->id}, Anggota ID: {$absensi->anggota_id}, Waktu: {$absensi->waktu_masuk}");
        }

        if ($this->option('dry-run')) {
            $this->info('🔍 Dry run mode - no data will be deleted.');
            return 0;
        }

        if ($this->confirm('Do you want to delete these orphaned records?')) {
            $deletedCount = $orphanedAbsensi->count();
            $orphanedAbsensi->each(function ($absensi) {
                $absensi->delete();
            });

            $this->info("✅ Successfully deleted {$deletedCount} orphaned absensi records.");
        } else {
            $this->info('❌ Operation cancelled.');
        }

        return 0;
    }
}
