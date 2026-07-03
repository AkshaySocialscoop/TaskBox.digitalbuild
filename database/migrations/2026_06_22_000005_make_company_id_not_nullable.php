<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Note: This migration uses change() which requires doctrine/dbal.
        // If you see an error, run: composer require doctrine/dbal

        $tables = ['users', 'departments', 'roles', 'shifts', 'attendances', 'leave_requests', 'tasks', 'notes', 'projects', 'calendar_events', 'media', 'social_accounts', 'scheduled_posts', 'messages', 'notifications', 'user_infos'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'company_id')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    // drop fk if exists
                    try {
                        $t->dropForeign(['company_id']);
                    } catch (\Throwable $e) {
                        // ignore
                    }

                    // If the column is nullable, change it to not nullable
                    $t->unsignedBigInteger('company_id')->nullable(false)->change();

                    // add FK with cascade on delete
                    $t->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['users', 'departments', 'roles', 'shifts', 'attendances', 'leave_requests', 'tasks', 'notes', 'projects', 'calendar_events', 'media', 'social_accounts', 'scheduled_posts', 'messages', 'notifications', 'user_infos'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'company_id')) {
                Schema::table($table, function (Blueprint $t) {
                    // drop FK and make column nullable
                    try {
                        $t->dropForeign(['company_id']);
                    } catch (\Throwable $e) {
                        // ignore
                    }
                    $t->unsignedBigInteger('company_id')->nullable()->change();
                });
            }
        }
    }
};
