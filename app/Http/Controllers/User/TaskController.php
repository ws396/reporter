<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(Project $project, Request $request)
    {
        $tasks = $project->tasks()
            ->orderBy('task_start')
            ->filter($request->only('search', 'trashed'))
            ->paginate(10)
            ->withQueryString()
            ->through(function ($task) {
                return [
                    'id' => $task->id,
                    'task_start' => $task->task_start,
                    'task_worktime' => $task->task_worktime,
                    'task_status' => $task->task_status,
                    'created_at' => $task->created_at,
                    'deleted_at' => $task->deleted_at,
                ];
            });

        return Inertia::render('User/Tasks/Index', [
            'filters' => $request->only('search', 'trashed'),
            'tasks' => $tasks,
            'users' => $project->users()->get(),
            'project' => $project,
            'can' => [
                'invite_to_task' => Auth::user()->can('lead_actions'),
                'invite_to_project' => Auth::user()->can('lead_actions'),
            ],
        ]);
    }

    public function create(Project $project)
    {
        return Inertia::render('User/Tasks/Create', [
            'project' => $project
        ]);
    }

    public function store(Project $project, TaskRequest $request)
    {
        $task = new Task;

        $task->task_start = $request->task_start;
        $task->task_end = $request->task_end;
        $task->project_id = $project->id;
        $task->task_status = $request->task_status;
        $task->task_description = $request->task_description;
        $task->task_worktime = $request->task_worktime;

        $task->save();

        $task->users()->attach(Auth::id(), ['is_taskgiver' => true]);

        return Redirect::route('user.projects.tasks.index', $project->id)->with('success', 'Задача #' . $task->id . ' создана.');
    }

    public function edit(Project $project, Task $task)
    {
        return Inertia::render('User/Tasks/Edit', [
            'task' => [
                'id' => $task->id,
                'project_id' => $project->id,
                'task_start' => $task->task_start,
                'task_end' => $task->task_end,
                'task_description' => $task->task_description,
                'task_worktime' => $task->task_worktime,
                'task_status' => $task->task_status,
                'created_at' => $task->created_at,
                'deleted_at' => $task->deleted_at,
                'updated_at' => $task->updated_at,
                'last_editor' => $task->lasteditor_id ? User::find($task->lasteditor_id)->name : null,
            ],
            'taskgiver' => $task->taskgivers()->first(),
            'users' => $task->users()->get(),
            'project' => $project,
            'can' => [
                'invite_to_task' => Auth::user()->can('lead_actions'),
            ],
        ]);
    }

    public function update(Project $project, Task $task, TaskRequest $request)
    {
        $task->lasteditor_id = Auth::id();
        $task->task_start = $request->task_start;
        $task->task_end = $request->task_end;
        $task->task_status = $request->task_status;
        $task->task_description = $request->task_description;
        $task->task_worktime = $request->task_worktime;

        $task->save();

        return Redirect::back()->with('success', 'Задача #' . $task->id . ' обновлена.');
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return Redirect::back()->with('success', 'Задача #' . $task->id . ' удалена.');
    }

    public function restore(Project $project, Task $task)
    {
        $task->restore();

        return Redirect::back()->with('success', 'Задача #' . $task->id . ' восстановлена.');
    }

    public function inviteToTask(Project $project, Task $task, Request $request)
    {
        $users = User::whereNotIn('id', function ($query) use ($task) {
                $query->select('user_id')
                    ->from('tasks_users')
                    ->where('task_id', $task->id);
            })
            ->whereIn('id', function ($query) use ($project) {
                $query->select('user_id')
                    ->from('projects_users')
                    ->where('project_id', $project->id);
            })
            ->orderBy('created_at')
            ->filter($request->only('search', 'trashed'))
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

        return Inertia::render('Admin/Invite/ToTask', [
            'filters' => $request->only('search', 'trashed'),
            'users' => $users,
            'project' => $project,
            'task' => $task,
        ]);
    }

    public function inviteStore(Project $project, Task $task, Request $request)
    {
        foreach ($request->picked_users as $userId) {
            $task->users()->attach($userId);
        }

        return Redirect::back()->with('success', 'Пользователи добавлены к задаче #' . $task->id);
    }
}
