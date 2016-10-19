function displayRegisterDialog()
{
	document.getElementById("main-div").style.visibility = "hidden";
	document.getElementById("register-dialog").style.visibility = "visible";
}

function displayLoginDialog()
{
	document.getElementById("main-div").style.visibility = "hidden";
	document.getElementById("login-dialog").style.visibility = "visible";
}

function comparePasswords()
{
	var user = document.getElementById("usernameValue").value;
	var pass = document.getElementById("passwordValue").value;
	var passRepeat = document.getElementById("passwordRepeatValue").value;
	if(pass === passRepeat && pass.length != 0 && user.length != 0)
	{
		document.getElementById("error").style.visibility = "hidden";
		return true;
	}
	else
	{
		document.getElementById("error").style.visibility = "visible";
		return false;
	}
}

function newBlogPost()
{
    document.getElementById("post-blog").submit();
}

function editBlogPost()
{
    document.getElementById("update-blog").submit();
}

//in the blog edit and post page trying to count the number of characters typed to show
function countChar(val)
{
	var len = val.value.length;
    if (len >= 20)
	{
		val.value = val.value.substring(0, 500);
    }
	else
	{
		$('#content').text(20- len);
    }
}
