<?PHP
  // Start session
  session_start();	

  // Include the init.php file
  require("./init.php");
  
  /**
  Source: http://www.linuxjournal.com/article/9585?page=0,3
  Validate an email address.
  Provide email address (raw input)
  Returns true if the email address has the email 
  address format and the domain exists.
  */
  function validEmail($email)
  {
     $isValid = true;
     $atIndex = strrpos($email, "@");
     if (is_bool($atIndex) && !$atIndex)
     {
        $isValid = false;
     }
     else
     {
        $domain = substr($email, $atIndex+1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64)
        {
           // local part length exceeded
           $isValid = false;
        }
        else if ($domainLen < 1 || $domainLen > 255)
        {
           // domain part length exceeded
           $isValid = false;
        }
        else if ($local[0] == '.' || $local[$localLen-1] == '.')
        {
           // local part starts or ends with '.'
           $isValid = false;
        }
        else if (preg_match('/\\.\\./', $local))
        {
           // local part has two consecutive dots
           $isValid = false;
        }
        else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
        {
           // character not valid in domain part
           $isValid = false;
        }
        else if (preg_match('/\\.\\./', $domain))
        {
           // domain part has two consecutive dots
           $isValid = false;
        }
        else if
  (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                   str_replace("\\\\","",$local)))
        {
           // character not valid in local part unless 
           // local part is quoted
           if (!preg_match('/^"(\\\\"|[^"])+"$/',
               str_replace("\\\\","",$local)))
           {
              $isValid = false;
           }
        }
        if ($isValid && !(checkdnsrr($domain,"MX") || 
   ↪checkdnsrr($domain,"A")))
        {
           // domain not found in DNS
           $isValid = false;
        }
     }
     return $isValid;
  }
  
  // Create JSON object for later
  $json = array();
  
  if ( !isset( $_GET['emailAddress'] ) 
        || !isset( $_GET['loginPassword'] )
        || !isset( $_GET['firstName'] ) 
        || !isset( $_GET['lastName'] ) 
        ) {
    $json['successful'] = false; // Indicate that the query was not successful
    $json['error'] = "Either 'firstName' or 'lastName' or 'emailAddress' or 'loginPassword' was not sent."; // Indicate the error that occured.
    echo json_encode($json);  
  
  } else if ( !validEmail( $_GET['emailAddress'] ) ) {
    
    $json['successful'] = false; // Indicate that the query was not successful
    $json['error'] = "Email address is invalid."; // Indicate the error that occured.
    echo json_encode($json);  
    
  } else {
    // Create MySQL Query
    $emailAddress = $db->escape_string( $_GET['firstName'] );
    $emailAddress = $db->escape_string( $_GET['lastName'] );
    $emailAddress = $db->escape_string( $_GET['emailAddress'] );
    $loginPassword = $db->escape_string( $_GET['loginPassword'] );
    
    $sql = "INSERT INTO  `HAW_User` ( ".
      "`UserId` , ".
      "`FirstName` , ".
      "`LastName` , ".
      "`EmailAddress` , ".
      "`LoginPassword` , ".
      "`DateAdded` ".
      ") ".
      "VALUES ( ".
      "NULL ,  '".$firstName."',  '".$lastName."',  '".$emailAddress."',  '".$loginPassword."', ".
      "CURRENT_TIMESTAMP );";
      
    // Run Query and add user
    if ( $db->query($sql) === TRUE ) {

      $json['successful'] = true; // Indicate that the query was successful
      $json['EmailAddress'] = $emailAddress;
      echo json_encode($json);   
    
    } else {
    
      $json['successful'] = false; // Indicate that the query was not successful
      $json['error'] = "Query executed unsuccessfully.";
      echo json_encode($json);   
    
    }
    
  }
  
  $db->close();

?>