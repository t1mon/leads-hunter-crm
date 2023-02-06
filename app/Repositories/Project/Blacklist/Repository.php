<?php

namespace App\Repositories\Project\Blacklist;

use App\Models\Project\Blacklist;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;

class Repository{
    public function query(): Builder
    {
        return Blacklist::query();
    } //query

    public function create(
        Project|int $project,
        string|int $phone,
        ?string $name = null,
        ?string $comment = null,
    ): Blacklist
    {
        return Blacklist::create([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'phone' => $phone,
            'name' => $name,
            'comment' => $comment,
        ]);
    } //create

    public function update(
        Blacklist $blacklist,
        Project|int $project,
        string|int $phone,
    ): Blacklist
    {
        $blacklist->update([
            'project_id' => $project instanceof Project ? $project->id : $project,
            'phone' => $phone,
        ]);

        return $blacklist;
    } //update

    public function remove(Blacklist|int $blacklist): void
    {
        if($blacklist instanceof Blacklist)
            $blacklist->delete();
        else
            $this->query()->where('id', $blacklist)->delete();
    } //remove

};

?>