<?php

namespace App\Providers;

use App\Models\UserShift;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Number;
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
    public function boot(): void
    {
        Number::useLocale('id');

        // Pastikan query hanya berjalan di web browser (bukan CLI) 
        // dan pastikan tabel user_shift sudah dibuat di database
        if (!App::runningInConsole() && Schema::hasTable('user_shift')) {
            View::share('shift', $this->getShift());
        }
    }

    private function getShift()
    {
        return UserShift::where('shift_active', 1)->latest('id_user_shift')->first();
    }
}