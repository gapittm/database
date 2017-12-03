<!DOCTYPE html>
<html>
<head>
<style>
  body {
    background-color: #404040;
  }

  a, p, h3, h5 {
    color: white;
  }

  table, th, td {
    text-align: left;
    border-collapse: collapse;
  }

  th, td {
    color: white;
    padding: 5px;
  }

  tr:nth-child(even) {
    background-color: #a6a6a6;
  }

  tr:nth-child(odd) {
    background-color: #808080;
  }

  th {
    background-color: #522D80;
  }

  td {
    width: 500px;
  }

  div.header {
    background-color: #f66733;
    margin: -10px;
    padding: 25px;
    height: 25px;
  }

  div.searchbody {
    padding: 15px;
    margin: auto;
  }
</style>
</head>
<body>
  <div class="header"> </div>
  <div class="searchbody">
    <?php
      #Connect to the sql server
      $servername = "mysql1.cs.clemson.edu";
      $username = "VrtlCrFr_ob4z";
      $password = "ob4zrtpass12217";
      $database = "VirtualCareerFair_lbre";

      $con = new mysqli($servername, $username, $password, $database);
      if($con->connect_error){
        echo "<p style=\"margin-top:100px;\">Error! Invalid credentials.</p>";
        exit();
      }

      #Search company
      $cname = $_POST["companyname"];
      if(isset($cname)){
        $smode = $_POST["category"];
        $ssort = $_POST["sort"];
        
        #Combine CompanyWebsites and CompanyEmails with an inner join, then return those rows
        $csql = "select cw.c_name, c_website, c_email
                  from CompanyWebsites as cw
                    inner join CompanyEmails as ce on cw.c_name = ce.c_name
                  where cw.c_name = '" . $cname . "'";

        if($company = $con->query($csql)){
          if($company->num_rows == 0){
            echo "<p>No company found.</p>";
            exit();
          } else {
            $companyrow = $company->fetch_assoc();
            echo "<p style=\"margin-top:-50px\"><b>" . $companyrow["c_name"] . 
                  "</b> &nbsp;&nbsp;&nbsp;&nbsp;" . $companyrow["c_website"] . 
                  " &nbsp;&nbsp;&nbsp;&nbsp;" . $companyrow["c_email"] . "</p><br>";
          }

        } else {
          echo "<p>Error! Could not get company.</p>";
          exit();
        }

        #Display reps associated with the company
        if($smode == "mjr"){
          if($ssort == "asc"){
            #Get the majors corresponding with company names
            $msql = "select d_major from DesiredMajors
                      where c_name = '" . $cname . "'
                      order by d_major";
          } else {
            $msql = "select d_major from DesiredMajors
                      where c_name = '" . $cname . "'
                      order by d_major desc";
          }

          $mresult = $con->query($msql);
          
          if($mresult->num_rows > 0){
            echo "<br><table><tr><th>Majors</th></tr>";
            while($mrow = $mresult->fetch_assoc()){
              echo "<tr><td>" . $mrow["d_major"] . "</td></tr>";
            }

            echo "</table>";

          } else {
            echo "<p>No results!</p>";
          }
        }

        #Display reps associated with the company
        if($smode == "rep"){
          if($ssort == "asc"){
            #Get rep info by company name
            $rsql = "select r_fname, r_lname, r_email, r_phone
                      from Representatives
                      where c_name = '" . $cname . "'
                      order by r_fname";
          } else {
            $rsql = "select r_fname, r_lname, r_email, r_phone
                      from Representatives
                      where c_name = '" . $cname . "'
                      order by r_fname desc";
          }

          $rresult = $con->query($rsql);

          if($rresult->num_rows > 0){
            echo "<br><table><tr><th>First Name</th><th>Last Name</th>
                  <th>Email</th><th>Phone</th></tr>";
            while($rrow = $rresult->fetch_assoc()){
              echo "<tr><td>" . $rrow["r_fname"] . "</td>
                    <td>" . $rrow["r_lname"] . "</td>
                    <td>" . $rrow["r_email"] . "</td>
                    <td>" . $rrow["r_phone"] . "</td></tr>";
            }

            echo "</table>";

          } else {
            echo "<p>No results!</p>";
          }
        }

        #Display locations associated with the company
        if($smode == "loc"){
          if($ssort == "asc"){
            #Get the locations tied to a company name
            $lsql = "select l_address, l_phone, l_nintern, l_nfulltime
                      from HiringLocations
                      where c_name = '" . $cname . "'
                      order by l_nintern, l_nfulltime";
          } else {
            $lsql = "select l_address, l_phone, l_nintern, l_nfulltime
                      from HiringLocations
                      where c_name = '" . $cname . "'
                      order by l_nintern, l_nfulltime desc";
          }

          $lresult = $con->query($lsql);

          if($lresult->num_rows > 0){
            echo "<br><table><tr><th>Address</th><th>Phone</th>
                  <th>Internships</th><th>Full Time Positions</th></tr>";
            while($lrow = $lresult->fetch_assoc()){
              echo "<tr><td>" . $lrow["l_address"] . "</td>
                    <td>" . $lrow["l_phone"] . "</td>
                    <td>" . $lrow["l_nintern"] . "</td>
                    <td>" . $lrow["l_nfulltime"] . "</td></tr>";
            }

            echo "</table>";

          } else {
            echo "<p>No results!</p>";
          }
        }
      }

      #Check if the user is searching reps
      $srterm = $_POST["srterm"];
      if(isset($srterm)){
        $smode = $_POST["category"];
        $ssort = $_POST["sort"];
        
        echo "<p style=\"margin-top:-50px\"><b>Representative</b></p><br>";

        #Search by first name
        if($smode == "fname"){
          if($ssort == "asc"){
            #Get all rep info using first name
            $repsql = "select r_fname, r_lname, r_email, r_phone, c_name
                        from Representatives
                        where r_fname = '" . $srterm . "'
                        order by r_fname";
          } else {
            $repsql = "select r_fname, r_lname, r_email, r_phone, c_name
                        from Representatives
                        where r_fname = '" . $srterm . "'
                        order by r_fname desc ";
          }
        }

        #Search by lname
        if($smode == "lname"){
          if($ssort == "asc"){
            #Get all rep info using last name
            $repsql = "select r_fname, r_lname, r_email, r_phone, c_name
                        from Representatives
                        where r_lname = '" . $srterm . "'
                        order by r_fname";
          } else {
            $repsql = "select r_fname, r_lname, r_email, r_phone, c_name
                        from Representatives
                        where r_lname = '" . $srterm . "'
                        order by r_fname desc";
          }
        }

        #Search by company
        if($smode == "com"){
          if($ssort == "asc"){
            #Get all rep info using company
            $repsql = "select r_fname, r_lname, r_email, r_phone, c_name
                        from Representatives
                        where c_name = '" . $srterm . "'
                        order by r_fname";
          } else {
            $repsql = "select r_fname, r_lname, r_email, r_phone, c_name
                        from Representatives
                        where c_name = '" . $srterm . "'
                        order by r_fname desc";
          }
        }

        $represult = $con->query($repsql);
          
        if($represult->num_rows > 0){
          echo "<br><table><tr><th>First Name</th><th>Last Name</th>
                  <th>Email</th><th>Phone</th><th>Company</th></tr>";
          while($reprow = $represult->fetch_assoc()){
            echo "<tr><td>" . $reprow["r_fname"] . "</td>
                  <td>" . $reprow["r_lname"] . "</td>
                  <td>" . $reprow["r_email"] . "</td>
                  <td>" . $reprow["r_phone"] . "</td>
                  <td>" . $reprow["c_name"] . "</td></tr>";
          }

          echo "</table>";

        } else {
          echo "<p>No results!</p>";
        }
      }

      #See if the user is searching for majors
      $mjrname = $_POST["majorname"];
      if(isset($mjrname)){
        $smode = $_POST["category"];
        $ssort = $_POST["sort"];
        
        echo "<p style=\"margin-top:-50px\"><b>" . $mjrname . "</b></p><br>";

        if($smode == "com"){
          if($ssort == "asc"){
            #Get the company looking for the major and how many locations they have
            $mjrsql = "select dm.c_name, p_count
                        from DesiredMajors as dm inner join (
                            select hp.c_name, count(*) as p_count
                            from HiringLocations as hp
                            group by hp.c_name
                          ) t on dm.c_name = t.c_name
                        where d_major = '" . $mjrname . "'
                        order by dm.c_name";
          } else {
            $mjrsql = "select dm.c_name, p_count
                        from DesiredMajors as dm inner join (
                            select hp.c_name, count(*) as p_count
                            from HiringLocations as hp
                            group by hp.c_name
                          ) t on dm.c_name = t.c_name
                        where d_major = '" . $mjrname . "'
                        order by dm.c_name desc";
          }
        }

        if($smode == "pos"){
          #Same as above, but sorted by number
          if($ssort == "asc"){
            $mjrsql = "select dm.c_name, p_count
                        from DesiredMajors as dm inner join (
                            select hp.c_name, count(*) as p_count
                            from HiringLocations as hp
                            group by hp.c_name
                          ) t on dm.c_name = t.c_name
                        where d_major = '" . $mjrname . "'
                        order by p_count";
          } else {
            $mjrsql = "select dm.c_name, p_count
                        from DesiredMajors as dm inner join (
                            select hp.c_name, count(*) as p_count
                            from HiringLocations as hp
                            group by hp.c_name
                          ) t on dm.c_name = t.c_name
                        where d_major = '" . $mjrname . "'
                        order by p_count desc";
          }
        }

        $mjrresult = $con->query($mjrsql);
          
        if($mjrresult->num_rows > 0){
          echo "<br><table><tr><th>Company</th><th>Hiring Locations</th></tr>";
          while($mjrrow = $mjrresult->fetch_assoc()){
            echo "<tr><td>" . $mjrrow["c_name"] . "</td>
                  <td>" . $mjrrow["p_count"] . "</td></tr>";
          }

          echo "</table>";

        } else {
          echo "<p>No results!</p>";
        }
      }

      #A company list
      $clist = $_POST["clist"];
      if(isset($clist)){
        $ssort = $_POST["sort"];

        echo "<p style=\"margin-top:-50px\"><b>Companies</b></p><br>";

        #Collect all the general company info, then append the number of location and the number of open jobs
        $clistsql = "select cw.c_name, c_website, c_email, l_count, i_count, f_count
                      from CompanyWebsites as cw inner join
                        CompanyEmails as ce on cw.c_name = ce.c_name
                        inner join (
                          select hl.c_name, count(*) as l_count,
                            sum(l_nintern) as i_count,
                            sum(l_nfulltime) as f_count
                          from HiringLocations as hl
                          group by hl.c_name
                        ) t on cw.c_name = t.c_name ";

        if($clist == "name"){
          $clistsql2 = $clistsql . "order by cw.c_name";
        }

        if($clist == "loc"){
          $clistsql2 = $clistsql . "order by l_count";
        }

        if($clist == "int"){
          $clistsql2 = $clistsql . "order by i_count";
        }

        if($clist == "ftp"){
          $clistsql2 = $clistsql . "order by f_count";
        }

        if($ssort = "des"){
          $clistsql3 = $clistsql2 . " desc";
        } else {
          $clistsql3 = $clistsql2;
        }

        $clistresult = $con->query($clistsql3);

        if($clistresult->num_rows > 0){
          echo "<br><table><tr><th>Name</th><th>Website</th>
                <th>Email</th><th>Locations #</th><th>Internships #</th>
                <th>Full Time #</th></tr>";
          while($clistrow = $clistresult->fetch_assoc()){
            echo "<tr><td>" . $clistrow["c_name"] . "</td>
                  <td>" . $clistrow["c_website"] . "</td>
                  <td>" . $clistrow["c_email"] . "</td>
                  <td>" . $clistrow["l_count"] . "</td>
                  <td>" . $clistrow["i_count"] . "</td>
                  <td>" . $clistrow["f_count"] . "</td></tr>";
          }

          echo "</table>";

        } else {
          echo "<p>No results!</p>";
        }
      }

      #A representative list
      $rlist = $_POST["rlist"];
      if(isset($rlist)){
        $ssort = $_POST["sort"];

        echo "<p style=\"margin-top:-50px\"><b>Representatives</b></p><br>";

        #Get basic info for each rep
        $rlistsql = "select r_fname, r_lname, r_email, r_phone, c_name
                      from Representatives ";

        if($rlist == "fname"){
          $rlistsql2 = $rlistsql . "order by r_fname";
        }

        if($rlist == "lname"){
          $rlistsql2 = $rlistsql . "order by r_lname";
        }

        if($rlist == "com"){
          $rlistsql2 = $rlistsql . "order by c_name";
        }

        if($ssort == "des"){
          $rlistsql3 = $rlistsql2 . " desc";
        } else {
          $rlistsql3 = $rlistsql2;
        }

        $rlistresult = $con->query($rlistsql3);

        if($rlistresult->num_rows > 0){
          echo "<br><table><tr><th>First Name</th><th>Last Name</th>
                <th>Email</th><th>Phone</th><th>Company</th></tr>";
          while($rlistrow = $rlistresult->fetch_assoc()){
            echo "<tr><td>" . $rlistrow["r_fname"] . "</td>
                  <td>" . $rlistrow["r_lname"] . "</td>
                  <td>" . $rlistrow["r_email"] . "</td>
                  <td>" . $rlistrow["r_phone"] . "</td>
                  <td>" . $rlistrow["c_name"] . "</td></tr>";
          }

          echo "</table>";

        } else {
          echo "<p>No results!</p>";
        }
      }

      #A major list
      $mlist = $_POST["mlist"];
      if(isset($mlist)){
        $ssort = $_POST["sort"];

        echo "<p style=\"margin-top:-50px\"><b>Majors</b></p><br>";

        #A simple count of each major
        $mlistsql = "select d_major, count(*) as m_count
                      from DesiredMajors
                      group by d_major " ;

        if($mlist == "mjr"){
          $mlistsql2 = $mlistsql . "order by d_major";
        }

        if($mlist == "com"){
          $mlistsql2 = $mlistsql . "order by m_count";
        }

        if($ssort == "des"){
          $mlistsql3 = $mlistsql2 . " desc";
        } else {
          $mlistsql3 = $mlistsql2;
        }

        $mlistresult = $con->query($mlistsql3);

        if($mlistresult->num_rows > 0){
          echo "<br><table><tr><th>Major</th><th>Number of Companies</th></tr>";
          while($mlistrow = $mlistresult->fetch_assoc()){
            echo "<tr><td>" . $mlistrow["d_major"] . "</td>
                  <td>" . $mlistrow["m_count"] . "</td></tr>";
          }

          echo "</table>";

        } else {
          echo "<p>No results!</p>";
        }
      }

      #Search students by major
      $rmjrname = $_POST["rmajorname"];
      if(isset($rmjrname)){
        $smode = $_POST["category"];
        $ssort = $_POST["sort"];

        echo "<p style=\"margin-top:-50px\"><b>Major: " . $rmjrname . "</b></p><br>";

        #Collect all student info based on major
        $mjrsql = "select sn.s_fname, sn.s_lname, se.s_email, sp.s_phone
                    from StudentNames as sn
                      inner join StudentEmails as se on sn.s_id = se.s_id
                      inner join StudentPhones as sp on sn.s_id = sp.s_id
                    where sn.s_major = '" . $rmjrname . "' ";

        if($smode == "fname"){
          $mjrsql2 = $mjrsql . "order by sn.s_fname";
        }

        if($smode == "lname"){
          $mjrsql2 = $mjrsql . "order by sn.s_lname";
        }

        if($ssort == "des"){
          $mjrsql3 = $mjrsql2 . " desc";
        } else {
          $mjrsql3 = $mjrsql2;
        }

        $mjrresult = $con->query($mjrsql3);

        if($mjrresult->num_rows > 0){
          echo "<br><table><tr><th>First Name</th><th>Last Name</th>
                <th>Email</th><th>Phone</th></tr>";
          while($mjrrow = $mjrresult->fetch_assoc()){
            echo "<tr><td>" . $mjrrow["s_fname"] . "</td>
                  <td>" . $mjrrow["s_lname"] . "</td>
                  <td>" . $mjrrow["s_email"] . "</td>
                  <td>" . $mjrrow["s_phone"] . "</td></tr>";
          }

          echo "</table>";

        } else {
          echo "<p>No results!</p>";
        }
      }

      #List of students
      $slist = $_POST["slist"];
      if(isset($slist)){
        $ssort = $_POST["sort"];

        echo "<p style=\"margin-top:-50px\"><b>Students</b></p><br>";

        #Combine all student info and return it
        $slistsql = "select sn.s_fname, sn.s_lname, se.s_email, sp.s_phone, sn.s_major
                    from StudentNames as sn
                      inner join StudentEmails as se on sn.s_id = se.s_id
                      inner join StudentPhones as sp on sn.s_id = sp.s_id ";

        if($slist == "fname"){
          $slistsql2 = $slistsql . "order by s_fname";
        }

        if($slist == "lname"){
          $slistsql2 = $slistsql . "order by s_lname";
        }

        if($slist == "mjr"){
          $slistsql2 = $slistsql . "order by s_major";
        }

        if($ssort == "des"){
          $slistsql3 = $slistsql2 . " desc";
        } else {
          $slistsql3 = $slistsql2;
        }

        $slistresult = $con->query($slistsql3);

        if($slistresult->num_rows > 0){
          echo "<br><table><tr><th>First Name</th><th>Last Name</th>
                <th>Email</th><th>Phone</th><th>Major</th></tr>";
          while($slistrow = $slistresult->fetch_assoc()){
            echo "<tr><td>" . $slistrow["s_fname"] . "</td>
                  <td>" . $slistrow["s_lname"] . "</td>
                  <td>" . $slistrow["s_email"] . "</td>
                  <td>" . $slistrow["s_phone"] . "</td>
                  <td>" . $slistrow["s_major"] . "</td></tr>";
          }

          echo "</table>";

        } else {
          echo "<p>No results!</p>";
        }
      }

      #List of users
      $ulist = $_POST["ulist"];
      if(isset($ulist)){
        echo "<p style=\"margin-top:-50px\"><b>User Entries</b></p><br>";

        #Selects all of the appropriate type of user
        if($ulist == "std"){
          $ulistsql = "select * from UserTypes where u_type = 'S'";
        }

        if($ulist == "rep"){
          $ulistsql = "select * from UserTypes where u_type = 'R'";
        }

        if($ulist == "all"){
          $ulistsql = "select * from UserTypes";
        }

        $ulistresult = $con->query($ulistsql);
        
        if($ulistresult->num_rows > 0){
          echo "<br><table><tr><th>User Id</th><th>User Type</th></tr>";
          while($ulistrow = $ulistresult->fetch_assoc()){
            echo "<tr><td>" . $ulistrow["u_id"] . "</td>
                  <td>" . $ulistrow["u_type"] . "</td></tr>";
          }

          echo "</table>";
        
        } else {
          echo "<p>No results!</p>";
        } 
      }

      #Delete user
      $delid = $_POST["delid"];
      if(isset($delid)){
        #Ensure that the user exists
        $usercheck = "select * from UserTypes where u_id = '" . $delid . "'";
        $ucresult = $con->query($usercheck);
        if($ucresult->num_rows == 0){
          echo "<p>Error! User not found.</p>";
          exit();
        }

        #Make sure the admin isn't getting deleted
        $ucrow = $ucresult->fetch_assoc();
        if($ucrow["u_type"] == 'A'){
          echo "<p>Error! Cannot delete admin.</p>";
          exit();
        }

        if($ucrow["u_type"] == 'S'){
          $delsql = array(
                      #Remove all traces of a student
                      "delete from StudentNames where s_id = '" . $delid . "'",
                      "delete from UserTypes where u_id = '" . $delid . "'"
                    );

          for($i = 0; $i < 2; $i++){
            if($con->query($delsql[$i]) == FALSE){
              echo "<p>Error! Could not delete user.</p>";
              exit();
            }
          }
        }

        if($ucrow["u_type"] == 'R'){
          #Check the number of representatives that a company has
          $companycheck = "select count(*) from Representatives as r1
                            where r1.c_name = (
                              select r2.c_name from Representatives as r2
                                where r2.r_id = '" . $delid . "'
                            )";

          #If the last one is getting deleted, delete the company too
          if($con->query($companycheck)->num_rows == 1){
            $delcom = "delete cw from CompanyWebsites as cw
                        where cw.c_name = (
                          select r.c_name from Representatives as r
                            where r.r_id = '" . $delid . "'
                        )";

            if($con->query($delcom) == FALSE){
              echo "<p>Error! Failed to delete company.</p>";
              exit();
            }

            echo "<p>Deleted company with no representatives left.</p>";

          } else {
            #Delete the rep from the Representatives table
            $delrep = "delete from Representatives where r_id = '" . $delid . "'";
                      
            if($con->query($delrep) == FALSE){
              echo "<p>Error! Failed to delete representative.</p>";
              exit();
            }
          }            

          #Delete the rep from the user tables
          $delusr = "delete from UserTypes where u_id = '" . $delid . "'";

          if($con->query($delusr) == FALSE){
            echo "<p>Error! Could not delete user.</p>";
            exit();
          }

        }

        echo "<p>User deleted successfully.</p>";
      }

      /*#Database backup
      if(isset($_POST["backup"])){
        $path = "/var/lib/mysql-files/";
        $bcksql = array(
                    #Store the contents of each tables in an external file
                    "select * into outfile '" . $path . "bck_companywebsites.txt' from CompanyWebsites",
                    "select * into outfile '" . $path . "bck_companyemails.txt' from CompanyEmails",
                    "select * into outfile '" . $path . "bck_desiredmajors.txt' from DesiredMajors",
                    "select * into outfile '" . $path . "bck_hiringlocations.txt' from HiringLocations",
                    "select * into outfile '" . $path . "bck_representatives.txt' from Representatives",
                    "select * into outfile '" . $path . "bck_studentnames.txt' from StudentNames",
                    "select * into outfile '" . $path . "bck_studentemails.txt' from StudentEmails",
                    "select * into outfile '" . $path . "bck_studentphones.txt' from StudentPhones",
                    "select * into outfile '" . $path . "bck_usertypes.txt' from UserTypes",
                    "use mysql",
                    "select * into outfile '" . $path . "bck_user' from user"
                  );

        for($i = 0; $i < 11; $i++){
          if($con->query($bcksql[$i]) == FALSE){
            echo "<p>Error! Failed to create backup.</p>";
            exit();
          }
        }

        echo "<p>Backup created successfully.</p>";
      }*/

      $con->close();
    ?>
  </div>
</body>
</html>
