<?php

namespace App\Repositories\Project\Integrations\EmailReader;

use App\Models\Project\Integrations\EmailReader;
use App\Models\Project\Project;
use App\Models\User;

class Repository{
    public function query()
    {
        return EmailReader::query();
    }

    public function create(
        User|int $user,
        Project $project,
        string $email,
        string $password,
        string $host,
        string $template,
        bool $enabled,
        int $interval,
        int $mails_per_time,
        bool $mark_as_read,
    ): EmailReader
    {
        return $this->query()->create([
            'user_id' => $user instanceof User ? $user->id : $user,
            'project' => $project instanceof Project ? $project->id : $project,
            'email' => $email,
            'password' => $password,
            'host' => $host,
            'template' => $template,
            'enabled' => $enabled,
            'interval' => $interval,
            'mails_per_time' => $mails_per_time,
            'mark_as_read' => $mark_as_read,
        ]);
    } //create

    public function update(
        EmailReader $emailReader,
        string $email,
        string $password,
        string $host,
        string $template,
        bool $enabled,
        int $interval,
        int $mails_per_time,
        bool $mark_as_read,
    ): EmailReader
    {
        $emailReader->update([
            'email' => $email,
            'password' => $password,
            'host' => $host,
            'template' => $template,
            'enabled' => $enabled,
            'interval' => $interval,
            'mails_per_time' => $mails_per_time,
            'mark_as_read' => $mark_as_read,
        ]);

        return $emailReader;
    } //update

    public function remove(EmailReader $emailReader): void
    {
        $emailReader->delete();
    } //remove

};

?>