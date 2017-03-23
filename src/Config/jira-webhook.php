<?php

return [
    'events' => [
        'jira:issue_created' => \Atlassian\JiraWebhook\Events\Issue\Created::class,
        'jira:issue_updated' => \Atlassian\JiraWebhook\Events\Issue\Updated::class,
        'jira:issue_deleted' => \Atlassian\JiraWebhook\Events\Issue\Deleted::class,

        /**
         * Will be deprecated in the future and be replaced by worklog_updated
         * Still catching the events because it is still being sent out
         *
         * @see https://developer.atlassian.com/cloud/jira/platform/deprecation-notice-worklog-data-in-issue-related-events-for-webhooks/
         */
        'jira:worklog_updated' => \Atlassian\JiraWebhook\Events\Issue\WorklogUpdated::class,

        'worklog_created' => \Atlassian\JiraWebhook\Events\Worklog\Created::class,
        'worklog_updated' => \Atlassian\JiraWebhook\Events\Worklog\Updated::class,
        'worklog_deleted' => \Atlassian\JiraWebhook\Events\Worklog\Deleted::class
    ]
];