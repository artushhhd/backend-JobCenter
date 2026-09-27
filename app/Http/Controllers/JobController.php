<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeJobRequest;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobListResource;
use App\Http\Resources\JobResource;
use App\Http\Resources\ManagedJobResource;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Job::class);

        return $this->respond($this->filtered($request));
    }

    public function show(Request $request, Job $job): JsonResponse
    {
        $this->authorize('view', $job);

        $job->setAttribute('liked', $job->likes()->byUser($request->user())->exists());

        return response()->json([
            'job' => new JobResource($job),
        ]);
    }

    public function store(StoreJobRequest $request): JsonResponse
    {
        $job = $request->user()->jobs()->create($request->validated());

        return response()->json([
            'message' => 'Job created successfully.',
            'job' => new JobResource($job),
        ], 201);
    }

    public function update(UpdateJobRequest $request, Job $job): JsonResponse
    {
        $job->update($request->validated());

        return response()->json([
            'message' => 'Job updated successfully.',
            'job' => new JobResource($job),
        ]);
    }

    public function destroy(Request $request, Job $job): JsonResponse
    {
        $this->authorize('delete', $job);

        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully.',
        ]);
    }

    public function like(LikeJobRequest $request, Job $job): JsonResponse
    {
        $job->likes()->firstOrCreate(['user_id' => $request->user()->id]);

        return response()->json([
            'message' => 'Job liked successfully.',
            'liked' => true,
            'like_count' => (int) $job->refresh()->likes_count,
        ]);
    }

    public function unlike(LikeJobRequest $request, Job $job): JsonResponse
    {
        $job->likes()->byUser($request->user())->first()?->delete();

        return response()->json([
            'message' => 'Job unliked successfully.',
            'liked' => false,
            'like_count' => (int) $job->refresh()->likes_count,
        ]);
    }

    public function liked(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Job::class);

        $listings = $this->filtered($request)->whereHas(
            'likes',
            fn (Builder $query) => $query->byUser($request->user())
        );

        return $this->respond($listings);
    }

    public function managed(Request $request): JsonResponse
    {
        $this->authorize('manage', Job::class);

        $listings = Job::query()
            ->with('recruiter:id,name,email')
            ->orderByDesc('id');

        $this->applySearch($listings, (string) $request->query('q'));

        $status = (string) $request->query('status');

        if (in_array($status, ['draft', 'published'], true)) {
            $listings->where('status', $status);
        }

        return $this->respond($listings, ManagedJobResource::class);
    }

    private function respond(Builder $listings, string $resource = JobListResource::class): JsonResponse
    {
        $paginated = $listings->paginate(self::PER_PAGE)->withQueryString();

        return response()->json([
            'jobs' => $resource::collection($paginated->items()),
            'total' => $paginated->total(),
            'page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
        ]);
    }

    private function filtered(Request $request): Builder
    {
        $userId = $request->user()->id;

        $listings = Job::query()
            ->published()
            ->withExists(["likes as liked" => fn (Builder $query) => $query->where('user_id', $userId)]);

        $this->applySearch($listings, (string) $request->query('q'));
        $this->applyLocation($listings, (string) $request->query('location'));

        return $this->applySort($listings, (string) $request->query('sort'));
    }

    private function applySearch(Builder $listings, string $search): void
    {
        $search = trim($search);

        if ($search === '') {
            return;
        }

        $listings->where(function (Builder $query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('company', 'like', "%{$search}%")
                ->orWhereRaw('cast(skills as char) like ?', ["%{$search}%"]);
        });
    }

    private function applyLocation(Builder $listings, string $location): void
    {
        $location = trim($location);

        if ($location === '') {
            return;
        }

        $listings->where(function (Builder $query) use ($location) {
            $query->where('location', 'like', "%{$location}%")
                ->orWhere('work_mode', 'remote');
        });
    }

    private function applySort(Builder $listings, string $sort): Builder
    {
        return match ($sort) {
            'recent' => $listings->orderByDesc('created_at')->orderByDesc('id'),
            'salary' => $listings->orderByDesc('salary_max')->orderByDesc('id'),
            default => $listings
                ->orderByDesc('featured')
                ->orderByDesc('created_at')
                ->orderByDesc('id'),
        };
    }
}
