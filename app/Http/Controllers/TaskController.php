<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller {

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'done' => ['nullable', 'string'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $token = $request->header('Authorization');

        $user = User::where('remember_token', $token)->first();

        $task = Task::create([
            'name' => $request->get('name'),
            'user_id' => $user->id,
            'done' => $request->get('done') == 'true' ? 1 : 0,
        ]);

        return response()->json(compact('task'), 201);
    }

    public function get($token){

        $tasks = Task::whereHas(
            'user', function($q) use($token) {
                $q->where('remember_token', $token);
            }
        )->get();

        if($tasks){
            return response()->json(compact('tasks'));
        }

        return response()->json(['error' => 'No hay tareas'], 404);
        
    }

    public function update(int $id, Request $request){
        $task = Task::find($id);

        if($task){
            $task->name = $request->get('name');
            $task->done = $request->get('done') == 'true' ? 1 : 0;
            $task->updated_at = \Carbon\Carbon::now();
            $task->update();

            return response()->json(compact('task'), 203);
        }

        return response()->json(['error' => 'No existe la tarea'], 404);
    }

    public function delete(int $id){
        $task = Task::find($id);

        if($task){
            $task->delete();

            return response()->json(203);
        }

        return response()->json(['error' => 'No existe la tarea'], 404);
    }
}
