<?php
	require_once('phpscripts/config.php');
	confirm_logged_in();
?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Taylor Eaves - Login Assignment</title>
    <link rel="stylesheet" href="css/app.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
  </head>
  <body>

		<form action="admin_login.php" method="post">
		<input type="submit" name="submit" value="Back" id="back">
	</form>

<?php
  if($_SESSION['current_hour'] <= "11") { //If it is 11am or earlier, then morning message will display
    $greeting = "🌅   Rise and shine, make <br> a coffee get to 🏢 ";
  }elseif($_SESSION['current_hour'] >= "12" && $_SESSION['current_hour'] <= "16") { //If the time is between 12pm & 4pm, then afternon message will display
    $greeting = "💼   Great, the work day is almost over <br> so you can go see your 🐶  ";
  }elseif($_SESSION['current_hour'] >= "16") { // if the current time is 4pm or later, then night message will display
    $greeting = "😴   Time to cook dinner and <br> get ready for 🛏️ ";
  }
?>

	<h1 id="greeting">

<?php
	echo $greeting;
	echo $_SESSION['users_fname'];
?>!
	</h1>

<br>
	<p id="lastLogin">The last time you logged in was
<?php echo $_SESSION['users_timestamp'];
?>.
	</p> <!--this fetches the session from login.php to display the last time the person logged on -->

<!-- <div id="logoutButCon"><a href="phpscripts/caller.php?caller_id=logout" id="logoutBut">Log Out</a></div> -->

</body>
</html>
