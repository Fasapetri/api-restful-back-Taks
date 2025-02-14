<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
}
