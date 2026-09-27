<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UploadCvRequest;
use App\Http\Resources\CvResource;
use App\Http\Resources\UserResource;
use App\Models\Cv;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class UserController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->only(['name', 'email', 'password', 'status']));
        } catch (QueryException $e) {
            throw $this->handleDuplicateEmail($e);
        }

        return response()->json([
            'message' => 'Registered successfully.',
            'user' => new UserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'message' => 'Logged in successfully.',
            'user' => new UserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()->loadMissing('cv')),
        ]);
    }

    public function uploadCv(UploadCvRequest $request): JsonResponse
    {
        $user = $request->user();
        $file = $request->file('file');
        $previous = $user->cv;

        $stored = $file->storeAs(
            'cvs/'.$user->id,
            (string) Str::uuid().'.'.$file->guessExtension(),
            'local'
        );

        $cv = Cv::updateOrCreate(
            ['user_id' => $user->id],
            [
                'filename' => $stored,
                'original_name' => $file->getClientOriginalName(),
                'mime' => (string) $file->getMimeType(),
                'size' => (int) $file->getSize(),
            ]
        );

        if ($previous && $previous->filename !== $stored) {
            Storage::disk('local')->delete($previous->filename);
        }

        return response()->json([
            'message' => 'Resume uploaded successfully.',
            'cv' => new CvResource($cv),
        ], 201);
    }

    public function downloadCv(Request $request)
    {
        $cv = $request->user()->cv;

        if (! $cv || ! Storage::disk('local')->exists($cv->filename)) {
            return response()->json(['message' => 'No resume found.'], 404);
        }

        return response()->download(
            Storage::disk('local')->path($cv->filename),
            $cv->original_name,
            ['Content-Type' => $cv->mime]
        );
    }

    public function destroyCv(Request $request): JsonResponse
    {
        $request->user()->cv?->delete();

        return response()->json([
            'message' => 'Resume removed.',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    private function handleDuplicateEmail(QueryException $e): Throwable
    {
        if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry')) {
            return ValidationException::withMessages([
                'email' => ['This email address is already registered.'],
            ]);
        }

        return $e;
    }
}
