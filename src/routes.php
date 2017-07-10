<?php

$admin = new MongoBlog\Middleware\AdminSecurity($_SESSION);

// Routes
$app->get('/', \MongoBlog\Pages\HomePage::class);

$app->post('/connect', MongoBlog\Pages\ConnectPage::class);

$app->post('/comment', MongoBlog\Pages\PostPage::class);

$app->post('/post', MongoBlog\Pages\PostPage::class.'publishPost')->add($admin);
