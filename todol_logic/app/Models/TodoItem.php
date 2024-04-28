<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{

    protected $table = 'public.todo_items';
    public $timestamps = false;

    public $fillable = [
        'id', 'todo_id', 'name', 'status'
    ];

    public static function get(int $id): TodoItem|null
    {
        $todoItemSQL = TodoItem::query()->where('id', $id)->first();

        if ($todoItemSQL === null) {
            return null;
        }

        $todoItem = new TodoItem();
        $todoItem->fillable['id'] = $todoItemSQL['id'];
        $todoItem->fillable['name'] = $todoItemSQL['name'];
        $todoItem->fillable['status'] = $todoItemSQL['status'];
        $todoItem->fillable['todo_id'] = $todoItemSQL['todo_id'];
        return $todoItem;
    }

    public function data(): array
    {
        return [
            'id' => $this->fillable['id'],
            'todo_id' => $this->fillable['todo_id'],
            'name' => $this->fillable['name'],
            'status' => $this->fillable['status']
        ];
    }

    public static function updateStatus(int $id, TodoItemStatus $status): bool
    {
        return TodoItem::query()->where('id', $id)->update([
            'status' => $status->value
        ]);
    }
}
