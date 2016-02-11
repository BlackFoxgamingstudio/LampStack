<html>
<head>
   <title>MY Web Form</title>
   <link rel="stylesheet" href="css/style.css" type="text/css" />
   <form id="email_list_form" method="post" action="My Web Form.php">
   <h2>Sign up for our email list!</h2>

   <label for="first_name">First Name: </label>
   <input id="first_name" name="first_name" type="text" value="<?php echo $firstname; ?>"/><br/>

   <label for="last_name">Last Name: </label>
   <input id="last_name" name="last_name" type="text" value="<?php echo $lastname; ?>"/><br/>

   <label for="gender">Gender:&nbsp;&nbsp;&nbsp;</label>
   <span>
      <label for="gender_m">Male</label>
      <input id="gender_m" name="gender" type="radio" value="male"/> 
      <label for="gender_f">Female</label>
      <input id="gender_f" name="gender" type="radio" value="female"/> 
      <br/>
   </span>

   <label for="email">Email Address: </label>
   <input id="email" name="email" type="text" value="<?php echo $email; ?>"/><br/>

   <input type="hidden" name="submitted" value="1" />

   <br/><input type="submit" value="Submit">
</form>
<body>
<?php
   include_once("Google_Spreadsheet.php");

   $user = 'russell.powers45@gmail.com';
   $pass = 'PowersBRos45me';

   $ss = new Google_Spreadsheet($user, $pass);
   $ss->useWorksheet("Sheet1");
   $ss->useSpreadsheet("Email List");

   $submitted = $_POST['submitted'];

   if ($submitted == 1) {
      $firstname = $_POST['first_name'];
      $lastname = $_POST['last_name'];
      $gender = $_POST['gender'];
      $email = $_POST['email'];

      // Do some error checking here if you want
      if (!$email) {
         echo "<h3><font color='red'>*Email Address is required</font></h3>";
      } else {
         // No errors, continue processing registration

         $row = array (
            "First Name" => $firstname
            , "Last Name" => $lastname
            , "Gender" => $gender
            , "Email Address" => $email
         );

         if ($ss->addRow($row)) {
            // Display success page here

            echo "<h1>Thanks for registering!</h1>";

            // Send a confirmation email here if you want
         } else {
            // Failed to write to the spreadsheet
            echo "<h1>Sorry there was an error processing your request.</h1>";
         }
      }
   }
?>
</body>
</html>
</head>