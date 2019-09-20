<?php

namespace Test;

use Atlassian\JiraWebhook\JiraWebhookServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

abstract class BaseTestCase extends TestCase
{
    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $app->register(JiraWebhookServiceProvider::class);

        return $app;
    }

    protected function callWebhook($data)
    {
        $this->postJson('jira/webhook', $data);
    }

    protected function getJiraEventData($event)
    {
        return file_get_contents(__DIR__.'/data/'.str_replace(':', '_', $event).'.json');
    }

    /**
     * Count if ony a single event is fired
     *
     * @return int
     */
    protected function countEvents()
    {
        $eventDispatcher = app()->make('events');
        $reflectionClass = new \ReflectionClass($eventDispatcher);
        $property = $reflectionClass->getProperty('events');
        $property->setAccessible(true);

        // Remove the RequestHandled event
        return collect($property->getValue($eventDispatcher))->reject(function ($value, $key) {
            return $key === 'Illuminate\Foundation\Http\Events\RequestHandled';
        })->count();
    }

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

}