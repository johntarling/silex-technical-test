<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

$app->get('/setup', function() use ($app) { 
	//Return a setup confirmation page
	return $app['twig']->render('setup.html', array('title' => 'Setup'));
});

$app->get('/setup/run', function() use ($app) {
	//Return a page confirming the status of the news table setup
	$success = $app['setup_service']->createNewsTable();
	if($success == 'success') {
		$success = $app['setup_service']->createDummyNewsArticles(10);
		if($success == 'success') {
			$subRequest = Request::create('/run/complete/setupsuccess', 'GET');
   			return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
		} else {
			$subRequest = Request::create('/run/complete/setupfail', 'GET');
	    	return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
		}
	} else {
		$subRequest = Request::create('/run/complete/setupfail', 'GET');
    	return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
	}
});

$app->get('/update/run', function() use ($app) {
	//Return a page confirming the status of the news table update
	$success = $app['setup_service']->updateNewsSchema();
	if($success == 'success') {
		$subRequest = Request::create('/run/complete/updatesuccess', 'GET');
   		return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
	} else {
		$subRequest = Request::create('/run/complete/updatefail', 'GET');
    	return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
	}
});

$app->get('/update', function() use ($app) {
	//Return a page confirming the news table needs updating
	return $app['twig']->render('update.html', array('title' => 'Update'));
});

$app->get('/run/complete/{command}', function ($command) use ($app) {
	//Return a page confirming the news table needs updating
	switch (trim($command)) {
		case "updatesuccess":
			$status = "The news table was updated succesfully.";
			$alert = "success";
			break;
		case "updatefail":
			$status = "There was a problem updating the news table to v1.1";
			$alert = "error";
			break;
		case "setupsuccess":
			$status = "Setup successfully completed";
			$alert = "success";
			break;
		case "setupfail":
			$status = "Unable to create news table";
			$alert = "error";
			break;
		default: 
			$status = "Hmm, I am not sure what was run but it may have worked!";
			$alert = "block";
			break;
	}
	return $app['twig']->render('complete.html', array('title' => 'Complete', 'status' => $status, 'alert' => $alert)); 
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
        	'article' => $article[0],
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

//Handle PDO errors
$app->error(function (PDOException $e) use ($app) {
	if($app['setup_service']->checkExists('news') == 'N'){
		return $app->redirect('/setup');
	}
    $message = $e->getMessage() ?: 'There has been a database error';
    return new Symfony\Component\HttpFoundation\Response(
    	$app['twig']->render('error.html', array('message' => $message)
    ), 500);
});

//Handle DBAL errors
$app->error(function (Doctrine\DBAL\DBALException $e) use ($app) {
	if (strpos($e->getMessage(), "SQLSTATE[HY000]: General error: 1 no such column" ) != null) {
		//ORM throws a column not found even if the db doesn't exist so check if it does exist or not
		//before redirecting
		if($app['setup_service']->checkExists('news') == 'Y'){
			return $app->redirect('/update');
		} else {
			return $app->redirect('/setup');
		}
	} elseif (strpos($e->getMessage(), "SQLSTATE[HY000]: General error: 1 no such table")) {
		return $app->redirect('/setup');
	}
    $message = $e->getMessage() ?: 'There has been a database error';
    return new Symfony\Component\HttpFoundation\Response(
    	$app['twig']->render('error.html', array('message' => $message)
    ), 500);
});