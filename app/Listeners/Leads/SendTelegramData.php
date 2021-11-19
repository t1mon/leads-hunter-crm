<?php

namespace App\Listeners\Leads;

use App\Events\Leads\LeadCreated;

use App\Models\Project\TelegramID;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTelegramData
{
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
        if($project->settings['telegram']['enabled'] and ($channel_id === null && !sizeof($private_ids))){
            //TODO Реализовать более сложную функцию составления сообщения
            // $message = "Получен лид:\nИмя: {$event->lead->name}\nТелефон: {$event->lead->phone}";
            $message = $channel_id->composeMessage($event->lead);

            //Отправка на канал
            if( !is_null($channel_id) )
                $channel_id->send($message);
            
            //Отправка в личку
            foreach($private_ids as $id)
                $id->send($message);

            //TODO Сделать запись в лог
        }
    }
}
