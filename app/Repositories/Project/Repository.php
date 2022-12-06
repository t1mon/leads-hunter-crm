<?php

namespace App\Repositories\Project;

use App\Models\Project\Project;

class Repository{
    public function create(
        // ...
    ): Project
    {
        return Project::create([
            // ...
        ]);
    } //create

    public function update(
        Project $project,
        // ...
    ): Project
    {
        $project->update([
            // ...
        ]);

        return $project;
    } //update

    public function remove(Project $project): void
    {
        $project->delete();
    } //remove

    public function toggle(): void
    {
        
    }
};

?>