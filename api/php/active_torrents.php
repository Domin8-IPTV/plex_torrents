<?php
require ("config.php");
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

-
$sql="SELECT * FROM  tbl_torrents where status != 0";
	  
$result = mysqli_query($con,$sql);

$returnArray = array();
while($row = mysqli_fetch_array($result)) {
    $link = $row['link'];

    if  ($link != '' && $link != '?'){
        $returnArray[] = array('_id'=> $row['_id'], 'link'=> $link, 'status'=> $row['status'], 'link_type'=> $row['link_type']);
    }
}
echo str_replace("\\","", json_encode($returnArray));

mysqli_close($con);
?>



	