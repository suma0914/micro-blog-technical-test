
<?php
class HeaderClass 
{
    public function getPageHeader($session)
    {
        if($session->get('logged_in') !== null)
        {
		$output = "<div class='dropdown'>
				<button class='btn btn-default dropdown-toggle' type='button' id='menu1' data-toggle='dropdown' style='float:right'>Tutorials
		<span class='caret'/>
	</button>
	<br/>
	<ul class='dropdown-menu dropdown-menu-right' role='menu' aria-labelledby='menu1'>
		<li role='presentation'>
			<a role='menuitem' tabindex='-1' href='http://micro-blog.dev/'>Home</a>
		</li>
		<li role='presentation'>
			<a role='menuitem' tabindex='-1' onclick='newBlogPost()'><form method='post' id='post-blog' action='http://micro-blog.dev/api/posts/new'>Post A Blog</form></a>
		</li>
		<li role='presentation'>
			<a role='menuitem' tabindex='-1' onclick='editBlogPost()'><form id='update-blog' action='http://micro-blog.dev/api/posts/user/" . $session->get('user_id') . "'>Update Your Blog</form></a>
		</li>
		<li role='presentation'>
			<a role='menuitem' tabindex='-1' href='http://micro-blog.dev/api/posts/delete/" . $session->get('user_id') . "' >Delete Blogs</a>
		</li>
		<li role='presentation'>
			<a role='menuitem' tabindex='-1' href='http://micro-blog.dev/api/logout'>Logout</a>
		</li>
	</ul>
</div>";
		//to trigger logout
			//$output = "<a href='http://micro-blog.dev/api/logout'><input type = 'button' value = '" . Logout . "'></a>";
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
