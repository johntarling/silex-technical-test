<?php

$app->get('/setup', function() use ($app) { 
	$sql = "CREATE TABLE IF NOT EXISTS news (
		id	INT NOT NULL,
		title TEXT,
		short_desc TEXT,
		body TEXT,
		PRIMARY KEY(id)
	)";
	$post = $app['db']->query($sql);
	for($i=1; $i<11; $i++) {
		$sql = "INSERT INTO news (id, title, short_desc, body) VALUES 
			($i, 'Test $i', 'Test $i', 'Test $i')";
		$post = $app['db']->query($sql);
	}
	
	return "done";
});

// Home route
$app->get('/', function() use ($app) {
	//Get latest 10 news articles
	$articles = $app['news_service']->getLatestNewsArticles(10);
	return $app['twig']->render('home.html', array(
        'articles' => $articles,
    ));
});

// View article route
$app->get('/{id}', function ($id) use ($app) {
	//Get article
    $article = $app['news_service']->getArticleById($id);
    if ($article != null) {
    	return $app['twig']->render('article.html', array(
        	'article' => $article,
      	));
    } else {
    	throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException("No articles were found for the id specified.");
    }
});

// Handle Not found errors
$app->error(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) use ($app) {
    $message = $e->getMessage() ?: 'Resource not found!';
    return new Symfony\Component\HttpFoundation\Response(
    	$app['twig']->render('error.html', array('message' => $message)
    ), 404);
});

//Handle DB errors
$app->error(function (PDOException $e) use ($app) {
    $message = $e->getMessage() ?: 'There has been a database error';
    return new Symfony\Component\HttpFoundation\Response(
    	$app['twig']->render('error.html', array('message' => $message)
    ), 500);
});