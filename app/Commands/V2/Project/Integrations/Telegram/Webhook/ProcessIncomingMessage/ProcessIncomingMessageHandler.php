<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Webhook\ProcessIncomingMessage;

class ProcessIncomingMessageHandler
{
    /**
     * ProcessIncomingMessageHandler constructor.
     */
    public function __construct(
        private \App\Repositories\Project\Integrations\Telegram\Chat\Repository $chatRepository,
    )
    {
    }

    /**
     * @param ProcessIncomingMessageCommand $command
     */
    public function handle(ProcessIncomingMessageCommand $command)
    {
        //Если бот не включен, ничего не делать
        if(!env(key: 'TELEGRAM_ENABLED', default: false))
            throw new \Exception(message: 'Telegram-бот отключен');

        //Определить характер команды
        if($command->isInviteCode())
            $this->_confirmInvite($command);
        elseif($command->isCommand())
            $this->_processCommand($command);
        else
            throw new \Exception(message: 'Такие сообщения не поддерживаются ботом');
        
    } //handle

    private function _confirmInvite(ProcessIncomingMessageCommand $command)
    {
        // $command->bot->sendMessage([
        //     'chat_id' => $command->message->chat->id,
        //     'text' => 'кот интиграци',
        // ]);

        //Поиск чата по коду приглашения
        $chat = $this->chatRepository->query()->invite($command->getInvite())->first();
        if(is_null($chat))
            throw new \Exception(message: 'Чат с кодом приглашения ' . $command->getInvite() . ' отсутствует');

        //Включить чат и обновить его данные из сообщения
        $this->chatRepository->confirm(
            chat: $chat,
            title: $command->isPrivate() ? $command->message->chat->username : $command->message->chat->title,
            type: $command->message->chat->type,
            chat_id: $command->message->chat->id
        );

        $chat->enable();

        $command->bot->sendMessage([
            'chat_id' => $chat->chat_id,
            'text' => 'Я вас понел интиграцея Настроил!!',
        ]);
    } //_confirmInvite

    private function _processCommand(ProcessIncomingMessageCommand $command)
    {
        // $command->bot->sendMessage([
        //     'chat_id' => $command->message->chat->id,
        //     'text' => 'каманда',
        // ]);

        //Поиск чата по идентификатору из сообщения
        $chat = $this->chatRepository->query()->chat($command->message->chat->id)->first();
        if(is_null($chat))
            throw new \Exception(message: 'Чат "' . $command->getChatTitle() . '" отсутствует');

        //Обновление информации чата
        $this->chatRepository->getInfoFromUpdate(chat: $chat, update: $command->update);

        if(!$chat->confirmed)
                throw new \Exception(message: 'Интеграция чата "' . $command->getChatTitle() . '" ещё не подтверждена');

        //Выполнение команды
        if( preg_match(pattern: $chat->getStartCommandPattern(), subject: $command->message->text) )
        {
            $chat->enable();
            $command->bot->sendMessage([
                'chat_id' => $chat->chat_id,
                'text' => 'Понял принял интеграцимя В лключена!!'
            ]);
        }

        if( preg_match(pattern: $chat->getStopCommandPattern(), subject: $command->message->text) )
        {
            $chat->disable();
            $command->bot->sendMessage([
                'chat_id' => $chat->chat_id,
                'text' => 'Понел вас ! дИазктвиурую росылку',
            ]);
        }
    } //_processCommand
}
