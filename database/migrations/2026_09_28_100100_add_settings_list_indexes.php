<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->createIndex('job_listings', ['status', 'id'], 'job_listings_status_id_index');
        $this->createIndex('users', ['role', 'id'], 'users_role_id_index');
    }

    public function down(): void
    {
        $this->dropIndex('job_listings', 'job_listings_status_id_index');
        $this->dropIndex('users', 'users_role_id_index');
    }

    private function createIndex(string $table, array $columns, string $name): void
    {
        if ($this->hasIndex($table, $name)) {
            return;
        }

        Schema::table($table, fn ($blueprint) => $blueprint->index($columns, $name));
    }

    private function dropIndex(string $table, string $name): void
    {
        if (! $this->hasIndex($table, $name)) {
            return;
        }

        Schema::table($table, fn ($blueprint) => $blueprint->dropIndex($name));
    }

    private function hasIndex(string $table, string $name): bool
    {
        return DB::select(
            'select 1 from information_schema.STATISTICS where TABLE_SCHEMA = database() and TABLE_NAME = ? and INDEX_NAME = ? limit 1',
            [$table, $name]
        ) !== [];
    }
};
