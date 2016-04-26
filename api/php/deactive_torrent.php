<?php
    $_id = intval($_GET['id']);
    if ($_id<1000){
    		echo '{_id: "0", link: "None"}';
    } else {
        require ("config.php");
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);        
        $stmt = $mysqli->prepare("UPDATE tbl_torrents SET status=0 WHERE _id = ?");
        $stmt->bind_param("i", $_id);
        
        $stmt->execute();
        /*printf("%d Row inserted.\n", $stmt->affected_rows);*/
        
        /* close statement and connection */
        $stmt->close();
        
        /* close connection */
        $mysqli->close();
    }
?>