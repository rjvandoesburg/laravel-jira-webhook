<?php

namespace Atlassian\JiraWebhook\Events;

use Symfony\Component\EventDispatcher\Event;

abstract class WebhookEvent extends Event
{
    /**
     * @var
     */
    public $data;

    /**
     * IssueEvent constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
}