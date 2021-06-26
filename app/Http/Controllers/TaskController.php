<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Task::class, 'task');
    }

    public function index(Request $request)
    {
        // get current user
        $user = $request->user();
        // return all user's tasks
        $todo = $user->tasks()->where('status', 'todo')->get();
        $done = $user->tasks()->where('status', 'done')->get();
        $in_progress = $user->tasks()->where('status', 'in progress')->get();

        return response()->json([
            'todo' => TaskResource::collection($todo),
            'in_progress' => TaskResource::collection($in_progress),
            'done' => TaskResource::collection($done)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail | required | min:5 | max:50',
            'description' => 'bail | min:5 | max:200',
        ]);
        // set current user's id as task's user_id
        $request['user_id'] = $request->user()->id;
        // create task
        Task::create($request->all());

        return response()->json([
            'message' => 'تسک با موفقیت ایجاد شد',
        ]);
    }

    public function show(Task $task)
    {
        // retrieve selected task
        return new TaskResource($task);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'bail | min:5 | max:50',
            'description' => 'bail | min:5 | max:200',
        ]);
        // update task
        $task->update($request->all());

        return response()->json([
            'message' => 'تسک با موفقیت بروز شد',
        ]);
    }

    public function destroy(Task $task)
    {
        // delete task
        $task->delete();
        return response()->json(['message' => 'تسک با موفقیت حذف شد']);
    }
}
