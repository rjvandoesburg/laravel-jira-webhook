<?php

Route::post('jira/webhook/{event?}', '\Atlassian\JiraWebhook\Http\Controllers\WebhookController')->name('jira-webhook');

