<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ADMIN = 1;
    public const MANAGER = 2;
    public const DRIVER = 3;
    public const USER = 4;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
