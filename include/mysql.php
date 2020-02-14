<?php
include "config.php";
class mysql_functions
{		
	function connect()
	{
		$con = mysqli_connect(DB_HOST,DB_USERNAME, DB_PASSWORD, DB_NAME);
		if($con)
			return $con;
		else
			return null;
	}
	
	/* 
		QR = Execute sql query retuen true or false
		taking one parameter of your sql query
	*/
	function qr($query) 
	{
		$con = $this->connect();
		return mysqli_query($con,$query);
	}

    /*
        error = Execute sql query retuen
        error if any occured
    */
    function error()
    {
        $con = $this->connect();
        return mysqli_error($con);
    }
	
	/* 
		get_last_id = Execute sql query retuen the last
		inset id
	*/
	function get_last_id($query) 
	{
		$con = $this->connect();
		if(mysqli_query($con,$query))
        {
            return mysqli_insert_id($con);
        }
        else
        {
            return false;
        }
	}
	
	/* 
		NR = mysqli_num_rows return number of rows
		taking one parameter of your sql query
	*/
	function nr($query) 
	{
		return mysqli_num_rows($query);
	}
	
	/* 
		FA = mysqli_fetch_assoc return associate array of your query
		taking one parameter of your sql query
	*/
	function fa($query)
	{
		return mysqli_fetch_assoc($query);
	}
	
	/* 
		last_id = mysqli_insert_id return last insert id
	*/
	function last_id()
	{
		$con = $this->connect();
		return mysqli_insert_id($con);
	}
}

?>