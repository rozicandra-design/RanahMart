<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        // Share notif count ke semua view
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $notifCount = Notifikasi::where('user_id', auth()->id())
                    ->where('sudah_dibaca', false)->count();
                $view->with('notifCount', $notifCount);
            }
        });
    }
}
