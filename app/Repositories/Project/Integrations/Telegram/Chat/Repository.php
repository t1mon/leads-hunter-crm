<?php

namespace App\Repositories\Project\Integrations\Telegram\Chat;

use App\Models\Project\Integrations\Telegram\Chat;
use App\Models\Project\Project;

class Repository{
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return Chat::query();
    } //query

    public function createBlank( //Создать неподтверждённую интеграцию
        Project|int $project,
        string $format,
    ): Chat
    {
        return Chat::create([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'invite' => Chat::generateInvite(),
            'format' => $format,
            'confirmed' => false,
            'enabled' => true,
        ]);
    } //createBlank

    public function confirm( //Обновить данные интеграции и подтвердить её
        Chat $chat,
        string $title,
        string $type,
        string $chat_id,
    ): Chat
    {
        $chat->update([
            'title' => $title,
            'type' => $type,
            'chat_id' => $chat_id,
            'confirmed' => true,
        ]);

        return $chat;
    } //update

    public function update(
        Chat $chat,
        string $format,
        bool $enabled,
    ): Chat
    {
        $chat->update([
            'format' => $format,
            'enabled' => $enabled,
        ]);

        return $chat;
    }

    public function remove(Chat $chat): void
    {
        $chat->delete();
    } //remove

};

?>