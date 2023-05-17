<?php

namespace App\Http\Controllers\Project\Integrations\Calltracking;

use App\Http\Controllers\Controller;
use App\Models\Project\Integrations\Calltracking\Phone;
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

    public function edit(int $project_id, int $phone_id)
    {
        $project = Project::findOrFail($project_id);
        $phone = Phone::findOrFail($phone_id);
        return view('material-dashboard.project.basic_settings.calltracking.phones.edit', compact('project', 'phone'));
    } //edit

    public function update(Request $request, int $project_id, int $phone_id)
    {
        $request->validate(
            rules: [
                'phone' => 'required|integer|regex:/^\d+$/s',
                'project_id' => 'required|exists:projects,id',
            ]
        );

        $phone = Phone::findOrFail($phone_id);

        $this->repository->update(
            integration: $phone,
            project: $request->project_id,
            phone: $request->phone,
            enabled: $phone->enabled,
        );

        return redirect()->route('project.settings-basic', $request->project_id);
    } //update

    public function toggle(int $project_id, int $phone_id)
    {
        $project = Project::findOrFail($project_id);
        $phone = Phone::findOrFail($phone_id);

        $this->repository->toggle($phone);
        return redirect()->route('project.settings-basic', $project_id);
    } //toggle

    public function destroy(int $project_id, int $phone_id)
    {
        $phone = Phone::findOrFail($phone_id);
        $this->repository->remove($phone);
        return redirect()->route('project.settings-basic', $project_id);
    } //destroy
}
