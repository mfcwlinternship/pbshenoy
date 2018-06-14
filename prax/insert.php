<?php
session_start();


header('Content-Type: application/json');
//database
define('DB_HOST', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'automart_demo');
//get connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if(!$mysqli){
	die("Connection failed: " . $mysqli->error);
}


if (isset($_POST['state-list']) && !empty($_POST['state-list']) && isset($_POST['country-list']) && !empty($_POST['country-list'])   && 
$_POST['dateTo'] != "" &&  $_POST['dateFrom'] != "") { //date make model
  
    $varModel = $_POST['state-list'];
    $varMake = $_POST['country-list'];
    $dateFrom = $_POST['dateFrom']; 
    $date = DateTime::createFromFormat('m/d/Y',$dateFrom);

$from_date = $date->format("Y-m-d");//start date

$dateTo = $_POST['dateTo'];
$fate = DateTime::createFromFormat('m/d/Y',$dateTo);

$to_date = $fate->format("Y-m-d");//end date

    $query = sprintf("SELECT date_time, count(*) as c FROM ibb_cardetails_tracker WHERE date_time BETWEEN DATE '$from_date' AND '$to_date' AND make
    = '$varMake' AND model = '$varModel' GROUP BY CAST(date_time as DATE)");
     
 

     
}
else if(isset($_POST['state-list']) && !empty($_POST['state-list']) && isset($_POST['country-list']) && !empty($_POST['country-list']) &&  
$_POST['dateTo'] == "" &&  $_POST['dateFrom'] == "") { //make model
    $varModel = $_POST['state-list'];
    $varMake = $_POST['country-list'];
    $query = sprintf("SELECT date_time, count(*) as c FROM ibb_cardetails_tracker WHERE  make
    = '$varMake' AND model = '$varModel' AND DATE(date_time) >= last_day(now()) + INTERVAL 1 DAY - INTERVAL 3 MONTH GROUP BY CAST(date_time as DATE)"); 

}



else if(!isset($_POST['state-list']) && empty($_POST['state-list']) && isset($_POST['country-list']) && !empty($_POST['country-list']) && 
$_POST['dateTo'] != "" &&  $_POST['dateFrom'] != "") { //date make
    $varMake = $_POST['country-list'];
    $dateFrom = $_POST['dateFrom']; 
    $date = DateTime::createFromFormat('m/d/Y',$dateFrom);

$from_date = $date->format("Y-m-d");//start date

$dateTo = $_POST['dateTo'];
$fate = DateTime::createFromFormat('m/d/Y',$dateTo);

$to_date = $fate->format("Y-m-d");//end date
$query = sprintf("SELECT date_time, count(*) as c FROM ibb_cardetails_tracker WHERE date_time BETWEEN DATE '$from_date' AND '$to_date' AND make
= '$varMake' GROUP BY CAST(date_time as DATE)"); 

}


else if(!isset($_POST['state-list']) && empty($_POST['state-list']) && isset($_POST['country-list']) && !empty($_POST['country-list']) && 
$_POST['dateTo'] == "" &&  $_POST['dateFrom'] == "") { //make
    $varMake = $_POST['country-list'];
    $query = sprintf("SELECT date_time, count(*) as c FROM ibb_cardetails_tracker WHERE make 
= '$varMake' AND DATE(date_time) >= last_day(now()) + INTERVAL 1 DAY - INTERVAL 3 MONTH GROUP BY CAST(date_time as DATE)"); 

    $query1 = "SELECT COUNT(*) as c FROM ibb_cardetails_tracker WHERE make = '$varMake'
     AND DATE(date_time) >= last_day(now()) + INTERVAL 1 DAY - INTERVAL 3 MONTH";
     
    $result1 = mysqli_query($mysqli,$query1);
    $row1 = mysqli_fetch_assoc($result1);
    $prerana =  $row1['c']; //Here is your count

    $_SESSION["num"] = $prerana;
   
    
    //this works
}



else if(!isset($_POST['state-list']) && empty($_POST['state-list']) && !isset($_POST['country-list']) && empty($_POST['country-list']) && 
$_POST['dateTo'] != "" &&  $_POST['dateFrom'] != "") { //just date

    $dateFrom = $_POST['dateFrom']; 
    $date = DateTime::createFromFormat('m/d/Y',$dateFrom);

$from_date = $date->format("Y-m-d");//start date

$dateTo = $_POST['dateTo'];
$fate = DateTime::createFromFormat('m/d/Y',$dateTo);

$to_date = $fate->format("Y-m-d");//end date
$query = sprintf("SELECT date_time, count(*) as c FROM ibb_cardetails_tracker WHERE date_time BETWEEN DATE '$from_date' AND '$to_date'  GROUP BY CAST(date_time as DATE)"); 
}
else{  
     
    $query = sprintf("SELECT date_time, count(*) as c FROM ibb_cardetails_tracker WHERE DATE(date_time) >= last_day(now()) + INTERVAL 1 DAY - INTERVAL 3 MONTH GROUP BY CAST(date_time as DATE)"); 
}

 $result = $mysqli->query($query);

 $data = array();
foreach ($result as $row) {
	$data[] = $row;
}



// close connection
$result->close();
session_write_close();
//close connection
$mysqli->close();
print json_encode($data);
?>