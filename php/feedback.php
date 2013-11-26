<?PHP
  // Start session
  session_start();	

  // Include the init.php file
  require("./init.php");
  
  // Create JSON object for later
  $json = array();
  
  if ( !isset( $_GET['id'] ) ) {
  /*
    $json['successful'] = false; // Indicate that the query was not successful
    $json['error'] = "The 'id' is not set"; // Indicate the error that occured.
    echo json_encode($json);   
  */
  
    // Create MySQL Query
    $sql = "SELECT * FROM `Feedback` ORDER BY `FeedbackId`;";
    
  } else {
  
    // Create MySQL Query
    $id = $db->escape_string( $_GET['id'] );
    $sql = "SELECT * FROM `Feedback` WHERE `FeedbackId` = '".$id."' ORDER BY `FeedbackId`;";
  }

  // Run Query and check if successful
  if($result = $db->query($sql)) { 
    // Query executed with no errors
    $json['successful'] = true; // Indicate that the query was successful
    // Now add the result from the query to the JSON object
    $json['result'] = array();
    while($row = $result->fetch_object()) {
      $tempArray = $row;
      array_push($json['result'], $tempArray);    
    }
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

  $db->close();

?>