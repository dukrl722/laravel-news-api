<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\User\Services\UserService;
use Modules\User\Transformers\UserResource;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function create(Request $request) {
        try {

            $request->validate([
                'name' => 'required|max:150',
                'email' => 'required|email|confirmed|unique:users',
                'password' => 'required|confirmed|min:8'
            ]);

            if ($user = $this->userService->getByEmail($request->email)) {
                return response()->json([
                    'message' => 'E-mail already registered'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            DB::beginTransaction();

            $newUser = $this->userService->create($request->only(['name', 'email', 'password']));

            DB::commit();

            return response()->json([
                'access_token' => $newUser->createToken("LaravelNewsAPI")->plainTextToken,
                'type' => 'Bearer',
                'user' => new UserResource($newUser)
            ]);

        } catch (\Throwable $throwable) {
            DB::rollBack();

            return response()->json([
                'message' => $throwable->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function authenticate(Request $request) {
        if (!Auth::guard('api')->attempt($request->only(['email', 'password']))){
            return response()->json([
                'message' => 'Email or Password does not match with our record.',
            ], JsonResponse::HTTP_NOT_ACCEPTABLE);
        }

        try {

            if (!$user = $this->userService->getByEmail($request->email)) {
                return response()->json([
                    'message' => 'User not found'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'access_token' => $user->createToken("LaravelNewsAPI")->plainTextToken,
                'type' => 'Bearer',
                'user' => new UserResource($user)
            ]);

        } catch (\Throwable $throwable) {
            return response()->json([
                'message' => $throwable->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout() {
        auth('sanctum')->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully Logout.'
        ], JsonResponse::HTTP_OK);
    }
}
