<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends BaseModel
{
    protected $guarded = [
        '_method',
        '_token'
    ];

    use HasFactory;
    use SoftDeletes;

    public function users()
    {
        return $this->belongsToMany(User::class, 'tasks_users', 'task_id', 'user_id');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function taskgivers()
    {
        return $this->belongsToMany(User::class, 'tasks_users', 'task_id', 'user_id')
            //->withTimestamps()
            ->withPivot(['is_taskgiver'])
            ->wherePivot('is_taskgiver', 1);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }
}
