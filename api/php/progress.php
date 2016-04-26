<?php
require ("config.php");
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

-
$sql="SELECT * FROM  tbl_torrent_status order by status desc";
	  
$result = mysqli_query($con,$sql);

$returnArray = array();

while($row = mysqli_fetch_array($result)) {
    $class = 'progress-bar progress-bar-info progress-bar-striped active';
        
    if (intval($row['status']) == 100){
        $class = 'progress-bar progress-bar-success';
    }

    $returnArray[] = array("status"=> $row['status'], "name"=> $row['name'], "class"=> $class);
}
echo str_replace("\\","", json_encode($returnArray));

mysqli_close($con);
?>



	