<?php

Route::post('jira/webhook', '\Atlassian\JiraWebhook\Http\Controllers\WebhookController@webhook')->name('jira-webhook');

