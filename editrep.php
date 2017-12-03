<!DOCTYPE html>
<html>
<head>
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
</head>
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
      echo "<p>Error! Invalid credentials.</p>";
      exit();
    }

    $uname = $_POST["id"];
    $pword = $_POST["password"];
    $cred = $con->query("select * from UserTypes where u_id = '" . $uname . "'");
    $credrow = $cred->fetch_assoc();
    if($credrow["u_password"] != $pword){
      echo "<p style=\"margin-top:100px;\">Error! Invalid username or password.</p>";
      exit();
    }

    #New representative information
    $id = $_POST["id"];
    $pass = $_POST["password"];
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    #Update fields in Representatives here
    $sql = "update Representatives
              set r_fname = '" . $fname . "', r_lname = '" . $lname . "', r_email= '" .
                $email . "', r_phone = '" . $phone . "'
              where r_id = '" . $id . "'";

    if($con->query($sql) == FALSE){
      echo "<p>Error! Failed to update account.</p>";
      exit();
    }

    echo  "<p>Information updated! Return to the <a href=\"index.html\">login page<a>.</p>";
    $con->close();
  ?>
</body>
</html>
