<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            //->withTimestamps()
            //->withPivot(['is_taskgiver']);
    }

    public function projects()
    {
        return $this->hasOne(Project::class);
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

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('id', 'like', '%' . $search . '%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
