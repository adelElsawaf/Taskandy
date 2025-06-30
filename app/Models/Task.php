<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Task extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = ['title', 'description', 'status', 'due_date', 'project_id', 'assigned_to'];

    protected $casts = [
        'status' => \App\Enums\TaskStatus::class,
        'due_date' => 'datetime'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class)->withDefault([
            'name' => 'No Project Assigned',
        ]);
    }
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}