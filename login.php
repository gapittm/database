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

  a, p , h3, h5, form {
    color: white;
    margin: auto;
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
      echo "<p style=\"margin-top:100px;\">Error! Invalid credentials.</p>";
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

    #Get general information about the user (for use in the personal info sections)
    $sql = array(
            "select * from UserTypes where u_id = '" . $uname . "'",
            "select sn.s_id, s_fname, s_lname, s_major, s_email, s_phone
              from StudentNames as sn
                inner join StudentEmails as se on sn.s_id = se.s_id
                inner join StudentPhones as sp on sn.s_id = sp.s_id
              where sn.s_id = '" . $uname . "'",
            "select * from Representatives where r_id = '" . $uname . "'",
           );

    if($temp = $con->query($sql[0])){
      $type = $temp->fetch_assoc();

      #The user is a student
      if($type["u_type"] == 'S'){
        $sinfo = $con->query($sql[1])->fetch_assoc();
        echo "<h3 style=\"margin-top:100px;\">Virtual Career Fair</h3><br>
              <p>Welcome, " . $sinfo["s_fname"] . "!</p><br>
              
              <p><b>Search Company</b></p><br>
              <form action=\"search.php\" method=\"post\" target=\"_blank\">
                <input type=\"text\" placeholder=\"Company Name\" name=\"companyname\" required>
                <input type=\"submit\" value=\"Search\"><br><br>
                <h5>List:</h5>
                <input type=\"radio\" name=\"category\" value=\"mjr\" checked> Majors  
                <input type=\"radio\" name=\"category\" value=\"rep\"> Representatives  
                <input type=\"radio\" name=\"category\" value=\"loc\"> Locations  <br>
                <input type=\"radio\" name=\"sort\" value=\"asc\" checked> Ascending  
                <input type=\"radio\" name=\"sort\" value=\"des\"> Descending  <br><br>
              </form><br>

              <p><b>Search Representative</b></p><br>
              <form action=\"search.php\" method=\"post\" target=\"_blank\">
                <input type=\"text\" placeholder=\"Search Term\" name=\"srterm\" required>
                <input type=\"submit\" value=\"Search\"><br><br>
                <h5>Search by:</h5>
                <input type=\"radio\" name=\"category\" value=\"fname\" checked> First Name  
                <input type=\"radio\" name=\"category\" value=\"lname\"> Last Name  
                <input type=\"radio\" name=\"category\" value=\"com\"> Company  <br>
                <input type=\"radio\" name=\"sort\" value=\"asc\" checked> Ascending  
                <input type=\"radio\" name=\"sort\" value=\"des\"> Descending  <br><br>
              </form><br>

              <p><b>Search Majors</b></p><br>
              <form action=\"search.php\" method=\"post\" target=\"_blank\">
                <input type=\"text\" placeholder=\"Major\" name=\"majorname\" required>
                <input type=\"submit\" value=\"Search\"><br><br>
                <h5>Sort by:</h5>
                <input type=\"radio\" name=\"category\" value=\"com\" checked> By Company  
                <input type=\"radio\" name=\"category\" value=\"pos\"> By Number of Locations  <br>
                <input type=\"radio\" name=\"sort\" value=\"asc\" checked> Ascending  
                <input type=\"radio\" name=\"sort\" value=\"des\"> Descending  <br><br>
              </form><br>

              <p><b>List All Companies</b></p><br>
              <h5>Sort by:</h5>
              <form action=\"search.php\" method=\"post\" target=\"_blank\">
                <input type=\"radio\" name=\"clist\" value=\"name\" checked> Name  
                <input type=\"radio\" name=\"clist\" value=\"loc\"> Number of Locations  <br>
                <input type=\"radio\" name=\"clist\" value=\"int\"> Number of Internships  <br>
                <input type=\"radio\" name=\"clist\" value=\"ftp\"> Number of Full Time Positions   <br>
                <input type=\"radio\" name=\"sort\" value=\"asc\" checked> Ascending  
                <input type=\"radio\" name=\"sort\" value=\"des\"> Descending  <br><br>
                <input type=\"submit\" value=\"Search\"><br><br>
              </form><br>

              <p><b>List All Representatives</b></p><br>
              <h5>Sort by:</h5>
              <form action=\"search.php\" method=\"post\" target=\"_blank\">
                <input type=\"radio\" name=\"rlist\" value=\"fname\" checked> First Name  
                <input type=\"radio\" name=\"rlist\" value=\"lname\"> Last Name  
                <input type=\"radio\" name=\"rlist\" value=\"com\"> Company  <br>
                <input type=\"radio\" name=\"sort\" value=\"asc\" checked> Ascending  
                <input type=\"radio\" name=\"sort\" value=\"des\"> Descending  <br><br>
                <input type=\"submit\" value=\"Search\"><br><br>
              </form><br>

              <p><b>List All Majors</b></p><br>
              <h5>Sort by:</h5>
              <form action=\"search.php\" method=\"post\" target=\"_blank\">
                <input type=\"radio\" name=\"mlist\" value=\"mjr\" checked> Major  
                <input type=\"radio\" name=\"mlist\" value=\"com\"> Number of Companies  <br>
                <input type=\"radio\" name=\"sort\" value=\"asc\" checked> Ascending  
                <input type=\"radio\" name=\"sort\" value=\"des\"> Descending  <br><br>
                <input type=\"submit\" value=\"Search\"><br><br>
              </form><br>

              <p><b>Personal Information</b></p>
              <p>Name: " . $sinfo["s_fname"] . " " . $sinfo["s_lname"] . "</p>
              <p>Major: " . $sinfo["s_major"] . "</p>
              <p>Email: " . $sinfo["s_email"] . "</p>
              <p>Phone: " . $sinfo["s_phone"] . "</p>
              <p>Id: " . $sinfo["s_id"] . "</p><br>
              <p><a href=\"editstd.html\">Edit Info</a></p>";
      }

      #The user is a representative
      else if($type["u_type"] == 'R'){
        $rinfo = $con->query($sql[2])->fetch_assoc();
        echo "<h3 style=\"margin-top:100px;\">Virtual Career Fair</h3><br>
              <p>Welcome, " . $rinfo["r_fname"] . "!</p><br>

              <p><b>Search Students</b></p><br>
              <form action=\"search.php\" method=\"post\" target=\"_blank\">
                <input type=\"text\" placeholder=\"Major\" name=\"rmajorname\" required>
                <input type=\"submit\" value=\"Search\"><br><br>
                <h5>List:</h5>
                <input type=\"radio\" name=\"category\" value=\"fname\" checked> First Name  
                <input type=\"radio\" name=\"category\" value=\"lname\"> Last Name  <br>
                <input type=\"radio\" name=\"sort\" value=\"asc\" checked> Ascending  
                <input type=\"radio\" name=\"sort\" value=\"des\"> Descending  <br><br>
              </form><br>

              <p><b>List All Students</b></p><br>
              <h5>Sort by:</h5>
              <form action=\"search.php\" method=\"post\" target=\"_blank\">
                <input type=\"radio\" name=\"slist\" value=\"fname\" checked> First Name  
                <input type=\"radio\" name=\"slist\" value=\"lname\"> Last Name  
                <input type=\"radio\" name=\"slist\" value=\"mjr\"> Major  <br>
                <input type=\"radio\" name=\"sort\" value=\"asc\" checked> Ascending  
                <input type=\"radio\" name=\"sort\" value=\"des\"> Descending  <br><br>
                <input type=\"submit\" value=\"Search\"><br><br>
              </form><br>

              <p><b>Personal Information</b></p>
              <p>Name: " . $rinfo["r_fname"] . " " . $rinfo["r_lname"] . "</p>
              <p>Email: " . $rinfo["r_email"] . "</p>
              <p>Phone: " . $rinfo["r_phone"] . "</p>
              <p>Id: " . $rinfo["r_id"] . "</p><br>
              <p><a href=\"editrep.html\">Edit Info</a></p><br><br>";

        #Grab info about what company this user represents (for use in company info section)
        $csql = array(
                  "select c_website, c_email
                    from CompanyWebsites as cw inner join
                      CompanyEmails as ce on cw.c_name = ce.c_name
                    where cw.c_name = '" . $rinfo["c_name"] . "'",

                  "select count(*) as l_count from HiringLocations
                    where c_name = '" . $rinfo["c_name"] . "'",

                  "select distinct d_major from DesiredMajors
                    where c_name = '" . $rinfo["c_name"] . "'"
                );

        $cinfo = array(
                  $con->query($csql[0])->fetch_assoc(),
                  $con->query($csql[1])->fetch_assoc(),
                  $con->query($csql[2])
                 );

        #Construct a single list out of all the major listings
        for($i = 0; $mrow = $cinfo[2]->fetch_assoc(); $i++){
          $minfo[$i] = $mrow["d_major"];   
        }

        $mlist = implode(", ", $minfo);

        echo "<p><b>Company Information</b></p>
              <p>Name: " . $rinfo["c_name"] . "</p>
              <p>Website: " . $cinfo[0]["c_website"] . "</p>
              <p>Email: " . $cinfo[0]["c_email"] . "</p>
              <p>Hiring Locations: " . $cinfo[1]["l_count"] . "</p>
              <p>Desired Majors: " . $mlist . "</p><br>
              <p><a href=\"editcom.html\">Edit Info</a></p>";

      }

      #If the user is the admininstrator
      else if($type["u_type"] == 'A'){
        echo "<h3 style=\"margin-top:100px;\">Administration</h3><br>
              <p>Welcome, " . $type["u_id"] . "!</p><br>

              <p><b>View User Accounts</b></p><br>
              <h5>Filter:</h5>
              <form action=\"search.php\" method=\"post\" target=\"_blank\">
                <input type=\"radio\" name=\"ulist\" value=\"std\" checked> Students  
                <input type=\"radio\" name=\"ulist\" value=\"rep\"> Representatives  
                <input type=\"radio\" name=\"ulist\" value=\"all\"> All  <br><br>
                <input type=\"submit\" value=\"Search\"><br><br>
              </form><br>

              <p><b>Change Password</b></p><br>
              <form action=\"search.php\" method=\"post\">
                <input type=\"text\" placeholder=\"User Id\" name=\"getid\" required><br><br>
                <input type=\"password\" placeholder=\"New Password\" name=\"pass\" required><br><br>
                <input type=\"password\" placeholder=\"Confirm Password\" name=\"passcheck\" required><br><br>
                <input type=\"submit\" value=\"Update\"><br><br>
              </form><br>

              <p><b>Delete User</b></p><br>
              <form action=\"search.php\" method=\"post\">
                <input type=\"text\" placeholder=\"User Id\" name=\"delid\" required><br><br>
                <input type=\"submit\" value=\"Delete\"><br><br>
              </form><br>

              <p><b>Backup Database</b></p><br>
              <form action=\"search.php\" method=\"post\">
                <input type=\"submit\" value=\"Backup\" name=\"backup\"><br><br>
              </form><br>
              ";
      }

      else {
        echo "<p style=\"margin-top:100px;\">Error! Invalid user type.</p>";
        exit();
      }
    } 

    else {
        echo "<p style=\"margin-top:100px;\">Error! Could not get user.</p>";
        exit();
    }

    $con->close();
  ?>
</body>
</html>
