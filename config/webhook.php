<?php

return [
    "active" => env("WEBHOOK_BULK_ACTIVE", false),

    "create_with_job" => env("WEBHOOK_CREATE_WITH_JOB", false),

    "check_process_per_minute" => env("WEBHOOK_CHECK_PER_MINUTE", 5),

    "slack_notification" => env("WEBHOOK_SLACK_NOTIFY", false),

    "slack_hook_url" => env("WEBHOOK_SLACK_URL"),

    "slack_mention_activate" => env('WEBHOOK_SLACK_MENTION_ACTIVATE', false),

    "slack_mention_user" => env('WEBHOOK_SLACK_MENTION_USER', '')
];
