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
- Notifications
  - WebhookErrorNotification
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
WEBHOOK_SLACK_NOTIFY=true
WEBHOOK_SLACK_URL=https://hooks.slack.com/services/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
WEBHOOK_SLACK_MENTION_ACTIVATE=true
WEBHOOK_SLACK_MENTION_USER=UFGQJUAD7
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

## UI Integration (Optional)
- WebhookProcessController.php
- WebhookProcessErrorController.php
- All files under resources folder
- Routes/web.php

## Error Notification Integration (Optional)
- Default slack webhook URL is `https://hooks.slack.com/services/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`. This URL is point to the `#webhook-errors` channel. This channel is open for everyone.
- You can create special channel. You need to new incoming webhook URL.
  - First, go to https://app.slack.com , sign in and create new app. 
  - Activate Incoming Webhooks
  - Scroll down and create new webhook URL on the Incoming Webhooks web page.
  - Then copy the new URL.
- Also, if you want to mention specific user or everyone on the channel;
  - For Everyone on the channel `WEBHOOK_SLACK_MENTION_USER=everyone`
  - For specific user, you have to use Slack Member ID `WEBHOOK_SLACK_MENTION_USER=UFGQJUAD7`; 
    - Open the Slack app
    - Click the profile picture in the top-right corner
    - Click "View Profile" on the Menu
    - Click "More" Button
    - Click "Copy Member ID"
  - Also, you can use more than one user for mention. You have to use "," separate the users. `WEBHOOK_SLACK_MENTION_USER=UFGQJUAD7,UFGQASUAD7,everyone`

## TO DO List

- [x] UI Implementation
- [x] Error notifications
- [ ] Retry all process button and function for Webhook Processes UI
- [ ] Retry all process button and function for Webhook Process Errors UI