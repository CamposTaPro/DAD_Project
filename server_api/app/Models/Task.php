<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'owner_id',
        'project_id',
        'completed',
        'description',
        'notes',
        'total_hours',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }
}
