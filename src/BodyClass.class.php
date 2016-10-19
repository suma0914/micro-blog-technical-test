<?php
class BodyClass 
{
	public function getPageBody($uri, $response, $session)
	{
		if(strcasecmp($uri, '/') == 0 || strcasecmp($uri, '/api/posts') == 0)
		{
			$response = "" . $response;
			$response = trim($response);
			$response = substr($response, strpos($response, "["), strpos($response, "]"));
			$output = "<br/>";
			$jsonArray = json_decode($response, true);
			for($i = 0; $i < count($jsonArray); $i++)
			{
				$output = $output . "<div style='background: #4CAF50'><h2><a href='http://micro-blog.dev/api/posts/id/" . $jsonArray[$i]['rowid'] ."'>" . substr($jsonArray[$i]['content'], 0, 25) . "</a></h2><h5><a href='http://micro-blog.dev/api/posts/user/" . $jsonArray[$i]['user_id'] ."'>" . $jsonArray[$i]['user_id'] . "</a></h5><h5>" . $jsonArray[$i]['date'] . "</h5></div><br/>";
			}		
		}
		else if(strcasecmp(substr($uri, strpos($uri, "/"), strrpos($uri, "/")), '/api/posts/user') == 0)
		{
			$response = "" . $response;
			$response = trim($response);
			$response = substr($response, strpos($response, "["), strpos($response, "]"));
			$output = $output . "<br/>";
			$jsonArray = json_decode($response, true);
			for($i = 0; $i < count($jsonArray); $i++)
			{
				$output = $output . "<div style='background: #4CAF50'>
							<h2><a href='http://micro-blog.dev/api/posts/id/" . $jsonArray[$i]['rowid'] ."'>" . $jsonArray[$i]['content'] . "</a></h2>
							<h5><a href='http://micro-blog.dev/api/posts/user/" . $jsonArray[$i]['user_id'] ."'>" . $jsonArray[$i]['user_id'] . "</a></h5>";
				if($session->get('logged_in') !== null)
				{
					$output = $output . "<form action = 'http://micro-blog.dev/api/edit/id/" . $jsonArray[$i]['rowid'] . "' style='float:right;' method= 'post'>
								<input type = 'submit' value = 'Edit Post'>
							    </form>";
				}
				$output = $output . "<h5>" . $jsonArray[$i]['date'] . "</h5></div><br/>";
			}
		}
		else if(strcasecmp(substr($uri, strpos($uri, "/"), strrpos($uri, "/")), '/api/posts/id') == 0)
		{
			$output = $output . "<div style='background: #4CAF50'><h3>" . $response['content'] . "</h3><h5><a href='http://micro-blog.dev/api/posts/user/" . $response['user_id'] ."'>" . $response['user_id'] . "</a></h5><h5>" . $response['date'] . "</h5></div><br/>";
		}
		else if(strcasecmp(substr($uri, strpos($uri, "/")), '/api/posts/new') == 0)
		{
			$output = $output . "<div>
						<h3>Your Content Please : </h3><br/>
						<form action='http://micro-blog.dev/api/posts/new' method='post'>
							<textarea rows='12' cols='70' name='content' wrap='hard' style = 'resize: none'></textarea><br/>
							<input type='submit' value = 'Submit'/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href = 'http://micro-blog.dev/'><input type = 'button' value = 'Cancel'/></a><br/>
						</form>
					     </div>";
		}
		else if(strcasecmp(substr($uri, strpos($uri, "/"), strrpos($uri, "/")), '/api/edit/id') == 0)
		{
			$output = $output . "<div>
						<h3>Please Edit Your Post : </h3><br/>
						<form action='http://micro-blog.dev/api/edit/id/" . $response['rowid'] . "' method='post'>
							<textarea rows='12' cols='70' name='content' wrap='hard' style = 'resize: none'>". $response['content'] . "</textarea><br/>
							<input type='submit' value = 'Submit'/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href = 'http://micro-blog.dev/'><input type = 'button' value = 'Cancel'/></a><br/>
						</form>
					     </div>";
			
		}
		else if(strcasecmp(substr($uri, strpos($uri, "/"), strrpos($uri, "/")), '/api/posts/delete') == 0)
		{
			$response = "" . $response;
			$response = trim($response);
			$response = substr($response, strpos($response, "["), strpos($response, "]"));
			$output = $output . "<br/>";
			$jsonArray = json_decode($response, true);
			$session->set('user', 'flag');
			for($i = 0; $i < count($jsonArray); $i++)
			{
				$output = $output . "<div style='background: #4CAF50'><span>
							<h2><a href='http://micro-blog.dev/api/posts/id/" . $jsonArray[$i]['rowid'] ."'>" . $jsonArray[$i]['content'] . "</a></h2>
							<h5><a href='http://micro-blog.dev/api/posts/user/" . $jsonArray[$i]['user_id'] ."'>" . $jsonArray[$i]['user_id'] . "</a></h5>
							<a href = 'http://micro-blog.dev/api/posts/delete/" . $jsonArray[$i]['rowid'] . "'><input type = 'button' style = 'float:right' value = 'Delete' /></a>
							<h5>" . $jsonArray[$i]['date'] . "</h5>
						    </span>
						    <span></span></div><br/>";
			}
		}
		else
		{
			$output = "<h2> 404 - Page not found";
		}
		return $output;
	}

	public function getLoginFailureBody($message)
	{
		return "<div id='login-dialog' title='Login' style = 'visibility:visible'>
		<form action = 'http://micro-blog.dev/api/login' method='post'>
			<h5 id = 'username'>*Username</h5>&nbsp;&nbsp;<input type = 'text' id = 'usernameValue' name = 'usernameValue'/><br/>
			<h5 id = 'password'>*Password</h5>&nbsp;&nbsp;<input type = 'password' id = 'passwordValue' name = 'passwordValue'/><br/>
			<h6 id = 'error' style = 'visibility:visible; color:red;'>* " . $message . "</h6>
			<input type = 'submit' value = 'Submit'/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href = 'http://micro-blog.dev/'><input type = 'button' value = 'Cancel'/></a><br/>
		</form>
	</div>
	<script>
        	window.history.pushState('object', 'New Title', 'http://micro-blog.dev/');
	</script>";
	}

	public function getRegisterFailureBody($message)
	{
		return "<div id='register-dialog' title='Register' style = 'visibility:visible'>
				<form onsubmit = 'return comparePasswords()' action = 'http://micro-blog.dev/api/register' method='post'>
					<h5 id = 'username'>*Username</h5>&nbsp;&nbsp;<input type = 'text' id = 'usernameValue' name = 'usernameValue'/><br/>
					<h5 id = 'password'>*Password</h5>&nbsp;&nbsp;<input type = 'password' id = 'passwordValue' name = 'passwordValue'/><br/>
					<h5 id = 'passwordRepeat'>*Repeat Password</h5>&nbsp;&nbsp;<input type = 'password' id = 'passwordRepeatValue'/><br/><br/>
					<h6 id = 'error' style = 'visibility:visible; color:red;'>* " . $message . "</h6>
					<input type = 'submit' value = 'Submit'/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href = 'http://micro-blog.dev/'><input type = 'button' value = 'Cancel'/></a><br/>
				</form>
			</div>
	<script>
        	window.history.pushState('object', 'New Title', 'http://micro-blog.dev/');
	</script>";
	}
}
?>
