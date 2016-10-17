
<?php
class HeaderClass 
{
    public function getPageHeader($session)
    {
        if($session->get('logged_in') !== null)
        {
        	$output = "<button class='button header' type='button' id = 'account-button' onclick = 'showOptions()'>username</button>
		   ";
	}
	else
	{
	    $output = "<a><button class='button header' type='button' id = 'register-button' onclick = 'displayRegisterDialog()'>Register</button></a> &nbsp;&nbsp;&nbsp;&nbsp; <a href='http://micro-blog.dev/api/login'><button class='button header' type='button'>Login</button></a>";
	    //$output = $output . "<div id='register-dialog' title='Register' style = 'visibility:hidden'><h5 id = 'username'>Username</h5>&nbsp;&nbsp;<input type = 'text' id = 'usernameValue'/><br/><h5 id = 'password'>Password</h5>&nbsp;&nbsp;<input type = 'password' id = 'passwordValue'/><br/><h5 id = 'passwordRepeat'>Repeat Password</h5>&nbsp;&nbsp;<input type = 'password' id = 'passwordRepeatValue'/><br/></div>";
        }
        return $output;
    }
}
?>
