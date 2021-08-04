<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Auth::user()->projects()
            ->orderBy('name')
            ->filter(Request::only('search', 'trashed'))
            ->paginate(10)
            ->withQueryString()
            ->through(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'created_at' => $project->created_at,
                    'deleted_at' => $project->deleted_at,
                ];
            });

        return Inertia::render('User/Projects/Index', [
            'filters' => Request::all('search', 'trashed'),
            'projects' => $projects,
        ]);
    }

    public function create()
    {
        return Inertia::render('User/Projects/Create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $project = new Project;

        $project->name = $request->name;

        $project->save();

        $project->users()->attach(Auth::id(), ['is_lead' => true]);

        return Redirect::route('user.projects')->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        return Inertia::render('User/Projects/Edit', [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
            ],
        ]);
    }

    public function update(Project $project, \Illuminate\Http\Request $request)
    {
        $project->name = $request->name;

        $project->save();

        return Redirect::back()->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return Redirect::back()->with('success', 'Project deleted.');
    }

    public function restore(Project $project)
    {
        $project->restore();

        return Redirect::back()->with('success', 'Project restored.');
    }
}
