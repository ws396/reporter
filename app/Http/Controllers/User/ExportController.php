<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Exports\TasksExport;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class ExportController extends Controller
{
    public function index(User $user)
    {
        $chosenUser = self::getValidUserOrAbort($user);

        $cb = function ($query) {
            $query->where('name', 'like', '%' . Request::input('search') . '%');
        };

        $tasks = $chosenUser->tasks()
            ->whereHas('projects', $cb)
            ->with('projects')
            ->orderBy('task_start')
            ->filterDeleted(Request::only('trashed'))
            ->paginate(10)
            ->withQueryString()
            ->through(function ($task) {
                return [
                    'id' => $task->id,
                    'project' => $task->projects->name,
                    'task_start' => $task->task_start,
                    'task_worktime' => $task->task_worktime,
                    'created_at' => $task->created_at,
                    'deleted_at' => $task->deleted_at,
                ];
            });

        return Inertia::render('User/Export/Index', [
            'filters' => Request::all('search', 'trashed'),
            'tasks' => $tasks,
            'user' => $chosenUser
        ]);
    }

    public function export(User $user)
    {
        $chosenUser = self::getValidUserOrAbort($user);

        return Excel::download(new TasksExport($chosenUser, Request::only('search', 'trashed')), 'Задачи ' . $chosenUser->name . '.xlsx');
    }

    private function getValidUserOrAbort($user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role === User::IS_ADMIN) {
            return $user;
        } else if ($user->id !== $currentUser->id) {
            abort(403);
        }

        return $user;
    }

}
