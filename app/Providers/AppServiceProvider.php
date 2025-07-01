<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\BuktiTransaksiModel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


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

        Carbon::setLocale('id');
        App::setLocale('id');
    }
}
