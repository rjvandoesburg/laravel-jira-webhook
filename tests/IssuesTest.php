<?php

namespace Tests;

use Atlassian\JiraWebhook\Events\Issue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Test\BaseTestCase;

class IssuesTest extends BaseTestCase
{
    public function testIssueCreated()
    {
        $json = $this->getJiraEventData('jira:issue_created');
        $data = json_decode($json, true);

        $this->callWebhook($data);
        Event::assertDispatched(Issue\Created::class, function ($eventName, $event) use ($data) {
            return Arr::get($event->data, 'user.name') === Arr::get($data, 'user.name')
                && Arr::get($event->data, 'issue.id') ===  Arr::get($data, 'issue.id');
        });
        $this->assertEquals(1, $this->countEvents());
    }

    public function testIssueUpdated()
    {
        $json = $this->getJiraEventData('jira:issue_updated');
        $data = json_decode($json, true);

        $this->callWebhook($data);
        Event::assertDispatched(Issue\Updated::class, function ($eventName, $event) use ($data) {
            return Arr::get($event->data, 'user.name') === Arr::get($data, 'user.name')
                && Arr::get($event->data, 'issue.id') === Arr::get($data, 'issue.id');
        });

        $this->assertEquals(1, $this->countEvents());
    }

    public function testIssueDeleted()
    {
        $json = $this->getJiraEventData('jira:issue_deleted');
        $data = json_decode($json, true);

        $this->callWebhook($data);
        Event::assertDispatched(Issue\Deleted::class, function ($eventName, $event) use ($data) {
            return Arr::get($event->data, 'user.name') === Arr::get($data, 'user.name')
                && Arr::get($event->data, 'issue.id') === Arr::get($data, 'issue.id');
        });

        $this->assertEquals(1, $this->countEvents());
    }

    public function testIssueWorklogUpdated()
    {
        $json = $this->getJiraEventData('jira:worklog_updated');
        $data = json_decode($json, true);

        $this->callWebhook($data);
        Event::assertDispatched(Issue\WorklogUpdated::class, function ($eventName, $event) use ($data) {
            return Arr::get($event->data, 'user.name') === Arr::get($data, 'user.name')
                && Arr::get($event->data, 'issue.id') === Arr::get($data, 'issue.id');
        });

        $this->assertEquals(1, $this->countEvents());
    }
}

