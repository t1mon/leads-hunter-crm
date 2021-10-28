<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Email;

use Illuminate\Http\Request;
use App\Http\Requests\EmailRequest;

use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{

    public function store(EmailRequest $request)
    {
        try{
            //Если указанный адрес уже существует и привязан к уже существующему проекту, не создавать его
            if(Email::where([ ['email', $request->email], ['project_id', $request->project_id] ])->exists())
                throw new \Exception('Ошибка: адрес с таким проектом уже существует.');
            Email::create($request->all());
        } catch(\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->route('project.notification', ['project' => $request->project_id])
                ->withErrors('Ошибка: адрес с таким проектом уже существует.');
        }
        return redirect()->route('project.notification', ['project' => $request->project_id])->withSuccess('Email добавлен в базу');
    } //store

    public function destroy(Project $project, Email $email){
        $email->delete();
        return redirect()->route('project.notification', $project)->withSuccess('Email удалён из базы.');
    }
}
