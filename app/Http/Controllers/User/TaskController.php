<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Carbon\Carbon;
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
                    'project' =>$task->project,
                    'deleted_at' => $task->deleted_at,
                ];
            });

        return Inertia::render('User/Tasks/Index', [
            'filters' => Request::all('search', 'trashed'),
            'tasks' => $tasks,
        ]);

        /*
        return Inertia::render('tasks/Index', [
            'filters' => Request::all('search', 'trashed'),
            'tasks' => Auth::user()->account->tasks()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($task) => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'phone' => $task->phone,
                    'city' => $task->city,
                    'deleted_at' => $task->deleted_at,
                ]),
        ]);
        */
    }

    public function create()
    {
        return Inertia::render('User/Tasks/Create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        /*
        $item = Auth::user()->tasks()->create(
            Request::validate([
                'project' => ['required', 'max:100'],
                'team_id' => ['nullable', 'max:50'],
                'task_start' => ['nullable', 'max:50'],
                'task_end' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );
        */

        $task= new Task;

        $task->user_id = Auth::id();
        $task->team_id = 0;
        $task->task_start = Carbon::parse($request->task_start);
        $task->task_end = Carbon::parse($request->task_end);
        $task->project = $request->project;
        $task->task_description = $request->task_description;
        $task->task_worktime = Carbon::parse("15:04");

        $task->save();

        return Redirect::route('user.tasks')->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        return Inertia::render('User/Tasks/Edit', [
            'task' => [
                'id' => $task->id,
                /*
                'name' => $task->name,
                'email' => $task->email,
                'phone' => $task->phone,
                'address' => $task->address,
                'city' => $task->city,
                'region' => $task->region,
                'country' => $task->country,
                'postal_code' => $task->postal_code,
                'deleted_at' => $task->deleted_at,
                'contacts' => $task->contacts()->orderByName()->get()->map->only('id', 'name', 'city', 'phone'),
                */
            ],
        ]);
    }
}
