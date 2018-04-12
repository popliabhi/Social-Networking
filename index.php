<?php 
include("includes/header.php");


if(isset($_POST['post'])){
	$post = new Post($con, $userLoggedIn);
	$post->submitPost($_POST['post_text'], 'none');
}


 ?>
	<div class="user_details column">
		<a href="<?php echo $userLoggedIn; ?>">  <img src="<?php echo $user['profile_pic']; ?>"> </a>

		<div class="user_details_left_right">
			<a href="<?php echo $userLoggedIn; ?>">
			<?php 
			echo $user['first_name'] . " " . $user['last_name'];

			 ?>
			</a>
			<br>
			<?php echo "Posts: " . $user['num_posts']. "<br>"; 
			echo "Likes: " . $user['num_likes'];

			?>
		</div>

	</div>

	<div class="main_column column">
		<form class="post_form" action="index.php" method="POST">
			<textarea name="post_text" id="post_text" placeholder="Got something to say?"></textarea>

			<input type="submit" name="post" id="post_button" value="Post">
			<br>
			
			

		</form>
		<br>
		<form action="upload_wall.php" method="post" enctype="multipart/form-data" class="wall_post" >
    	Select image to upload:
    	<input type="file" name="fileToUpload" id="fileToUpload">
    	<br>
    	<input type="submit" value="Upload Image" name="submit_pic">
		</form>

		<hr>
		<div class="posts_area"></div>
		<img id="loading" src="assets/images/icons/loading.gif">


	</div>

	<div class="user_details column">

		<h4>Popular</h4>

		<div class="trends">
			<?php 
			$query = mysqli_query($con, "SELECT * FROM trends ORDER BY hits DESC LIMIT 9");

			foreach ($query as $row) {
				
				$word = $row['title'];
				$word_dot = strlen($word) >= 14 ? "..." : "";

				$trimmed_word = str_split($word, 14);
				$trimmed_word = $trimmed_word[0];

				echo "<div style'padding: 1px'>";
				echo $trimmed_word . $word_dot;
				echo "<br></div><br>";


			}

			?>
		</div>


	</div>




	<script>
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';

	$(document).ready(function() {

		$('#loading').show();

		//Original ajax request for loading first posts 
		$.ajax({
			url: "includes/handlers/ajax_load_posts.php",
			type: "POST",
			data: "page=1&userLoggedIn=" + userLoggedIn,
			cache:false,

			success: function(data) {
				$('#loading').hide();
				$('.posts_area').html(data);
			}
		});

		$(window).scroll(function() {
			var height = $('.posts_area').height(); //Div containing posts
			var scroll_top = $(this).scrollTop();
			var page = $('.posts_area').find('.nextPage').val();
			var noMorePosts = $('.posts_area').find('.noMorePosts').val();

			if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
				$('#loading').show();

				var ajaxReq = $.ajax({
					url: "includes/handlers/ajax_load_posts.php",
					type: "POST",
					data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
					cache:false,

					success: function(response) {
						$('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
						$('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

						$('#loading').hide();
						$('.posts_area').append(response);
					}
				});

			} //End if 

			return false;

		}); //End (window).scroll(function())


	});

	</script>




	</div>
</body>
</html>