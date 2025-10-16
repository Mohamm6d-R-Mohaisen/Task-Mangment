<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Resources\Admin\AdminResource;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Project;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles,HasApiTokens;


    protected $guard_name = 'admin';
    public $resource = AdminResource::class;
    public $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone_code',
        'phone',
        'email',
        'email_verified_at',
        'username',
        'password',
        'remember_token',
        'status',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // these user types have a gaurd name for each
    public const USER_TYPES = [
        '0' => 'admin',
        '1' => 'user',
    ];

    public function scopeSearch($query, $request)
    {
        if (!empty($request->search['value'])) {
            $search = '%' . $request->search['value'] . '%';
            return $query->where('name', 'LIKE' , '%' . $search . '%');
        }
        return $query;
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }
public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}
