<?php
   require ("config.php");
   $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);      
   
   $stmt = $mysqli->prepare("TRUNCATE TABLE tbl_torrent_status");
   $stmt->execute();
   $json_array =  json_decode(file_get_contents('php://input'), true);
  
   $stmt = $mysqli->prepare("INSERT INTO tbl_torrent_status(hash, name, status) VALUES (?,?,?)"); 
   $stmt->bind_param("ssd", $hash, $name, $status);
   for($i=0; $i<count($json_array['myData']); $i++) {
   	   
	   $json 	  = ($json_array ['myData'][$i]);
	   	   	   
	   $name 	  =  $json['name'];
	   $hash 	  =  $json['hash'];
	   $status 	  =  $json['complete'];
	   $stmt->execute();   
   }
   
   /* close statement and connection */
   $stmt->close();
   
   /* close connection */
   $mysqli->close();
?>



	