<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobCenterAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function jobAttributes(int $userId, string $status = 'published'): array
    {
        return [
            'user_id' => $userId,
            'title' => 'Backend Developer',
            'company' => 'Acme',
            'employment_type' => 'full_time',
            'experience_level' => 'mid',
            'description' => 'Build and maintain backend services for the platform.',
            'responsibilities' => ['Build APIs'],
            'requirements' => ['PHP experience'],
            'skills' => ['PHP', 'Laravel'],
            'salary_min' => 1000,
            'salary_max' => 2000,
            'pay_period' => 'monthly',
            'show_salary_range' => true,
            'work_mode' => 'remote',
            'application_method' => 'platform',
            'status' => $status,
            'publish_to_marketplace' => true,
            'notify_matching_candidates' => false,
        ];
    }

    public function test_recruiter_cannot_update_another_recruiters_job(): void
    {
        $owner = User::factory()->create(['status' => 'job_poster']);
        $otherRecruiter = User::factory()->create(['status' => 'job_poster']);
        $job = Job::create($this->jobAttributes($owner->id));

        $response = $this->actingAs($otherRecruiter)->putJson('/api/jobs/'.$job->id, [
            ...$this->jobAttributes($otherRecruiter->id),
            'title' => 'Unauthorized update',
        ]);

        $response->assertForbidden();
        $this->assertSame('Backend Developer', $job->fresh()->title);
    }

    public function test_recruiter_cannot_delete_another_recruiters_job(): void
    {
        $owner = User::factory()->create(['status' => 'job_poster']);
        $otherRecruiter = User::factory()->create(['status' => 'job_poster']);
        $job = Job::create($this->jobAttributes($owner->id));

        $this->actingAs($otherRecruiter)
            ->deleteJson('/api/jobs/'.$job->id)
            ->assertForbidden();

        $this->assertDatabaseHas('job_listings', ['id' => $job->id]);
    }

    public function test_comment_owner_can_update_and_staff_can_delete_comments(): void
    {
        $owner = User::factory()->create();
        $staff = User::factory()->create(['role' => 'moderator']);
        $jobOwner = User::factory()->create(['status' => 'job_poster']);
        $job = Job::create($this->jobAttributes($jobOwner->id));
        $comment = Comment::create([
            'job_id' => $job->id,
            'user_id' => $owner->id,
            'body' => 'Original comment',
        ]);

        $this->actingAs($owner)
            ->putJson('/api/comments/'.$comment->id, ['body' => 'Updated comment'])
            ->assertOk();

        $this->actingAs($staff)
            ->deleteJson('/api/comments/'.$comment->id)
            ->assertOk();

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_like_requires_a_published_job(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create(['status' => 'job_poster']);
        $job = Job::create($this->jobAttributes($owner->id, 'draft'));

        $this->actingAs($user)
            ->postJson('/api/jobs/'.$job->id.'/like')
            ->assertForbidden();
    }
}
