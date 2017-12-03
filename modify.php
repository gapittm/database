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
    #Connect to the sql server
    $servername = "localhost";
    $username = "root";
    $password = "asdf";
    $database = "vcf_test";

    $con = new mysqli($servername, $username, $password, $database);
    if($con->connect_error){
      echo "<p style=\"margin-top:100px;\">Error! Cannot connect.</p>";
      exit();
    }

/*    #Populate the database
    if(isset($_POST["pop"])){
      $majors = array("Compsci", "Engr", "History", "Art", "English", "Biology", "Chemistry", "Psychology");
      $ccount = 100;
      $rfactor = 5;
      $lfactor = 5;
      $sfactor = 5;

      #Add ccount company entries to the database
      for($i = 0; $i < $ccount; $i++){
        $cname = uniqid('company');
        $cwebsite = uniqid('www.') . ".com";
        $cemail = $cname . "@" . $cwebsite;
        $rid = uniqid('rep');
        $rfname = uniqid('rn');
        $rlname = uniqid('ln');
        $remail = $rlname . "@" . $cwebsite;
        $rphone = substr(uniqid('rp'), 0, 10);
        $laddress = mt_rand(100,999) . " " . uniqid();
        $lphone = substr(uniqid('lp'), 0, 10);
        $lnintern = mt_rand(0,50);
        $lnfulltime = mt_rand(0,50);

        $csql = array(
                  #Insert company info
                  "insert into CompanyWebsites
                    values('" . $cname . "', '" . $cwebsite . "')",
                  "insert into CompanyEmails
                    values('" . $cname . "', '" . $cemail . "')",
                  #Insert major info
                  "insert into DesiredMajors
                    values('" . $cname . "', '" . $majors[0] . "')",
                  #Insert rep info
                  "insert into Representatives
                    values('" . $cname . "', '" . $rid . "', '" . $rfname . "', '" . 
                      $rlname . "', '" . $remail . "', '" . $rphone . "')",
                  #Insert location info
                  "insert into HiringLocations
                    values('" . $cname . "', '" . $laddress . "', '" . $lphone . "', '" . 
                      $lnintern . "', '" . $lnfulltime . "')",
                  #Add the rep to both user tables
                  "insert into UserTypes
                    values('" . $rid . "', 'R')",
                  "grant SELECT, UPDATE, CREATE VIEW on " . $database . ".* to '" . $rid . "'@'" .
                      $servername . "' identified by 'password'"
                );

        for($x = 0; $x < 8; $x++){
          $con->query($csql[$x]);
        }

        #Add ccount * rfactor representative entries to the database
        for($j = 0; $j < $ccount * $rfactor; $j++){
          $rid = uniqid('rep');
          $rfname = uniqid('rfn');
          $rlname = uniqid('rln');
          $remail = $rlname . "@" . $cwebsite;
          $rphone = substr(uniqid('rp'), 0, 10);

          $rsql = array(
                    #Insert the representative, into its own table and the two user ones
                    "insert into Representatives
                      values('" . $cname . "', '" . $rid . "', '" . $rfname . "', '" . 
                        $rlname . "', '" . $remail . "', '" . $rphone . "')",
                    "insert into UserTypes
                      values('" . $rid . "', 'R')",
                    "grant SELECT, UPDATE, CREATE VIEW on " . $database . ".* to '" . $rid . "'@'" .
                      $servername . "' identified by 'password'"
                  );

          for($y = 0; $y < 3; $y++){
            $con->query($rsql[$y]);
          }
        }

        #Add ccount * lfactor location entries to the database
        for($k = 0; $k < $ccount * $lfactor; $k++){
          $laddress = mt_rand(100,999) . " " . uniqid();
          $lphone = substr(uniqid('lp'), 0, 10);
          $lnintern = mt_rand(0,50);
          $lnfulltime = mt_rand(0,50);

          #Add a single hiring location
          $lsql = "insert into HiringLocations
                    values('" . $cname . "', '" . $laddress . "', '" . $lphone . "', '" . 
                    $lnintern . "', '" . $lnfulltime . "')";

          $con->query($lsql);
        }

        #Add ccount * sfactor student entries to the database
        for($l = 0; $l < $ccount * $sfactor; $l++){
          $sid = uniqid('std');
          $sfname = uniqid('sfn');
          $slname = uniqid('sln');
          $semail = $slname . "@" . uniqid() . ".com";
          $sphone = substr(uniqid('sp'), 0, 10);

          $ssql = array(
                    #Insert student info
                    "insert into StudentNames
                      values('" . $sid . "', '" . $sfname . "', '" . $slname . "', '" . 
                      $majors[mt_rand(0, 7)] . "')",
                    "insert into StudentEmails
                      values('" . $sid . "', '" . $semail . "')",
                    "insert into StudentPhones
                      values('" . $sid . "', '" . $sphone . "')",
                    #Insert student into the user accounts
                    "insert into UserTypes
                      values('" . $sid . "', 'S')",
                    "grant SELECT, UPDATE, CREATE VIEW on " . $database . ".* to '" . $sid . "'@'" .
                      $servername . "' identified by 'password'"
                  );

          for($z = 0; $z < 5; $z++){
            $con->query($ssql[$z]);
          }
        }

        #Add a random number of majors to the current company
        for($m = 1; $m < mt_rand(1,8); $m++){
          $msql = "insert into DesiredMajors
                    values('" . $cname . "', '" . $majors[$m] . "')";

          $con->query($msql);
        }
      }
    }
*/
    #Completely wipe the database, then remake the admin account
    if(isset($_POST["pur"])){
      #Figure out who to get rid of from the user table
      $delusr = $con->query("select u_id from UserTypes");
      for($i; $delusrrow = $delusr->fetch_assoc(); $i++){
        $dellist[$i] = $delusrrow["u_id"];
      }

      #Drop the user entries
      foreach($dellist as $val){
        $con->query("drop user '" . $val . "'@'" . $servername . "'");
      }

      #Remake the admin account
      $con->query("grant all on " . $database . ".* to 'admin'@'" . $servername .
                    "' identified by 'vcfadpass'");

      #Delete all tables (deleting cascades, so only these are necessary)
      $delsql = array(
                  "delete from StudentNames",
                  "delete from CompanyWebsites",
                  "delete from UserTypes"
                );

      for($j = 0; $j < 3; $j++){
        if($con->query($delsql[$j]) == FALSE){
          echo "<p style=\"margin-top:100px;\">Error! Failed to delete tables.</p>";
          exit();
        }
      }

      #Put the admin back into the list of user types
      $con->query("insert into UserTypes values('admin', 'A')");
    }

    echo "<p style=\"margin-top:100px;\">Operation was successful!</p>";
    $con->close();
  ?>
</body>
</html>
