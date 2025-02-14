<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Task\TaskStoreRequest;
use App\Http\Requests\Api\Task\TaskUpdateRequest;
use App\Models\Task;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponser;
    
    public function index(){
        try{
            $tasks = Task::all();
            return $this->successResponse(
                $tasks,
                'Tareas consultadas exitosamente.',
                200
            );
        }catch(Exception $e){
            return $this->errorResponse(
                [
                    'message' => 'Algo salio mal.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function store(TaskStoreRequest $request){
        try{
            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->save();
            return $this->successResponse(
                $task,
                'Tarea creada exitosamente.',
                201
            );
        }catch(Exception $e){
            return $this->errorResponse(
                [
                    'message' => 'Algo salio mal.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function edit($id){
        try{
            $task = Task::findOrFail($id);
            
            return $this->successResponse(
                $task,
                'Tarea encontrada exitosamente.',
                200
            );
        }catch(Exception $e){
            return $this->errorResponse(
                [
                    'message' => 'Algo salio mal.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function update(TaskUpdateRequest $request, $id){
        try{
            $task = Task::findOrFail($id);
            $task->title = $request->title;
            $task->description = $request->description;
            $task->save();
            return $this->successResponse(
                $task,
                'Tarea modificada exitosamente.',
                200
            );
        }catch(Exception $e){
            return $this->errorResponse(
                [
                    'message' => 'Algo salio mal.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function delete($id){
        try{
            $task = Task::findOrFail($id);
            $task->delete();
            return $this->successResponse(
                $task,
                'Tarea eliminada exitosamente.',
                200
            );
        }catch(Exception $e){
            return $this->errorResponse(
                [
                    'message' => 'Algo salio mal.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function complete($id){
        try{
            $task = Task::findOrFail($id);
            $task->status = 'completada';
            $task->save();
            return $this->successResponse(
                $task,
                'Tarea completada exitosamente.',
                200
            );
        }catch(Exception $e){
            return $this->errorResponse(
                [
                    'message' => 'Algo salio mal.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
