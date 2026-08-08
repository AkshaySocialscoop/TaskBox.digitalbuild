<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
<<<<<<< HEAD
        $tables = [
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
=======
        // Note: This migration uses change() which requires doctrine/dbal.
        // If you see an error, run: composer require doctrine/dbal

        $tables = ['users', 'departments', 'roles', 'shifts', 'attendances', 'leave_requests', 'tasks', 'notes', 'projects', 'calendar_events', 'media', 'social_accounts', 'scheduled_posts', 'messages', 'notifications', 'user_infos'];

        foreach ($tables as $table) {
>>>>>>> 499935304e322d54198aa14fa8e21225ded305fd

        foreach ($tables as $tableName) {

            if (!Schema::hasTable($tableName)) {
                continue;
            }

<<<<<<< HEAD
            if (!Schema::hasColumn($tableName, 'company_id')) {
                continue;
            }

            // First remove the foreign key
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['company_id']);
            });

            // Then make company_id optional
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')
                    ->nullable()
                    ->change();
=======
            Schema::table($table, function (Blueprint $table) {

                try {
                    $table->dropForeign(['company_id']);
                } catch (\Throwable $e) {
                }

                $table->unsignedBigInteger('company_id')->nullable(false)->change();

                $table->foreign('company_id')
                    ->references('id')
                    ->on('companies')
                    ->cascadeOnDelete();
>>>>>>> 499935304e322d54198aa14fa8e21225ded305fd
            });
        }
    }

    public function down(): void
    {
<<<<<<< HEAD
        // No foreign key is restored.
        // company_id remains optional.
=======
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
>>>>>>> 499935304e322d54198aa14fa8e21225ded305fd
    }
};
