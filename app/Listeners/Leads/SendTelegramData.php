<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;

use App\Models\Project\TelegramID;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTelegramData implements ShouldQueue
{

    public $queue = 'telegram';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(LeadCreated $event)
    {
        $project = $event->lead->project;
        $channel_id = $project->telegram_channel_id;
        $private_ids = $project->telegram_private_ids;

        //Рассылка по Telegram
        if($project->settings['telegram']['enabled']){
            if($channel_id === null && !sizeof($private_ids)) return;

            $message = $channel_id->composeMessage($event->lead);

            //Отправка на канал
            if( !is_null($channel_id) )
                $channel_id->send($message);

            //Отправка в личку
            foreach($private_ids as $id)
                $id->send($message);

            //TODO Сделать запись в лог
            Log::channel('leads')->info("Lead id:" . $event->lead->id . " Отправлен по каналу Telegram");
            if( !is_null($channel_id) ) Log::channel('leads')->info("### номер канала {$channel_id->number}, имя канала {$channel_id->name} ###");
            if(sizeof($private_ids) > 0) Log::channel('leads')->info("### telegram список id лички {$private_ids}");
        }
    }

    /**
     * Определить, следует ли ставить слушателя в очередь.
     *
     * @param  \App\Events\Leads\LeadCreated  $event
     * @return bool
     */
    public function shouldQueue(LeadCreated $event)
    {
        $project = $event->lead->project;

        return $project->settings['telegram']['enabled'];
    }
}
