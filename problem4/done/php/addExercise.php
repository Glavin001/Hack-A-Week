<?PHP
  // Start session
  session_start();	

  // Include the init.php file
  require("./init.php");
  
  // Create JSON object for later
  $json = array();
  
  if ( !isset( $_GET['name'] ) 
        || !isset( $_GET['description'] )
        || !isset( $_GET['calories'] ) 
        ) {
    $json['successful'] = false; // Indicate that the query was not successful
    $json['error'] = "Either 'name' or 'description' or 'calories' was not sent."; // Indicate the error that occured.
    echo json_encode($json);   
  } else {
    // Create MySQL Query
    $name = $db->escape_string( $_GET['name'] );
    $description = $db->escape_string( $_GET['description'] );
    $calories = $db->escape_string( $_GET['calories'] );
    
    $sql = "INSERT INTO  `HAW_Exercise` ( ".
      "`ExerciseId` , ".
      "`Name` , ".
      "`Description` , ".
      "`Calories` ".
      ") ".
      "VALUES ( ".
      "NULL ,  '".$name."',  '".$description."',  '".$calories."' );";
      
    // Run Query and add user
    if ( $db->query($sql) === TRUE ) {

      $json['successful'] = true; // Indicate that the query was successful
      echo json_encode($json);   
    
    } else {
    
      $json['successful'] = false; // Indicate that the query was not successful
      $json['error'] = "Query executed unsuccessfully.";
      echo json_encode($json);   
    
    }
    
  }
  
  $db->close();

?>