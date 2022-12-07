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

    public function toggle(Project $project, bool $value = null): void
    {
        $new = $project->settings;
        $new['enabled'] = is_null($value) ? !$project->settings['enabled'] : $value;

        $project->update(['settings' => $new]);
    } //toggle

    public function enable(Project $project): void
    {
        $this->toggle(project: $project, value: true);
    } //enable

    public function disable(Project $project): void
    {
        $this->toggle(project: $project, value: false);
    } //disable
};

?>