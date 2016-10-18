
<?php
class HeaderClass 
{
    public function getPageHeader($session)
    {
        if($session->get('logged_in') !== null)
        {
		//to trigger logout
			$output = "<a href='http://micro-blog.dev/api/logout'><input type = 'button' value = '" . Logout . "'></a>";
		//to trigger delete a blog
		/*$output = "<form action = 'http://micro-blog.dev/api/posts/delete/" . $session->get('user_id') . " method = 'get'>
			<input type = 'submit' value = '" . $session->get('logged_in') . "'>
		</form>";*/
		//to trigger edit - delete post
		/*$output = "<form action = 'http://micro-blog.dev/api/posts/user/" . $session->get('user_id') . "'>
			<input type = 'submit' value = '" . $session->get('logged_in') . "'>
		</form>";*/
		//to trigger new post
		/*$output = "<form action = 'http://micro-blog.dev/api/posts/new' method = 'post'>
			<input type = 'submit' value = '" . $session->get('logged_in') . "'>;
		</form>";*/


        	//$output = "<a href='http://micro-blog.dev/api/posts/new'><button class='button header' type='button' id = 'account-button'>" . $session->get('logged_in') . "</button></a>";
	}
	else
	{
	    $output = "<button class='button header' type='button' id = 'register-button' onclick = 'displayRegisterDialog()'>Register</button>
		       &nbsp;&nbsp;&nbsp;&nbsp; 
		       <button class='button header' type='button' id = 'login-button' onclick = 'displayLoginDialog()'>Login</button>";
	}
        return $output;
    }
}
?>
