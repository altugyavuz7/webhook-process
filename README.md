## Files

- Commands
  - CheckWebhookProcess
  - CheckWebhookProcessError
- Controllers
  - WebhookController (only listen function changed)
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

## Setup

- Add `WEBHOOK_BULK_ACTIVE=true` code to your .env file
- All files/codes copy the related places.
- Run `php artisan migrate` command.
- Add `$schedule->command('check:webhooks')->everyFiveMinutes();` command with `CheckWebhookProcess` Class implementation to `App\Console\Kernel.php` file.

## Error Check

If there is a record that has received an error, you can run the `php artisan check:webhook-errors` command after correcting the error and reprocess the records.

You can use the `php artisan check:webhook-errors --ids=1 --ids=2...` command to reprocess specific records instead of all records in the table.