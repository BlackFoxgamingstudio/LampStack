<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Task List</title>
	<link rel="stylesheet" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/css/styles.css">

	<script src="/assets/javascripts/jquery.min.js"></script>
	<script>
		$(document).ready(function(){

			//add new task 
			$("#add_todo").submit(function(){
				var form = $(this);

				$.post(form.attr('action'), form.serialize(), function(data){
					$(".table tbody").append(data.html).children("tr:last-child").hide().fadeIn(300);
					$("#name").val("");
				}, "json");

				return false;
			});

			//when checkbox is clicked, it submits the hidden form to update a task's status
			$(document).on("click", '.status', function(){
				var task_id = $(this).val();
				var status = $(this).prop('checked');
				$("#status_task_id").val(task_id);
				$("#status").val(status);
				$("#update_status").submit();
			});

			//update task's status
			$(document).on("submit", "#update_status", function(){
				var form = $(this);

				$.post(form.attr('action'), form.serialize(), function(data){
					$(".task" + data.task_id).replaceWith(data.html);
				}, "json");

				return false;
			});

			//function to listen for a click on the edit button, to replace the text to an input field
			$(document).on("click", ".edit", function(){
				var task_name = $(this).siblings("strong").text();
				var task_id = $(this).siblings("input").val();
				var update_form = "<form style='display: inline;' class='form-inline update_name' action='/tasks/update' method='post'>"+
									"<input class='form-control' type='text' name='name' value=" + task_name + ">"+
									"<input type='hidden' name='task_id' value=" + task_id + ">"+
									"<input type='hidden' name='action' value='update_name'>"+
								  "</form>";
				//replace the text to input
				$(this).siblings("strong").replaceWith(update_form);
			});

			//function to listen for an .update_name submission
			$(document).on("submit", ".update_name", function(){
				var form = $(this);
				$.post(form.attr('action'), form.serialize(), function(data){
					$(".task" + data.task_id).replaceWith(data.html);
				}, "json");

				return false;
			});
		});
		
	</script>
</head>
<body>
	<!--hidden form to update task's status-->
	<form action="/tasks/update" method="post" id="update_status">
		<input type="hidden" name="action" value="update_status">
		<input type="hidden" name="status" value="" id="status">
		<input type="hidden" name="task_id" value="" id="status_task_id">
	</form>

	<div id="wrapper">
		<h3>List of Tasks</h3>
		<!-- form to add new task-->
		<form action="/tasks/add_task" method="post" class="form-inline" id="add_todo">
			<div class="form-group">
				<input type="hidden" name="action" value="add_task">
				<input type="text" name="name" id="name" class="form-control" placeholder="Task title">
				<button type="submit" class="btn btn-success btn-lg">Add Task</button>
			</div>
		</form>

		<table class="table table-hover">
			<tbody>
<?php 		foreach($tasks as $task)
			{ 
				$class =  ($task['status'] == 0) ? "" : "checked"; ?>
				<tr class="task<?= $task['id']; ?>">
					<td>
						<button class="btn btn-info edit">Edit</button> 
						<input  class="status" <?= $class; ?> type="checkbox" name="task_id" value="<?= $task['id']; ?>"> 
						<strong class="<?= $class; ?>"><?= $task['name']; ?></strong>
					</td>
				</tr>
<?php 		} ?>
			</tbody>
		</table>
	</div>
</body>
</html>