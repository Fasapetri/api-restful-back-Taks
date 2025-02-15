<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Task\TaskStoreRequest;
use App\Http\Requests\Api\Task\TaskUpdateRequest;
use App\Models\Task;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


class TaskController extends Controller
{
    use ApiResponser;
    
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Obtener todas las tareas",
     *     @OA\Response(response=200, description="Lista de tareas")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Crear una nueva tarea",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status"},
     *             @OA\Property(property="title", type="string", example="Nueva tarea"),
     *             @OA\Property(property="description", type="string", example="Descripción de la tarea"),
     *             @OA\Property(property="status", type="string", example="pendiente")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tarea creada")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Obtener una tarea por ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Tarea encontrada exitosamente"),
     *     @OA\Response(response=500, description="Error en el servidor")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Actualizar una tarea",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Tarea modificada exitosamente"),
     *     @OA\Response(response=500, description="Error en el servidor")
     * )
     */
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

     /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Eliminar una tarea",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Tarea eliminada exitosamente"),
     *     @OA\Response(response=500, description="Error en el servidor")
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/tasks/{id}/complete",
     *     summary="Marcar una tarea como completada",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Tarea completada exitosamente"),
     *     @OA\Response(response=500, description="Error en el servidor")
     * )
     */
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
