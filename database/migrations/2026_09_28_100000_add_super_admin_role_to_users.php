<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const ENUM_WITH_SUPER = "enum('user','moderator','admin','super_admin') not null default 'user'";

    private const ENUM_WITHOUT_SUPER = "enum('user','moderator','admin') not null default 'user'";

    public function up(): void
    {
        if (! $this->roleSupportsSuperAdmin()) {
            DB::statement('alter table users modify role '.self::ENUM_WITH_SUPER);
        }

        $this->promoteFirstSuperAdmin();
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);

        DB::statement('alter table users modify role '.self::ENUM_WITHOUT_SUPER);
    }

    private function roleSupportsSuperAdmin(): bool
    {
        $column = DB::select(
            "select column_type from information_schema.columns
             where table_schema = database() and table_name = 'users' and column_name = 'role'
             limit 1"
        )[0] ?? null;

        return $column !== null && str_contains($column->column_type, 'super_admin');
    }

    private function promoteFirstSuperAdmin(): void
    {
        if (DB::table('users')->where('role', 'super_admin')->exists()) {
            return;
        }

        $candidate = DB::table('users')
            ->where('role', 'admin')
            ->orderBy('id')
            ->value('id');

        if ($candidate !== null) {
            DB::table('users')->where('id', $candidate)->update(['role' => 'super_admin']);
        }
    }
};
