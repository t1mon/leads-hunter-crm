<?php

namespace App\Repositories\Project\Export;

use App\Models\Project\Export;
use App\Models\Project\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;

class Repository{
    private const ID_LENGTH = 6; //Длина идентификатора документа в символах

    public function create(
        Project $project,
        User $user,
        string $token,
        ?string $name = null,
    ): Export
    {
        return Export::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'name' => $name ?? $this->generateName(project: $project, token: $token),
            'token' => $token,
        ]);
    } //create

    public function finish( //Дополнить информацию после того, как завершилась генерация файла
        Export $export
    ): Export
    {
        $export->update([
            'expires_at' => Carbon::now(tz: config('app.timezone'))->addDays(Export::DEFAULT_VALID_FOR),
            'finished' => true,
            'download_url' => route('v2.project.export.download', [$export->project->id, $export->token]), //TODO продумать, откуда берётся URL
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

    public function generateToken(): string
    {
        return Str::random(self::ID_LENGTH);
    } //generateToken

    public function generateName(Project $project, string $token)
    {
        $name = implode(separator: '_', array: [
            $project->name,
            Carbon::now(tz: config('app.timezone'))->setTimezone($project->timezone)->format('d.m.Y'),
            $token,
        ]);

        $name .= '.' . Excel::XLSX;

        return $name;
    } //_generateName
};

?>