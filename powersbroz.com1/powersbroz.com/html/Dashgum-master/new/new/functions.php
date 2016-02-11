<?php
/**
 *
 */
/*---------------CLASS DB------------------------*/
//Variables
class DB {

	private $con;
	private $stmt;
	private $host;
	private $user;
	private $password;
	private $database;
	private $table;
	private $table1;
	private $table2;
    private $table3;

	/*-------------CONSTRUCTOR---------------------*/
	function __construct() {
		$host = "localhost";
		$user = "root";
		$password = "kousiksatish";
		$database = "calteta";
		$con = mysql_connect($host, $user, $password)or die("not connected");
		$stmt = mysql_select_db($database)or die("not selected");
	}

	/*-------------CONSTRUCTOR---------------------*/

	/*----------------------------------------DATABASE  METHODS-------------------------------------------*/
	/* get all
	 *
	 * *****************/

	public function get_all($table) {
		$this -> table = $table;
                $query = mysql_query("select * from $this->table") or die("error in get all");
		return $query;
	}

	/*get
	 *
	 * *********************/

	public function get($table, $condition) {
		$this -> table = $table;
		$cnd = $condition;
                $query=mysql_query("select * from $this->table where $cnd")or die("error in get") ;
		return $query;

	}

	public function getorder($table, $condition, $orderby) {
		$this -> table = $table;
		$cnd = $condition;
		$oby = $orderby;
                $query=mysql_query("select * from $this->table where $cnd ORDER BY $oby")or die("error in get") ;
		return $query;

	}

	public function cols($table) {
		$this->table = $table;
		$query = mysql_query("show columns from $this->table") or die ("error in cols");
		return $query;
	}

	/*insert
	 *
	 * **************/

	public function insert($table, $data) {
		$this->table=$table;

		$fields = array_keys($data);
		$values = array_map("mysql_real_escape_string", array_values($data));

		$f=implode(',',$fields);
		$v=implode(',',$values);

		$query="INSERT INTO $this->table ($f) values ('" . implode( "','", $values ) . "')";
		mysql_query($query) or die("not inserted");

	}

	public function grpcnt ($table, $column)
	{
		$this->table = $table;
		$query = "SELECT $column, count($column) AS cnt FROM $this->table GROUP BY $column ORDER BY $column desc";
		$result = mysql_query($query) or die("unable to grp nd count");
		//echo $result;
		return $result;
	}


   /*update
    *
    * ****************************/

    public function update($table,$data,$condition){
    	$this->table=$table;
		$query="UPDATE $this->table SET $data where $condition";
                mysql_query($query) or die("not Updated") ;


    }


	/*Delete
	 *
	 * ********************************/

	 public function delete($table,$condition){

		$this->table=$table;
		$cnd=$condition;
		$query="delete from $table where $cnd";
                mysql_query($query)or die("not deleted");
	 }

	 /*Join 2 tables
	  *
	  * ***********************/
	  public function join($table1,$table2,$condition){
	  	$this->table1=$table1;
		$this->table2=$table2;
		$con=$condition;
		$query=mysql_query("select * from $this->table1 JOIN $this->table2 ON $con ")or die("Error in Join");
		return $query;

	  }

	  /*Join Condition
	   *
	   * *************************/
	   public function joincond($table1,$table2,$condition1,$condition2){
	  	$this->table1=$table1;
		$this->table2=$table2;
		$con1=$condition1;
		$con2=$condition2;
		$query=mysql_query("select * from $this->table1 JOIN $this->table2 ON $con1 where $con2 ")or die("Error in Joincond");
		echo 'Success';
		return $query;

	  }


	  public function joincond1($table1,$table2,$condition1,$condition2,$condition3){
    	$this->table1=$table1;
		$this->table2=$table2;
		$con1=$condition1;
		$con2=$condition2;
		$con3=$condition3;
		$query=mysql_query("select *,$con3 from $this->table1 JOIN $this->table2 ON $con1 where $con2 ")or die("Error in Joincond");
		return $query;

	  }
	  /*Join 3 tables
	   *
	   * *********************/
	  public function join3($table1,$table2,$table3,$condition1,$condition2)
	  {
		  $this->table1=$table1;
		  $this->table2=$table2;
		  $this->table3=$table3;
		  $con1=$condition1;
		  $con2=$condition2;

		   $query=mysql_query("select * from $this->table1 JOIN $this->table2 ON $con1 JOIN $this->table3 ON $con2")or die("Error in Join3");
		   return $query;

	  }

	   /*Join On COndition 3 tables
	   *
	   * *********************/
	  public function join3cond($table1,$table2,$table3,$condition1,$condition2,$condition3)
	  {
		  $this->table1=$table1;
		  $this->table2=$table2;
		  $this->table3=$table3;
		  $con1=$condition1;
		  $con2=$condition2;
		  $con3=$condition3;

		   $query=mysql_query("select * from $this->table1 JOIN $this->table2 ON $con1 JOIN $this->table3 ON $con2  WHERE $con3")or die("Error in Join3cond");
		   return $query;

	  }
	  /* GROUP COUNT --> Kaushik's function */
	  public function groupcount($fields, $table1, $table2, $condition, $groupby)
	  {
	  		$this->table1=$table1;
	  		$this->table2=$table2;
	  		$flds = $fields;
	  		$con = $condition;
	  		$gby = $groupby;
	  		$query = mysql_query("SELECT $flds from $this->table1,$this->table2 WHERE $con GROUP BY $gby") or die ("Error in query");
	  		return $query;

	  }

	  /*Export
	   *
	   ***********************/
	   public function export($fileName,$fileFormat,$table)
	   {
	   	$content="";
			$content="catName\n";
			$db=new DB;
			$result=$db->get_all($table);
			while($row=mysql_fetch_array($result))
			{
			$content.=$row['catName']."\n";
			}
			if($fileFormat=='csv'){
			header("Content-Type: application/csv");
			header("content-Disposition: attachment; filename=".$fileName);
			}
			return $content;

	   }

   /*close connection
    *
    * ***************************/
    public function close()
	{
				unset($this);
	}

	/*---------------------------------------------DATA BASE METHODS----------------------------------------------*/

}
/*------------------------------------------------User class---------------------------------------------------*/


	/*--------------------METHODS-------------------*/
	/*
	 *   change user type
	 * ********************************/

	/*
	 * Add new user
	 * ***************************/


	/*--------------------METHODS-------------------*/


/*------------------------------------------------User class---------------------------------------------------*/

/*--------------------------------Product class-------------------------------------*/


?>
