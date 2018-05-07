<?php
$sql = "SELECT * FROM Student";
      foreach ($conn->query($sql) as $row) {
        print $row['studentid'] . "\t";
        print $row['last_name'] . "\t";
        print $row['first_name'] . "<br>";
      }
      
      
      echo "<br><br>";
      $sql = "SELECT * FROM Student WHERE Last_Name = ?";
      $query = $conn->prepare($sql);
      $query->execute(array("Murdock"));
      $count = $query->rowCount();
      print $count;
      echo "<br><br>";
      $result = $query->fetch(PDO::FETCH_ASSOC);
      //print_r($result);
      echo $result['studentid'];
      echo "<br>";
      echo $result['last_name'];
      
      
      echo "<br><br>";
      $var = "M%";
      $sql = "SELECT * FROM Student WHERE Last_Name LIKE ?";
      $query = $conn->prepare($sql);
      $query->execute(array($var));
                      
      $count = $query->rowCount();
      print $count;
      echo "<br>";
      
//      while($row = $query->fetch(PDO::FETCH_OBJ)) {
//         print $row->studentid . "\t";
//         print $row->last_name . "\t";
//         print $row->first_name . "<br>";
//      }
      //echo "<br><br>";
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
         print $row['studentid'] . "\t";
        print $row['last_name'] . "\t";
        print $row['first_name'] . "<br>";
       }



//      $sql = "SELECT * FROM Student WHERE Last_Name = $1 AND First_Name = $2";
//      $result = pg_prepare($dbconn, "select_query", $sql);
//      $result = pg_execute($dbconn, "select_query", array("Murdock", "Matt"));
//      $test = pg_fetch_array($result, NULL, PGSQL_ASSOC);
//      echo $test["studentid"];
//      
//      echo "<br>";
//      
//      $sql2 = "SELECT * FROM Student";
//      $result2 = pg_query($dbconn, $sql2);
//      
//      $html = '     
//      <table class="table table-striped tableBorder"><tbody>
//      <tr><td>StudentID</td><td>Last Name</td></tr>';
//      while ($row = pg_fetch_assoc($result2)) {
//         
//         $html .= 
//         '<tr>
//            <td>' .$row['studentid']. '</td>
//            <td>' .$row['last_name']. '</td>
//         </tr>';
//      }
//      $html .= '</tbody></table>';
//      print $html;
//      
//      $testVar = "pass";
//      $hashed = crypt($testVar);
//      echo $hashed; 
//      $testVar2 = "pass";
//      echo "<br>";
//      
//      if(password_verify($testVar2, $hashed))
//      {
//         echo "cool";
//      }
//         else {
//            echo "lame";
//         }
         
//            $conn_string = "host=dbase.dsa.missouri.edu dbname=s18dbmsgroups user=s18group08 password=MedOpp18";
//      $dbconn = pg_connect($conn_string);
//      $sql = "INSERT INTO Login VALUES ('test', $1)";
//      pg_prepare($dbconn, "insert_login", $sql);
//      $hashed = crypt("pass");
//      pg_execute($dbconn, "insert_login", array($hashed));
?>


//      echo "<h3>Research</h3>";
//      echo "<br>lab: ";
//      print_r($researchLabArray);
//      echo "<br>start: ";
//      print_r($researchStartArray);
//      echo "<br>End: ";
//      print_r($researchEndArray);
//      echo "<br>Lastname: ";
//      print_r($researchLastNameArray);
//      echo "<br>Firstname: ";
//      print_r($researchFirstNameArray);
//      echo "<br>Pos: ";
//      print_r($researchPosArray);
//      echo "<br>volunteer: ";
//      print_r($researchVolunteerArray);
//      echo "<br>hours: ";
//      print_r($researchHoursArray);
//      
//      echo "<h3>Work</h3>";
//      echo "<br>employer: ";
//      print_r($workEmployerArray);
//      echo "<br>pos: ";
//      print_r($workPositionArray);
//      echo "<br>start: ";
//      print_r($workStartArray);
//      echo "<br>end: ";
//      print_r($workEndArray);
//      echo "<br>hours: ";
//      print_r($workHoursArray);
//      echo "<br>healthcare: ";
//      print_r($workHealthcareArray);
//     
//      echo "<h3>Shadow</h3>";
//      echo "<br>lastname: ";
//      print_r($shadowLastNameArray);
//      echo "<br>firstname: ";
//      print_r($shadowFirstNameArray);
//      echo "<br>specialty: ";
//      print_r($shadowSpecialtyArray);
//      echo "<br>hours: ";
//      print_r($shadowHoursArray);
//      
//      echo "<h3>Volunteer</h3>";
//      echo "<br>org: ";
//      print_r($volunteerOrgArray);
//      echo "<br>start: ";
//      print_r($volunteerStartArray);
//      echo "<br>end: ";
//      print_r($volunteerEndArray);
//      echo "<br>hours: ";
//      print_r($volunteerHoursArray);
//      echo "<br>avg: ";
//      print_r($volunteerAvgArray);
//      echo "<br>healthcare: ";
//      print_r($volunteerHealthcareArray);
//      
//       echo "<h3>Abroad</h3>";
//      echo "<br>school: ";
//      print_r($abroadSchoolArray);
//      echo "<br>start: ";
//      print_r($abroadStartArray);
//      echo "<br>end: ";
//      print_r($abroadEndArray);
//      echo "<br>city: ";
//      print_r($abroadCityArray);
//      echo "<br>country: ";
//      print_r($abroadCountryArray);
//   
//      
//      
//       
//      exit;