<?php

namespace Panelis\User\Commands;

use Illuminate\Console\Command;
use Panelis\User\Actions\SyncPermission;

class SyncPermissionsCommand extends Command
{
    protected $signature = 'panelis:sync-permissions';

    protected $description = 'Synchronize permissions registered by Panelis modules';

    public function handle(): int
    {
        $result = SyncPermission::run();

        $this->info(sprintf(
            'Permissions synchronized successfully. Created: %d, updated: %d, deleted: %d.',
            $result['created'],
            $result['updated'],
            $result['deleted'],
        ));

        return self::SUCCESS;
    }
}
