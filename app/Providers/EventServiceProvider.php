<?php

namespace App\Providers;

use App\Events\Leads\LeadCreated;
use App\Events\Leads\LeadDeleted;
use App\Listeners\Leads\SendEmailData;
use App\Listeners\Leads\SendTelegramData;
use App\Listeners\Leads\SendWebhookData;
use App\Listeners\Leads\SendSMSData;
use App\Listeners\Leads\CountLeadsInProject;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Listeners\Leads\SendMangoData;

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
            SendEmailData::class,
            SendTelegramData::class,
            SendSMSData::class,
            SendWebhookData::class,
            SendMangoData::class,
            CountLeadsInProject::class
        ],

        LeadDeleted::class => [
            CountLeadsInProject::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
