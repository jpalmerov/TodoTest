<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{

    protected $table = 'public.todo_items';

    protected $fillable = [
        'id', 'name', 'status'
    ];
}
