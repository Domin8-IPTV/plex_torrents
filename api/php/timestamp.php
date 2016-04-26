<?php
require ("config.php");
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

-
$sql="SELECT date_added FROM tbl_torrent_status order by status desc limit 1";
	  
$result = mysqli_query($con,$sql);

$returnArray = array();
while($row = mysqli_fetch_array($result)) {
   $returnArray  = array ("ts" => $row['date_added'] );
}
echo str_replace("\\","", json_encode($returnArray));

mysqli_close($con);
?>



	