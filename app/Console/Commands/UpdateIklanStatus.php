<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Iklan;

class UpdateIklanStatus extends Command
{
    protected $signature = 'iklan:update-status';
    protected $description = 'Update status iklan yang sudah melewati tanggal selesai';

    public function handle(): void
    {
        $jumlah = Iklan::where('status','aktif')
            ->where('tanggal_selesai','<', now()->toDateString())
            ->update(['status' => 'selesai']);
        $this->info("✅ {$jumlah} iklan diubah ke status selesai.");
    }
}
