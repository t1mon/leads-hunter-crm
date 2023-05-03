<?php

namespace App\Listeners\Project\Integrations\Telegram;

use App\Events\Leads\LeadCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use TeleBot;

class SendLeadDataToTG
{
    private $chatRepository;
    private $bot;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->chatRepository = app(\App\Repositories\Project\Integrations\Telegram\Chat\Repository::class);
        $this->bot = TeleBot::bot('bot');
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Leads\LeadCreated  $event
     * @return void
     */
    public function handle(LeadCreated $event)
    {
        if(!env('TELEGRAM_ENABLED'))
            return;

        $chats = $this->chatRepository->query()->from($event->lead->project_id)->get();
        
        foreach($chats as $chat){
            if($chat->enabled)
                $this->bot->sendMessage([
                    'chat_id' => $chat->chat_id,
                    'text' => $chat->composeMessage($event->lead),
                    // 'parse_mode' => 'HTML',
                ]);
        }
    }
}
