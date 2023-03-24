<?php

namespace App\Repositories\Project\Integrations\Telegram\Bot;

use App\Models\Project\Integrations\Telegram\Bot;
use App\Models\Project\Project;

class Repository{
    public function create(
        Project|int $project,
        string $username,
        string $api_token,
    ): Bot
    {
        return Bot::create([
            'project_id' => $project instanceof Project ? $project->id : $project,
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
        if($api_token !== $bot->api_token && $bot->checkWebhookStatus())
            $bot->deleteWebhook();

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

    public function setWebhook(Bot $bot): void
    {
        $bot->setWebhook();
    } //setWebhook

};

?>