<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected array $tables = [
        'users',
        'departments',
        'roles',
        'shifts',
        'attendances',
        'leave_requests',
        'tasks',
        'notes',
        'projects',
        'calendar_events',
        'media',
        'social_accounts',
        'scheduled_posts',
        'messages',
        'notifications',
        'user_infos',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {

            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'company_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable(false)->change();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {

            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'company_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->change();
            });
        }
    }
};
