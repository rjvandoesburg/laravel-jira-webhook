<?php

namespace Atlassian\JiraWebhook;


use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class JiraWebhookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');

        $path = Container::getInstance()->make('path.config').'/atlassian/jira-webhook.php';

        $this->publishes([
            __DIR__.'/Config/jira-webhook.php' => $path,
        ]);
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/jira-webhook.php', 'atlassian.jira-webhook'
        );
    }
}
