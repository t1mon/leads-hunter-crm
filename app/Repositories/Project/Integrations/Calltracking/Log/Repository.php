<?php

namespace App\Repositories\Project\Integrations\Calltracking\Log;

use App\Models\Project\Integrations\Calltracking\Log;
use App\Models\Project\Integrations\Calltracking\Phone;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;

class Repository{
    public function query(): Builder
    {
        return Log::query();
    } //query

    public function create(
        Project|int $project,
        Phone|int $phone,
        string $json
    ): Log
    {
        return $this->query()->create([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'phone_id' => $phone instanceof Phone ? $phone->id : $phone,
            'json' => $json,
        ]);
    } //create

    public function update(
        Log $entry,
        Project|int $project,
        Phone|int $phone,
        string $json,
    ): Log
    {
        $entry->update([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'phone_id' => $phone instanceof Phone ? $phone->id : $phone,
            'json' => $json,
        ]);

        return $entry;
    } //update

    public function remove(Log $log): void
    {
        $this->query()->delete();
    } //remove

};

?>