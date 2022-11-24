<?php

namespace App\Commands\V2\Project\Journal;

use App\Models\Project\UserPermissions;
use App\Repositories\Lead\ReadRepository as LeadRepository;
use App\Repositories\Project\UserPermissions\ReadRepository as UserPermissionsRepository;
use Illuminate\Support\Arr;
use Illuminate\Validation\UnauthorizedException;

class GetVariantsHandler
{
    private const ALL_FIELDS = [ //Все поля лида
        'owner',
        'name',
        'surname',
        'patronymic',
        'phone',
        'entries',
        'email',
        'cost',
        'comment',
        'city',
        'ip',
        'referrer',
        'source',
        'host',
        'url_query_string',
    ];

    private const UTM_FIELDS = [
        'utm_medium',
        'utm_source',
        'utm_campaign',
        'utm_content',
    ];

    public function __construct(
        private LeadRepository $leadRepository,
        private UserPermissionsRepository $permissionsRepository,
    )
    {
    } //Конструктор

    /**
     * @param GetVariantsCommand $command
     */
    public function handle(GetVariantsCommand $command)
    {
        if(auth('api')->user()->isAdmin())
            return $this->getAllVariants($command->project);

        $permissions = $this->permissionsRepository->findByCurrentUserInProject(project: $command->project);
        if(is_null($permissions))
            throw new UnauthorizedException('Unauthorized');

        return $this->getVariantsFromPermissions(project: $command->project, permissions: $permissions);
    }

    private function getAllVariants(int $project): array //Получить варианты по всем полям лида
    {
        $leads = $this->leadRepository->query()->from($project)->get();

        //Загрузка базовых полей
        $variants = [];
        foreach(self::ALL_FIELDS as $field)
            $variants[$field] = $leads->whereNotNull($field)->pluck($field)->unique()->values();
        Arr::forget(array: $variants, keys: ['id', 'comment']);

        //Загрузка UTM-меток
        foreach(self::UTM_FIELDS as $field)
            $variants[$field] = $leads->pluck("utm.$field")->whereNotNull()->unique()->values();

        return $variants;
    } //getAllVariants

    private function getVariantsFromPermissions(int $project, ?UserPermissions $permissions): array //Получить варианты на основе разрешений пользователя
    {
        $leads = $this->leadRepository->query()->from($project)->get();

        //Составление списка базовых и дополнительных полей
        $view_fields = collect($permissions->view_fields);
        $additional = $view_fields->reject(function($value){
            return in_array(needle: $value, haystack: self::UTM_FIELDS);
        })->values();

        $fields = array_merge(UserPermissions::ALLOWED_BASIC_FIELDS, $additional->toArray());

        //Загрузка базовых полей
        $variants = [];
        foreach($fields as $field)
            $variants[$field] = $leads->whereNotNull($field)->pluck($field)->unique()->values();
        Arr::forget(array: $variants, keys: ['id']);

        //Загрузка UTM-меток
        $utm_fields = $view_fields->map(function($value){ //UTM-метки, которые есть в разрешениях пользователя
            if(in_array(needle: $value, haystack: self::UTM_FIELDS))
                return $value;
        })->whereNotNull()->values();


        foreach($utm_fields as $item)
            $variants[$item] = $leads->pluck("utm.$item")->whereNotNull()->unique()->values();

        return $variants;
    } //getVariantsFromPermissions
}
