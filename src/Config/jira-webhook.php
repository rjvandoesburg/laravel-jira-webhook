<?php

return [
    'events' => [
        'jira:issue_updated' => \Atlassian\JiraWebhook\Events\Issue\Updated::class,
        'jira:issue_created' => \Atlassian\JiraWebhook\Events\Issue\Created::class,
        'jira:issue_deleted' => \Atlassian\JiraWebhook\Events\Issue\Deleted::class
    ]
];