<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

    protected $table = 'public.todos';

    public $timestamps = false;

    public $fillable = [
        'id', 'name', 'description', 'finish_date', 'todo_item_count'
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

    public function itemCount(): int
    {
        return $this->fillable['todo_item_count'];
    }

    public function addItem(string $name, string $status): TodoItem|null
    {
        if ($this->itemCount() >= 10) {
            return null;
        }
        $itemSQL = TodoItem::query()->create([
            'name' => $name,
            'status' => $status,
            'todo_id' => $this->fillable['id']
        ]);
        $item = new TodoItem();
        $item->fillable['id'] = $itemSQL['id'];
        $item->fillable['name'] = $itemSQL['name'];
        $item->fillable['status'] = $itemSQL['status'];
        $item->fillable['todo_id'] = $itemSQL['todo_id'];

        # update the item count
        $this->fillable['todo_item_count'] = $this->itemCount() + 1;

        return $item;
    }

    public function items(): array
    {
        $itemsSQL = TodoItem::query()->where('todo_id', $this->fillable['id'])->get();
        $items = [];
        foreach ($itemsSQL as $itemSQL) {
            $item = new TodoItem();
            $item->fillable['id'] = $itemSQL['id'];
            $item->fillable['name'] = $itemSQL['name'];
            $item->fillable['status'] = $itemSQL['status'];
            $item->fillable['todo_id'] = $itemSQL['todo_id'];
            $items[] = $item;
        }
        return $items;
    }

    public function delete(): bool
    {
        $deleted = Todo::query()->where('id', $this->fillable['id'])->delete();
        if ($deleted) {
            TodoItem::query()->where('todo_id', $this->fillable['id'])->delete();
            # update the item count
            $this->fillable['todo_item_count'] = 0;
        }
        return $deleted;
    }
}
