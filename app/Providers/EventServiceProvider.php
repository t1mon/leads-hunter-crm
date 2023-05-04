<?php

namespace App\Providers;

use App\Events\Leads\LeadAdded;
use App\Events\Leads\LeadCreated;
use App\Events\Leads\LeadExists;
use App\Events\Leads\LeadDeleted;
use App\Listeners\Leads\SplitUTM;
use App\Listeners\Leads\MakeFullName;
use App\Listeners\Leads\FindRegion;
use App\Listeners\Leads\GetRegionFromPreviousLead;
use App\Listeners\Leads\SendEmailData;
use App\Listeners\Leads\SendTelegramData;
use App\Listeners\Leads\SendWebhookData;
use App\Listeners\Leads\SendSMSData;

use App\Listeners\Project\Integrations\Telegram\SendLeadDataToTG;

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

        LeadAdded::class => [ //Общее событие (используется для вызова общих обработчиков для LeadCreated и LeadExists)
            SplitUTM::class,
            MakeFullName::class,
            SendEmailData::class,
            CountLeadsInProject::class,
        ],

        LeadCreated::class => [
            SendTelegramData::class, //Старая версия интеграции Telegram
            SendLeadDataToTG::class, //Новая версии интеграции Telegram
            SendSMSData::class,
            SendWebhookData::class,
            SendMangoData::class,
            FindRegion::class,
        ],

        LeadExists::class => [
            GetRegionFromPreviousLead::class,
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
