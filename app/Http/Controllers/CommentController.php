<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private const PER_PAGE = 20;

    public function index(Request $request, Job $job): JsonResponse
    {
        $this->authorize('view', $job);

        $comments = Comment::query()
            ->forJob($job)
            ->with('author')
            ->oldest('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return response()->json([
            'comments' => CommentResource::collection($comments->items()),
            'total' => $comments->total(),
            'page' => $comments->currentPage(),
            'last_page' => $comments->lastPage(),
        ]);
    }

    public function store(CommentRequest $request, Job $job): JsonResponse
    {
        $this->authorize('view', $job);

        $comment = new Comment($request->validated());
        $comment->author()->associate($request->user());
        $comment->job()->associate($job);
        $comment->save();

        return response()->json([
            'message' => 'Comment created successfully.',
            'comment' => new CommentResource($comment->load('author')),
            'total' => (int) $job->refresh()->comments_count,
        ], 201);
    }

    public function update(CommentRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->validated());

        return response()->json([
            'message' => 'Comment updated successfully.',
            'comment' => new CommentResource($comment->load('author')),
        ]);
    }

    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $jobId = $comment->job_id;
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.',
            'total' => (int) Job::query()->whereKey($jobId)->value('comments_count'),
        ]);
    }
}
