<?php
	session_start();
	require("connection.php");

	if(!isset($_SESSION['logged_in']))
	{
		header("Location: /");
	}

	$query = "SELECT messages.*, users.first_name, users.last_name FROM messages LEFT JOIN users ON users.id = messages.user_id
		ORDER BY messages.id DESC";
	$messages = fetch_all($query);
	// var_dump($messages);
?>

<style type="text/css">
	textarea{
		width:250px;
		display:block;
	}
	form input[type='submit']{
		margin-top:5px;
		font-size:12px;
		background-color:blue;
		color:white;
	}
	p.author {
		font-size:10px;
		margin:3px 0px;
		padding:0px;
	}
	p.message{
		font-size:12px;
		margin:5px 0px;
		padding:0px;
	}
	.comment, p.author.comment{
		margin:0px 0px 0px 15px;
	}
	p.comment{
		font-size:10px;
		padding:3px 0px;
	}

</style>

Things I want to do

1) Post a message (done)
2) Post a comment
3) Retrieve messages (done)
4) Retrieve comments (done)

Hello <?= $_SESSION['user']['first_name'] ?>

<a href="/process.php">Log Off</a>

<h1>The Wall!!!</h1>

<h2>Post a message</h2>
<form action='process.php' method='post'>
	<input type='hidden' name='action' value='post_message' />
	<textarea name='message' placeholder='enter your message'></textarea>
	<input type='submit' value='Post a messsage' />
</form>


<?php
foreach($messages as $message)
{
	$query = "SELECT comments.*, users.first_name, users.last_name FROM comments LEFT JOIN users ON comments.user_id = users.id WHERE comments.message_id = " . $message['id'];
	$comments = fetch_all($query);
	// echo "<pre>";
	// var_dump($comments);
	// echo "</pre>";
?>
<p class='author'>Message from <?= $message['first_name'] ?> <?= $message['last_name'] ?> (<?= $message['created_at'] ?>)</p>
<p class='message'><?= $message['message'] ?></p>

<?php
	foreach($comments as $comment)
	{ ?>
	<p class='author comment'>Comment from <?= $comment['first_name'] ?> <?= $comment['last_name'] ?> (<?= $comment['created_at'] ?>)</p>
	<p class='comment'><?= $comment['comment'] ?></p>
<?php
 	} ?>
<form class='comment' action='process.php' method='post'>
	<input type='hidden' name='action' value='post_comment' />
	<input type='hidden' name='message_id' value='<?= $message['id'] ?>' />
	<textarea name='comment' placeholder='place a comment'></textarea>
	<input type='submit' value='Post a comment' />
</form>
<?php
}
?>