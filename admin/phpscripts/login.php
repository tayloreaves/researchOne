<?php
	function logIn($username, $password, $ip) {
		require_once("connect.php");
		$username = mysqli_real_escape_string($link, $username);
		$password = mysqli_real_escape_string($link, $password);
		$loginString = "SELECT * FROM tbl_user WHERE user_username='{$username}' AND user_pass='{$password}'";
		//echo $loginString;
		$user_set = mysqli_query($link, $loginString);

		$lockoutString = "SELECT * FROM tbl_user WHERE user_username = '$username' OR user_pass = '$password'"; //this sees if the password and username matches $username and $password
		$result = mysqli_query($link, $lockoutString); //this takes the results from $lockoutString and stores them in $result
		$match = mysqli_num_rows($result); // this is seeing if the password and username both match, in which case it would be $match= 2, if one of the two matches it would be $match= 1, if neither username or password matches, it would be $match= 0
		if($match < 2) { // in the case that the password and username both don't match ($match < 2) then it will use $updateLogin and $newLoginQuery
			$updateLogin = "UPDATE tbl_user SET user_loginAttempts = user_loginAttempts + 1 WHERE user_ip = '$ip'"; //add +1 to the user_loginAttempts
			$newLoginQuery = mysqli_query($link, $updateLogin);
// I
			$attemptsString = "SELECT user_loginAttempts FROM tbl_user WHERE user_ip ='$ip' AND user_loginAttempts >= '3'"; //this query selects the ip's of users with more than 3 unsuccessful login attempts
			$attemptsQuery = mysqli_query($link, $attemptsString);
			$threeAttempts = mysqli_num_rows($attemptsQuery);
			//$failmessage = "Uh oh. You have reached 3 failed login attempts"
			if($threeAttempts == "1") { //this statement is saying that if someone has tried to login 3 times with the wrong username/password, they will be locked out and the echo statement will show up
				//return $failmessage;
				//echo 'Uh oh. You have reached 3 failed login attempts.'; <-this didnt work because it wasn't recognizing the difference between failed and successful login attempts
				//My database isn't recognizing a successful login attempt from a failed login attempt - in the database it is tracking all of my logins and not able to say that 3 failed ones locks you out
				//I tried to create a $failmessage value but it showed up after every attempt, not after 3.
			}

		}elseif($match = 2) { //this means thatthe password & username were both correct
			$updateLogin = "UPDATE tbl_user SET user_loginAttempts = '0' WHERE user_ip = '$ip'"; //this will reset user_loginAttempts to 0 when the username and password were entered correctly
			$newLoginQuery = mysqli_query($link, $updateLogin);
		}

		if(mysqli_num_rows($user_set)) {
			$found_user = mysqli_fetch_array($user_set, MYSQLI_ASSOC);
			$id = $found_user['user_id'];
			$_SESSION['users_id'] = $id;
			$_SESSION['users_name'] = $found_user['user_username'];
			$_SESSION['users_fname'] = $found_user['user_fname'];
			if(mysqli_query($link, $loginString)) {
				$updateString = "UPDATE tbl_user SET user_ip = '{$ip}' WHERE user_id = {$id}";
				$newQuery = mysqli_query($link, $updateString);

				//this fetches the person's last login and displays it on in the corner
				date_default_timezone_set('America/Toronto'); //this is how to set the default timezone
				$currentDate = date('l F jS Y, \a\t h:ia T'); //this shows the date and time as Sunday February 11th 2018, at 03:37pm EST for example
				$_SESSION['current_hour'] = date('G'); //this is taking the current hour and putting it in the session variable
				$updateTime = "UPDATE tbl_user SET user_loginTime = '$currentDate' WHERE user_id = {$id}"; //this will update the user_loginTime column of tbl_user with $currentDate when the user logs in
				$newTimeQuery = mysqli_query($link, $updateTime); //this is what links the above query (new login time) to database
				$timestamp = $found_user['user_loginTime']; //this will store the user's loginTime value of $found_user in the variable $timestamp
				$_SESSION['users_timestamp'] = $timestamp; //this stores the last time of login and puts it in the $timestamp variable
			}
			redirect_to("admin_index.php");
		}else{
			$message = "Uh oh, your username/password is incorrect.";
			return $message;

		}
		mysqli_close($link);
	}
?>
