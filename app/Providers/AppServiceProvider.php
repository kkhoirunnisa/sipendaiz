<?php

namespace App\Providers;

use App\Models\BuktiTransaksiModel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     //
    // }

    public function boot()
    {
        View::composer('*', function ($view) {
            $jumlahPending = BuktiTransaksiModel::where('status', 'Pending')->count();
            $view->with('jumlahPending', $jumlahPending);
        });
    }
}
