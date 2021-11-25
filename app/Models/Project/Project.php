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
            "email":
            {
                "enabled": true,
                "send_all": true,
                "subject": "!Empty Subject!",
                "fields": []
            },

            "telegram":
            {
                "enabled": false,
                "fields": []
            },

            "timezone": "UTC"
        }',
    ];

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
        foreach($webhook->fields as $field)
            $parameters[$field] = $lead->$field;

        $response = null;

        //Отправка запроса
        if($webhook->method === 'POST')
            $response = Http::asForm()->post($webhook->url, $parameters);
        elseif($webhook->method === 'GET')
            $response = Http::asForm()->get($webhook->url, $parameters);

        //TODO Запись в лог
        //...

        return $response->json();
    } //webhook_send

    public function leads()
    {
        return $this->hasMany(Leads::class, 'project_id');
    }

    public function leadsToday()
    {
        return $this->hasMany(Leads::class, 'project_id')->whereDate('created_at', Carbon::today());
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

