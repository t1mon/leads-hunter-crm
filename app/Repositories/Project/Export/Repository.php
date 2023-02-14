<?php

namespace App\Repositories\Project\Export;

use App\Models\Project\Export;
use App\Models\Project\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Repository{
    private const ID_LENGTH = 6; //Длина идентификатора документа в символах

    public function create(
        Project $project,
        User $user,
        ?string $name = null,
    ): Export
    {
        return Export::create([
            'project_id' => $project,
            'user_id' => $user,
            'name' => $name ?? $this->_generateName(project: $project),
        ]);
    } //create

    public function finish( //Дополнить информацию после того, как завершилась генерация файла
        Export $export
    ): Export
    {
        $export->update([
            'expires_at' => Carbon::now(tz: config('app.timezone'))->addDays(Export::DEFAULT_VALID_FOR),
            'finished' => true,
            'download_url' => '', //TODO продумать, откуда берётся URL
        ]);

        return $export;
    }

    public function remove(Export $export): void
    {
        $export->delete();
    } //remove

    public function cleanExpired(): void //Удалить файлы с истёкшим сроком годности
    {
        Export::expired()->delete();
    } //cleanInvalid

    private function _generateName($project)
    {
        $name = implode(separator: '_', array: [
            $project->name,
            Carbon::now(tz: config('app.timezone'))->setTimezone($project->timezone)->format('d.m.Y'),
            Str::rand(6),
        ]); 
    } //_generateName
};

?>