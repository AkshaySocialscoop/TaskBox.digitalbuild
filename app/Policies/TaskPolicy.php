<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->assigned_by || $user->role === 'super_admin';
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->assigned_by || $user->role === 'super_admin';
    }
}
