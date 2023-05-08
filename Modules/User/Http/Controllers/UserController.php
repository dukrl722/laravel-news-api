<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\User\Services\UserService;
use Modules\User\Transformers\UserResource;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function getUserInfo() {
        $user_id = auth('sanctum')->user()->id;

        try {

            if (!$user = $this->userService->getById($user_id)) {
                return response()->json([
                    'message' => 'User not found'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'user' => new UserResource($user)
            ]);

        } catch (\Throwable $throwable) {
            return response()->json([
                'message' => $throwable->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProfileInfo(Request $request) {

    }

    public function updateProfilePicture(Request $request) {
        $user_id = auth('sanctum')->user()->id;

        DB::beginTransaction();

        try {
            if (!$user = $this->userService->getById($user_id)) {
                return response()->json([
                    'message' => 'User not found'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            $picture = $this->userService->updateProfilePicture($user, $request->file('image'));

            DB::commit();

            return response()->json([
                'image' => $picture->getUrl()
            ], JsonResponse::HTTP_OK);

        } catch (\Throwable $throwable) {
            DB::rollBack();

            return response()->json([
                'message' => $throwable->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePreferenceSettings(Request $request) {
        $user_id = auth('sanctum')->user()->id;

        DB::beginTransaction();

        try {

            $request->validate([
                'type' => 'required',
                'values' => 'required|array'
            ]);

            if (!$user = $this->userService->getById($user_id)) {
                return response()->json([
                    'message' => 'User not found'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            $userPreferences = $this->userService->getUserPreferences($user_id, $request->type);

            $diff = collect($userPreferences->pluck('value'))->diff(collect($request->values));

            if (!$diff->toArray()) {
                $diff = collect($request->values)->diff(collect($userPreferences->pluck('value')));
            }

            $this->userService->updatePreferenceSettings($user_id, $request->type, $diff);

            DB::commit();

            return response()->json([
                'message' => 'Settings updated successfully'
            ], JsonResponse::HTTP_OK);

        } catch (\Throwable $throwable) {
            DB::rollBack();

            return response()->json([
                'message' => $throwable->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
