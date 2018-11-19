<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\AdminRepository\AdminInterface','App\Repositories\AdminRepository\AdminRepo'); # Admin
        $this->app->bind('App\Repositories\UserRepository\UserInterface','App\Repositories\UserRepository\UserRepo'); # User
        $this->app->bind('App\Repositories\LoanRepository\LoanInterface','App\Repositories\LoanRepository\LoanRepo'); # Loan
        $this->app->bind('App\Repositories\RepaymentRepository\RepaymentInterface','App\Repositories\RepaymentRepository\RepaymentRepo'); # Repayment

    }
}
