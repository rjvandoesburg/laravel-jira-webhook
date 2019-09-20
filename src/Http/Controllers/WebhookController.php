<?php

namespace Atlassian\JiraWebhook\Http\Controllers;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Psr\Log\LoggerInterface;
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
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public function __construct(EventDispatcher $events, LoggerInterface $logger, ConfigRepository $config)
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
        $content = $request->all();
        if ($event !== null) {
            $this->fireEvent($event, $content);
        } else if (! empty($body = $request->getContent())) {
            $body = \json_decode($body, false);
            $this->fireEvent($body->webhookEvent, $content);
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