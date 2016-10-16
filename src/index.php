<?php

// Silex documentation: http://silex.sensiolabs.org/doc/

require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
require_once 'HeaderClass.class.php';
require_once 'BodyClass.class.php';
$app = new Silex\Application();

$app['debug'] = true;

/* SQLite config

TODO: Add a users table to sqlite db
*/

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ),
));

// Twig template engine config
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));


/* ------- micro-blog api ---------

All CRUD operations performed within our /api/ endpoints below

TODO: Error checking - e.g. if try retrieve posts for a user_id that does
      not exist, return an error message and an appropriate HTTP status code.

      Implement /api/posts/new endpoint to add a new micro-blog post for a
      given user.

      Extra: Add new API endpoints for any extra features you can think of.

      Extra: Improve on current API code where you see necessary
*/

$app->get('/api/posts', function() use($app) {
    $sql = "SELECT rowid, * FROM posts";
    $posts = $app['db']->fetchAll($sql);

    return $app->json($posts, 200);
});

$app->get('/api/posts/user/{user_id}', function($user_id) use($app) {
    $headerInstance = new HeaderClass();
    $header = $headerInstance->getPageHeader();
    $sql = "SELECT rowid, * FROM posts WHERE user_id = ?";
    $posts = $app['db']->fetchAll($sql, array((int) $user_id));
    $bodyInstance = new BodyClass();
    $body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $app->json($posts, 200));
    return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
});

$app->get('/api/posts/id/{post_id}', function($post_id) use($app) {
    $headerInstance = new HeaderClass();
    $header = $headerInstance->getPageHeader();
    $sql = "SELECT rowid, * FROM posts WHERE rowid = ?";
    $post = $app['db']->fetchAssoc($sql, array((int) $post_id));
    $bodyInstance = new BodyClass();
    $body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $post);
    return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
});

$app->post('/api/posts/new', function (Request $request) {
  //TODO
});



/* ------- micro-blog web app ---------

All Endpoints for micro-blog web app below.

TODO: Build front-end of web app in the / endpoint below - Add more
      endpoints if you like.

      See TODO in index.twig for more instructions / suggestions
*/

$app->get('/', function() use($app) {
	$headerInstance = new HeaderClass();
	$header = $headerInstance->getPageHeader();
	$subRequest = Request::create('/api/posts');
	$response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
	$bodyInstance = new BodyClass();
	$body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $response);
  return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
});


$app->run();
