<?php session_start();
if(!isset($_SESSION["id"]))
{
header("location:login.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ask a question</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/grayscale.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="aboutus.php">
                    <i class="fa fa-play-circle"></i>  About Us
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#askquest">Ask a Question</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="profile.php">Edit Profile</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Intro Header -->
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading">Calteta</h1>
                        <p class="intro-text">Your Problems.Our passion.</p>
						<p><i>Fire Your Question here</i></p>
                        <a href="#askquest" class="btn btn-circle page-scroll">
                            <i class="fa fa-angle-double-down animated"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section id="askquest" class="container content-section text-center" onfocus="if(this.value==this.defaultValue)this.value='';this.style.color='#333'"  {this.value=this.defaultValue;this.style.color='#CCC'}">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h2>Ask a Question</h2>
                <form action="askquestion.php" method="post">
  
 <b> <i>Your Question : </i></b>
 </br></br>
 <textarea name="quest" required rows='6' cols='100' onfocus="if(this.value==this.defaultValue)this.value='';this.style.color='#333'"  {this.value=this.defaultValue;this.style.color='#CCC'}"></textarea>​
        </br>
		<input type="image" name="submit" src="ask.png" border="0" alt="Submit">
</form>
				
			
       </br>
</br>	   
		OR
		<form action="upload1.php" method="post" enctype="multipart/form-data">
	
  Select file:     <input type="file" name="fileToUpload" id="fileToUpload">
    	<table> <tr>   
<input type="image" name="submit" src="ask.png" border="0" alt="Submit">
</tr></table>
   	</form>
</div> </div>
		
    </section>



    <?php 
	$nam=$_SESSION["id"];
	 
	?>
    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>Copyright &copy; Calteta 2015</p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Google Maps API Key - Use your own API key to enable the map feature. More information on the Google Maps API can be found at https://developers.google.com/maps/ -->
    
    <script src="js/grayscale.js"></script>
<?php 
$quest="";



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calteta";


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$a=0;

if($_SERVER["REQUEST_METHOD"]=="POST")
{
$quest=$_POST["quest"];


$sql ="INSERT INTO questions(question,asked_by) VALUES('$quest',$nam)";
$sql2="INSERT INTO questions_status (current_status) VALUES ('$a')";

if ($conn->query($sql) === TRUE AND $conn->query($sql2)===TRUE )
{
echo "Successfully Asked ! ";
}

} 


$conn->close(); ?>




</body>

</html>
