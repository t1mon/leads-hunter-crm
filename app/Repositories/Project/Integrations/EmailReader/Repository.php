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
        Project|int $project,
        string $subject,
        string $email,
        string $password,
        string $host,
        string $template,
        bool $enabled,
        int $interval,
        int $mails_per_time,
    ): EmailReader
    {
        return $this->query()->create([
            'user_id' => $user instanceof User ? $user->id : $user,
            'project_id' => $project instanceof Project ? $project->id : $project,
            'subject' => $subject,
            'email' => $email,
            'password' => $password,
            'host' => $host,
            'template' => $template,
            'enabled' => $enabled,
            'interval' => $interval,
            'mails_per_time' => $mails_per_time,
        ]);
    } //create

    public function update(
        EmailReader $emailReader,
        string $subject,
        string $email,
        string $password,
        string $host,
        string $template,
        bool $enabled,
        int $interval,
        int $mails_per_time,
    ): EmailReader
    {
        $emailReader->update([
            'subject' => $subject,
            'email' => $email,
            'password' => $password,
            'host' => $host,
            'template' => $template,
            'enabled' => $enabled,
            'interval' => $interval,
            'mails_per_time' => $mails_per_time,
        ]);

        return $emailReader;
    } //update

    public function remove(EmailReader $emailReader): void
    {
        $emailReader->delete();
    } //remove

};

?>