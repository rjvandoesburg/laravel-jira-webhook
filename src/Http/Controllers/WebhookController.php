<?php

namespace Atlassian\JiraWebhook\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

/**
 * Class WebhookController
 * @package Atlassian\JiraWebhook\Http\Controllers
 */
class WebhookController
{
    /**
     * Post route to handle the webhook events fired from Jira.
     *
     * @param Request $request
     * @param EventDispatcher $events
     * @param Log $logger
     */
    public function webhook(Request $request, EventDispatcher $events, Log $logger)
    {
        $content = json_decode($request->getContent());

        $event = Config::get('atlassian.jira-webhook.events.' . $content->webhookEvent);

        if ($event !== null) {
            $events->dispatch($event, new $event($content));
        } else {
            $logger->info('No events registered for: ' . $content->webhookEvent);
        }
    }

}