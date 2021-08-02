<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    protected $guarded = [
        '_method',
        '_token'
    ];

    use HasFactory;
    use SoftDeletes;

    public function users()
    {
        return $this->belongsToMany(User::class, 'tasks_users', 'task_id', 'user_id')
            //->withTimestamps()
            ->withPivot(['is_taskgiver']);
    }

    public function projects()
    {
        return $this->hasOne(Project::class);
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

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y H:i:s');
    }
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y H:i:s');
    }
    public function getTaskWorktimeAttribute($date)
    {
        return Carbon::createFromFormat('H:i:s', $date)->format('H ч. i мин.');
    }

    public function setTaskStartAttribute($date)
    {
        $this->attributes['task_start'] = Carbon::parse($date);
    }
    public function setTaskEndAttribute($date)
    {
        $this->attributes['task_end'] = Carbon::parse($date);
    }
    public function setTaskWorktimeAttribute($date)
    {
        $this->attributes['task_worktime'] = Carbon::createFromFormat('H ч. i мин.', $date);
    }
}
