<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Remove Sanctum Tokens
//use Laravel\Sanctum\HasApiTokens;
// Add Laravel Tokens
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'photo_url',
        'type',
        'blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function order_item()
    {
        return $this->hasMany(Order_Item::class);
    }

    public function getGenderNameAttribute()
    {
        return $this->gender == 'M' ? 'Masculine' : 'Feminine';
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'responsible_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
    }

    public function assigedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_user');
    }

    // Relations that return only a subset of the tasks

    // Owner and Completed
    public function tasksCompleted()
    {
        return $this->hasMany(Task::class, 'owner_id')->where('completed', 1);
    }

    // Owner and NOT Completed
    public function tasksNotCompleted()
    {
        return $this->hasMany(Task::class, 'owner_id')->where('completed', 0);
    }

    // Assigned and Completed
    public function assigedTasksCompleted()
    {
        return $this->belongsToMany(Task::class, 'task_user')->where('completed', 1);
    }

    // Assigned and Not Completed
    public function assigedTasksNotCompleted()
    {
        return $this->belongsToMany(Task::class, 'task_user')->where('completed', 0);
    }
}
