<?php
    $_id = intval($_GET['id']);
    $name = 'No Name';
    $status = intval($_GET['status']);    
    $complete = floatval ($_GET['complete']); 
    echo $complete;
    
    if (isset($_GET["name"])){
        $name= $_GET['name'];    
    }
    
    require ("config.php");
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    $stmt = $mysqli->prepare("UPDATE tbl_torrents SET status=?, name=?, complete=? WHERE _id = ?;");
    	
    $stmt->bind_param("isdi", $status, $name, $complete, $_id );
    
    $stmt->execute();
    printf("\n%d Row inserted.\n", $stmt->affected_rows);

    /* close statement and connection */
    $stmt->close();

    /* close connection */
    $mysqli->close();
    
?>