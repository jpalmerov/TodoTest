<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    # create Todo
    public function create(Request $request): JsonResponse
    {
        $name = $request->input('name');
        $description = $request->input('description');
        $finish_date = $request->input('finish_date');
        $user_id = $request->input('user_id');

        $todoSQL = Todo::query()->create([
            'name' => $name,
            'description' => $description,
            'finish_date' => $finish_date,
            'user_id' => $user_id
        ]);

        if ($todoSQL === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Todo not created'
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
    public function todos (Request $request): JsonResponse
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

}
