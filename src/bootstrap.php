<?php
use Solution\Services\NewsService;
use \Doctrine\Common\Cache\ApcCache;
use \Doctrine\Common\Cache\ArrayCache;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
//use Dflydev\Silex\Provider\Psr0ResourceLocator\Psr0ResourceLocatorServiceProvider;
//use Dflydev\Silex\Provider\Psr0ResourceLocator\Composer\ComposerResourceLocatorServiceProvider;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ),
));

// Register Doctrine ORM
$app->register(new Nutwerk\Provider\DoctrineORMServiceProvider(), array(
    'db.orm.proxies_dir'           => __DIR__ . '/cache/doctrine/proxy',
    'db.orm.proxies_namespace'     => 'DoctrineProxy',
    'db.orm.cache'                 => 
        !$app['debug'] && extension_loaded('apc') ? new ApcCache() : new ArrayCache(),
    'db.orm.auto_generate_proxies' => true,
    'db.orm.entities'              => array(array(
        'type'      => 'annotation',       // entity definition 
        'path'      => __DIR__ . '/src/solution/entities/',   // path to your entity classes
        'namespace' => 'Solution\Entities', // your classes namespace
    )),
));

//Register twig template engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/solution/views',)
);

//Register a database setup service to help us get the application ready
$app['setup_service'] = $app->share(function ($app) {
    return new Solution\Services\SetupService($app['db.orm.em']);
});

//Register a news service to help us find news articles
$app['news_service'] = $app->share(function ($app) {
    return new Solution\Services\NewsService($app['db.orm.em']);
});

$app['debug'] = true;