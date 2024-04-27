<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;


    // constructor
    public function __construct()
    {
        parent::__construct();
    }

    protected $table = 'public.users';

    public $fillable = [
        'id',
        'username',
    ];

    public $hidden = [
        'password'
    ];

    public static function where(string $column, string $username): User|null
    {
        $userSQL = self::query()->where($column, $username)->first();
        if ($userSQL === null) {
            return null;
        }

        $user = new User();
        $user->fillable['id'] = $userSQL->columns['id'];
        $user->fillable['username'] = $userSQL->columns['username'];
        $user->hidden['password'] = $userSQL->columns['password'];
        return $user;
    }

}
