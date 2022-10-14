<?php

namespace App\Repositories\Project\Integrations\Mango;

use App\Models\Project\Integrations\Mango;

class Repository{
    public function create(
        string $name,
        int|string $project_id,
        string $vpbx_api_key,
        string $vpbx_api_salt,
        bool $enabled = true,
    ): Mango
    {
        return Mango::create([
            'name' => $name,
            'project_id' => $project_id,
            'vpbx_api_key' => $vpbx_api_key,
            'vpbx_api_salt' => $vpbx_api_salt,
            'enabled' => $enabled,
        ]);
    } //create

    public function update(
        Mango $mango,
        string $name = null,
        int|string $project_id = null,
        string $vpbx_api_key = null,
        string $vpbx_api_salt = null,
        bool $enabled = null,
    ): Mango
    {
        $mango->update([
            'name' => $name ?? $mango->name,
            'project_id' => $project_id ?? $mango->project_id,
            'vpbx_api_key' => $vpbx_api_key ?? $mango->vpbx_api_key,
            'vpbx_api_salt' => $vpbx_api_salt ?? $mango->vpbx_api_salt,
            'enabled' => $enabled ?? $mango->enabled,
        ]);

        return $mango;
    } //update

    public function remove(Mango $mango): void
    {
        $mango->delete();
    } //remove

    public function toggle(Mango $mango): Mango
    {
        return $this->update(mango: $mango, enabled: !$mango->enabled);
    } //toggle
};

?>