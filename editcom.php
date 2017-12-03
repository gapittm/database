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

    #See if the company name has changed
    $cname = $_POST["companyname"];
    if(isset($cname)){
      $coname = $_POST["oldcompanyname"];
      $cwebsite = $_POST["companywebsite"];
      $cemail = $_POST["companyemail"];
      
      #Update company names
      $sql = array(
              "update CompanyWebsites
                set c_name = '" . $cname . "', c_website = '" . $cwebsite . "'
                where c_name = '" . $coname . "'",

              "update CompanyEmails
                set c_email = '" . $cemail . "'
                where c_name = '" . $cname . "'"
             );
      
      for($i = 0; $i < 2; $i++){
        if($con->query($sql[$i]) == FALSE){
          echo "<p>Error! Failed to update information.</p>";
          exit();
        }
      }
    }

    #See if major has changed
    $nmajor = $_POST["newmajor"];
    if(isset($nmajor)){
      $coname = $_POST["oldcompanyname"];

      #Make sure that the major doesn't exist already
      $check = "select * from DesiredMajors
                  where c_name = '" . $coname . "' and d_major = '" . $nmajor . "'";

      if($con->query($check)->num_rows > 0){
        echo "<p>Error! Major already exists.</p>";
        exit();
      }
      
      #Insert the new major
      $sql = "insert into DesiredMajors values ('" . $coname . "', '" . $nmajor . "')";

      if($con->query($sql) == FALSE){
        echo "<p>Error! Failed to add major.</p>";
        exit();
      }
    }

    #See if an old major was entered
    $omajor = $_POST["oldmajor"];
    if(isset($omajor)){
      $coname = $_POST["oldcompanyname"];

      #Select to make sure it exists
      $check = "select * from DesiredMajors
                  where c_name = '" . $coname . "' and d_major = '" . $omajor . "'";

      if($con->query($check)->num_rows == 0){
        echo "<p>Error! Major does not exist.</p>";
        exit();
      }

      #Another select to make sure a company has at least one desired major
      $checknum = "select * from DesiredMajors
                    where c_name = '" . $coname . "'";

      if($con->query($checknum)->num_rows == 1){
        echo "<p>Error! Must have at least one major.</p>";
        exit();
      }

      #Delete the major
      $sql = "delete from DesiredMajors
                where c_name = '" . $coname . "' and d_major = '" . $omajor . "'";

      if($con->query($sql) == FALSE){
        echo "<p>Error! Failed to remove major.</p>";
        exit();
      }
    }

    #See if a new location was entered
    $nladdress = $_POST["newlocationaddress"];
    if(isset($nladdress)){
      $coname = $_POST["oldcompanyname"];
      $nlphone = $_POST["locationphone"];
      $nlintern = $_POST["locationintern"];
      $nlfulltime = $_POST["locationfulltime"];

      #Check if the location is available
      $check = "select * from HiringLocations
                  where l_address = '" . $nladdress . "'";

      if($con->query($check)->num_rows > 0){
        echo "<p>Error! Location already in use.</p>";
        exit();
      }

      #Insert the new location
      $sql = "insert into HiringLocations
                values ('" . $coname . "', '" . $nladdress . 
                  "', '" . $nlphone . "', '" . $nlintern .
                  "', '" . $nlfulltime . "')";

      if($con->query($sql) == FALSE){
        echo "<p>Error! Failed to add location.</p>";
        exit();
      }
    }

    #See if an old location was entered
    $oladdress = $_POST["oldlocationaddress"];
    if(isset($oladdress)){
      #Make sure it exists
      $check = "select * from HiringLocations
                  where l_address = '" . $oladdress . "'";

      if($con->query($check)->num_rows == 0){
        echo "<p>Error! Location does not exist.</p>";
        exit();
      }

      #Delete it
      $sql = "delete from HiringLocations
                where l_address = '" . $oladdress . "'";

      if($con->query($sql) == FALSE){
        echo "<p>Error! Failed to remove location.</p>";
        exit();
      }
    }

    #See if there's an update to a location address
    $claddress = $_POST["curlocationaddress"];
    if(isset($claddress)){
      $nlphone = $_POST["locationphone"];
      $nlintern = $_POST["locationintern"];
      $nlfulltime = $_POST["locationfulltime"];

      #Make sure it exists
      $check = "select * from HiringLocations where l_address = '" . $claddress . "'";
      
      if($con->query($check)->num_rows == 0){
        echo "<p>Error! Location not found.</p>";
        exit();
      }

      #Update it with the new info
      $sql = "update HiringLocations
                set l_phone = '" . $nlphone . "', l_nintern = '" . $nlintern .
                  "', l_nfulltime = '" . $nlfulltime . "'
                where l_address = '" . $claddress . "'";

      if($con->query($sql) == FALSE){
        echo "<p>Error! Failed to update location.</p>";
        exit();
      }
    }

    echo "<p>Information updated! Return to the 
          <a href=\"editcom.html\">edit page<a> or the 
          <a href=\"index.html\">login page<a>.</p>";

    $con->close();
  ?>
</body>
</html>
