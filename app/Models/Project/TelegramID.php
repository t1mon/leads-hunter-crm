<?php

namespace App\Models\Project;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Leads;

class TelegramID extends Model
{
    use HasFactory;

    //Типы контакта
    const TYPE_CHANNEL = 'channel'; //Канал
    const TYPE_PRIVATE = 'private'; //Линое сообщение

    protected $table = 'telegram_ids';
    protected $fillable = ['project_id', 'number', 'name', 'type', 'approved'];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /*
     *  
     * Базовые API-функции для использования в других функциях
     * 
    */
    static public function API_MakeRequest(string $command, array $parameters = [], string $method = "post"){ //Базовая функция для запроса на сервер
        $url = env('TELEGRAM_API_URL') . env('TELEGRAM_BOT_TOKEN') . '/' . $command;
        
        if($method === "post")
            return Http::post($url, $parameters)->json();
        if($method === "get")
            return Http::get($url)->json();
    } //API_MakeRequest

    static public function API_SetWebhook(string $url){ //Установить вебхук
        return self::API_MakeRequest('setWebhook', ['url' => $url, 'drop_pending_updates' => true, 'allowed_updates' => [] ]);
    } //API_SetWebhook

    static public function API_DeleteWebhook(){ //Снять вебхук
        return self::API_MakeRequest('deleteWebhook');
    } //API_RemoveWebhook

    static public function API_GetWebhook(){ //Получить информацию по вебхуку
        return self::API_MakeRequest('getWebhookInfo');
    } //getWebhookAttribute

    static public function API_SendMessageTo(string $id, string $text){ //Отправить сообщение какому-либо пользователю
        return self::API_MakeRequest(
            'sendMessage',
            [
                'chat_id' => $id,
                'text' => $text
            ],
        );
    } //API_SendMessageTo

    public function composeMessage(Leads $lead): string{ //Составляет текст сообщения на основании настроек проекта
        $settings = $this->project->settings['telegram'];

        //Основная часть сообщения
        $message = "Получен лид по проекту {$this->project->name}:\nИмя: {$lead->name}\nТелефон: +{$lead->phone}";

        foreach($settings['fields'] as $field)
            $message .= "\n" . trans('projects.journal.' . $field) . ": {$lead->$field}";
        
        return $message;
    } //composeMessage

    public function send(string $text){ //Отправить сообщение пользователю, которому принадлежит данная модель
        return self::API_SendMessageTo($this->number, $text);
    } //API_SendMessage

    public function API_GetUpdates(){ //Получить последние новости для бота с сервера
        return $this->API_MakeRequest('getUpdates');
    }
}
