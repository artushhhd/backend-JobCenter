<?php

use App\Support\Database\ManagesTableIndexes;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use ManagesTableIndexes;

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
};
