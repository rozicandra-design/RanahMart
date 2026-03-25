<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Voucher;

class ExpireVoucher extends Command
{
    protected $signature = 'voucher:expire-check';
    protected $description = 'Nonaktifkan voucher yang sudah kadaluarsa';

    public function handle(): void
    {
        $jumlah = Voucher::where('aktif', true)
            ->where('berlaku_hingga', '<', now()->toDateString())
            ->update(['aktif' => false]);
        $this->info("✅ {$jumlah} voucher kadaluarsa dinonaktifkan.");
    }
}
