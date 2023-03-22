<?php

namespace App\Repositories\Lead;

use App\Journal\Facade\Journal;
use App\Models\Leads;
use App\Models\Project\Project;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Repository{
    protected const REGION_SERVICE_URL = 'http://htmlweb.ru/geo/api.php'; //Адрес службы определения региона
    protected const REGION_SERVICE_API_KEY = 'e6a69c81b471789f28756464e3363e48'; //API-ключ службы определения региона

    //Коды статуса
    public const STATUS_OK = 0;
    public const STATUS_CONNECTION_ERROR = 1;
    public const STATUS_ERROR = 2;

    /**
     * URL для проверки баланса: https://htmlweb.ru/api.php?json&obj=money&m=get_limit&api_key=e6a69c81b471789f28756464e3363e48
     */

    public function query()
    {
        return Leads::query();
    } //query

    public function create(
        Project $project,
        string $name,
        int $phone,
        ?string $host,
        ?string $surname,
        ?string $patronymic,
        ?string $owner,
        ?string $cost,
        ?string $email,
        ?string $comment,
        ?string $city,
        // ?string $region,
        ?string $manual_region,
        ?string $company,
        ?string $ip,
        ?string $referrer,
        ?string $source,
        ?string $utm_medium,
        ?string $utm_campaign,
        ?string $utm_source,
        ?string $utm_term,
        ?string $utm_content,
        ?string $url_query_string,
        ?string $nextcall_date,
    ): Leads
    {
        //Создание лида
        $lead = $this->query()->create([
            'project_id' => $project->id,
            'owner' => $owner,
            'name' => $name,
            'surname' => $surname,
            'patronymic' => $patronymic,
            'phone' => $phone,
            'email' => $email,
            'cost' => $cost,
            'comment' => $comment,
            'city' => $city,
            // 'region' => $region,
            'manual_region' => $manual_region,
            'company' => $company,
            'ip' => $ip,
            'referrer' => $referrer,
            'source' => $source,
            'utm_medium' => $utm_medium,
            'utm_source' => $utm_source,
            'utm_campaign' => $utm_campaign,
            'utm_content' => $utm_content,
            'utm_term' => $utm_term,
            'host' => $host,
            'url_query_string' => $url_query_string,
            'nextcall_date' => is_null($nextcall_date) ? null : Carbon::parse(time: $nextcall_date, tz: $project->timezone)->setTimezone(config('app.timezone')),
        ]);

        //Заполнение остальных полей
        $lead->update([
            'full_name' => $lead->getClientName(),
            'utm' => [
                'utm_medium' => $utm_medium,
                'utm_source' => $utm_source,
                'utm_campaign' => $utm_campaign,
                'utm_content' => $utm_content,
                'utm_term' => $utm_term,
            ],
        ]);

        return $lead;
    } //create

    public function update(Leads $lead, array $params): Leads
    {
        $lead->update($params);
        return $lead;
    } //update

    public function remove(Leads $lead)
    {
        $lead->delete();
    } //remove
    
    public function findRegion(Leads $lead, bool $online = true): int //Определить регион у лида
    {
        if($online){
            try{
                //Запрос на сервис
                $response = Http::timeout(5)
                ->retry(times: 3, sleep: 60000)
                ->get(
                    env(key: 'REGION_SERVICE_URL')."?json&telcod={$lead->phone}&charset=utf-8&api_key=".env(key: 'REGION_SERVICE_API_KEY')
                );

                $response->throw();

                //Обработка ответа
                $data = $response->json(key: 'region');

                if(empty($data)){
                    Journal::leadError(lead: $lead, text: 'Сервис не смог определить регион');
                    return self::STATUS_ERROR;
                }
                else{
                    $lead->update(['region' => $data['name']]);
                    Journal::lead(lead: $lead, text: 'Регион лида определён: ' . $lead->region);
                    return self::STATUS_OK;
                }
            }
            catch(ConnectionException $e){
                Journal::leadError(lead: $lead, text: 'Ошибка при определении региона: время ожидания ответа с сервера истекло. Исключение: ' . $e->getMessage());
                return self::STATUS_CONNECTION_ERROR;
            }
            catch(RequestException $e){
                Journal::leadError(lead: $lead, text: 'Ошибка при определении региона: ' . $e->getMessage());
                return self::STATUS_ERROR;
            }
        }
        else{
            throw new \Exception(message: 'Пока что оффлайн-опредление региона не реализовано');
            return self::STATUS_OK; //TODO Изменить, когда будет реализовано оффлайн-опредление региона
        }
    } //findRegion

    public function getRegionFromPreviousLead(Leads $lead, bool $retry = false): void //Получить регион из предыдущего лида
    {
        //Поиск предыдущего лида
        $previous = $this->query()->orderByDesc('id')->where('phone', $lead->phone)->whereNotNull('region')->first();
        
        //Опция retry означает, что нужно попробовать найти регион, если в предыдущем лиде его нет
        if(is_null($previous)){
            if($lead->project->find_region){
                Journal::leadWarning(lead: $lead, text: 'Регион в прошлом лиде не обнаружен. Будет сделана попытка найти регион через запрос.');
                $this->findRegion(lead: $lead);
            }
        }
        else{
            $lead->update(['region' => $previous->region]);
            Journal::lead(lead: $lead, text: "Регион \"{$lead->region}\" взят из предыдущего лида.");
        }
    } //getRegionFromPreviousLead

    public function makeFullNamesForProject(Project|int $project, bool $nullOnly = true) //Заполнить поле full_name в лидах по проекту
    {
        $leads = $this->query()
            ->from($project)
            ->when($nullOnly, function($query){ //Опция nullOnly указывает, что надо загрузить только лиды с пустым full_name
                return $query->whereNotNull('full_name');
            })
            ->chunkById(500, function($leads){
                $leads->each(function($lead){
                    $lead->update(['full_name' => $lead->getClientName()]);
                });
            });

    } //makeFullNames

    public static function makeFullNameForLead(Leads $lead) //Заполнить поле full_name у лида. Используется для старых API, где ещё не используется метод create из данного репозитория
    {
        $lead->update(['full_name' => $lead->getClientName()]);
    } //makeFullNameForLead

    public function splitUTMForProject(Project|int $project) //Взять значения из json-поля utm и записать их в отдельные поля
    {
        $this->query()
            ->from($project)
            ->whereNotNull('utm')
            ->where('utm', '!=', '[]')
            ->chunkById(500, function($leads){
                $leads->each(function($lead){
                    $lead->update([
                        'utm_medium' => $lead->utm['utm_medium'] ?? null,
                        'utm_source' => $lead->utm['utm_source'] ?? null,
                        'utm_campaign' => $lead->utm['utm_campaign'] ?? null,
                        'utm_content' => $lead->utm['utm_content'] ?? null,
                        'utm_term' => $lead->utm['utm_term'] ?? null,
                    ]);
                });
            });
    } //splitUTMForProject

    public static function splitUTMForLead(Leads $lead) //Заполнить отдельные поля UTM у лида. Используется для старых API, где ещё не используется метод create из данного репозитория
    {
        if(!is_null($lead->utm))
            $lead->update([
                'utm_medium' => $lead->utm['utm_medium'] ?? null,
                'utm_source' => $lead->utm['utm_source'] ?? null,
                'utm_campaign' => $lead->utm['utm_campaign'] ?? null,
                'utm_content' => $lead->utm['utm_content'] ?? null,
                'utm_term' => $lead->utm['utm_term'] ?? null,
            ]);
    } //splitUTMForLead

    public function addManualRegion(Leads $lead, string $region): void
    {
        $lead->update(['manual_region' => $region]);
    } //addManualRegion

    public function clearManualRegion(Leads $lead): void
    {
        $lead->update(['manual_region' => null]);
    } //clearManualRegion

    public function addNextCallDate(Leads $lead, string $datetime): void //Добавить дату следующего звонка
    {
        $lead->update(['nextcall_date' => Carbon::parse(time: $datetime, tz: $lead->project->timezone)->setTimezone(config('app.timezone'))]);
    } //addNextCallDate

    public function clearNextCallDate(Leads $lead): void //Удалить дату следующего звонка
    {
        $lead->update(['nextcall_date' => null]);
    } //clearNextCallDate
};

?>