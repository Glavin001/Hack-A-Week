<?PHP
  // Start session
  session_start();	

  // Logout by unsetting the `UserId` variable
  unset($_SESSION['UserId']);
  // After logged out, return successful result
  $json = array("successful" => true);
  echo json_encode($json);   
  
?>