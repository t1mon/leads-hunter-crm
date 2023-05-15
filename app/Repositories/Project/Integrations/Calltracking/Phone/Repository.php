<?php

namespace App\Repositories\Project\Integrations\Calltracking\Phone;

use App\Models\Project\Integrations\Calltracking\Phone;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;

class Repository{
    public function query(): Builder
    {
        return Phone::query();
    }

    public function create(
        Project|int $project,
        string|int $phone
    ): Phone
    {
        return Phone::create([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'phone' => $phone,
            'enabled' => true,
        ]);
    } //create

    public function update(
        Phone $integration,
        Project|int $project,
        string|int $phone,
        bool $enabled,
    ): Phone
    {
        $integration->update([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'phone' => $phone,
            'enabled' => $enabled,
        ]);

        return $integration;
    } //update

    public function toggle(Phone $phone): Phone
    {
        $phone->update(['enabled' => !$phone->enabled]);
        return $phone;
    } //toggle

    public function toggleByProject(Project|int $project, bool $enabled): void
    {
        $this->query()->project($project)->update(['enabled', $enabled]);
    } //toggleByProject

    public function remove(Phone $phone): void
    {
        $phone->delete();
    } //remove

};

?>