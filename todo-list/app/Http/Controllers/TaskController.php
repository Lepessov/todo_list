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

    public function store(CreateTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        return $this->successResponse($task, ResponseAlias::HTTP_CREATED);
    }

    public function show($id): JsonResponse
    {
        $task = Task::findOrFail($id);

        return $this->successResponse($task);
    }

    public function update(UpdateTaskRequest $request, $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        $task->update($request->validated());

        return $this->successResponse($task);
    }

    public function destroy($id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return $this->successResponse([], ResponseAlias::HTTP_NO_CONTENT, APIMessageEntity::DELETED);
    }
}
