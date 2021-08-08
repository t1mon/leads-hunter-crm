<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('project.index', [
            'projects' => Project::where('user_id', Auth::guard()->id())
                                ->with('leads', 'leadsToday')
                                ->withCount('leads', 'leadsToday')
                                ->paginate(50)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        $request->merge([
            'user_id' => Auth::id()
        ]);

        $project = Project::create($request->only('name', 'user_id'));

        $project->update([
            'api_token' => Str::random(60)
        ]);

        return redirect()->route('project.index')->withSuccess('Проект успешно создан');
        ;
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Project $project)
    {
        return view('project.show', compact('project'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function journal(Request $request, Project $project)
    {
        if (Gate::denies('view', $project)) {
            return redirect()->route('project.index');
        }

        $leads = $project->leads();

        if ($request->has('date_from') && !empty($request->date_from)) {
            $leads->whereDate('created_at', '>=', Carbon::parse($request->date_from)->format('Y-m-d'));
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $leads->whereDate('created_at', '<=', Carbon::parse($request->date_to)->format('Y-m-d'));
        }


        $leads = $leads->orderBy('created_at', 'desc')->paginate(50)->withPath("?" . $request->getQueryString());


        return view('project.journal', compact('project', 'leads'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('project.index')->withSuccess('Проект удален');
    }
}
