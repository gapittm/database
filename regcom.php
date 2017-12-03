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

    #Company, representative, and hiring location info
    $cname = $_POST["companyname"];
    $cwebsite = $_POST["companywebsite"];
    $cemail = $_POST["companyemail"];
    $id = $_POST["id"];
    $pass = $_POST["password"];
    $passcheck = $_POST["passcheck"];
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $remail = $_POST["email"];
    $rphone = $_POST["phone"];
    $laddress = $_POST["locationaddress"];
    $lphone = $_POST["locationphone"];
    $lintern = $_POST["locationintern"];
    $lfulltime = $_POST["locationfulltime"];

    if($pass != $passcheck){
      echo "<p>Error! Passwords did not match.</p>";
      exit();
    }

    $sql = array(
            #Add it to user types
            "insert into UserTypes
              values('" . $id . "' , 'R', '" . $u_password . "')",

            #Insert company information
            "insert into CompanyWebsites
              values('" . $cname . "', '" . $cwebsite . "')",

            "insert into CompanyEmails
              values('" . $cname . "', '" . $cemail . "')",

            #Insert representative information
            "insert into Representatives
              values('" . $cname . "', '" . $id . "', '" . $fname . "', '" . $lname . 
              "', '" . $remail . "', '" . $rphone . "')",

            #Insert hiring location info
            "insert into HiringLocations
              values('" . $cname . "', '" . $laddress . "', '" . $lphone . "', '" .
              $lintern . "', '" . $lfulltime . "')"
           );

    for($i = 0; $i < 5; $i++){
      if($con->query($sql[$i]) == FALSE){
        echo "<p>Error! Failed to create account.</p>";
        exit();
      }
    }

    #Get the list input (major1,major2,major3) and separate it into an array
    $majors = explode(",", $_POST["majors"]);
    foreach($majors as $val){
      #Insert each major
      if($con->query("insert into DesiredMajors values('" . $cname . "', '" . $val . "')") == FALSE){
        echo "<p>Error! Failed to insert majors. </p>";
        exit();
      }
    }

    echo  "<p>Welcome to the Virtual Career Fair! Return to the <a href=\"index.html\">login page<a>  to access your new representative account.</p>";
    $con->close();
  ?>
</body>
</html>
