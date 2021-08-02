<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()
            ->orderBy('task_start')
            ->filter(Request::only('search', 'trashed'))
            ->paginate(10)
            ->withQueryString()
            ->through(function ($task) {
                return [
                    'id' => $task->id,
                    //'project' => $task->project,
                    'task_start' => $task->task_start,
                    'task_worktime' => $task->task_worktime,
                    'created_at' => $task->created_at,
                    'deleted_at' => $task->deleted_at,
                ];
            });

        return Inertia::render('User/Tasks/Index', [
            'filters' => Request::all('search', 'trashed'),
            'tasks' => $tasks,
        ]);
    }

    public function create()
    {
        return Inertia::render('User/Tasks/Create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $task = new Task;

        $task->task_start = $request->task_start;
        $task->task_end = $request->task_end;
        $task->project_id = $request->project_id;
        $task->task_description = $request->task_description;
        $task->task_worktime = $request->task_worktime;

        $task->save();

        $task->users()->attach(Auth::id(), ['is_taskgiver' => true]);

        return Redirect::route('user.tasks')->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        return Inertia::render('User/Tasks/Edit', [
            'task' => [
                'id' => $task->id,
                'project' => $task->project,
                'task_start' => $task->task_start,
                'task_end' => $task->task_end,
                'task_description' => $task->task_description,
                'task_worktime' => $task->task_worktime,
                'created_at' => $task->created_at,
                'deleted_at' => $task->deleted_at,
            ],
        ]);
    }

    public function update(Task $task, \Illuminate\Http\Request $request)
    {
        $task->lasteditor_id = Auth::id();
        $task->task_start = $request->task_start;
        $task->task_end = $request->task_end;
        //$task->project_id = $request->project_id;
        $task->task_description = $request->task_description;
        $task->task_worktime = $request->task_worktime;

        $task->save();

        return Redirect::back()->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return Redirect::back()->with('success', 'Task deleted.');
    }

    public function restore(Task $task)
    {
        $task->restore();

        return Redirect::back()->with('success', 'Task restored.');
    }
}
