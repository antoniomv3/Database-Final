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