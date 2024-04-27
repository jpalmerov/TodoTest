<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table = 'public.users';

    public $fillable = [
        'id',
        'username',
    ];

    public $hidden = [
        'password'
    ];

    public static function get(string $username, int $id = null): User|null
    {

        $userSQL = User::query()->where($id === null ? 'username' : 'id', $id ?? $username)->first();

        if ($userSQL === null) {
            return null;
        }

        $user = new User();
        $user->fillable['id'] = $userSQL['id'];
        $user->fillable['username'] = $userSQL['username'];
        $user->hidden['password'] = $userSQL['password'];
        return $user;
    }

    public function save(array $options = []): bool
    {
        $username = $this->fillable['username'];
        $password = $this->hidden['password'];

        return User::query()->insert([
            'username' => $username,
            'password' => $password
        ]);
    }

    public function data(): array
    {
        return [
            'id' => $this->fillable['id'],
            'username' => $this->fillable['username']
        ];
    }

}
