<?php
include("../../config/config.php");
include("../../includes/classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

//If query contains an underscore, assume user is searching for usernames
if(strpos($query, '_') !== false) 
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM users,posts WHERE users.username=posts.added_by AND ((users.username LIKE '$query%' AND users.user_closed='no') OR (posts.body LIKE '%$query%')) LIMIT 8");
//If there are two words, assume they are first and last names respectively
else if(count($names) == 2)
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM users,posts WHERE users.username=posts.added_by AND (((users.first_name LIKE '$names[0]%' AND users.last_name LIKE '$names[1]%') AND users.user_closed='no') OR (posts.body LIKE '%$query%')) LIMIT 8");
//If query has one word only, search first names or last names 
else 
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM users,posts WHERE users.username=posts.added_by AND (((users.first_name LIKE '$names[0]%' OR users.last_name LIKE '$names[0]%') AND users.user_closed='no') OR (posts.body LIKE '%$query%'))  LIMIT 8");


if($query != ""){

	while($row = mysqli_fetch_array($usersReturnedQuery)) {
		$user = new User($con, $userLoggedIn);

		if($row['username'] != $userLoggedIn)
			$mutual_friends = $user->getMutualFriends($row['username']) . " friends in common";
		else 
			$mutual_friends == "";

		echo "<div class='resultDisplay'>
				<a href='" . $row['username'] . "' style='color: #1485BD'>
					<div class='liveSearchProfilePic'>
						<img src='" . $row['profile_pic'] ."'>
					</div>

					<div class='liveSearchText'>
						" . $row['first_name'] . " " . $row['last_name'] . "
						<p>" . $row['username'] ."</p>
						<p>".$row['body']."</p>
						<p id='grey'>" . $mutual_friends ."</p>
					</div>


				</a>
				</div>";

	}

}

?>