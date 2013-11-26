<?PHP
  // Start session
  session_start();	

  // Include the init.php file
  require("./init.php");
  
  // Create JSON object for later
  $json = array();
  
  if ( !isset( $_GET['content'] ) 
        || !isset( $_GET['userName'] )
        ) {
    $json['successful'] = false; // Indicate that the query was not successful
    $json['error'] = "Either 'content' or 'userName' was not sent."; // Indicate the error that occured.
    echo json_encode($json);   
  } else {
    // Create MySQL Query
    $content = $db->escape_string( $_GET['content'] );
    $userName = $db->escape_string( $_GET['userName'] );
    
    $sql = "INSERT INTO  `Feedback` ( ".
      "`FeedbackId` , ".
      "`Content` , ".
      "`UserName` , ".
      "`DateAdded`) ".
      "VALUES ( ".
      "NULL ,  '".$content."',  '".$userName."',  CURRENT_TIMESTAMP );";
      
    // Run Query and add user
    if ( $result = $db->query($sql) === TRUE ) {

      $json['successful'] = true; // Indicate that the query was successful
      echo json_encode($json);   
    
    } else {
    
      $json['successful'] = false; // Indicate that the query was not successful
      $json['error'] = "Query executed unsuccessfully.";
      echo json_encode($json);   
    
    }
    
    
    $result->close();
    
  }
  
  $db->close();

?>