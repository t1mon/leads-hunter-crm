<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\Project\Project;
use App\Models\Project\TelegramID;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Journal\Facade\Journal;

class TelegramIDController extends Controller
{
    /*###############
        CRUD
    #################*/

    //Возможно, эта функция понадобится при управлении через API
    public function index(Project $project, Request $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        $ids = TelegramID::where('project_id', $project->id)->get(['id', 'name', 'type', 'approved']);
        return response()->json(['data' => $ids], Response::HTTP_OK);
    } //index

    public function store(Project $project, Request $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        //TODO: Валидация
        //...

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

            Journal::project($project,  $user->name . ' добавил контакт группового чата Telegram: ' . $request->name);
            return response()->json(['message' => 'Group chat has been added'], Response::HTTP_CREATED);
        }

        //Личка
        if($request->type === TelegramID::TYPE_PRIVATE){
            if( TelegramID::where(['project_id' => $project->id, 'name' => $request->name])->exists() ){
                Journal::projectError($project,  
                                        trans('projects.notifications.telegram.create_error') . ': ' . $request->name . ' – ' . trans('projects.notifications.telegram.error_exists'));
                return response()->json(['error' => 'Private chat already exists'], Response::HTTP_PRECONDITION_FAILED); 
            }

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
            Journal::project($project,  $user->name . ' добавил личный контакт Telegram: ' . $request->name);
            return response()->json(['message' => 'Private chat has been added'], Response::HTTP_CREATED);
        }
        
        return response()->json(['error' => 'No type specified'], Response::HTTP_PRECONDITION_FAILED);
    } //store 

    public function destroy(Project $project, $contact, Request $request){
        $user = Auth::guard('api')->user();
        //Проверка полномочий пользователя
        if (Gate::forUser($user)->denies('settings', $project))
            return response()->json(['message' => 'You are not authorized for this action'], Response::HTTP_FORBIDDEN);

        try{
            $contact = TelegramID::findOrFail($contact);
        }
        catch(\Exception $exception){
            Journal::projectError($project, $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $name = $contact->name;
        $contact->delete();

        Journal::project($project, $user->name . ' удалил Telegram-контакт ' . $name);
        return response()->json(['message' => 'Chat has been deleted'], Response::HTTP_OK);
    } //destroy

    /*###############
    Служебные функции
    #################*/
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
            Journal::projectWarning('Неудачная проверка Telegram-контакта ' . $username . ': не указан в проекте, либо уже одобрен.');
            return;
        }

        //Добавление номера в контакты
        foreach($contacts as $contact){
            $contact->number = $id;
            $contact->approved = true;
            $contact->save();
        }

        TelegramID::API_SendMessageTo($id, "Добро пожаловать, $username!");
        Journal::project($project,  'Telegram-контакт ' . $username . ' был одобрен.');
    } //approve

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
