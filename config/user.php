<?php

use Panelis\User\Models\Permission;
use Panelis\User\Models\Role;
use Panelis\User\Models\User;

return [
    'models' => [
        'user' => User::class,
        'role' => Role::class,
        'permission' => Permission::class,
    ],
];
