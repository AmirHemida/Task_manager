<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    function index()
    {
        $tasks = Auth::user()->task;
        return response()->json($tasks, 200);
    }
    function show($id)
    {
        try {
            $task = Auth::user()->task()->findOrFail($id);
            return response()->json($task, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Id Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function store(StoreTaskRequest $request)
    {
        try {
            $task = Task::create([
                ...$request->validated(),
                'user_id' => Auth::user()->id,
            ]);
            return response()->json($task, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function update(StoreTaskRequest $request, $id)
    {
        try {
            $task = Auth::user()->task()->findOrFail($id);
            $task->update($request->validated());
            return response()->json($task, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Id not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function destroy($id)
    {
        try {
            $task = Auth::user()->task()->findOrFail($id);
            $task->delete();
            return response()->json([
                'message' => 'Task deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Id not found'
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function getuser($id) // admin
    {
        try {
            $user = Task::findOrFail($id)->user;
            return response()->json($user, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Id not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function getcategories($id)
    {
        try {
            $category = Auth::user()->task()->findOrFail($id)->category;
            return response()->json($category, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Id not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function  gettasksfromcategory($id) // admin
    {
        try {
            $task = Category::findOrFail($id)->task;
            return response()->json($task, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Id not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }

    function addTaskToCategory(Request $request /*category_id*/, $id /*task_id*/) // بدل الاي دي احط الاسم
    {
        try {
            $task = Auth::user()->task()->findOrFail($id);
            $task->category()->syncWithoutDetaching([$request->category_id]);
            return response()->json("added to category", 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Id not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function getalltasks() //admins
    {
        return response()->json(Task::all(), 200);
    }
    function gettasksbypriority()
    {
        $tasks = Auth::user()->task()->orderByRaw("FIELD(priority,'high','medium','low')")->get();
        return response()->json($tasks, 200);
    }
    function addtofavourite($id)
    {
        try {
            $task = Auth::user()->task()->findOrFail($id);
            Auth::user()->favouritetask()->syncWithoutDetaching($id);
            return response()->json('added to favourites', 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Id not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function deletefromfavourite($id)
    {
        try {
            $task = Auth::user()->task()->findOrFail($id);
            Auth::user()->favouritetask()->Detach($id);
            return response()->json('deleted from favourites', 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Id not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went Wrong'
            ], 500);
        }
    }
    function getallfavourites()
    {
        $tasks = Auth::user()->favouritetask;
        return response()->json($tasks, 200);
    }
}
