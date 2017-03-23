# Add a Jira webhook endpoint to your Laravel 5 application

This package adds a route to your laravel application to handle the webhook events sent by Jira 

## Install

You can install the package via composer:

``` bash
composer require rjvandoesburg/laravel-jira-webhook
```

You must install this service provider:

```php
// config/app.php

'providers' => [
    ...
    Atlassian\JiraWebhook\JiraWebhookServiceProvider::class,
    ...
];
```

If you're planning on linking your own events you must publish the config file with this command:

```bash
php artisan vendor:publish --provider="Atlassian\JiraWebhook\JiraWebhookServiceProvider"
```

A file named `jira-webhook.php` will be created in the config directory under the folder `atlassian`. 
The options you can set are as followed `key` is the dispatched event by Jira and the `value` is the event class to fire.
By default all events have a `data` property that holds the data sent by Jira. 

```php
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

```

## Usage

To handle the events you need to either register listeners to you EventServiceProvider or listen to the events manually. 
Please see [https://laravel.com/docs/events#defining-listeners](https://laravel.com/docs/events#defining-listeners) for more information.

## Credits

- [Robert-John van Doesburg](https://github.com/rjvandoesburg)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## To do

- Make url configurable
- Catch All events [Available events](https://developer.atlassian.com/jiradev/jira-apis/webhooks#Webhooks-configureConfiguringawebhook)
- Implement broadcasting events