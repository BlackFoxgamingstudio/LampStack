<?php 
  session_start();
?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
  <meta charset="UTF-8">
  <title>Great Number Game</title>
  <style type="text/css">
    #container {
      width: 650px;
      height: 700px;
      margin: 0 auto;
    }

    .box {
      width: 200px;
      height: 200px;
    }
    .box.green {
      background-color: green
    }

    .box.red {
      background-color: red;
    }
  </style>
</head>
<body>
  <div id="container">
    <div id="top">
      <h1>Welcome to the greate number game</h1>
      <h2>I am thinking of a number between 1 and 100</h2>
      <h3>Please take a guess</h3>
    </div>

<?php 
    if(isset($_SESSION['low']))
    {
      echo $_SESSION['low'];
      unset($_SESSION['low']);
    }

    if(isset($_SESSION['high']))
    {
      echo $_SESSION['high'];
      unset($_SESSION['high']);
    }

    if(!isset($_SESSION['correct']))
    {
?>      
      <div>
        <form action="process.php" method="post">
          <input type="text" name="guess">
          <input type="submit" value="Submit">
        </form>
      </div>
<?php
    }
    else
    {
      echo $_SESSION['correct'];
      unset($_SESSION['correct']);
    }
?>  
  </div>

</body>
</html>