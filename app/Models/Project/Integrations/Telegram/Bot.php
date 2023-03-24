<?php

namespace App\Models\Project\Integrations\Telegram;

use App\Models\Leads;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * 
 *  @OA\Schema(
 *      title="Модель Project\Integrations\Telegram\Bot",
 *      description="Модель, содержащая данные Telegram-бота",
 *      required={"username", "api_token"},
 *  ),
 * 
 *  @OA\Property(
 *      property="username",
 *      description="Идентификатор бота в Telegram",
 *      type="string"
 *  ),
 * 
 *  @OA\Property(
 *      property="api_token",
 *      description="API-токен бота, который используется для запросов в Telegram API",
 *      type="string"
 *  ),
 * 
 *  @OA\Property(
 *      property="webhook_token",
 *      description="Секретный ключ бота, по которому Leads Hunter CRM API распознаёт, от какого бота пришёл запрос на вебхук. Генерируется автоматически",
 *      type="string"
 *  ),
 * 
 *  @OA\Property(
 *      property="enabled",
 *      description="Флаг, указывающий, включен ли бот в текущий момент",
 *      type="boolean"
 *  )
 */
class Bot extends Model
{
    protected $table = 'integrations_tg_bots';

    protected $fillable = [
        'project_id',
        'username',
        'api_token',
        'webhook_token', //Токен, по которому будет распознаваться, для какого бота пришёл запрос на вебхук
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     *  @OA\Property(
     *      description="Поле в заголовке запроса, в котором приходит webhook_token бота",
     *      type="const string"
     *  )
     */
    public const HEADER_WEBHOOK_TOKEN = 'x-telegram-bot-api-secret-token'; //Поле в заголовке запроса, в котором приходит webhook_token бота

    /**
     *  @OA\Property(
     *      description="Базовый адрес Telegram API, куда бот отправляет запросы",
     *      type="const string"
     *  )
     */
    public const TG_API_REQUEST_URL = 'https://api.telegram.org/bot'; //Базовый адрес Telegram API, куда бот отправляет запросы

    /**
     *      Отношения
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id');
    } //project

    public function chats(): HasMany
    {
        return $this->hasMany(related: Chat::class, foreignKey: 'bot_id');
    } //chats

    /**
     *      Фильтры
     */
    public function scopeFrom($query, Project|int $project)
    {
        return $this->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeFrom

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    } //scopeEnabled

    public function scopeDisabled($query)
    {
        return $query->where('enabled', false);
    } //scopeDisabled

    /**
     *      Геттеры
     */
    public static function getWebhookRoute(): string
    {
        return route('api.v2.integrations.telegram.webhook');
    } //getWebhookRoute

    public static function getWebhookNgrokRoute(): string
    {
        return env(key: 'TELEGRAM_NGROK_WEBHOOK_URL') . '/api/v2/integrations/telegram/webhook';
    } //getWebhookNgrokRoute

    /**
     *      Рабочие методы
     */
    public static function generateWebhookToken(): string
    {
        return Str::random(60);
    } //generateWebhookToken

    /**
     *      API-методы
     */
    protected function _makeRequestUrl(string $methodName): string //Базовый метод для запроса на API
    {
        return self::TG_API_REQUEST_URL.$this->api_token.'/'.$methodName;
    } //_makeRequestUrl

    protected function _makeApiRequest(string $methodName, string $type, array $data = [], array $headers = []): Response //Общая функция для осуществления запросов
    {
        $request = Http::withHeaders($headers)
            ->timeout(5)
            ->retry(
                times: 3,
                sleep: 5000);

        $url = $this->_makeRequestUrl($methodName);

        // throw new \Exception(message: 'URL запроса: ' . $url);

        switch(Str::lower($type)){
            case 'post':
                return $request->post(url: $url, data: $data);
            case 'get':
                return $request->get(url: $url, query: $data);
            throw new InvalidArgumentException (message: 'Указан некорректный метод запроса: может быть лоибо POST, либо GET');
        }
    } //_makeApiRequest

    public function getWebhookInfo(): Response
    {
        return $this->_makeApiRequest(methodName: 'getWebhookInfo', type: 'get');
    } //getWebhookInfo

    public function checkWebhookStatus(): bool //Проверка статуса вебхука
    {
        $response = $this->getWebhookInfo();

        if($response->failed() || $response['ok'] === false)
            return false;

        if(strlen($response['result']['url']) > 0)
            return true;
        else
            return false;
    } //checkWebhookStatus

    public function setWebhook(): Response
    {
        $this->update(['webhook_token' => self::generateWebhookToken()]);

        $data = [
            'url' => env(key: 'TELEGRAM_USE_NGROK', default: true) ? self::getWebhookNgrokRoute() : self::getWebhookRoute(),
            'secret_token' => $this->webhook_token,
            'drop_pending_updates' => true,
        ];

        $response = $this->_makeApiRequest(
            methodName: 'setWebhook',
            type: 'post',
            data: $data
        );

        return $response;
    } //setWebhook

    public function deleteWebhook(): Response
    {
        $response = $this->_makeApiRequest(
            methodName: 'deleteWebhook',
            type: 'get',
        );

        return $response;
    } //deleteWebhook

    public function sendMessage(string $chat_id, string $message, array $data = []): Response
    {
        $basicData = [ //Основные данные, которые отправляются на API
            'chat_id' => $chat_id,
            'text' => $message,
        ];

        $response = $this->_makeApiRequest(
            methodName: 'sendMessage',
            type: 'post',
            data: array_merge($data, $basicData)
        );

        return $response;
    } //sendMessage

    public function setDefaultCommands()
    {
        $data = [
            'commands' => [
                [
                    'command' => 'start',
                    'description' => 'Включить уведомления',
                ],

                [
                    'command' => 'stop',
                    'description' => 'Выключить уведомления',
                ],
            ],
            // 'scope' => [
            //     'type' => 'all_chat_administrators',
            // ],
        ];

        $response = $this->_makeApiRequest(methodName: 'setMyCommands', type: 'post', data: $data);
        return $response;
    } //setDefaultCommands
}
