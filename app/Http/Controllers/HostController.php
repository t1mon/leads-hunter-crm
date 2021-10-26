<?php

namespace App\Http\Controllers;

use App\Http\Requests\HostRequest;
use App\Models\Host;
use App\Models\Project;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class HostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HostRequest $request)
    {
        $host = parse_url($request->host);
        $request->merge([
            'host' => $host['host'],
        ]);

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


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Host  $host
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Host $host)
    {
        $host->delete();
        return redirect()->route('project.hosts', $project)->withSuccess(trans('projects.hosts.delete-success'));
    } //destroy
}
