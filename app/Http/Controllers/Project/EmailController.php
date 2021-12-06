<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Project\Email;

use Illuminate\Http\Request;
use App\Http\Requests\EmailRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Journal\Facade\Journal;

class EmailController extends Controller
{

    public function store(EmailRequest $request)
    {
        try{
            //Если указанный адрес уже существует и привязан к уже существующему проекту, не создавать его
            if(Email::where([ ['email', $request->email], ['project_id', $request->project_id] ])->exists())
                throw new \Exception('Ошибка: адрес с таким проектом уже существует.');
            $email = Email::create($request->all());
            Journal::project(Project::find($request->project_id), Auth::user()->name . ' добавил e-mail ' . $email->email);
        } catch(\Exception $exception){
            Log::error($exception->getMessage());
            Journal::projectError(Project::find($request->project_id), $exception->getMessage());
            return redirect()->route('project.notification', ['project' => $request->project_id])
                ->withErrors('Ошибка: адрес с таким проектом уже существует.');
        }
        return redirect()->route('project.settings-sync', ['project' => $request->project_id])->withSuccess('Email добавлен в базу');
    } //store

    public function destroy(Project $project, Email $email){
        $email_name = $email->email;
        $email->delete();
        Journal::project($project, 'Пользователь ' . Auth::user()->name . ' удалил e-mail ' . $email->email);
        return redirect()->route('project.settings-sync', $project)->withSuccess('Email удалён из базы.');
    }
}
