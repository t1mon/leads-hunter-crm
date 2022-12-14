<?php

namespace App\Repositories\Lead;

use App\Journal\Facade\Journal;
use App\Models\Leads;
use App\Models\Project\Project;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Repository{
    protected const REGION_SERVICE_URL = 'http://htmlweb.ru/geo/api.php'; //Адрес службы определения региона
    protected const REGION_SERVICE_API_KEY = 'e6a69c81b471789f28756464e3363e48'; //API-ключ службы определения региона

    public function query()
    {
        return Leads::query();
    } //query

    public function create(
        Project|int $project,
        string $name,
        int $phone,
        string $host,
        ?string $surname,
        ?string $patronymic,
        ?string $cost,
        ?string $comment,
        ?string $city,
        ?string $ip,
        ?string $email,
        ?string $utm_medium,
        ?string $utm_campaign,
        ?string $utm_source,
        ?string $utm_content,
        ?string $referrer,
        ?string $url_query_string,
    )
    {
        //Подготовка параметров
        $project = $project instanceof Project ? $project->id : $project;
        
        if(filter_var($host, FILTER_VALIDATE_URL))
            $host = Str::lower( parse_url($host) );

        if($phone[0] === 8)
            $phone = preg_replace('/^./','7', $phone);
        
        //TODO Спросить
        $cost = preg_replace("/[^0-9]/", '', trim($cost));

        //Создание лида
        $this->query()->create([
            'project_id' => $project,
            'name' => $name,
            'surname' => $surname,
            'patronymic' => $patronymic,
            //...
        ]);
    } //create

    public function update(Leads $lead, array $params)
    {

    } //update

    public function remove(Leads $lead)
    {
        $lead->delete();
    } //remove
    
    public function findRegion(Leads $lead, bool $online = true): void //Определить регион у лида
    {
        if($online){
            try{
                //Запрос на сервис
                $response = Http::timeout(5)
                ->retry(times: 3, sleep: 500)
                // ->get(url: self::REGION_SERVICE_URL, query: [
                //     'json',
                //     'telcod' => $lead->phone,
                //     'charset' => 'utf-8',
                //     'api_key' => self::REGION_SERVICE_API_KEY,
                //     // 'fields' => ['region', 'city'],
                // ]);
                ->get(
                    self::REGION_SERVICE_URL."?json&telcod={$lead->phone}&charset=utf-8&api_key=".self::REGION_SERVICE_API_KEY
                );

                $response->throw();

                

                //Обработка ответа
                $data = $response->json(key: 'region');
                // $data = json_decode($response->body(), true);
                // throw new \Exception(message: $response->body());

                if(empty($data))
                    Journal::leadError(lead: $lead, text: 'Сервис не смог определить регион');
                else{
                    $lead->update(['region' => $data['name']]);
                    // $lead->update(['region' => $data['region']['name'] ]);
                    Journal::lead(lead: $lead, text: 'Регион лида определён: ' . $lead->region);
                }
            }
            catch(ConnectionException $e){
                Journal::leadError(lead: $lead, text: 'Ошибка при определении региона: время ожидания ответа с сервера истекло. Исключение: ' . $e->getMessage());
            }
            catch(RequestException $e){
                Journal::leadError(lead: $lead, text: 'Ошибка при определении региона: ' . $e->getMessage());
            }
        }
        else{
            throw new \Exception(message: 'Пока что оффлайн-опредление региона не реализовано');
        }
    } //findRegion

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
};

?>