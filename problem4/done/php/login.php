<?PHP
  // Start session
  session_start();	

  // Include the init.php file
  require("./init.php");
  
  // Create JSON object for later
  $json = array();
  
  if ( !isset( $_GET['emailAddress'] ) || !isset( $_GET['loginPassword'] ) ) {
    $json['successful'] = false; // Indicate that the query was not successful
    $json['error'] = "Either 'emailAddress' or 'loginPassword' was not sent."; // Indicate the error that occured.
    echo json_encode($json);   
  } else {
    // Create MySQL Query
    $emailAddress = $db->escape_string( $_GET['emailAddress'] );
    $loginPassword = $db->escape_string( $_GET['loginPassword'] );
    $sql = "SELECT * FROM `HAW_User` WHERE `EmailAddress` = '".$emailAddress."' AND `LoginPassword` = '".$loginPassword."';";
  
    // Run Query and check if successful
    if($result = $db->query($sql) ) {
      if ( $result->num_rows == 1 ) { 
        // Query executed with no errors
        $json['successful'] = true; // Indicate that the query was successful
        // Save the UserId that was found 
        $row = $result->fetch_assoc();
        $_SESSION['UserId'] = $row['UserId'];
               
        // Only for debugging purposes
        // $json['UserId'] = $_SESSION['UserId']; // Indicate that the query was successful
        // Return the JSON object
        echo json_encode($json); 
        
      } else {
        // There was an error
        $json['successful'] = false; // Indicate that the query was not successful
        $json['error'] = "The email and/or password is invalid."; // Indicate the error that occured.
        echo json_encode($json);   
      }
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