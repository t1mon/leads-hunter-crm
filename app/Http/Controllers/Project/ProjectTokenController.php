<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Token;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectTokenController extends Controller
{
    public function edit($id): View
    {
        $user = auth()->user();

        //$this->authorize('api_token', $user);

        $project = Project::findOrFail($id);

        // return view('project.token', compact('project'));
        return view('material-dashboard.project.token', compact('project'));
    }


    public function update(Project $project): RedirectResponse
    {
        $user = auth()->user();

        //$this->authorize('api_token', $user);

        $project->update([
            'api_token' => Str::random(60)
        ]);

        return redirect()->route('project.token', $project->id)->withSuccess(__('tokens.updated'));
    }
}
