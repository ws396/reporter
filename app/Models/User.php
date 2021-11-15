<?php

namespace App\Models;

use App\Http\Traits\FiltersTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, FiltersTrait;

    public const IS_USER = 1;
    public const IS_LEAD = 2;
    public const IS_ADMIN = 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCreatedAtAttribute($date)
    {
        return $date !== null ? Carbon::parse($date)->format('d-m-Y H:i:s') : $date;
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'tasks_users', 'user_id', 'task_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projects_users', 'user_id', 'project_id');
    }
}
