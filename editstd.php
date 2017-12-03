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
    #Connect to sql server
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
    #New student information
    $id = $_POST["id"];
    $pass = $_POST["password"];
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $major = $_POST["major"];

    #Update student records accordingly
    $sql = array(
            "update StudentNames
              set s_fname = '" . $fname . "', s_lname = '" . 
                $lname . "', s_major = '" . $major . "'
              where s_id = '" . $id . "'",

            "update StudentEmails
              set s_email = '" . $email . "'
              where s_id = '" . $id . "'",

            "update StudentPhones
              set s_phone = '" . $phone . "'
              where s_id = '" . $id . "'"
           );

    for($i = 0; $i < 3; $i++){
      if($con->query($sql[$i]) == FALSE){
        echo "<p>Error! Failed to update account.</p>";
        exit();
      }
    }

    echo  "<p>Information updated! Return to the <a href=\"index.html\">login page<a>.</p>";
    $con->close();
  ?>
</body>
</html>
