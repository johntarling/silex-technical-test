<?php
use Solution\Services\NewsService;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ),
));

//Register twig template engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/solution/views',
));

//Register a news service to help us find news articles
$app['news_service'] = $app->share(function ($app) {
    return new Solution\Services\NewsService($app['db']);
});

$app['debug'] = true;