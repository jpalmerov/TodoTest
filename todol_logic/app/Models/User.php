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
        $this->table = 'users';
    }

    public string $password;
    public string $username;
    public int $id;

    public static function where(string $column, string $username): User
    {
        $userSQL = self::query()->where($column, $username)->first();
        $user = new User();
        $user->id = $userSQL->columns['id'];
        $user->username = $userSQL->columns['username'];
        $user->password = $userSQL->columns['password'];
        return $user;
    }
}
