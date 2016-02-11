<body>
<?php
   include_once("Google_Spreadsheet.php");

   $user = 'russell.powers45@gmail.com';
   $pass = 'PowersBros45';

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