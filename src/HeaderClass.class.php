<?php
class HeaderClass 
{
    public function getPageHeader($session)
    {
	// responsible for rendering header and footer. header is judged by checking whether the user is loggedin or not. if he/she is logged in then user is eligible for operations which are shown in the menu
        if($session->get('logged_in') !== null)
        {
			$output = "<div class='dropdown'>
							<button class='button header' btn-default dropdown-toggle' type='button' id='menu1' data-toggle='dropdown' style='float:right'>Account
						<span class='caret'/>
						</button><br/><br/>
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
						</ul></div>";
	}
	else
	{
	    $output = "<button class='button header' type='button' id = 'register-button' onclick = 'displayRegisterDialog()'>Register</button>&nbsp;&nbsp;&nbsp;&nbsp; 
					<button class='button header' type='button' id = 'login-button' onclick = 'displayLoginDialog()'>Login</button>";
	}
        return $output;
    }
}
?>
