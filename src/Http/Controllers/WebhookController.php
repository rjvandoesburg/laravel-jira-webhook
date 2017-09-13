<?php

namespace Atlassian\JiraWebhook\Http\Controllers;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;

/**
 * Class WebhookController
 *
 * @package Atlassian\JiraWebhook\Http\Controllers
 */
class WebhookController
{
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * @var \Illuminate\Contracts\Logging\Log
     */
    protected $logger;

    /**
     * @var
     */
    protected $config;

    /**
     * WebhookController constructor.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @param \Illuminate\Contracts\Logging\Log $logger
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public function __construct(EventDispatcher $events, Log $logger, ConfigRepository $config)
    {
        $this->events = $events;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Post route to handle the webhook events fired from Jira.
     *
     * @param Request $request
     * @param string|null $event
     */
    public function __invoke(Request $request, $event = null)
    {
        $content = null;
        if ($event !== null) {
            $content = $request->query();
            $this->fireEvent($event, $content);
        } else if (! empty($body = $request->getContent())) {
            $content = json_decode($body);
            $this->fireEvent($content->webhookEvent, $content);
        } else {
            $this->logger->critical('Failed to handle the webhook, check if you are passing a body or an eventname in the hook');
        }
    }

    /**
     * @param $eventName
     * @param null $content
     */
    public function fireEvent($eventName, $content = null)
    {
        $event = $this->config->get('atlassian.jira-webhook.events.'.$eventName);

        if ($event !== null) {
            $this->events->dispatch($event, new $event($content));
        } else {
            $this->logger->info('No events registered for: '.$eventName);
        }
    }

}