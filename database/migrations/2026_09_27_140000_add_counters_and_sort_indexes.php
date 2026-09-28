<?php

use App\Support\Database\ManagesTableIndexes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use ManagesTableIndexes;

    private string $table = 'job_listings';

    public function up(): void
    {
        if (! Schema::hasColumn($this->table, 'likes_count')) {
            Schema::table($this->table, function (Blueprint $table) {
                $table->unsignedInteger('comments_count')->default(0)->after('status');
                $table->unsignedInteger('likes_count')->default(0)->after('comments_count');
            });
        }

        $this->backfillCounters();

        $this->createIndex($this->table, ['status', 'featured', 'created_at', 'id'], 'job_listings_default_sort_index');
        $this->createIndex($this->table, ['status', 'created_at', 'id'], 'job_listings_recent_sort_index');
        $this->createIndex($this->table, ['status', 'salary_max', 'id'], 'job_listings_salary_sort_index');
        $this->dropIndex($this->table, 'job_listings_status_featured_index');
        $this->dropIndex($this->table, 'job_listings_status_published_at_index');

        $this->createIndex('comments', ['job_id', 'id'], 'comments_job_thread_index');
        $this->dropIndex('comments', 'comments_job_id_created_at_index');
    }

    public function down(): void
    {
        $this->createIndex('comments', ['job_id', 'created_at'], 'comments_job_id_created_at_index');
        $this->dropIndex('comments', 'comments_job_thread_index');

        $this->createIndex($this->table, ['status', 'featured'], 'job_listings_status_featured_index');
        $this->createIndex($this->table, ['status', 'published_at'], 'job_listings_status_published_at_index');
        $this->dropIndex($this->table, 'job_listings_default_sort_index');
        $this->dropIndex($this->table, 'job_listings_recent_sort_index');
        $this->dropIndex($this->table, 'job_listings_salary_sort_index');

        Schema::table($this->table, function (Blueprint $table) {
            $table->dropColumn(['comments_count', 'likes_count']);
        });
    }

    private function backfillCounters(): void
    {
        DB::statement('update job_listings set comments_count = (select count(*) from comments where comments.job_id = job_listings.id), likes_count = (select count(*) from likes where likes.job_id = job_listings.id)');
    }
};
