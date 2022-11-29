<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'responsible_id',
        'status',
        'preview_start_date',
        'preview_end_date',
        'real_start_date',
        'real_end_date',
        'total_hours',
        'billed',
        'total_price',
    ];

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 'P':
                return 'Pending';
            case 'W':
                return 'Work In Progress';
            case 'C':
                return 'Completed';
            case 'I':
                return 'Interrupted';
            case 'D':
                return 'Discarded';
        }
    }

    public function getTotalTasksAttribute()
    {
        return Task::where('project_id', $this->id)->count();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}
