<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\TodoItem;
use App\Models\TodoItemStatus;
use Exception;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    # --- todo ---
    # create Todo
    public function create(Request $request): JsonResponse
    {
        $name = $request->input('name');
        $description = $request->input('description');
        $finish_date = $request->input('finish_date');
        $user_id = $request->input('user_id');

        try {
            $todoSQL = Todo::query()->create([
                'name' => $name,
                'description' => $description,
                'finish_date' => $finish_date,
                'user_id' => $user_id
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'There is already a Todo with the same name.'
            ]);
        } catch (Exception $e) {
            $todoSQL = null;
        }

        if ($todoSQL === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Todo not created by unknown reason.'
            ]);
        }

        $todo = new Todo();
        $todo->fillable['id'] = $todoSQL['id'];
        $todo->fillable['name'] = $todoSQL['name'];
        $todo->fillable['description'] = $todoSQL['description'];
        $todo->fillable['finish_date'] = $todoSQL['finish_date'];
        $todo->fillable['user_id'] = $todoSQL['user_id'];

        return response()->json([
            'status' => 'success',
            'message' => 'Todo created',
            'data' => $todo->data()
        ]);
    }

    # get todo list by user_id
    public function list(Request $request): JsonResponse
    {
        $user_id = $request->input('user_id');
        $todos = Todo::query()->where('user_id', $user_id)->get();
        $data = [];
        foreach ($todos as $todo) {
            $todoItem = new Todo();
            $todoItem->fillable['id'] = $todo['id'];
            $todoItem->fillable['name'] = $todo['name'];
            $todoItem->fillable['description'] = $todo['description'];
            $todoItem->fillable['finish_date'] = $todo['finish_date'];
            $todoItem->fillable['user_id'] = $todo['user_id'];
            $data[] = $todoItem->data();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Todo list',
            'data' => $data
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $deleteSQL = Todo::query()->where('id', $id)->delete();

        if ($deleteSQL === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Todo not deleted.',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Todo deleted.',
            'id' => $id
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        $finish_date = $request->input('finish_date');

        try {
            $updateSQL = Todo::query()->where('id', $id)->update([
                'name' => $name,
                'description' => $description,
                'finish_date' => $finish_date
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'There is already a Todo with the same name.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        if ($updateSQL === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Todo not updated.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Todo updated.',
            'data' => [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'finish_date' => $finish_date
            ]
        ]);
    }

    public function get(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $todo = Todo::get($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Todo found.',
            'data' => $todo->data()
        ]);
    }

    # --- todo_item ---
    public function createItem(Request $request): JsonResponse
    {
        $todo_id = $request->input('todo_id');
        $name = $request->input('name');
        $status = $request->input('status') ?? TodoItemStatus::TODO->value;
        $todoItemStatus = TodoItemStatus::fromValue($status);

        $itemsSQL = TodoItem::query()->where('todo_id', $todo_id)->get();

        // Max 10 items
        if (count($itemsSQL) == 10) {
            return response()->json([
                'status' => 'error',
                'message' => 'You can only have 10 Todo items in this list.',
            ]);
        }

        // Check if item already exists
        foreach ($itemsSQL as $itemSQL) {
            if ($itemSQL['name'] == $name) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'There is already a Todo item with the same name in this list.'
                ]);
            }
        }

        try {
            $insertedItem = TodoItem::query()->create([
                'name' => $name,
                'status' => $todoItemStatus->value,
                'todo_id' => $todo_id
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Todo item created.',
            'data' => $insertedItem
        ]);
    }

    public function createItems(Request $request): JsonResponse
    {
        $items = $request->input('items');
        $insertedItems = [];

        foreach ($items as $item) {
            $todo_id = $item['todo_id'];
            $name = $item['name'];
            $status = $item['status'] ?? TodoItemStatus::TODO->value;
            $todoItemStatus = TodoItemStatus::fromValue($status);

            $itemsSQL = TodoItem::query()->where('todo_id', $todo_id)->get();

            // Max 10 items
            if (count($itemsSQL) == 10) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You can only have 10 Todo items in this list.',
                ]);
            }

            // Check if item already exists
            foreach ($itemsSQL as $itemSQL) {
                if ($itemSQL['name'] == $name) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'There is already a Todo item with the name' . $name . ' in this list.'
                    ]);
                }
            }

            try {
                $insertedItems[] = TodoItem::query()->create([
                    'name' => $name,
                    'status' => $todoItemStatus->value,
                    'todo_id' => $todo_id
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }


        }

        return response()->json([
            'status' => 'success',
            'message' => 'Todo item created.',
            'data' => $insertedItems
        ]);

    }

    public function listItems(Request $request): JsonResponse
    {
        $todo_id = $request->input('todo_id');
        $itemsSQL = TodoItem::query()->where('todo_id', $todo_id)->get();
        $data = [];
        foreach ($itemsSQL as $itemSQL) {
            $item = new TodoItem();
            $item->fillable['id'] = $itemSQL['id'];
            $item->fillable['name'] = $itemSQL['name'];
            $item->fillable['status'] = $itemSQL['status'];
            $item->fillable['todo_id'] = $itemSQL['todo_id'];
            $data[] = $item->data();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Todo items found.',
            'data' => $data
        ]);
    }

    public function updateItems(Request $request): JsonResponse
    {
        $itemArray = $request->input('items');
        $updateSQL = 0;
        foreach ($itemArray as $item) {
            $id = $item['id'];
            $todo_id = $item['todo_id'];
            $name = $item['name'];
            $status = $item['status'] ?? TodoItemStatus::TODO->value;
            $todoItemStatus = TodoItemStatus::fromValue($status);

            $itemsSQL = TodoItem::query()->where('todo_id', $todo_id)->get();
            // Check if item already exists
            foreach ($itemsSQL as $itemSQL) {
                if ($itemSQL['name'] == $name) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'There is already a Todo item with the name "' . $name . '" in this list.'
                    ]);
                }
            }
            $updateSQL += TodoItem::query()->where('id', $id)->update([
                'name' => $name,
                'status' => $todoItemStatus->value
            ]);
        }

        if ($updateSQL === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Todo item not updated.',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Todo item updated.',
            'data' => [
                'id' => $id,
                'name' => $name,
                'status' => $todoItemStatus->value
            ]
        ]);
    }

    public function deleteItem(Request $request): JsonResponse
    {
        $id = $request->input('id');
        TodoItem::query()->where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Todo item deleted.'
        ]);
    }

    public function updateItemStatus(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $updated = TodoItem::updateStatus($id, TodoItemStatus::fromValue($request->input('status')));
        if (!$updated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Todo item not updated.',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Todo item updated.',
            'item_status' => TodoItemStatus::fromValue($request->input('status'))->value
        ]);
    }

}
