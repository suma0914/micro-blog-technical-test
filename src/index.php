<?php

// Silex documentation: http://silex.sensiolabs.org/doc/

require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Doctrine\DBAL\Schema\Table;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
require_once 'HeaderClass.class.php';
require_once 'BodyClass.class.php';

$session = new Session();
if(!$session->isStarted())
{
	$session->start();
}


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


//create users table is it doesn't exist


$schema = $app['db']->getSchemaManager();
if (!$schema->tablesExist('users'))
{
	$users = new Table('users');
	$users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
	$users->setPrimaryKey(array('id'));
	$users->addColumn('username', 'string', array('length' => 32));
	$users->addUniqueIndex(array('username'));
	$users->addColumn('password', 'string', array('length' => 255));
	$schema->createTable($users);
	//$_SESSION['table'] = "created";
}


$app->get('/api/posts', function() use($app) {
    $sql = "SELECT rowid, * FROM posts";
    $posts = $app['db']->fetchAll($sql);

    return $app->json($posts, 200);
});

$app->get('/api/posts/user/{user_id}', function($user_id) use($app, $session) {
    $headerInstance = new HeaderClass();
    $header = $headerInstance->getPageHeader($session);
    $sql = "SELECT rowid, * FROM posts WHERE user_id = ?";
    $posts = $app['db']->fetchAll($sql, array((int) $user_id));
    $bodyInstance = new BodyClass();
    $body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $app->json($posts, 200));
    return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
});

$app->get('/api/posts/id/{post_id}', function($post_id) use($app, $session) {
    $headerInstance = new HeaderClass();
    $header = $headerInstance->getPageHeader($session);
    $sql = "SELECT rowid, * FROM posts WHERE rowid = ?";
    $post = $app['db']->fetchAssoc($sql, array((int) $post_id));
    $bodyInstance = new BodyClass();
    $body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $post);
    return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
});

$app->post('/api/register', function (Request $request) use($app, $session) {
	$username = $_POST["usernameValue"];
	$password = md5($_POST["passwordValue"]);
	$sql = "SELECT * FROM users WHERE username = ?";
	$user = $app['db']->fetchAll($sql, array((int) $username));
	try
	{
		$app['db']->insert('users', array('username' => $username, 'password' => $password));
		$session->set('user', $username);
		$session->set('logged_in', 1);
		return $app->redirect('/');
		//$app->redirect('/');
			//return new RedirectResponse('http://micro-blog.dev/');
		//$headerInstance = new HeaderClass();
		//$header = $header . $headerInstance->getPageHeader();
		//$subRequest = Request::create('/');
		//$response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
		//return $app->get('/');
		//$bodyInstance = new BodyClass();
		//$body = $bodyInstance->getPageBody('/api/posts', $response);
		//$_SERVER['REQUEST_URI'] = '/api/posts';
	  	//return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
	}
	catch (Exception $e)
	{
		$headerInstance = new HeaderClass();
		$header = $header . $headerInstance->getPageHeader($session);
		return $app['twig']->render('error.twig', array('header' => $header, 'body' => $e->getMessage()));
	}
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

$app->get('/', function() use($app, $session) {
	//$sql = "drop table users";
	//$post = $app['db']->executeQuery($sql);
    	$headerInstance = new HeaderClass();
	//$header = '$_SESSION[logged_in] - ' . $session->get('logged_in') . "<br/>";
	$header = $header . $headerInstance->getPageHeader($session);
	$subRequest = Request::create('/api/posts');
	$response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
	$bodyInstance = new BodyClass();
	$body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $response);
  return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
});


$app->run();
