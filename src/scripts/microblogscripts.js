function displayRegisterDialog()
{
	document.getElementById("main-div").style.visibility = "hidden";
	document.getElementById("register-dialog").style.visibility = "visible";
	//document.body.innerHTML = document.getElementById("register-dialog");
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