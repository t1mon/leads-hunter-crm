<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Journal\Journal;

class JournalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('journal', function(){
            return new Journal();
        });
    }
}
