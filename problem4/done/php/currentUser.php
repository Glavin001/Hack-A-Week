<?PHP
  // Start session
  session_start();	

  // Include the init.php file
  require("./init.php");
  
  // Create JSON object for later
  $json = array();
  
  if ( !isset( $_SESSION['UserId'] ) ) {
    $json['successful'] = false; // Indicate that the query was not successful
    $json['error'] = "Not currently logged in."; // Indicate the error that occured.
    echo json_encode($json);   
     
  } else {
  
    // Create MySQL Query
    $id = $db->escape_string( $_SESSION['UserId'] );
    $sql = "SELECT UserId, FirstName, LastName, EmailAddress, DateAdded FROM `HAW_User` WHERE `UserId` = '".$id."';";
  
    // Run Query and check if successful
    if($result = $db->query($sql)) { 
      // Query executed with no errors
      $json['successful'] = true; // Indicate that the query was successful
      // Now add the result from the query to the JSON object
      $json['result'] = array();
      $row = $result->fetch_object();
      $tempArray = $row;
      array_push($json['result'], $tempArray);
      // Return the JSON object
      echo json_encode($json);
    } else {
      // There was an error
      $json['successful'] = false; // Indicate that the query was not successful
      $json['error'] = $db->error; // Indicate the error that occured.
      echo json_encode($json);   
      //die('There was an error running the query [' . $db->error . ']');
    }
    $result->close();
  }
  $db->close();

?>