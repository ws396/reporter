<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\User;
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
            'can' => [
                'create_project' => Auth::user()->can('lead_actions'),
                'edit_project' => Auth::user()->can('lead_actions'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('User/Projects/Create');
    }

    public function store(ProjectRequest $request)
    {
        $project = new Project;

        $project->name = $request->name;

        $project->save();

        $project->users()->attach(Auth::id(), ['is_lead' => true]);

        return Redirect::route('user.projects')->with('success', 'Проект ' . $project->name . ' создан.');
    }

    public function edit(Project $project)
    {
        return Inertia::render('User/Projects/Edit', [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'updated_at' => $project->deleted_at,
                'deleted_at' => $project->deleted_at,
            ],
        ]);
    }

    public function update(Project $project, ProjectRequest $request)
    {
        $project->name = $request->name;

        $project->save();

        return Redirect::back()->with('success', 'Проект ' . $project->name . ' обновлён.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return Redirect::back()->with('success', 'Проект ' . $project->name . ' удалён.');
    }

    public function restore(Project $project)
    {
        $project->restore();

        return Redirect::back()->with('success', 'Проект ' . $project->name . ' восстановлен.');
    }

    public function inviteToProject(Project $project)
    {
        $users = User::whereNotIn('id', function ($query) use ($project) {
                $query->select('user_id')
                    ->from('projects_users')
                    ->where('project_id', $project->id);
            })
            ->orderBy('created_at')
            ->filter(Request::only('search', 'trashed'))
            ->paginate(10)
            ->withQueryString()
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'created_at' => $user->created_at,
                    'deleted_at' => $user->deleted_at,
                    'role' => $user->role,
                ];
            });

        return Inertia::render('Admin/Invite/ToProject', [
            'filters' => Request::all('search', 'trashed'),
            'users' => $users,
            'project' => $project
        ]);
    }

    public function inviteStore(Project $project, \Illuminate\Http\Request $request)
    {
        foreach ($request->picked_users as $userId) {
            $project->users()->attach($userId);
        }

        return Redirect::back()->with('success', 'Пользователи добавлены к проекту ' . $project->name);
    }
}
