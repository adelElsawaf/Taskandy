<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function memberships()
    {
        return $this->hasMany(ProjectMembership::class);
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_memberships')
            ->withPivot('membership_type', 'joined_at')
            ->withTimestamps();
    }
}