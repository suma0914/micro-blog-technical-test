
<?php
class HeaderClass 
{
    public function getPageHeader()
    {
        if(isset($_SESSION['logged_in']))
        {
            $user = unserialize($_SESSION['user']);
            $output = "<button type='button' class='button header'>$user</button>";
	}
	else
	{
            $output = "<button type='button' class='button header'>Login</button> &nbsp;&nbsp;&nbsp;&nbsp; <button class='button header' type='button'>Register</button>";
	}
        return $output;
    }
}
?>
