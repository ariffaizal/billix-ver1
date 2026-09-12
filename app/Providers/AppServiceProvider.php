<?php

namespace App\Providers;

use App\Models\UserShift;
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
        View::share('shift', $this->getShift());
        Number::useLocale('id');
    }

    private function getShift()
    {
        return UserShift::where('shift_active', 1)->latest('id_user_shift')->first();
    }
}
