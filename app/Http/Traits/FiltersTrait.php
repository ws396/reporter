<?php

namespace App\Http\Traits;

trait FiltersTrait
{
    public function scopeFilterByColumn($query, array $filters, $type)
    {
        if (isset($filters['search'])) $query->where($type, 'like', '%' . $filters['search'] . '%');
    }

    public function scopeFilterDeleted($query, array $filters)
    {
        if (isset($filters['trashed'])) {
            if ($filters['trashed'] === 'with')
                $query->withTrashed();
            else if ($filters['trashed'] === 'only')
                $query->onlyTrashed();
        }
    }
}
