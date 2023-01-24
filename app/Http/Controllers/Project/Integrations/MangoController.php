<?php

namespace App\Http\Controllers\Project\Integrations;

use App\Http\Controllers\Controller;
use App\Services\Project\Integrations\MangoService;
use App\Http\Requests\Project\Integrations\Mango\Create as CreateRequest;
use App\Http\Requests\Project\Integrations\Mango\Update as UpdateRequest;
use App\Models\Project\Project;
use App\Services\Project\ProjectService;

class MangoController extends Controller
{
    public function __construct(
        private MangoService $service,
    )
    {} //Конструктор

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $project_id)
    {
        $project = $this->service->findProject(id: $project_id, fail: true);
        $integrations = $this->service->findByProjectId(project: $project_id);

        return view('material-dashboard.project.integrations.mango.index', compact('integrations', 'project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(int $project_id)
    {
        $project = $this->service->findProject(id: $project_id, fail: true);
        return view('material-dashboard.project.integrations.mango.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Project\Integrations\Mango\Create
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $integration = $this->service->create($request);
        return redirect()->route('project.integrations.mango.index', $integration->project_id)->withSuccess('Интеграция добавлена в проект');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $mango
     * @return \Illuminate\Http\Response
     */
    public function show(int $mango)
    {
        $integration = $this->service->findById(id: $mango, fail: true, with: 'project');
        $project = $integration->project;

        return view('material-dashboard.project.integrations.mango.show', compact('integration', 'project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $mango
     * @return \Illuminate\Http\Response
     */
    public function edit(int $mango)
    {
        $integration = $this->service->findById(id: $mango, fail: true, with: 'project');
        $project = $integration->project;
        return view('material-dashboard.project.integrations.mango.edit', compact('integration', 'project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Project\Integrations\Mango\Update  $request
     * @param  int  $mango
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, int $mango)
    {
        $this->service->update(mango: $mango, request: $request);
        return redirect()->route('project.integrations.mango.show', $mango)->withSuccess('Данные интеграции обновлены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $mango
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $mango)
    {
        $this->service->remove(mango: $mango);
        return redirect()->route('project.integrations.mango.index')->withSuccess('Интеграция удалена');
    }

    /**
     * Toggle the integration
     *
     * @param  int  $mango
     * @return \Illuminate\Http\Response
     */
    public function toggle(int $mango)
    {
        $this->service->toggle($mango);
        return back();
    }
}
