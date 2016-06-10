<?php

namespace App\Providers;

use App\Services\Mailers\AppMailer;
use App\Services\Mailers\MailerContract;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if( $this->isInDevelopement() ){
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->bind(MailerContract::class, AppMailer::class);
    }

    /**
     * @return bool
     */
    private function isInDevelopement()
    {
        return $this->app->environment() == 'local' || $this->app->environment() == 'developement';
    }
}
