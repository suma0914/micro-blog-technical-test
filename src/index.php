<?php
require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Doctrine\DBAL\Schema\Table;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
require_once 'HeaderClass.class.php';
require_once 'BodyClass.class.php';

//start a session for current user if it is not started already
$session = new Session();
if(!$session->isStarted())
{
	$session->start();
}

$app = new Silex\Application();
$app['debug'] = true;

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
}

//endpoint to see all the blogs available to read
$app->get('/api/posts', function() use($app)
{
	//get all the posts from db and return the array of json object of them
	$sql = "SELECT rowid, * FROM posts order by date desc";
	$posts = $app['db']->fetchAll($sql);
	return $app->json($posts, 200);
});

//endpoint to see all the blogs posted by user_id
$app->get('/api/posts/user/{user_id}', function($user_id) use($app, $session)
{
	// get all the blog posted by $user_id and store in $posts
	$headerInstance = new HeaderClass();
	$header = $headerInstance->getPageHeader($session);
	$sql = "SELECT rowid, * FROM posts WHERE user_id = ?  order by date desc";
	$posts = $app['db']->fetchAll($sql, array((int) $user_id));
	//if there are elements in $posts, render else "No posts to show by"
	if(count($posts) > 0)
	{
		$bodyInstance = new BodyClass();
		$body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $app->json($posts, 200), $session);
		return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
	}
	else
	{
		return $app['twig']->render('index.twig', array('header' => $header, 'body' => "<h1>No posts to show by " . $user_id . "</h1>"));
	}
});

//endpoind to view a particular blog
$app->get('/api/posts/id/{post_id}', function($post_id) use($app, $session)
{
	//fetch the blog's details from db having rowid = $post_id
	$headerInstance = new HeaderClass();
	$header = $headerInstance->getPageHeader($session);
	$sql = "SELECT rowid, * FROM posts WHERE rowid = ?  order by date desc";
	$post = $app['db']->fetchAssoc($sql, array((int) $post_id));
	//if blog exists render else "No such Blog"
	if($post != null)
	{
		$bodyInstance = new BodyClass();
		$body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $post, $session);
		return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
	}
	else
	{
		return $app['twig']->render('index.twig', array('header' => $header, 'body' => "<h1>No such Blog</h1>"));
	}
});

//endpoint is to register to the micro-blog.dev as a user. after getting the username and password in the request try to insert the record in the table if insertion fails that means user already exists and return the error page content. stor the md5 hash of the password for security purpose
$app->post('/api/register', function (Request $request) use($app, $session)
{
	$username = $_POST["usernameValue"];
	$password = md5($_POST["passwordValue"]);
	$sql = "SELECT * FROM users WHERE username = ?";
	$user = $app['db']->fetchAll($sql, array((int) $username));
	try
	{
		$app['db']->insert('users', array('username' => $username, 'password' => $password));
		$session->set('user', $username);
		$session->set('user_id', $user['id']);
		$session->set('logged_in', 1);
		return $app->redirect('/');
	}
	catch (Exception $e)
	{
		$bodyInstance = new BodyClass();
		$body = $bodyInstance->getRegisterFailureBody("user already exists");
		return $app['twig']->render('index.twig', array('header' => '', 'body' => $body));
	}
});

//endpoint is used to login micro-blog.dev. try to fetch user details using username passed in the request. here post request is used because of the transportation of sensitive data.
/*
if query execution = success
	if(username or password in db is not null)
		if(username and password are validated successfully)
			set session variables and redirect to homepage
		else
			return "incorrect password"
	else
		return "user doesn't exist"
else
	return runtime execption
*/ 
$app->post('/api/login', function (Request $request) use($app, $session)
{
	$username = $_POST["usernameValue"];
	$password = md5($_POST["passwordValue"]);
	$sql = "SELECT * FROM users WHERE username = ?";
	$user = $app['db']->fetchAssoc($sql, array((string) $username));
	try
	{
		if($user['username'] !== null || $user['password'] !== null)
		{
			if(strcmp($username, $user['username']) == 0 && strcmp($password, $user['password']) == 0)
			{
				$session->set('user', $username);
				$session->set('user_id', $user['id']);
				$session->set('logged_in', 1);
				return $app->redirect('/');
			}
			else
			{
				$bodyInstance = new BodyClass();
				$body = $bodyInstance->getLoginFailureBody("incorrect password");
				return $app['twig']->render('index.twig', array('header' => '', 'body' => $body));
			}
		}
		else
		{
			$bodyInstance = new BodyClass();
			$body = $bodyInstance->getLoginFailureBody("user doesn't exist");
			return $app['twig']->render('index.twig', array('header' => '', 'body' => $body));
		}
	}
	catch (Exception $e)
	{
		$headerInstance = new HeaderClass();
		$header = $header . $headerInstance->getPageHeader($session);
		return $app['twig']->render('index.twig', array('header' => $header, 'body' => $e->getMessage()));
	}
});

/*
a new blog post requests is basically consists of two requests. there are so many otherways to do this but I thought ib this way I don't have to create seperate html or php
1. to render the empty textarea to get provide a platform to user to enter data
2. to store the data and metadata entered by user in the db
both are done in the same endpoint by using $_POST['content']
$_POST['content'] is initially null and is set to some value when the request is to store in the db
*/
$app->post('/api/posts/new', function (Request $request) use($app, $session)
{
	if($_POST['content'] == null || strcmp(trim($_POST['content']), '') == 0)
	{
		$headerInstance = new HeaderClass();
		$header = $_POST['content'] . $headerInstance->getPageHeader($session);
		$bodyInstance = new BodyClass();
		$body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], '', $session);
		return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
	}
	else
	{
		$time = date_timestamp_get(date_create());
		$app['db']->insert('posts', array('content' => $_POST['content'], 'user_id' => $session->get('user_id'), 'date' => "" . $time));
		$sql = "SELECT rowid FROM posts WHERE date = ?";
   		$post = $app['db']->fetchAssoc($sql, array((string) $time));
		return $app->redirect('/api/posts/id/' . $post['rowid']);
	}
});

/*
endpoint is used to enable the user to edit the blog whic are "only posted by themselves" similar as above one, this uri shouldn't be triggered directly because it is post
*/
$app->post('/api/edit/id/{post_id}', function($post_id) use($app, $session)
{
	$headerInstance = new HeaderClass();
	$header = $headerInstance->getPageHeader($session);
	$bodyInstance = new BodyClass();
	if($_POST['content'] == null || strcmp(trim($_POST['content']), '') == 0)
	{
	    	$sql = "SELECT rowid,* FROM posts WHERE rowid = ?";
	    	$posts = $app['db']->fetchAssoc($sql, array((string) $post_id));
			$body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $posts, $session);
			return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
	}
	else
	{
		$sql = "update posts set content = ? where rowid = ?";
		$posts = $app['db']->executeQuery($sql, array((string) $_POST['content'], (string) $post_id));
		return $app->redirect('/api/posts/id/' . $post_id);
	}
});

/*
endpoint is used to enable the user to delete the blog whic are "only posted by themselves" similar as above
*/

$app->get('/api/posts/delete/{user_id}', function($user_id) use($app, $session)
{
	$headerInstance = new HeaderClass();
	$header = $headerInstance->getPageHeader($session);
	$bodyInstance = new BodyClass();
	if($session->get('flag') == null)
	{
		$sql = "SELECT rowid, * FROM posts WHERE user_id = ?  order by date desc";
    	$posts = $app['db']->fetchAll($sql, array((int) $user_id));
		$body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $app->json($posts, 200), $session);
		return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
	}
	else
	{
		$sql = "delete FROM posts WHERE rowid = :id";
		$preparedStatement = $app['db']->prepare($sql);
		$preparedStatement->execute(array(':id' => $user_id));
		$session->set('flag', null);
		return $app->redirect('/api/posts/delete/' . $session->get('user_id'));
	}
});

//destroy all the session properties
$app->get('/api/logout', function() use($app, $session)
{
	session_unset();
	return $app->redirect('/');
});

//homepage lists all the blogs posted
$app->get('/', function() use($app, $session)
{
   	$headerInstance = new HeaderClass();
	$header = $headerInstance->getPageHeader($session);
	$subRequest = Request::create('/api/posts');
	$response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
	$bodyInstance = new BodyClass();
	$body = $bodyInstance->getPageBody($_SERVER['REQUEST_URI'], $response, $session);
  	return $app['twig']->render('index.twig', array('header' => $header, 'body' => $body));
});

$app->run();
?>
