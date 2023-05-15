<?php

namespace App\Http\Controllers\Project\Integrations\Calltracking;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use Illuminate\Http\Request;

use App\Repositories\Project\Integrations\Calltracking\Phone\Repository;
use App\Repositories\Project\Integrations\Calltracking\Phone\ReadRepository;

class PhoneController extends Controller
{
    public function __construct(
        private Repository $repository,
    )
    {
        //
    } // Конструктор

    public function create(int $project_id)
    {
        $project = Project::findOrFail($project_id);
        return view('material-dashboard.project.basic_settings.calltracking.phones.create', ['project' => $project]);
    } //create

    public function store(Request $request)
    {
        $request->validate(
            rules: [
                'phone' => 'required|integer|regex:/^\d+$/s',
                'project_id' => 'required|exists:projects,id',
            ]
        );

        $this->repository->create(
            project: $request->project_id,
            phone: $request->phone
        );

        return redirect()->route('project.settings-basic', $request->project_id);
    } //store

    public function edit()
    {

    } //edit

    public function update()
    {

    } //update

    public function destroy()
    {

    } //destroy
}
