<?php

namespace App\Http\Controllers;

use App\Contracts\APIMessageEntity;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    use ApiResponse;
    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
            return $this->errorResponse([], ResponseAlias::HTTP_UNAUTHORIZED, message: APIMessageEntity::INVALID_CREDENTIALS);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->successResponse(['token' => $token]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->successResponse(['token' => $token]);
    }
}
