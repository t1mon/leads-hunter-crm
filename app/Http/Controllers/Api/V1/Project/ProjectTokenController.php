<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project\Project;
use App\Models\Token;
use App\Http\Resources\Project as ProjectResource;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Journal\Facade\Journal;

class ProjectTokenController extends Controller
{
    public function edit(Project $project, Request $request)
    {
        $user = Auth::guard('api')->user();

        $this->authorize('api_token', $user);

        return new ProjectResource($project);
    } //edit

    public function update(Project $project, Request $request): RedirectResponse
    {
        $user = Auth::guard('api')->user();

        $this->authorize('api_token', $user);

        $project->update([
            'api_token' => Str::random(60)
        ]);

        Journal::project($project, $user->name . ' обновил токен проекта: "' . $project->api_token . '".');

        return response()->json(['message' => 'Project token upated'], Response::HTTP_OK);
    } //update
}
