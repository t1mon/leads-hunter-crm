<?php

namespace App\Commands\V2\Project\Integrations\Telegram\Webhook\ProcessIncomingMessage;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use TeleBot;

class ProcessIncomingMessageCommand
{
    public readonly \WeStacks\TeleBot\TeleBot $bot;
    public readonly Update $update;
    public readonly Message $message;

    public function __construct(
        Request $request,
    )
    {
        $this->bot = TeleBot::bot('bot');
        $this->update = Update::create($request->all());
        
        if(is_null($this->update->message()))
                throw new \Exception(message: 'Сообщения нет');
        
        $this->message = $this->update->message();

        if($this->message->from->is_bot)
            throw new \Exception(message: 'Сообщение отправлено ботом');
    } //Конструктор

    public function isPrivate(): bool
    {
        return $this->message->chat->type === 'private';
    } //isPrivate

    public function isInviteCode(): bool
    {
        $pattern = $this->isPrivate() ? '/^\w{6}$/' : ('/^@' . env('TELEGRAM_BOT_NAME') . ' \w{6}$/');
        return preg_match(pattern: $pattern, subject: $this->message->text);
    } //isConfirm

    public function isCommand(): bool
    {
        $pattern = $this->isPrivate() ? '/^\/(start|stop)$/' : ('/^\/(start|stop)@' . env('TELEGRAM_BOT_NAME') . '$/');
        return preg_match(pattern: $pattern, subject: $this->message->text);
    } //isCommand

    public function getChatTitle(): string
    {
        return $this->isPrivate() ? $this->message->chat->username : $this->message->chat->title;
    }

    public function getInvite(): string
    {
        if($this->isPrivate())
            return $this->message->text;

        return Str::after(subject: $this->message->text, search: '@' . env('TELEGRAM_BOT_NAME') . ' ' );
    } //getInvite

    public function getCommand(): string
    {
        if($this->isPrivate())
            return $this->message->text;

        return Str::before(subject: $this->message->text, search: '@' . env('TELEGRAM_BOT_NAME'));
    } //getCommand
}
