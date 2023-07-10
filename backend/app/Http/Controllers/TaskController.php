<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['index', 'create']]);
        //$this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {        
        $tasks = Task::all();
        $result = Task::whereBetween('id', [$request->start, $request->end])->get();
        return response()->json([
            "tasks" => $result,
            "count" => count($tasks)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $taskData = [
            'description' => $request->description,
            'check' => $request->check,
        ];
        $newTask = Task::create($taskData);
        return response()->json(['checkStatus' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        DB::table('tasks')
        ->where('id', $request->id)
        ->update(array('description' => $request->description));
    }

    public function status(Request $request)
    {
        DB::table('tasks')
        ->where('id', $request->id)
        ->update(array('check' => $request->check));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Task::where('id',$request->id)->delete();
    }
}
