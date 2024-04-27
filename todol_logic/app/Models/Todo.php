<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

    protected $table = 'public.todos';

    protected $fillable = [
        'id', 'name', 'description', 'finish_date'
    ];
}
