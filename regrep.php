<!DOCTYPE html>
<html>
<style>
  body {
    background-color: #404040;
  }

  img {
    display: block;
    margin: 0 auto;
  }

  a, p {
    color: white;
    margin: auto;
    margin-top: 100px;
    width: 420px;
  }

  div.header {
    background-color: #f66733;
    margin: -10px;
    padding: 25px;
    height: 100px;
  }
</style>
<body>
  <div class="header">
    <img style="max-width: 420px;" src="clemsonwordmark.png">
  </div>
  <?php
    #Connect to the sql server
    $servername = "mysql1.cs.clemson.edu";
    $username = "VrtlCrFr_ob4z";
    $password = "ob4zrtpass12217";
    $database = "VirtualCareerFair_lbre";

    $con = new mysqli($servername, $username, $password, $database);
    if($con->connect_error){
      echo "<p>Error! Failed to connect to the database.</p>";
      exit();
    }

    #Representative information
    $id = $_POST["id"];
    $pass = $_POST["password"];
    $passcheck = $_POST["passcheck"];
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $company = $_POST["companyname"];

    if($pass != $passcheck){
      echo "<p>Error! Passwords did not match.</p>";
      exit();
    }

    #Quick select to make sure the provided company actually exists
    $comcheck = "select c_name from CompanyWebsites where c_name = '" . $company . "'";
    $result = $con->query($comcheck);
    if($result->num_rows == 0){
      echo "<p>Error! Company not found. Go to <a href=\"regcom.html\">company registration</a>.</p>";
      exit();
    }

    #Create the new user and insert its information
    $sql = array(
            "insert into UserTypes
              values('" . $id . "' , 'R', '" . $u_password . "')",

            "insert into Representatives
              values('" . $company . "', '" . $id . "', '" . $fname . "', '" . $lname . 
              "', '" . $email . "', '" . $phone . "')"
           );

    for($i = 0; $i < 2; $i++){
      if($con->query($sql[$i]) == FALSE){
        echo "<p>Error! Failed to create account.</p>";
        exit();
      }
    }

    echo  "<p>Welcome to the Virtual Career Fair! Return to the <a href=\"index.html\">login page<a>  to access your new representative account.</p>";
    $con->close();
  ?>
</body>
</html>
