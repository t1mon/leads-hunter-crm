<?php

namespace App\Providers;

use App\Events\Leads\LeadCreated;
use App\Listeners\Leads\SendEmailData;
use App\Listeners\Leads\SendTelegramData;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LeadCreated::class => [
            SendEmailData::class
        ],
        LeadCreated::class => [
            SendTelegramData::class
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
