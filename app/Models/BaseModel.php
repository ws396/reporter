<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }
    public function getDeletedAtAttribute($date)
    {
        return $date !== null ? Carbon::parse($date)->format('d-m-Y H:i:s') : $date;
    }
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }
    public function getTaskStartAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }
    public function getTaskEndAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }
    /*
    public function getTaskWorktimeAttribute($date)
    {
        return Carbon::createFromFormat('H:i:s', $date)->format('H ч. i мин.');
    }
    */

    public function setTaskStartAttribute($date)
    {
        $this->attributes['task_start'] = Carbon::parse($date);
    }
    public function setTaskEndAttribute($date)
    {
        $this->attributes['task_end'] = Carbon::parse($date);
    }
    /*
    public function setTaskWorktimeAttribute($date)
    {
        $this->attributes['task_worktime'] = Carbon::createFromFormat('H ч. i мин.', $date);
    }
    */
}
