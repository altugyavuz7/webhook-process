## Files

- Commands
  - CheckWebhookProcess
  - CheckWebhookProcessError
- Controllers
  - WebhookController (only listen function changed)
  - WebhookProcessController (for UI)
  - WebhookProcessErrorController (for UI)
- Jobs
  - WebhookProcess (updated)
- Models
  - WebhookProcess
  - WebhookProcessError
- Config
  - webhook
- Database Migration
  - Webhook process table
  - Webhook process error table
- Resources (for UI)
  - Menu
  - Webhook process list
  - Webhook process error list
- Routes (for UI)
  - Web

## Setup

```dotenv
WEBHOOK_BULK_ACTIVE=true
WEBHOOK_CREATE_WITH_JOB=true
WEBHOOK_CHECK_PER_MINUTE=5
```

- Add `WEBHOOK_BULK_ACTIVE=true` code to your .env file and set `true` for activate this system.
- If you have too much data you can add `WEBHOOK_CREATE_WITH_JOB=true` code to your .env file.
- `WEBHOOK_CHECK_PER_MINUTE` The process specifying how many minutes intervals the transactions will be checked. After changing this time, you need to manually change the schedule time via `App\Console\Kernel.php`.
- All files/codes copy the related places.
- Run `php artisan migrate` command.
- Add `$schedule->command('check:webhooks')->everyFiveMinutes();` command with `CheckWebhookProcess` Class implementation to `App\Console\Kernel.php` file.

## Error Check

If there is a record that has received an error, you can run the `php artisan check:webhook-errors` command after correcting the error and reprocess the records.

You can use the `php artisan check:webhook-errors --ids=1 --ids=2...` command to reprocess specific records instead of all records in the table.

## UI Implementation (Optional)
- WebhookProcessController.php
- WebhookProcessErrorController.php
- All files under resources folder
- Routes/web.php

## TO DO List

- [x] UI Implementation
- [ ] Error notifications