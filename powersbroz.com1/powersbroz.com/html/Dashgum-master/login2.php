<?php session_start();
if(isset($_SESSION["id"]))
{
header("location:askquestion.php");
}
?>
<?php include "functions.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Login&nbsp; | &nbsp; Calteta</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">    
    
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
   <style>body {
background-image: url("icon.png");

background-repeat: no-repeat;
background-position: 23% 10%;

}
.table1{
margin:0 0 0 100px;
}
.aboutus
{
color:white;
text-align:center;
}
</style>
    <script src="assets/js/chart-master/Chart.js"></script>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
     <?php include "header1.php"; ?>
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <!--<aside>-->
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              <?php include "userdisp1.php"; ?>
              	  	
              <?php include "sidebar4.php"; ?>    

              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">

              <div class="row">
                  <div class="col-lg-9 main-chart">
				  
<br />
<br /><br /><br />
                  <center> 
<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post"><table><tr><td>
<b>Username:</b>&nbsp;&nbsp;</td>
<td>
<input type="text" name="id1" > </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<b>Password:</b>&nbsp;&nbsp;</td>
<td>
<input type="password" name="password1"></td>
</tr>
</table>
<br />
<br /> <table class="table1" value="table1" name="table1">         <tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
<input type="submit" value="Log in" name="login" color="red">&nbsp;&nbsp;&nbsp;</td><td>

<input type="reset" value="Reset" name="reset">&nbsp;&nbsp;&nbsp;</td></tr></table>
<br /><br />Don't have an account?<a href="sign_up.php">
<b><em>Sign up now </em></b></a>to discover amazing features

</tr>
</center><br /><br /><br /><br /><br /><br /><br /><br />

<?php
if($_SERVER["REQUEST_METHOD"]=="POST"){
$myusername=$mypassword=$mypassword1="";
$table_name='users';
$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password=""; // Mysql password 
$db_name="calteta"; // Database name 



$conn=new mysqli($host, $username, $password,$db_name);
if($conn->connect_error)
{
die("cannot connect"); }
 
$myusername=$_POST['id1']; 
$mypassword=$_POST['password1']; 



$mypassword1 = sha1($mypassword);


$sql="SELECT * FROM users WHERE username='$myusername' and password='$mypassword1'";
$count=0;

if($username!=NULL){$m=mysqli_query($conn,$sql) or die("error");
$count=mysqli_num_rows($m);}
if(!empty($myusername)){
if($count==1){
session_start();
$idque=mysqli_query($conn,"SELECT id FROM users WHERE username='$myusername'");
$idarr=mysqli_fetch_array($idque,MYSQLI_ASSOC);
$id=$idarr["id"];
$_SESSION["id"]=$id; 
header('location:askquestion.php');
}
else {
echo "Wrong Username or Password";
}}

else
{
echo "<div style='text-align:center;color:red'><h3> Please Enter Your Username and Password!</h3></div>";

}} ?>
<!--$sql=new DB;
//$idque1=new DB;
//$m=$sql->get($table_name,"username='$myusername' and password='$mypassword1'");
/*--$sql="SELECT * FROM users WHERE username='$myusername' and password='$mypassword1'";--*/
//$count=0;


/*$count=mysqli_num_rows($m);
if(!empty($myusername)){
if($count==1){


$idque=$idque1->get($table_name,"username='$myusername'");
$idarr=mysqli_fetch_array($idque,MYSQLI_ASSOC);
$id=$idarr["id"];
session_start();
$_SESSION["id"]=$id;
header("location:askquestion.php");
}
else {
echo "<div style='text-align:center;color:red'><h3> Invalid Username or Password!</h3></div>";

}}

else
{
echo "<div style='text-align:center;color:red'><h3> Please Enter Your Username and Password!</h3></div>";

}} -->


</table>
</form>

        
					
                  </div><!-- /col-lg-9 END SECTION MIDDLE -->
                  
                  

      <!-- **********************************************************************************************************************************************************
      RIGHT SIDEBAR CONTENT
      *********************************************************************************************************************************************************** -->                  
                  
                  <div class="col-lg-3 ds">
                    <!--COMPLETED ACTIONS DONUTS CHART-->
						<h3>NOTIFICATIONS</h3>
                                        
                      Any content

                        
                      
                  </div>  <!-- /col-lg-3 -->
              </div> <!-- /row -->
          </section>
      </section>

      <!--main content end-->
      <!--footer start-->
      <footer class="site-foter">
          <div class="text-center">
              2015 - Calteta.com
              <a href="login.php" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/jquery-1.8.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  
	
	<script type="text/javascript">
        $(document).ready(function () {
        var unique_id = $.gritter.add({
            // (string | mandatory) the heading of the notification
            title: 'Welcome to Dashgum!',
            // (string | mandatory) the text inside the notification
            text: 'Hover me to enable the Close Button. You can hide the left sidebar clicking on the button next to the logo. Free version for <a href="http://blacktie.co" target="_blank" style="color:#ffd777">BlackTie.co</a>.',
            // (string | optional) the image to display on the left
            image: 'assets/img/ui-sam.jpg',
            // (bool | optional) if you want it to fade out on its own or just sit there
            sticky: true,
            // (int | optional) the time you want it to be alive for before fading out
            time: '',
            // (string | optional) the class name you want to apply to that specific message
            class_name: 'my-sticky-class'
        });

        return false;
        });
	</script>
	
	

  </body>
</html>


<body>

</body>

 
