<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Auth::user()->projects()
            ->orderBy('name')
            ->filterByColumn($request->only('search', 'trashed'), 'name')
            ->with('media')
            ->paginate(10)
            ->withQueryString()
            ->through(function ($project) {
                /*
                $output = $project->only('id', 'name', 'created_at', 'deleted_at');
                $output['avatar'] = $project->getFirstMediaUrl('avatars', 'thumb');
                return $output;

                // Тоже вариант
                */
                return [
                    'id' => $project->id,
                    'avatar' => $project->getFirstMediaUrl('avatars', 'thumb'),
                    'name' => $project->name,
                    'created_at' => $project->created_at,
                    'deleted_at' => $project->deleted_at,
                ];
            });
        // https://laravel.com/api/8.x/Illuminate/Pagination/AbstractPaginator.html#method_through Не затирающий остальные данные аналог transform

        return Inertia::render('User/Projects/Index', [
            'filters' => $request->only('search', 'trashed'),
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

        if ($request->hasFile('avatar')) $project->addMediaFromRequest('avatar')->toMediaCollection('avatars');

        $project->save();
        $project->users()->attach(Auth::id(), ['is_lead' => true]);

        return Redirect::route('user.projects.index')->with('success', 'Проект ' . $project->name . ' создан.');
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

        if ($request->hasFile('avatar')) $project->addMediaFromRequest('avatar')->toMediaCollection('avatars');

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

    public function inviteToProject(Project $project, Request $request)
    {
        $users = User::whereNotIn('id', function ($query) use ($project) {
                $query->select('user_id')
                    ->from('projects_users')
                    ->where('project_id', $project->id);
            })
            ->orderBy('created_at')
            ->filterByColumn($request->only('search', 'trashed'), 'name')
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
            'filters' => $request->only('search', 'trashed'),
            'users' => $users,
            'project' => $project
        ]);
    }

    public function inviteStore(Project $project, Request $request)
    {
        foreach ($request->picked_users as $userId) $project->users()->attach($userId);

        return Redirect::back()->with('success', 'Пользователи добавлены к проекту ' . $project->name);
    }
}
