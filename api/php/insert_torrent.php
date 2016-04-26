<?php
   require ("config.php");
   $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
   
   $json  =  json_decode(file_get_contents('php://input'), true);
   $stmt = $mysqli->prepare("INSERT INTO tbl_torrents (link, link_type) VALUES (?,?)");
   $stmt->bind_param("ss", $link, $link_type );
   $link = $json['link'];
   $link_type = $json['link_type'];
   
   
   $stmt->execute();
   /*printf("%s json\n", $json);*/
   
   /* close statement and connection */
   $stmt->close();
   
   /* close connection */
   $mysqli->close();
?>



	