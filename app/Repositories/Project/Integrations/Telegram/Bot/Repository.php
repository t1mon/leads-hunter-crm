<?php

namespace App\Repositories\Project\Integrations\Telegram\Bot;

use App\Models\Project\Integrations\Telegram\Bot;

class Repository{
    public function create(
        string $username,
        string $api_token,
    ): Bot
    {
        return Bot::create([
            'username' => $username,
            'api_token' => $api_token,
            'enabled' => true,
        ]);
    } //create

    public function update(
        Bot $bot,
        string $username,
        string $api_token,
        bool $enabled,
    ): Bot
    {
        //TODO Если указан новый api_token, отвязать вебхук
        //...

        $bot->update([
            'username' => $username,
            'api_token' => $api_token,
            'enabled' => true,
        ]);

        return $bot;
    } //update

    public function remove(Bot $bot): void
    {
        $bot->delete();
    } //remove

};

?>