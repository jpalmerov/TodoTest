<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

    protected $table = 'public.todos';

    public $timestamps = false;

    public $fillable = [
        'id', 'user_id', 'name', 'description', 'finish_date', 'todo_item_count'
    ];

    public function data(): array
    {
        return [
            'id' => $this->fillable['id'],
            'name' => $this->fillable['name'],
            'description' => $this->fillable['description'],
            'finish_date' => $this->fillable['finish_date'],
            'item_count' => $this->fillable['todo_item_count'] ?? 0
        ];
    }

    public static function get(int $id): Todo|null
    {
        $todoSQL = Todo::query()->where('id', $id)->first();

        if ($todoSQL === null) {
            return null;
        }

        $todo = new Todo();
        $todo->fillable['id'] = $id;
        $todo->fillable['name'] = $todoSQL['name'];
        $todo->fillable['description'] = $todoSQL['description'];
        $todo->fillable['finish_date'] = $todoSQL['finish_date'];
        $todo->fillable['todo_item_count'] = TodoItem::query()->where('todo_id', $id)->count();
        return $todo;
    }

}
