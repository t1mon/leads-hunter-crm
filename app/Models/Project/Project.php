<?php

namespace App\Models\Project;

use App\Models\User;
use App\Models\Leads;

use http\Exception\BadMethodCallException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Journal\Facade\Journal;

class Project extends Model
{
    use HasFactory;

    const DISABLED = 'Project disabled';
    const ENABLED = 'Project enabled';

    //Типы шаблона письма, в котором отправляется лид
    const TEMPLATE_VIEW = 'view';
    const TEMPLATE_MARKDOWN = 'markdown';
    const TEMPLATE_TEXT = 'text';

    const DEFAULT_COLOR = '5F9EA0'; //Цвет иконки проекта по умолчанию

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
            "color": "5F9EA0",
            "leadValidDays": 0,
            "email":
            {
                "template": "text",
                "enabled": false,
                "send_all": true,
                "subject": "!Empty Subject!",
                "fields": []
            },

            "vk":
            {
                "enabled": false
            },

            "telegram":
            {
                "enabled": false,
                "fields": []
            },

            "SMS":
            {
                "enabled": false,
                "text": ""
            },

            "webhooks": [],

            "timezone": "UTC"
        }',
    ];

    //Типы вебхуков
    public const WEBHOOK_COMMON = 'common'; //Обычный вебхук
    public const WEBHOOK_BITRIX24 = 'bitrix24'; //Вебхук Bitrix24
    public const WEBHOOK_AMOCRM = 'amocrm'; //Вебхук AmoCRM

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
        //Установка срока годности токена у вебхука AmoCRM
        if($new_webhook['type'] === self::WEBHOOK_AMOCRM)
            $this->webhook_add_amocrm($new_webhook);
        else{    
            $new_settings = $this->settings;
            $new_settings['webhooks'][ $new_webhook['name'] ] = $new_webhook;

            $this->settings = $new_settings;
        }
    } //webhook_add

    public function webhook_add_amocrm(array $new_webhook){
        //Вычисление URL для обновления access_token
        $new_webhook['auth_url'] = 'https://' . parse_url($new_webhook['url'], PHP_URL_HOST) . '/oauth2/access_token';
        
        //Отправка кода авторизации для получения токенов
        $body = [
            'client_id' => $new_webhook['client_id'],
            'client_secret' => $new_webhook['client_secret'],
            'grant_type' => 'authorization_code',
            'code' => $new_webhook['authorization_code'],
            'redirect_uri' => $new_webhook['redirect_uri'],
        ];

        $response = Http::withBody(json_encode($body), 'application/json')->post($new_webhook['auth_url']);
        $response->throw(); //Выбросить исключение, если произошла ошибка запроса

        //Парсинг ответа
        $new_webhook['access_token'] = $response['access_token'];
        $new_webhook['refresh_token'] = $response['refresh_token'];
        
        //Установка срока годности токена у вебхука AmoCRM
        $new_webhook['expires_at'] = Carbon::now()->addSeconds(86400);

        $new_settings = $this->settings;
        $new_settings['webhooks'][ $new_webhook['name'] ] = $new_webhook;
        $this->settings = $new_settings;
    } //webhook_add_amocrm

    public function webhook_update(string $webhook_name, array $params, bool $save = false){ //Обновить параметры в вебхука
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

        //Установка срока годности токена у вебхука AmoCRM
        if($new_settings['webhooks'][$webhook_name]['type'] === self::WEBHOOK_AMOCRM)
            $new_settings['webhooks'][$webhook_name]['expires_at'] = Carbon::now()->addSeconds(86400);

        $this->settings = $new_settings;
        if($save) $this->save();
    } //webhook_update

    public function webhook_delete(string $name){ //Удалить вебхук
        if(array_key_exists($name, $this->settings['webhooks'])){
            $new_settings = $this->settings;
            unset($new_settings['webhooks'][$name]);
            $this->settings = $new_settings;
        }
    } //webhook_delete

    public function webhook_send(string $name, Leads $lead)
    { //Отправить данные по вебхуку
        //Составление тела запроса
        $webhook = $this->webhook_get($name);

        if (isset($webhook->query)) {
            $fields = ['name', 'patronymic', 'surname', 'phone', 'email', 'cost', 'city', 'comment', 'utm_medium', 'utm_source', 'utm_campaign', 'utm_content'];
            foreach ($fields as $field) {
                $webhook->query = str_replace('$' . $field, $lead->$field, $webhook->query);
            }
        }

        try{
            if($webhook->type === "amocrm")
                return $this->webhook_send_amocrm($webhook);

            return $this->doRequest(
                $webhook->url,
                isset($webhook->query) ? yaml_parse($webhook->query) : [],
                mb_strtolower($webhook->method),
                (isset($webhook->as_form) && $webhook->as_form === "1")
            );
        }
        catch(\Illuminate\Http\Client\ConnectionException | \Illuminate\Http\Client\RequestException $e){
            Journal::leadError($lead, "Ошибка отправления вебхука \"$name\": ".mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8').". Вебхук автоматически отключен.");
            $this->webhook_update($name, ['enabled' => 0], true);
            return json_decode($e->response);
        }
        
    } //webhook_send

    public function doRequest(string $url, array $data, $method = 'post', $asForm = false) : Response
    {
        if (!in_array($method,['post','get']))
            throw new \BadMethodCallException("wrong method '$method', only 'POST' or 'GET' ");

        return $asForm ?
                Http::withOptions(['verify' => false])->asForm()->$method($url, $data)
                :
                Http::withOptions(['verify' => false])->$method($url, $data);
    }

    public function webhook_send_amocrm($webhook){ //Отправка специального запроса на AmoCRM
        //Обновление access_token
        if( Carbon::now()->greaterThan( Carbon::parse($webhook->expires_at)) ){
            $this->webhook_amocrm_update_token($webhook);
            $old_query = $webhook->query; //Сохранение уже составленного запроса
            $webhook = $this->webhook_get($webhook->name);
            $webhook->query = $old_query;
        }
    
        // Отправка запроса
        $response = Http::withToken($webhook->access_token)->withBody(json_encode(yaml_parse($webhook->query)), 'application/json')->post($webhook->url);
        
        if($response->failed() and $response['status'] == 401){ //Если попытка подключения не удалась из-за устаревшего токена
            $this->webhook_amocrm_update_token($webhook);
            $old_query = $webhook->query; //Сохранение уже составленного запроса
            $webhook = $this->webhook_get($webhook->name);
            $webhook->query = $old_query;
            $response = Http::withToken($webhook->access_token)->withBody(json_encode(yaml_parse($webhook->query)), 'application/json')->post($webhook->url);
            $response->throw();
        }
        else
            $response->throw();

        return $response;
    } //webhook_send_amocrm

    public function webhook_amocrm_update_token($webhook){ //Обновление access_token у вебхука AmoCRM
        $body = [
            'client_id' => $webhook->client_id,
            'client_secret' => $webhook->client_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $webhook->refresh_token,
            'redirect_uri' => $webhook->redirect_uri,
        ];

        //Отправка запроса
        $response = Http::withBody(json_encode($body), 'application/json')->post($webhook->auth_url);
        $response->throw(); //Выбросить исключение, если произошла ошибка запроса

        //Парсинг ответа
        $this->webhook_update($webhook->name, [
            'expires_at' => Carbon::now()->addSeconds($response['expires_in']),
            'access_token' => $response['access_token'],
            'refresh_token' => $response['refresh_token'],
        ], true);
    }

    public function leads()
    {
        return $this->hasMany(Leads::class, 'project_id');
    }


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

    public function vk_forms(){ //Получить формы, привязанные к проекту
        return $this->hasMany(VKForm::class);
    } //vk_forms
}

