<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use Carbon\Carbon;

class UpdateStatusTagihan extends Command
{
    protected $signature = 'tagihan:update-status';
    protected $description = 'Update status tagihan (Belum Bayar → Tunggakan)';

    public function handle()
    {
        $updated = Tagihan::where('status', 'Belum Bayar')
                         ->where('jatuh_tempo', '<', Carbon::now())
                         ->update(['status' => 'Tunggakan']);

        $this->info("✅ Status tagihan berhasil diupdate");
        $this->info("📝 Total diupdate: {$updated} tagihan");
    }
}