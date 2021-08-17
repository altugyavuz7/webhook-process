<?php

return [
    "active" => env("WEBHOOK_BULK_ACTIVE", false),

    "create_with_job" => env("WEBHOOK_CREATE_WITH_JOB", false),

    "check_process_per_minute" => env("WEBHOOK_CHECK_PER_MINUTE", 5)
];
