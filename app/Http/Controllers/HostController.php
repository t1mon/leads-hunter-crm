<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Project;
use App\Models\Host;

class HostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if(Host::where('host', $request->host)->exists())
                throw new \Exception( trans('projects.hosts.create-error') . ': ' . trans('projects.hosts.error-exists') );

            Host::create($request->all());

        } catch(\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->route('project.hosts', ['project' => $request->project_id])
                ->withErrors( trans('projects.hosts.create-error') . ': ' . trans('projects.hosts.error-exists') );
        }
        return redirect()->route('project.hosts', ['project' => $request->project_id])->withSuccess(trans('projects.hosts.create-success'));
    } //store


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Host  $host
     * @return \Illuminate\Http\Response
     */
    public function destroy(Host $host)
    {
        $project_id = $host->project_id;
        $host->delete();
        return redirect()->route('project.hosts', [$project_id])->withSuccess(trans('projects.hosts.delete-success'));
    } //destroy
}
