<?php

namespace App\Models\Project;

use App\Models\User;
use App\Models\Leads;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Project extends Model
{
    use HasFactory;

    const DISABLED = 'Project disabled';
    const ENABLED = 'Project enabled';

    //Типы шаблона письма, в котором отправляется лид
    const TEMPLATE_VIEW = 'view';
    const TEMPLATE_MARKDOWN = 'markdown';

    protected $fillable = [
        'name',
        'host',
        'user_id',
        'settings',
        'api_token'
    ];

    //Настройки по умолчанию
    protected $attributes = [
        'settings' =>
        '{
            "enabled": true,
            "description": false,
            "email":
            {
                "template": "view",
                "enabled": false,
                "send_all": true,
                "subject": "!Empty Subject!",
                "fields": []
            },

            "telegram":
            {
                "enabled": false,
                "fields": []
            },

            "webhooks": [],

            "timezone": "UTC"
        }',
    ];

    //Типы вебхуков
    public const WEBHOOK_COMMON = 'common'; //Обычный вебхук
    public const WEBHOOK_BITRIX24 = 'bitrix24'; //Обычный вебхук

    protected $casts = ['settings' => 'array'];

    public function isOwner(): bool
    {
        return Project::findOrFail($this->id)->user_id === Auth::id();
    }

    public function hosts()
    {
        return $this->hasMany(Host::class);
    }

    public function user_permissions()
    {
        return $this->hasMany(UserPermissions::class);
    }

    public function emails() //Получить все e-mail адреса рассылки
    {
        return $this->hasMany(Email::class);
    }

    public function getTelegramChannelIdAttribute() //Получить идентификатор канала, на который назначен проект
    {
        return TelegramID::where(['project_id' => $this->id, 'type' => TelegramID::TYPE_CHANNEL])->first();
    } //getTelegramChannelIdAttribute

    public function getTelegramPrivateIdsAttribute() //Получить всех подписчиков личной рассылки проекта
    {
        return TelegramID::where(['project_id' => $this->id, 'type' => TelegramID::TYPE_PRIVATE, 'approved' => true])->get();
    } //getTelegramPrivateIdsAttribute

    public function getTimezoneAttribute() //Аксессор для удобного доступа к часовому поясу
    {
        return $this->settings['timezone'];
    } //getTimezoneAttribute

    public function setTimezoneAttribute(string $value) //Мутатор для удобного изменения часового пояса
    {
        $this->settings['timezone'] = $value;
    } //setTimezoneAttribute

    public function webhook_get(string $name){ //Получить конкретный вебхук
        //Вебхук возвращается в виде объекта для удобства
        return array_key_exists($name, $this->settings['webhooks'])
            ? json_decode(json_encode($this->settings['webhooks'][$name]))
            : null;
    } //webhook_get

    public function webhooks_active(){ //Возвращает включенные вебхуки
        $active = [];
        foreach($this->settings['webhooks'] as $webhook){
            if($webhook['enabled'])
                $active[] = $this->webhook_get($webhook['name']);
        }

        return $active;
    } //webhook_active

    public function getWebhooksAttribute(){ //Аксессор для удобного получения всех вебхуков
        $objects = [];
        foreach($this->settings['webhooks'] as $webhook)
            $objects[] = json_decode(json_encode($webhook));

        //Возвращает null, если вебхуков нет
        return count($this->settings['webhooks']) ? $objects : null;
    } //getWebhooksAttribute

    public function webhook_add(array $new_webhook){ //Добавить или обновить вебхук
        $new_settings = $this->settings;
        $new_settings['webhooks'][ $new_webhook['name'] ] = $new_webhook;

        $this->settings = $new_settings;
    } //webhook_add

    public function webhook_update(string $webhook_name, array $params){ //Обновить параметры в вебхука
        $new_settings = $this->settings;

        //Переименование ключа массива, если изменилось название вебхука (чтобы не было конфликтов)
        if(array_key_exists('name', $params)
            and ($new_settings['webhooks'][$webhook_name] !== $params['name']) ){
                $new_settings['webhooks'][$params['name']] = $new_settings['webhooks'][$webhook_name];
                unset($new_settings['webhooks'][$webhook_name]);
                $webhook_name = $params['name'];
            }

        foreach($params as $key => $value)
            $new_settings['webhooks'][$webhook_name][$key] = $value;

        $this->settings = $new_settings;
    } //webhook_update

    public function webhook_delete(string $name){ //Удалить вебхук
        if(array_key_exists($name, $this->settings['webhooks'])){
            $new_settings = $this->settings;
            unset($new_settings['webhooks'][$name]);
            $this->settings = $new_settings;
        }
    } //webhook_delete

    public function webhook_send(string $name, Leads $lead){ //Отправить данные по вебхуку
        //Составление тела запроса
        $webhook = $this->webhook_get($name);
        $parameters = [];

        //Упаковать параметры в зависимости от типа вебхука
        // if($webhook->type === self::WEBHOOK_COMMON)
        // $parameters = $this->webhook_makeParams_common($webhook, $lead);
        // elseif($webhook->type === self::WEBHOOK_BITRIX24)
        //     $parameters = $this->webhook_makeParams_bitrix24($webhook, $lead);

        if(isset($webhook->query)){
            $fields = ['name', 'patronymic', 'surname', 'phone', 'email', 'cost', 'city', 'utm_medium', 'utm_source', 'utm_campaign', 'utm_content'];
            foreach($fields as $field){
                $webhook->query = str_replace('$'.$field, $lead->$field, $webhook->query);
            }
        }

        // return yaml_parse($webhook->query);

        $response = null;

        //Отправка запроса
        if($webhook->method === 'POST')
            $response = Http::withOptions(['verify' => false])->asForm()
                ->post($webhook->url, isset($webhook->query) ?  yaml_parse($webhook->query) : []);
        elseif($webhook->method === 'GET')
            $response = Http::withOptions(['verify' => false])->asForm()
                ->get($webhook->url, isset($webhook->query) ?  yaml_parse($webhook->query) : []);

        return $response;
    } //webhook_send

    public function webhook_makeParams_common(Object $webhook, Leads $lead) //Упаковать параметры для обычного вебхука
    {
        $parameters = [];
        foreach($webhook->fields as $field)
            $parameters[$field] = $lead->$field;

        return $parameters;
    } //webhook_makeParams_common

    public function webhook_makeParams_bitrix24(Object $webhook, Leads $lead) //Упаковать параметры для Битрикс24
    {
        $parameters = ['fields' => [(array)$webhook->params][0] ];

        foreach($webhook->fields as $field){
            $corr = config("webhooks-fields-correlation.bitrix24.{$field}");
            $parameters['fields'][$field] = $lead->$corr;
        }

        //"Правильная" упаковка номера телефона
        if(array_key_exists('PHONE', $parameters['fields'])){
            $parameters['fields']['PHONE'] = [ ['VALUE' => $parameters['fields']['PHONE'], 'VALUE_TYPE' => 'WORK'] ];
        }

        //"Правильная" упаковка e-mail
        if(array_key_exists('EMAIL', $parameters['fields'])){
            $parameters['fields']['EMAIL'] = [ ['VALUE' => $parameters['fields']['EMAIL'], 'VALUE_TYPE' => 'WORK'] ];
        }

        return $parameters;
    } //webhook_makeParams_bitrix24

    public function leads()
    {
        return $this->hasMany(Leads::class, 'project_id');
    }

//    public function leadsToday()
//    {
//        //$date = Carbon::parse($this->leads()->created_at, $this->timezone)->startOfDay()->setTimezone(config('app.timezone'));
//        //$leads->where('created_at', '>=' ,$date);
//        return $this->hasMany(Leads::class, 'project_id')->whereDate('created_at', '>=', Carbon::parse(Carbon::today(), $this->timezone)->startOfDay()->setTimezone(config('app.timezone') ));
//    }

    public function leadsToday(){
        return $this->leads->filter(function($lead){
            return $lead->created_at >= Carbon::today($this->timezone);
        });
    }

    /**
     * Return the user project
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function classes(){ //Получить классы, назначенные проекту
        return $this->hasMany(LeadClass::class);
    } //classes
}

