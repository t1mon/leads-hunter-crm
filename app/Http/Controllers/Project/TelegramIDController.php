<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Client\Response;

use App\Models\Project\Project;
use App\Models\Project\TelegramID;

class TelegramIDController extends Controller
{
    public function store(Project $project, Request $request){
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index')->withError(trans('projects.not_authorized'));

        //Канал
        //Если идентификатор канала уже есть, он заменяется новым (тогда не нужно писать метод update)
        if($request->type === TelegramID::TYPE_CHANNEL){
            TelegramID::updateOrCreate(
                ['project_id' => $project->id, 'type' => TelegramID::TYPE_CHANNEL],
                [
                    'name' => $request->name,
                    'number' => $request->number,
                    'approved' => is_null($request->number) ? false : true
                ]
            );

            return redirect()->route('project.settings-sync', [$project, 'telegram'])
                ->withSuccess( trans('projects.notifications.telegram.group_create_success') );
        }

        //Личка
        //TODO Если у пользователя с таким юзернеймом уже есть id в базе данных, сразу взять его оттуда и одобрить

        if($request->type === TelegramID::TYPE_PRIVATE){
            if( TelegramID::where(['project_id' => $project->id, 'name' => $request->name])->exists() )
                return redirect()->route('project.settings-sync', [$project, 'telegram'])
                    ->withError( trans('projects.notifications.telegram.create_error') . ': ' . trans('projects.notifications.telegram.error_exists') );

            $parameters = [
                'project_id' => $project->id,
                'name' => $request->name,
                'number' => $request->number,
                'type' => TelegramID::TYPE_PRIVATE,
                'approved' => is_null($request->number) ? false : true
            ];

            //Если ID неизвестен. но в другом проекте уже указан для пользователя с таким именем, сразу взять его оттуда
            if(is_null($parameters['number'])){
                $existing_contact = TelegramID::where('name', $parameters['name'])->whereNotNull('number')->first();

                if( !is_null($existing_contact) ){
                    $parameters['number'] = $existing_contact->number;
                    $parameters['approved'] = true;
                }
            }

            TelegramID::create($parameters);

            return redirect()->route('project.settings-sync', [$project, 'telegram'])
                ->withSuccess( trans('projects.notifications.telegram.private_create_success') );
        }

    } //store

    public function approve($username, $id){ //Добавляет к неподтверждённым контактам id-номера
        //Поиск неподтверждённых контактов
        $contacts = TelegramID::where([
            //'type' => TelegramID::TYPE_PRIVATE,
            'approved' => false,
            'name' => $username
            ])->get();

        //Если контакта нет в базе данных, либо он уже одобрен, написать отказ
        if($contacts->isEmpty()){
            TelegramID::API_SendMessageTo($id, "Уважаемый $username! Ваш контакт не указан ни в одном проекте, либо уже был одобрен.");
            return;
        }

        //Добавление номера в контакты
        foreach($contacts as $contact){
            $contact->number = $id;
            $contact->approved = true;
            $contact->save();
        }

        TelegramID::API_SendMessageTo($id, "Добро пожаловать, $username!");
    }

    public function destroy(Project $project, $id){
        //Проверка полномочий пользователя
        if (Gate::denies('settings', [Project::class, $project]))
            return redirect()->route('project.index')->withError(trans('projects.not_authorized'));

        $contact = TelegramID::find($id);

        $contact->delete();
        return redirect()->route('project.settings-sync', [$project, 'telegram'])
                ->withSuccess( trans('projects.notifications.telegram.delete_success') );
    } //destroy

    //TODO Удалить или изменить после тестирования
    public function telegram(){ //Тестовая страница для проверки функций Telegram API
        $projects = Project::all();
        $contacts = TelegramID::all();
        return view('material-dashboard.admin.settings.telegram', compact('projects', 'contacts'));
    } //telegram

    public function getUpdates(){
        return TelegramID::API_MakeRequest('getUpdates');

    } //getUpdates

    public function webhookInfo(){
        return TelegramID::API_MakeRequest('getWebhookInfo');
    }

    public function setWebhook(){
        TelegramID::API_DeleteWebhook();
        $response = TelegramID::API_SetWebhook(env('WEBHOOK_URL') . '/telegram/webhook');
        return $response['ok'] ? redirect()->route('admin.settings.telegram.main') : $response['description'];
    } //setWebhook

    public function deleteWebhook(){
        $response = TelegramID::API_DeleteWebhook();
        return $response['ok'] ? redirect()->route('admin.settings.telegram.main') : $response['description'];
    } //setWebhook

    public function webhook(Request $request){
        $body = json_decode($request->getContent(), true);

        //Реакция на сообщения
        if( key_exists('message', $body) ){
            if (key_exists('text', $body['message'])) {
                //Команда на подписку (пользователь будет добавлен в проект)
                if ($body['message']['text'] === '/start') {
                    $this->approve($body['message']['from']['username'], $body['message']['from']['id']);

                }

                //Команда на удаление пользователя из проекта
                if ($body['message']['text'] === '/stop') {

                }
            }
        }

        //Реакция на назначение админом канала
        if( key_exists('my_chat_member', $body) ){
            Log::channel('leads')->info('Найден ключ my_chat_member');
            if( key_exists('new_chat_member', $body['my_chat_member']) ){
                Log::channel('leads')->info('Найден ключ new_chat_member');
                if( $body['my_chat_member']['new_chat_member']['user']['username'] === env('TELEGRAM_BOT_NAME')
                    and
                    ($body['my_chat_member']['chat']['type'] === 'channel' or $body['my_chat_member']['chat']['type'] === 'group')
                )
                    {
                        Log::channel('leads')->info('Пройдена проверка');
                        $this->approve($body['my_chat_member']['chat']['title'], $body['my_chat_member']['chat']['id']);
                    }
            }
        }

        // Log::channel('leads')->info(print_r($body, true));
    }
}
