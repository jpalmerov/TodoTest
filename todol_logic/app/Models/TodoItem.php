<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{

    protected $table = 'public.todo_items';
    public $timestamps = false;

    public $fillable = [
        'id', 'name', 'status'
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
        return $todoItem;
    }

    public function save(array $options = []): bool
    {
        $name = $this->fillable['name'];
        $status = $this->fillable['status'];

        return TodoItem::query()->insert([
            'name' => $name,
            'status' => $status
        ]);
    }

    public function data(): array
    {
        return [
            'id' => $this->fillable['id'],
            'name' => $this->fillable['name'],
            'status' => $this->fillable['status']
        ];
    }

    public function updateStatus(TodoItemStatus $status): bool
    {
        return TodoItem::query()->where('id', $this->fillable['id'])->update([
            'name' => $this->fillable['name'],
            'status' => $this->fillable['status']
        ]);
    }
}
