<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Project extends BaseModel implements HasMedia
{
    protected $guarded = [
        '_method',
        '_token'
    ];

    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'projects_users', 'project_id', 'user_id')
            ->withPivot(['is_lead']);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->singleFile()
            ->useFallbackUrl('/storage/images/project_placeholder.jpg');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(60)
            ->height(60);
    }
}
