<?php

function check_login($con)
{

	if(isset($_SESSION['user_id']))
	{

		$id = $_SESSION['user_id'];
		$query = "select * from login where uid = '$id' limit 1";

		$result = mysqli_query($con,$query);
		if($result && mysqli_num_rows($result) > 0)
		{

			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}

	//redirect to login
	else{
	header("Location: login.php");
	die;
	}

}
function random_num()
{

	$text = "";
	for ($i=0; $i < 6; $i++) { 
		$text .= rand(0,9);
	}

	return $text;
}