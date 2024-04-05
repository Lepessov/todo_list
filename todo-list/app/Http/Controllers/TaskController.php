<?php

namespace App\Http\Controllers;

use App\Contracts\APIMessageEntity;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\TaskFilterRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TaskController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *      path="/api/tasks",
     *      operationId="getTasks",
     *      tags={"Tasks"},
     *      security={{"bearerAuth":{}}},
     *      summary="Get paginated list of tasks",
     *      description="Returns a list of tasks, optionally filtered and sorted.",
     *      @OA\Parameter(
     *          name="status",
     *          in="query",
     *          description="Filter tasks by status",
     *          required=false,
     *          @OA\Schema(type="string", enum={"todo", "in_progress", "done"})
     *      ),
     *      @OA\Parameter(
     *          name="sort_field",
     *          in="query",
     *          description="Field to sort tasks by",
     *          required=false,
     *          @OA\Schema(type="string", enum={"id", "title", "description", "status", "created_at", "updated_at"})
     *      ),
     *      @OA\Parameter(
     *          name="sort_direction",
     *          in="query",
     *          description="Sort direction ('asc' or 'desc')",
     *          required=false,
     *          @OA\Schema(type="string", enum={"asc", "desc"})
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of tasks per page",
     *          required=false,
     *          @OA\Schema(type="integer", minimum=1, maximum=100)
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Returns data",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *              )
     *          }
     *      )
     * )
     *
     * @param  \App\Http\Requests\TaskFilterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TaskFilterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $status = $validatedData['status'] ?? null;

        $sortField = $validatedData['sort_field'] ?? 'created_at';
        $sortDirection = $validatedData['sort_direction'] ?? 'asc';
        $tasks = Task::query();

        if ($status) {
            $tasks->where('status', $status);
        }

        $tasks->orderBy($sortField, $sortDirection);
        $tasks = $tasks->paginate($validatedData['per_page'] ?? 10)->items();

        return $this->successResponse($tasks);
    }

    /**
     * @OA\Post(
     *      path="/api/tasks",
     *      operationId="createTask",
     *      tags={"Tasks"},
     *      summary="Create a new task",
     *      description="Creates a new task with the provided data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Task data",
     *          @OA\MediaType(
     *               mediaType="multipart/form-data",
     *               @OA\Schema(
     *                   @OA\Property(
     *                       property="title",
     *                       type="string",
     *                       description="Title of the task",
     *                   ),
     *                   @OA\Property(
     *                       property="description",
     *                       type="string",
     *                       description="Description of the task",
     *                   ),
     *                   @OA\Property(
     *                       property="status",
     *                       type="string",
     *                       description="Status of the task (todo, in_progress, done)",
     *                       enum={"todo", "in_progress", "done"}
     *                   ),
     *                   @OA\Property(
     *                       property="file_path",
     *                       type="file",
     *                       description="File to upload"
     *                   )
     *               )
     *           )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Task created successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example="1"),
     *              @OA\Property(property="title", type="string", example="Task title"),
     *              @OA\Property(property="description", type="string", example="Task description"),
     *              @OA\Property(property="status", type="string", example="todo"),
     *              @OA\Property(property="file_path", type="file", example="file path"),
     *              @OA\Property(property="created_at", type="string", format="date-time"),
     *              @OA\Property(property="updated_at", type="string", format="date-time")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object")
     *          )
     *      )
     * )
     */
    public function store(CreateTaskRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/tasks', $fileName);
            $validatedData['file_path'] = $filePath;
        }
//dd($validatedData);
        $task = Task::create($validatedData);

        return $this->successResponse($task, ResponseAlias::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *      path="/api/tasks/{id}",
     *      operationId="getTaskById",
     *      tags={"Tasks"},
     *      summary="Get a task by ID",
     *      description="Retrieves a task by its ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the task",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Task not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Task not found")
     *          )
     *      )
     * )
     */
    public function show($id): JsonResponse
    {
        $task = Task::findOrFail($id);

        return $this->successResponse($task);
    }

    /**
     * @OA\Put(
     *      path="/api/tasks/{id}",
     *      operationId="updateTask",
     *      tags={"Tasks"},
     *      summary="Update a task by ID",
     *      description="Updates a task with the provided data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the task",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Task data",
     *          @OA\MediaType(
     *               mediaType="multipart/form-data",
     *               @OA\Schema(
     *                   @OA\Property(
     *                       property="title",
     *                       type="string",
     *                       description="Title of the task",
     *                   ),
     *                   @OA\Property(
     *                       property="description",
     *                       type="string",
     *                       description="Description of the task",
     *                   ),
     *                   @OA\Property(
     *                       property="status",
     *                       type="string",
     *                       description="Status of the task (todo, in_progress, done)",
     *                       enum={"todo", "in_progress", "done"}
     *                   ),
     *                   @OA\Property(
     *                       property="file_path",
     *                       type="file",
     *                       description="File to upload"
     *                   )
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Task not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Task not found")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object")
     *          )
     *      )
     * )
     */
    public function update(UpdateTaskRequest $request, $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        $validatedData = $request->validated();

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/tasks', $fileName);
            $validatedData['file_path'] = $filePath;
        }

        $task->update($validatedData);

        return $this->successResponse($task);
    }

    /**
     * @OA\Delete(
     *      path="/api/tasks/{id}",
     *      operationId="deleteTask",
     *      tags={"Tasks"},
     *      summary="Delete a task by ID",
     *      description="Deletes a task by its ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the task",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Task deleted successfully"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Task not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Task not found")
     *          )
     *      )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return $this->successResponse([], ResponseAlias::HTTP_NO_CONTENT, APIMessageEntity::DELETED);
    }
}
