<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\HostRequest;
use App\Models\Project\Host;
use App\Models\Project\Project;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Journal\Facade\Journal;

class HostController extends Controller
{
    public function store(HostRequest $request)
    {
        //TODO Сделать проверку полномочий

        if(filter_var($request->host, FILTER_VALIDATE_URL)){
            $host = parse_url($request->host);
            $request->merge([
                'host' => $host['host'],
            ]);
        }
        $request->merge(['host' =>  Str::lower($request->host), 'user_id' => Auth::user()->id]); //Перевод в нижний регистр


        try {
            if (Host::where(['host' => $request->host, 'project_id' => $request->project_id])->exists()) {
                throw new \Exception(trans('projects.hosts.create-error') . ' ' . $request->host . ': ' . trans('projects.hosts.error-exists'));
            }
            Host::create($request->all());
        } catch (\Exception $exception) {
            Journal::projectError(Project::find($request->project_id), $exception->getMessage());
            Log::error($exception->getMessage());
            return redirect()->route('project.settings-basic', ['project' => $request->project_id])
                ->withErrors(trans('projects.hosts.create-error') . ': ' . trans('projects.hosts.error-exists'));
        }
        Journal::project(Project::find($request->project_id), Auth::user()->name . ' добавил хост ' . $request->host);
        return redirect()->route('project.settings-basic', ['project' => $request->project_id])->withSuccess(trans('projects.hosts.create-success'));
    } //store

    public function destroy(Project $project, Host $host)
    {
        //TODO Сделать проверку полномочий

        $name = $host->host;
        $host->delete();
        Journal::project($project, Auth::user()->name . ' удалил хост ' . $name);
        return redirect()->route('project.settings-basic', $project)->withSuccess(trans('projects.hosts.delete-success'));
    } //destroy
}
