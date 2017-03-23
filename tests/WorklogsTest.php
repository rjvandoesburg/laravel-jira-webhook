<?php

namespace Tests;

use Atlassian\JiraWebhook\Events\Worklog;
use Illuminate\Support\Facades\Event;
use Test\BaseTestCase;

class WorklogsTest extends BaseTestCase
{
    public function testWorklogCreated()
    {
        $json = $this->getJiraEventData('worklog_created');
        $data = json_decode($json);

        $this->callWebhook($data);
        Event::assertDispatched(Worklog\Created::class, function ($eventName, $event) use ($data) {
            return $event->data->worklog->author->name === $data->worklog->author->name
                && $event->data->worklog->issueId === $data->worklog->issueId;
        });

        $this->assertEquals(1, $this->countEvents());
    }

    public function testWorklogUpdated()
    {
        $json = $this->getJiraEventData('worklog_updated');
        $data = json_decode($json);

        $this->callWebhook($data);
        Event::assertDispatched(Worklog\Updated::class, function ($eventName, $event) use ($data) {
            return $event->data->worklog->author->name === $data->worklog->author->name
                && $event->data->worklog->issueId === $data->worklog->issueId;
        });

        $this->assertEquals(1, $this->countEvents());
    }

    public function testWorklogDeleted()
    {
        $json = $this->getJiraEventData('worklog_deleted');
        $data = json_decode($json);

        $this->callWebhook($data);
        Event::assertDispatched(Worklog\Deleted::class, function ($eventName, $event) use ($data) {
            return $event->data->worklog->author->name === $data->worklog->author->name
                && $event->data->worklog->issueId === $data->worklog->issueId;
        });

        $this->assertEquals(1, $this->countEvents());
    }
}

