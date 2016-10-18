
<?php
class HeaderClass 
{
    public function getPageHeader($session)
    {
        if($session->get('logged_in') !== null)
        {
		$output = "<form action = 'http://micro-blog.dev/api/posts/new' method = 'post'>
			<input type = 'submit' value = '" . $session->get('logged_in') . "'>;
		</form>";
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
