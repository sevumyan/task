<?php

namespace App\Models;

use App\Enums\User\UserPosition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $position
 * @property Carbon $created_at
 * @property Carbon| $updated_at
 * @property-read Collection<Task> $tasks
 * @property-read Collection<TaskComment> $taskComments
 * @property-read Collection<TaskNotification> $taskNotifications
 *
*/
class User extends Authenticatable
{

    protected $fillable = [
        'name',
        'email',
        'position',
    ];

    protected $casts = [
        'position' => UserPosition::class,
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function taskComments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    public function taskNotifications(): HasMany
    {
        return $this->hasMany(TaskNotification::class);
    }

    public function isManager(): bool
    {
        return $this->position == UserPosition::MANAGER->value;
    }

    public function isDeveloper(): bool
    {
        return $this->position == UserPosition::DEVELOPER->value;
    }

    public function isTester(): bool
    {
        return $this->position == UserPosition::TESTER->value;
    }
}
