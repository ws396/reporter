<?php

namespace App\Exports;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TasksExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $chosenUser;

    public function __construct($chosenUser)
    {
        $this->chosenUser = $chosenUser;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $cb = function ($query) {
            $query->where('name', 'like', '%' . Request::input('search') . '%');
        };

        $output = $this->chosenUser->tasks()
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
                    'task_end' => $task->task_end,
                    'task_worktime' => $task->task_worktime,
                    'task_status' => $task->task_status,
                    'created_at' => $task->created_at,
                    'deleted_at' => $task->deleted_at,
                    'task_description' => $task->task_description,
                ];
            });

        $worktimeHours = 0;
        $worktimeMinutes = 0;
        foreach ($output as $row) {
            if (!preg_match('~(\d+) ч. (\d+) мин.~', $row['task_worktime'], $match)) {
                break;
            } else {
                $worktimeHours += $match[1];
                $worktimeMinutes += $match[2];
            }
        }
        $worktimeHours += intdiv($worktimeMinutes, 60);
        $worktimeMinutes = $worktimeMinutes % 60;

        $output->push([
            'id' => 'Сумма:',
            'project' => '',
            'task_start' => '',
            'task_end' => '',
            'task_worktime' => $worktimeHours . ' ч. ' . $worktimeMinutes . ' мин.',
            'task_status' => '',
            'created_at' => '',
            'deleted_at' => '',
            'task_description' => '',
        ]);

        return $output;
    }

    public function headings(): array
    {
        return [
            '#',
            'Проект',
            'Начало работы',
            'Конец работы',
            'Время работы',
            'Статус задачи',
            'Дата создания',
            'Дата удаления',
            'Описание'
        ];
    }
}
