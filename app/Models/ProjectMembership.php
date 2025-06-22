<?php

namespace App\Models;

use App\Enums\ProjectMembershipType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMembership extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectMembershipFactory> */
    use HasFactory;

    protected $fillable = [
        "project_id",
        "user_id",
        "membership_type",
        "joined_at",
    ];
    protected $casts = [
        'membership_type' => ProjectMembershipType::class,
        'joined_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function isOwner(): bool
    {
        return $this->membership_type === ProjectMembershipType::OWNER;
    }

    public function isAdmin(): bool
    {
        return $this->membership_type === ProjectMembershipType::ADMIN;
    }

    public function canManageProject(): bool
    {
        return $this->membership_type->canManageProject();
    }
}