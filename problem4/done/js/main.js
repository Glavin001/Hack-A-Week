//===== Main.js =====

// Events
$(document).ready( function ( ) { 
  // When the document is fully finished loading
  // the following will be executed.
  
  // Get the current user's information
  // This will also check if there is in fact a user signed in or not and deal with the page appropriately.
  getUserInfo();
  
});

// User functions
function registerUser( callback ) {
  // Get user input
  var firstName = $("#inputFirstName").val();
  var lastName = $("#inputLastName").val();
  var email = $("#inputEmail").val();
  var password = $("#inputPassword").val();
  var confirm = $("#inputPasswordConfirm").val();

  console.log(firstName, lastName, email, password, confirm);
  
  // Validate
  if ( password === confirm ) {
    console.log("Valid");
    // Submit registration
    $.getJSON(
    "php/register.php", 
    { 
      "firstName": firstName, "lastName": lastName, 
      "emailAddress": email, "loginPassword": password 
    }, 
    function(data) {
      console.log(data);
      if ( callback )
        callback(data);
      // Check if successfully logged in
      if (data.successful) {
        // Registered
        $('#registerModal').modal('hide');
        displayModal("Successful", "You are now registered.  Now you may login.", "success");
      } else {
        // Not registered
        displayModal("Error", data.error||"Registration was unsuccessful.", "error");
      }    
    });
  
  } else {
  // Not valid 
  
  }
  
}

function login( emailAddress , loginPassword , callback) { 
  $.getJSON(
  "php/login.php", 
  { "emailAddress": emailAddress, "loginPassword": loginPassword }, 
  function(data) {
    console.log(data);
    
    if ( callback )
      callback(data);
      
    // Check if successfully logged in
    if (data.successful) {
      // Logged in
      $('body').removeClass("not-logged-in").addClass("logged-in");
      getUserInfo();
    } else {
      // Not logged in
      $('body').removeClass("logged-in").addClass("not-logged-in"); 
      displayModal("Error", data.error||"Login unsuccessful.", "success");    
    }
    
  });
}

function getUserInfo( callback ) {
  $.getJSON("php/currentUser.php", function(data) {
    console.log(data);

    if ( callback )
      callback(data);

    // Check if successfully retrieved current user information
    if (data.successful) {
      // Successful
      $('body').removeClass("not-logged-in").addClass("logged-in");
      // Change account name to the new name of the user who is currently logged in.
      $(".userName").html( data.result[0].FirstName + " " + data.result[0].LastName );
    } else {
      // Not successful
    }    
  });
}

function logout( callback ) {
  $.getJSON("php/logout.php", function(data) {
    console.log(data);

    if ( callback )
      callback(data);

    // Check if successfully logged out
    if (data.successful) {
      // Logged out
      $('body').removeClass("logged-in").addClass("not-logged-in");
    } else {
      // Not logged out
      displayModal("Error", data.error||"Logout unsuccessful.", "error");    
    }

  });
  
}

// Get Data from Database functions
function exercise( exerciseId , callback) {
  $.getJSON(
  "php/exercise.php", 
  (exerciseId)?{ "id":exerciseId }:undefined, function(data) {
    console.log(data);

    if ( callback )
      callback(data);
    
    // Check if successfully retrieved current user information
    if (data.successful) {
      // Successful
    } else {
      // Not successful
    }    
  });
}

// Get Data from Database functions
function food( foodId , callback) {
  $.getJSON(
  "php/food.php", 
  (foodId)?{ "id": foodId }:undefined, function(data) {
    console.log(data);

    if ( callback )
      callback(data);
    
    // Check if successfully retrieved current user information
    if (data.successful) {
      // Successful
    } else {
      // Not successful
    }    
  });
}

// Adding more data
function addFood( callback ) {
  // Get user input
  var name = $("#inputName").val();
  var foodGroup = $("input[name='inputFoodGroup']:checked").val();
  var calories = $("#inputCalories").val();

  console.log(name, foodGroup, calories);
  
  // Validate
  if ( name && foodGroup && calories ) {
    console.log("Valid");
    // Submit registration
    $.getJSON(
    "php/addFood.php", 
    { 
      "name": name, "foodGroup": foodGroup, 
      "calories": calories
    }, 
    function(data) {
      console.log(data);
      
      if ( callback )
        callback(data);
      else {
        // Check if successfully logged in
        if (data.successful) {
          // Logged in
          $('#addFoodModal').modal('hide'); 
          displayModal("Successful", "Food was successfully added.", "success");    
          if ( updateFoods )
            updateFoods();
        } else {
          // Not logged in
          displayModal("Error", data.error||"", "error");    
        }
      }
    
    });
  
  } else {
    // Not valid 
    console.log("Invalid");
    displayModal("Error", "Invalid data. Please try again.", "error");    
  }
  
}


function addExercise( callback ) {
  // Get user input
  var name = $("#inputName").val();
  var description = $("#inputDescription").val();
  var calories = $("#inputCalories").val();

  console.log(name, description, calories);
  console.log(callback);
  
  // Validate
  if ( name && description && calories ) {
    console.log("Valid");
    // Submit registration
    $.getJSON(
    "php/addExercise.php", 
    { 
      "name": name, "description": description, 
      "calories": calories
    }, 
    function(data) {
      console.log(data);
      
      if ( callback )
        callback(data);
      else {
        // Check if successfully logged in
        if (data.successful) {
          // Logged in
          $('#addExerciseModal').modal('hide'); 
          displayModal("Successful", "Exercise was successfully added.", "success");    
          if ( updateExercises )
            updateExercises();
        } else {
          // Not logged in
          console.log("Invalid");
          displayModal("Error", data.error||"", "error");    
        }
      }
          
    });
  
  } else {
  // Not valid 
  
  }
  
}


// Display functions
function displayTable(selector, headers, data) {

  // Headers
  $(selector).find("thead").html("").append("<tr></tr>");
  $.each(headers, function(index, value) {
    console.log(index, value);
    $(selector).find("thead").find("tr").append("<th>"+value+"</th>");
  });
  
  // Table content data
  $(selector).find("tbody").html("");
  $.each(data, function(rowIndex, rowValue) {
    var row = $("<tr></tr>");  
    $.each(rowValue, function(colIndex, colValue) {
      row.append("<td>"+colValue+"</td>");
    });
    $(selector).find("tbody").append(row);
  });
 
}

function displayModal(heading, message, status) {
  $("<div class='modal hide fade' tabindex='-1' role='dialog'>"+
  '<div class="modal-header">'+
  '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
  '<h3 id="tempModal">'+heading+'</h3>'+
  '</div>'+
  "<div class='modal-body'><div class='text-"+status+"'>"+
  message+
  "</div></div>"+
  "<div class='modal-footer'>"+
  "<button class='btn btn-"+status+"' data-dismiss='modal' aria-hidden='true'>Close</button>"+
  "</div>"+
  "</div>")
  .modal();
}