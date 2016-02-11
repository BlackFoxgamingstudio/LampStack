<!DOCTYPE HTML>
<html>
	<head>
		<title></title>
		<link href="style.css" rel="stylesheet">
	</head>

	<body>
		<h1>Table Exercise</h1>
<?php

		/* This example shows that $users array contains 14 arrays separated by commas.
		* Each array holds two items (first_name and last_name) and you can access each data
		* that each of the array hold by using a foreach within a foreach loop:
		* foreach ($users as $user) {
		*		foreach ($user as $value) {
		*			echo $value;
		*		}
		* }
		* where in $value are the items inside first_name and last_name column e.g: Michael Choi
		*/

		$users = array( 
			array ('first_name' => 'Michael', 'last_name' => 'Choi'),
			array ('first_name' => 'John', 'last_name' => 'Supsupin'),
			array ('first_name' => 'Mark','last_name' => 'Guillen'),
			array ('first_name' => 'KB','last_name' => 'Tonel'),   	
			array ('first_name' => 'Ian','last_name' => 'Dahlberg'),
			array ('first_name' => 'Joe','last_name' => 'Schmoe'),
			array ('first_name' => 'Ludwig','last_name' => 'Beethoven'),
			array ('first_name' => 'Gustav','last_name' => 'Mahler'),
			array ('first_name' => 'Pete','last_name' => 'Tchaikovsky'),
			array ('first_name' => 'Allan', 'last_name' => 'Vogel'),
			array ('first_name' => 'John', 'last_name' => 'Doe'),
			array ('first_name' => 'Norman','last_name' => 'Mailer'),
			array ('first_name' => 'Igor','last_name' => 'Stravinsky'),
			array ('first_name' => 'Edgar','last_name' => 'Poe')
			);

		/* Here we set a variable $full_name to null and $counter to 1. $full_name is where we
		* will store the concatinated first_name and last_name. $counter will be 
		* incremented by one in our loop and will echo out the numbers
		* under our User# column in our table and will also serve as our condition variable to check
		* if it is divisible by 5 to determine if a particular row should be highlighted or not.
		*/

		$full_name = NULL;
		$counter = 1;
?>

		<!-- here we created headers for our table -->
		<table>
			<thead>
				<tr>
					<th>User #</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Full Name</th>
					<th>Full Name in upper case</th>
					<th>Length of name</th>
				</tr>
			</thead>
			<tbody>
<?php			// Table Data

				/* This first loop (foreach) will be responsible for eachoing out each row in our
				* multidimentional array. remember that we have 14 arrays within our $users array?
				* Check out the table and see how many rows got generated our of this loop (excluding
				* the table headers of course.). Also, take note of the $user variable. We will use it
				* again to iterate each data in our array using another foreach loop.
				*/
				foreach ($users as $user)
				{
					/* This condition over here checks if $counter (which we setup earlier and gave
					* it a value of 1) is divisible by 5 (read more about the % operand). If it is
					* then give our <tr> tag a class called hightlight. (notice we didn't 
					* close the <tr> tag yet.)
					*/
					if ($counter % 5 == 0)
					{
						echo "<tr class='highlight'>";
					}

					/* if $counter is not divisible by 5, then give it a normal <tr>. (again, notice
					* we didn't close the <tr> tag yet. its because we will be creating another
					* foreach loop that will echo out the datas on each of our rows
					* e.g: first_name, last_name .)
					*/
					else
					{
						echo "<tr>";
					}
					
					/* This is the first <td> item in our <tr>.. our first <td> item is User# column
					* so in here we will be echoing out the $counter variable which is at first
					* run will have the value of 1.
					*/
					echo "<td>". $counter. "</td>";
					

					/* This is where we will use the $user variable by creating another foreach
					* loop and store the datas in $user_data variable.
					*/
					foreach ($user as $user_data)
					{
						/* here we echoed the first name and last name from our $users array
						* and put it inside a <td> tag. the first row of arrays contain
						* Michael Choi, so at first it will echo: <td> Michael </td> <td> Choi </td>
						*/
						echo "<td>".$user_data."</td>";

						/* here we used the $full_name variable (research about .= operator.).
						* What this code looks like in layman's term is:
						*		$full_name = $full_name . $user_data;
						* Since we are in a foreach loop, the first $user_data (which is Michael)
						* will be stored in $full_name variable concatenated by the second
						* $user_data(which is Choi). $full_name variable now holds 'Michael Choi'
						* notice that "$user_data " has a space inside the quotaion marks?
						* This is needed because we need to have a space between the first name
						* and the last name.
						*/
						$full_name .= "$user_data ";
					}

					/* Here we stored the uppercase version of $full_name inside the $name_uppercase
					* variable. we are also storing the length of the $full_name in 
					* $name_length variable.
					*/
					$name_uppercase = strtoupper($full_name);
					$name_length = strlen($full_name)-1; //subtracting 1 for the trailing space added on line 122

					/* we are just simply echoing out the items we stored in variables earlier. */
					echo "<td>".$full_name."</td>";
					echo "<td>".$name_uppercase."</td>";
					echo "<td>".$name_length."</td>";

					// increment the $counter variable.
					$counter++;
					/* Now we close our </tr> tag so that on the next loop, the information/data will
					* be displayed on the next row.
					*/
					echo "</tr>";

					$full_name = NULL; //reset full name

				}
?>
			</tbody>
		</table>
	</body>
</html>