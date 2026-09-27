<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Job;
use App\Models\Like;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class RebuildJobCounters extends Command
{
    protected $signature = 'counters:rebuild';

    protected $description = 'Recalculate job_listings.comments_count and likes_count from the comment and like tables';

    public function handle(): int
    {
        Job::query()->chunkById(500, function (Collection $jobs): void {
            $jobs->each(function (Job $job): void {
                Job::query()->whereKey($job->id)->update([
                    'comments_count' => Comment::query()->forJob($job)->count(),
                    'likes_count' => Like::query()->forJob($job)->count(),
                ]);
            });
        });

        $this->info('Job counters rebuilt.');

        return self::SUCCESS;
    }
}
