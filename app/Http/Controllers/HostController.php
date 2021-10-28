<?php

namespace App\Http\Controllers;

use App\Http\Requests\HostRequest;
use App\Models\Host;
use App\Models\Project;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HostController extends Controller
{
    public function store(HostRequest $request)
    {
        if(filter_var($request->host, FILTER_VALIDATE_URL)){
            $host = parse_url($request->host);
            $request->merge([
                'host' => $host['host'],
            ]);
        }
        $request->merge(['host' =>  Str::lower($request->host)]); //Перевод в нижний регистр


        try {
            if (Host::where('host', $request->host)->exists()) {
                throw new \Exception(trans('projects.hosts.create-error') . ': ' . trans('projects.hosts.error-exists'));
            }
            Host::create($request->all());
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('project.hosts', ['project' => $request->project_id])
                ->withErrors(trans('projects.hosts.create-error') . ': ' . trans('projects.hosts.error-exists'));
        }
        return redirect()->route('project.hosts', ['project' => $request->project_id])->withSuccess(trans('projects.hosts.create-success'));
    } //store

    public function destroy(Project $project, Host $host)
    {
        $host->delete();
        return redirect()->route('project.hosts', $project)->withSuccess(trans('projects.hosts.delete-success'));
    } //destroy
}
