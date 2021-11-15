<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\FiltersTrait;

class BaseModel extends Model
{
    use FiltersTrait;

    // Accessors
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

    // Mutators
    public function setTaskStartAttribute($date)
    {
        $this->attributes['task_start'] = Carbon::parse($date);
    }

    public function setTaskEndAttribute($date)
    {
        $this->attributes['task_end'] = Carbon::parse($date);
    }
}
