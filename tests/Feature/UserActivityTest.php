<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Panelis\User\Models\User;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

it('logs user lifecycle activity with translated descriptions', function (): void {
    $user = User::factory()->create([
        'name' => 'Yugo',
    ]);

    $activity = Activity::query()->where('subject_id', $user->getKey())->firstOrFail();

    expect($activity->log_name)->toBe('user')
        ->and($activity->event)->toBe('created')
        ->and($activity->description)->toBe('user::activity.created')
        ->and($activity->subject->is($user))->toBeTrue()
        ->and($activity->subject_type)->toBe('user');
});

it('does not log user activity when activity logging is disabled', function (): void {
    config()->set('activitylog.enabled', false);

    User::factory()->create();

    expect(Activity::query()->count())->toBe(0);
});
