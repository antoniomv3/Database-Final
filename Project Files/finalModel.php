<?php
class finalModel {
   public function __construct(){
   //On construction, the model will start the session and if logStatus is not set, meaning the browser is new to this page or the previous session expired, it will set it to false, meaning the user is not logged in. It will also check if there is a log error, if so it will print an error message when it reloads the login screen.
      session_start();
      if(!isset($_SESSION['logStatus'])) $_SESSION['logStatus'] = 'false';
      if(!isset($_SESSION['logError'])) $_SESSION['logError'] = 'No';
   }
   public function __destruct(){
   }
   
   public function preparePageContent($nav){
   //The functionality of this method is determined by the $nav value passed from the controller. It first creates the variables it will return, getting $logStatus from session to maintain dynamic status no matter where the user redirects on the page, then manipulates those variables based on the value of 'nav'.  
      $formOptions = '';
      $logStatus = $_SESSION['logStatus'];
      $tableData = '';
      $recordData = '';
      $source = '';
      $studentSchools = '';
      $studentData = '';
      
      //If the on page back button is used in a record, this points the page back to the master list of that type of record. 
      if($group = $_GET['group']) {
         switch($group) {
         case "Student":
            $nav = 'allStudents';
            break;
         case "MedOpp Advisor":
            $nav = 'allAdvisors';
            break;
         case "Letter Writer":
            $nav = 'allWriters';
            break;
         }
      }
      
      //Checks if the user is logged in, if not they can only go to the login page.
      if($_SESSION['logStatus'] === 'false') {
         $source = 'loginContent';
      } else {
         switch($nav){
               
            case "logout":
            //This logs out the user, updates $logStatus so that it now shows false, then shoots the user back to the login page.
               $this->processLogout();
               $logStatus = $_SESSION['logStatus'];
               $source = 'loginContent';
               break;
               
            case "home":
               $source = 'homeContent';
               break;
               
            case "search":
               $source = 'viewContent';
               list($tableData, $tableSource) = $this->processSearch();
               break;
               
            case "allStudents":
               $source = 'viewContent';
               $tableSource = 'students';
               $tableData = $this->prepareAll($tableSource);
               break;
               
            case "allAdvisors":
               $source = 'viewContent';
               $tableSource = 'advisors';
               $tableData = $this->prepareAll($tableSource);
               break;
               
            case "allWriters":
               $source = 'viewContent';
               $tableSource = 'writers';
               $tableData = $this->prepareAll($tableSource);
               break;
               
            case "allMembers":
               $source = 'viewContent';
               $tableSource = 'committee members';
               $tableData = $this->prepareAll($tableSource);
               break;
               
            case "selectRecord":
               $source = 'recordContent';
               list($recordData, $group, $tableData, $tableSource, $studentData) = $this->prepareRecordData('select');
               break;
              
            case "deleteRecord": //WORKING ON THIS STILL
               $source = 'viewContent';
               $tableSource = $this->deleteRecordData();
               $tableData = $this->prepareAll($tableSource);
               break;
               
            case "editRecord":
               $source = 'formContent';
               list($recordData, $group, $tableData, $tableSource, $studentData) = $this->prepareRecordData('update');
               $formOptions = $this->prepareEditFormOptions();
               break;
               
            case "submitForm":
               $this->submitForm();
               $source = 'viewContent';
               $tableSource = 'students';
               $tableData = $this->prepareAll($tableSource);
               break;
               
            case "newStudent":
               $source = 'formContent';
               $formOptions = $this->prepareFormOptions();
               break;
               
            default:
            //If anything not listed above is present in the nav variable, it will return it to the home page anyway.
               $source = 'homeContent';
               break;
         }
      }
      return array($source, $formOptions, $logStatus, $tableData, $recordData, $group, $studentSchools, $tableSource, $studentData);
   }
 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   public function processLogin() {
   //This function logs the user in, first it connects to the database, then it obtains the username and password variables from the post data. It sees if there is a user in the database matching the username variable inputted. If so, it then compares the passwords and on a match sets the session log status to true. If not it sets it to false. It then frees the results and closes the connection, and returns the logStatus obtained from the session. 
      $username = $_POST['username'];
      $password = $_POST['pwd'];
      
      $conn = $this->initDatabaseConnection();
         
      if(!$query = $conn->prepare("SELECT * FROM Login WHERE username = ?")){
         echo "There was an error processing the login! Try again!";
         exit;     
      }
      
      $query->execute(array($username));
      $count = $query->rowCount();
      
      if($count === 1) {
         $result = $query->fetch(PDO::FETCH_ASSOC);
         if(password_verify($password, $result['password'])) {
            $_SESSION['logStatus'] = 'true';
            $_SESSION['logError'] = 'No';
         } else {
            $_SESSION['logStatus'] = 'false';
            $_SESSION['logError'] = 'Yes';
         }
      } else {
         $_SESSION['logStatus'] = 'false';
         $_SESSION['logError'] = 'Yes';
      }
      
      $conn = null;
      $query = null;
      $result = null;
      
      return $_SESSION['logStatus'];
   }
   
   private function processLogout(){
      $_SESSION['logStatus'] = 'false';
   }
 
   private function initDatabaseConnection() {
      $dsn = "pgsql:dbname=s18dbmsgroups;host=dbase.dsa.missouri.edu";
      $user = "s18group08";
      $password = "MedOpp18";
      
      try {
         $conn = new PDO($dsn, $user, $password);
      } catch (PDOException $e) {
         echo 'Connection failed: ' . $e->getMessage();
         exit;
      }      
      
      return $conn;
}
   
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   public function processSearch() {
      $searchBy = $_POST['searchBy'];
      $searchText = $_POST['searchText'];
      $tableSource = '';
      
      switch($searchBy) {
         case 'StudentID':
            $tableSource = 'students';
            break;
         case 'Student_Last_Name':
            $tableSource = 'students';
            $searchBy = 'Last_Name';
            break;
         default:
      }
      
      $conn = $this->initDatabaseConnection();
      
      $sql = "SELECT StudentID, Last_Name, First_Name FROM Student WHERE $searchBy = ?";
      $query = $conn->prepare($sql);
      $query->execute(array($searchText));
      $count = $query->rowCount();
     
      if($count === 0) {
         $tableData = '<tr><td colspan="4">No search results at this time!</td></tr>';
         $tableSource = 'noResult';
         return array($tableData, $tableSource);
      }
      
      $tableData = '';
      
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $tableData .= '<tr><td><a class="recordSelect" href="#">' .$row['studentid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td>
         <td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a><a href="#"><img class="iconBorder deleteIcon" src="Images/open-iconic/png/trash-2x.png" alt="Delete Icon"></a></td></tr>';
      }
      
      $conn = null;
      $query = null;
      $row = null;
      
      return array($tableData, $tableSource);
   } 
   
   
   
   //Prepares all records in a given category such as 'student' or 'advisor'
   public function prepareAll($tableSource) {
      $conn = $this->initDatabaseConnection();
      $id = '';
      
      switch($tableSource) {
         case 'students':
            $sql = "SELECT StudentID, Last_Name, First_Name FROM Student ORDER BY Last_Name ASC";
            $id = 'studentid';
            break;
         case 'advisors':
            $sql = "SELECT AdvisorID, Last_Name, First_Name FROM MedOpp_Advisor ORDER BY AdvisorID ASC";
            $id = 'advisorid';
            break;
         case 'writers':
            $sql = "SELECT WriterID, Last_Name, First_Name FROM Letter_Writer ORDER BY Last_Name ASC";
            $id = 'writerid';
            break;
         case 'committee members':
            $sql = "SELECT InterviewerID, Last_Name, First_Name FROM Interviewer ORDER BY Last_Name ASC";
            $id = 'interviewerid';
            break;
      }
      
      
      $query = $conn->prepare($sql);
      $query->execute();
      $count = $query->rowCount();
     
      if($count === 0) {
         $tableData = '<tr><td colspan="4">There are no ';
         $tableData .= $tableSource;
         $tableData .= ' in the database at this time!</td></tr>';
         return $tableData;
      }
      
      $tableData = '';
      if($tableSource === 'advisors') {
         while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $tableData .= '<tr><td><a class="recordSelect" href="#">' .$row[$id]. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td>
            <td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a></td></tr>';
         }
      } else {
         while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $tableData .= '<tr><td><a class="recordSelect" href="#">' .$row[$id]. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td>
            <td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a><a href="#"><img class="iconBorder deleteIcon" src="Images/open-iconic/png/trash-2x.png" alt="Delete Icon"></a></td></tr>';
         }
      } 
      
      $conn = null;
      $query = null;
      $row = null;
      
      return $tableData;
   } 
   
   
   private function prepareRecordData($formStatus) {
   //This function grabs all the information in individual records. recordData creates the information cards, while tableData creates a list of the students that individual is responsible for. 
   
      $conn = $this->initDatabaseConnection();
   
      $ID = $_POST['ID'];
      $groupSelect = $_POST['groupSelect'];
      $group = '';
      $sql = '';
      $sql2 = '';
      $tableData = '';
      $tableSource = '';
      $studentData = array();
      
      
      switch($groupSelect) {
         case 'Advisor List':
            $sql = "SELECT * FROM MedOpp_Advisor WHERE AdvisorID = ?";
            $sql2 = "SELECT StudentID, Last_Name, First_Name FROM Student WHERE AdvisorID = ? ORDER BY Last_Name ASC";
            $group = 'MedOpp Advisor';
            $tableSource = 'students';
            
            $query = $conn->prepare($sql2);
            $query->execute(array($ID));
            $count = $query->rowCount();
     
            if($count === 0) {
                $tableData = '<tr><td colspan="4">There are no students in the database at this time!</td></tr>';
            } else {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $tableData .= '<tr><td><a class="recordSelect" href="#">' .$row['studentid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td><td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a><a href="#"><img class="iconBorder deleteIcon" src="Images/open-iconic/png/trash-2x.png" alt="Delete Icon"></a></td></tr>';
               }
            }
            break;
            
            
            
            
            
            
            
            
            
            
         case 'Student List':
            $sql = "SELECT S.*, MA.Last_Name AS MA_Last_Name, MA.First_Name AS MA_First_Name
                     FROM Student S JOIN MedOpp_Advisor MA ON S.AdvisorID = MA.AdvisorID WHERE S.StudentID = ? ";
            $honorsSQL = "SELECT * FROM Honors_Info WHERE StudentID = ?";
            $languageSQL = "SELECT Language FROM Language_Fluency WHERE StudentID = ?";
            $degreeSQL = "SELECT Degree, Academic_Program FROM Academic_Plan WHERE StudentID = ?";
            $extraSQL = "SELECT * FROM Extra_Curricular WHERE StudentID = ? ORDER BY Start_Date";
            $leadershipSQL = "SELECT * FROM Leadership_Position WHERE StudentID = ? ORDER BY Start_Date";
            $stugroupsSQL = "SELECT * FROM Student_Groups WHERE StudentID = ? ORDER BY Start_Date";
            $abroadSQL = "SELECT * FROM Study_Abroad WHERE StudentID = ? ORDER BY Start_Date";
            $workSQL = "SELECT * FROM Work_Experience WHERE StudentID = ? ORDER BY Start_Date";
            $volunteerSQL = "SELECT * FROM Volunteer_Experience WHERE StudentID = ? ORDER BY Start_Date";
            $shadowSQL = "SELECT * FROM Shadow_Experience WHERE StudentID = ?";
            $researchSQL = "SELECT * FROM Research WHERE StudentID = ? ORDER BY Start_Date";
            $hptestSQL = "SELECT * FROM Health_Profession_Test WHERE StudentID = ? ORDER BY Test_Date";
            $hpschoolSQL = "SELECT * FROM Health_Profession_School WHERE StudentID = ?";
            $eventSQL = "SELECT * FROM Event WHERE StudentID = ? ORDER BY Date_Completed";
            $writerSQL = "SELECT LW.WriterID, LW.Last_Name, LW.First_Name, LJ.Reception_Date FROM Letter_Join LJ JOIN Letter_Writer LW ON LJ.WriterID = LW.WriterID AND LJ.StudentID = ? ORDER BY LW.Last_Name ASC";
            $interviewSQL = "SELECT * FROM Interview WHERE StudentID = ?";
            $interviewerSQL = "SELECT I.interviewerid, I.first_name, I.last_name FROM Interview_Join AS IJ JOIN Interviewer AS I ON IJ.InterviewerID = I.InterviewerID WHERE StudentID = ?";
            $group = 'Student';
         
            
            $query = $conn->prepare($honorsSQL);
            $query->execute(array($ID));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $studentData['participating'] = $row['participating'];
            $studentData['credit_hours'] = $row['credit_hours'];
            $studentData['course_count'] = $row['course_count'];
                   
            
            $query = $conn->prepare($languageSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select') {
               if($count === 0) {
                  $studentData['languageData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no language data for this student in the database at this time!</td></tr></tbody></table>';
               } else {
                  while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                     $studentData['languageData'] .= '<tr><td>' .$row['language']. '</td></tr>';
                  }
               }
            }
            
            $i = 1;
            if($formStatus == 'update') {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['languageData'] .= '<tr><td><input type="text" class="form-control" name="Language[]" value="'.$row['language'].'"></td><td class="text-right"><a href="#"><img class="iconBorder languageAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                  $studentData['languageData'] .= '<tr id="languageRow'.$i.'"><td><input type="text" class="form-control" name="Language[]" value="'.$row['language'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }
            }
            
      
               
               
            $query = $conn->prepare($degreeSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select') {
            if($count === 0) {
                $studentData['degreeData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no degree data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['degreeData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Academic Program</th>
                        <th>Degree</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['degreeData'] .= '<tr><td>' .$row['academic_program']. '</td><td>' .$row['degree']. '</td></tr>';
               }
               $studentData['degreeData'] .= '</tbody></table>';
            }
            }
            
            $i = 1; 
           
            if($formStatus == 'update') {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['degreeData'] .= '<tr><td><input type="text" class="form-control" id="academicprogram" name="Academic_Program[]" value="'.$row['academic_program'].'"></td><td><input type="text" class="form-control" id="degree" name="Degree[]" value="'.$row['degree'].'"></td><td class="text-right"><a href="#"><img class="iconBorder academicAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['degreeData'] .= '<tr id="academicRow'.$i.'"><td><input type="text" class="form-control" id="academicprogram" name="Academic_Program[]" value="'.$row['academic_program'].'"></td><td><input type="text" class="form-control" id="degree" name="Degree[]" value="'.$row['degree'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }  
            }
           
            $query = $conn->prepare($extraSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select'){
            if($count === 0) {
                $studentData['extraData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no extra-curricular data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['extraData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Organization</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['extraData'] .= '<tr><td>' .$row['organization']. '</td><td>' .$row['start_date']. '</td><td>' .$row['end_date']. '</td></tr>';
               }
               $studentData['extraData'] .= '</tbody></table>';
            }
            }
         
            if($formStatus == 'update') {
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['extraData'] .= '<tr><td><input type="text" class="form-control" id="extraorg" name="Extra_Org[]" value="'.$row['organization'].'"></td><td><input type="text" class="form-control" id="extrastart" name="Extra_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" id="extraend" name="Extra_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder extraAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['extraData'] .= '<tr id="extraRow'.$i.'"><td><input type="text" class="form-control" id="extraorg" name="Extra_Org[]" value="'.$row['organization'].'"></td><td><input type="text" class="form-control" id="extrastart" name="Extra_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" id="extraend" name="Extra_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }  
            }
            
            $query = $conn->prepare($stugroupsSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select'){
            if($count === 0) {
                $studentData['stugroupsData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no student group data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['stugroupsData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Organization</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['stugroupsData'] .= '<tr><td>' .$row['group_name']. '</td><td>' .$row['start_date']. '</td><td>' .$row['end_date']. '</td></tr>';
               }
               $studentData['stugroupsData'] .= '</tbody></table>';
            }
            }
            if($formStatus == 'update') {
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['stugroupsData'] .= '<tr><td><input type="text" class="form-control" id="grouporg" name="Group_Org[]" value="'.$row['group_name'].'"></td><td><input type="text" class="form-control" id="groupstart" name="Group_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" id="groupend" name="Group_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder groupAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['stugroupsData'] .= '<tr id="groupRow'.$i.'"><td><input type="text" class="form-control" id="grouporg" name="Group_Org[]" value="'.$row['group_name'].'"></td><td><input type="text" class="form-control" id="groupstart" name="Group_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" id="groupend" name="Group_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }     
            }
            
            $query = $conn->prepare($leadershipSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select'){
            if($count === 0) {
                $studentData['leadershipData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no leadership data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['leadershipData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Organization</th>
                        <th>Position</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['leadershipData'] .= '<tr><td>' .$row['organization']. '</td><td>' .$row['position']. '</td><td>' .$row['start_date']. '</td><td>' .$row['end_date']. '</td></tr>';
               }
               $studentData['leadershipData'] .= '</tbody></table>';
            }
            }
            if($formStatus == 'update'){
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['leadershipData'] .= '<tr><td><input type="text" class="form-control" id="leaderorg" name="Leader_Org[]" value="'.$row['organization'].'"></td><td><input type="text" class="form-control" id="leaderpos" name="Leader_Pos[]" value="'.$row['position'].'"></td><td><input type="text" class="form-control" id="leaderstart" name="Leader_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" id="leaderend" name="Leader_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder leaderAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['leadershipData'] .= '<tr id="leaderRow'.$i.'"><td><input type="text" class="form-control" id="leaderorg" name="Leader_Org[]" value="'.$row['organization'].'"></td><td><input type="text" class="form-control" id="leaderpos" name="Leader_Pos[]" value="'.$row['position'].'"></td><td><input type="text" class="form-control" id="leaderstart" name="Leader_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" id="leaderend" name="Leader_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }        
            }
            
            $query = $conn->prepare($abroadSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select'){
            if($count === 0) {
                $studentData['abroadData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no study abroad data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['abroadData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>School Abroad</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['abroadData'] .= '<tr><td>' .$row['school_abroad']. '</td><td>' .$row['city']. '</td><td>' .$row['country']. '</td><td>' .$row['start_date']. '</td><td>' .$row['end_date']. '</td></tr>';
               }
               $studentData['abroadData'] .= '</tbody></table>';
            }
            }
            if($formStatus == 'update'){
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['abroadData'] .= '<tr><td><input type="text" class="form-control" name="Abroad_School[]" value="'.$row['school_abroad'].'"></td><td><input type="text" class="form-control" name="Abroad_City[]" value="'.$row['city'].'"></td><td><input type="text" class="form-control" name="Abroad_Country[]" value="'.$row['country'].'"></td><td><input type="text" class="form-control" name="Abroad_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" name="Abroad_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder abroadAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['abroadData'] .= '<tr id="abroadRow'.$i.'"><td><input type="text" class="form-control" name="Abroad_School[]" value="'.$row['school_abroad'].'"></td><td><input type="text" class="form-control" name="Abroad_City[]" value="'.$row['city'].'"></td><td><input type="text" class="form-control" name="Abroad_Country[]" value="'.$row['country'].'"></td><td><input type="text" class="form-control" name="Abroad_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" name="Abroad_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }   
            }
            
            
            $query = $conn->prepare($workSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select'){
            if($count === 0) {
                $studentData['workData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no work data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['workData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Employer</th>
                        <th>Position</th>
                        <th>Hours Per Week</th>
                        <th>Health Care Related?</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['workData'] .= '<tr><td>' .$row['employer']. '</td><td>' .$row['position']. '</td><td>' .$row['hours_per_week']. '</td><td>' .$row['healthcare_related']. '</td><td>' .$row['start_date']. '</td><td>' .$row['end_date']. '</td></tr>';
               }
               $studentData['workData'] .= '</tbody></table>';
            }
            }
            if($formStatus == 'update'){
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['workData'] .= '<tr><td><input type="text" class="form-control" name="Work_Employer[]" value="'.$row['employer'].'"></td><td><input type="text" class="form-control" name="Work_Pos[]" value="'.$row['position'].'"></td><td><input type="text" class="form-control" name="Work_Hours[]" value="'.$row['hours_per_week'].'"></td><td><select id="choice" class="form-control" name="Work_Healthcare[]"><option value="'.$row['healthcare_related'].'" selected>'.$row['healthcare_related'].'</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><input type="text" class="form-control" name="Work_Start[]" value="'.$row['start_date'].'">   </td><td><input type="text" class="form-control" name="Work_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder workAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['workData'] .= '<tr id="workRow'.$i.'"><td><input type="text" class="form-control" name="Work_Employer[]" value="'.$row['employer'].'"></td><td><input type="text" class="form-control" name="Work_Pos[]" value="'.$row['position'].'"></td><td><input type="text" class="form-control" name="Work_Hours[]" value="'.$row['hours_per_week'].'"></td><td><select id="choice" class="form-control" name="Work_Healthcare[]"><option value="'.$row['healthcare_related'].'" selected>'.$row['healthcare_related'].'</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><input type="text" class="form-control" name="Work_Start[]" value="'.$row['start_date'].'">   </td><td><input type="text" class="form-control" name="Work_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }      
            }
            
            $query = $conn->prepare($volunteerSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select') {
            if($count === 0) {
                $studentData['volunteerData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no volunteer data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['volunteerData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Organization</th>
                        <th>Total Hours</th>
                        <th>Average Hours Per Week</th>
                        <th>Health Care Related?</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['volunteerData'] .= '<tr><td>' .$row['organization']. '</td><td>' .$row['total_hours']. '</td><td>' .$row['hours_per_week']. '</td><td>' .$row['healthcare_related']. '</td><td>' .$row['start_date']. '</td><td>' .$row['end_date']. '</td></tr>';
               }
               $studentData['volunteerData'] .= '</tbody></table>';
            }
            }
            if($formStatus == 'update'){
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['volunteerData'] .= '<tr><td><input type="text" class="form-control" name="Volunteer_Org[]" value="'.$row['organization'].'"></td><td><input type="text" class="form-control" name="Volunteer_Hours[]" value="'.$row['total_hours'].'"></td><td><input type="text" class="form-control" name="Volunteer_Avg[]" value="'.$row['hours_per_week'].'"></td><td><select id="choice" class="form-control" name="Volunteer_Healthcare[]"><option value="'.$row['healthcare_related'].'" selected>'.$row['healthcare_related'].'</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><input type="text" class="form-control" name="Volunteer_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" name="Volunteer_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder volunteerAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['volunteerData'] .= '<tr id="volunteerRow'.$i.'"><td><input type="text" class="form-control" name="Volunteer_Org[]" value="'.$row['organization'].'"></td><td><input type="text" class="form-control" name="Volunteer_Hours[]" value="'.$row['total_hours'].'"></td><td><input type="text" class="form-control" name="Volunteer_Avg[]" value="'.$row['hours_per_week'].'"></td><td><select id="choice" class="form-control" name="Volunteer_Healthcare[]"><option value="'.$row['healthcare_related'].'" selected>'.$row['healthcare_related'].'</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><input type="text" class="form-control" name="Volunteer_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" name="Volunteer_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }  
            }
            
            $query = $conn->prepare($shadowSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select'){
            if($count === 0) {
                $studentData['shadowData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no shadow data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['shadowData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Physician Last Name</th>
                        <th>Physician First Name</th>
                        <th>Specialty</th>
                        <th>Total Hours</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['shadowData'] .= '<tr><td>' .$row['physician_last_name']. '</td><td>' .$row['physician_first_name']. '</td><td>' .$row['specialty']. '</td><td>' .$row['total_hours']. '</td></tr>';
               }
               $studentData['shadowData'] .= '</tbody></table>';
            }
            }
            if($formStatus == 'update') {
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['shadowData'] .= '<tr><td><input type="text" class="form-control" name="Shadow_Last_Name[]" value="'.$row['physician_last_name'].'"></td><td><input type="text" class="form-control" name="Shadow_First_Name[]" value="'.$row['physician_first_name'].'"></td><td><input type="text" class="form-control" name="Shadow_Specialty[]" value="'.$row['specialty'].'"></td><td><input type="text" class="form-control" name="Shadow_Hours[]" value="'.$row['total_hours'].'"></td><td class="text-right"><a href="#"><img class="iconBorder shadowAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['shadowData'] .= '<tr id="shadowRow'.$i.'"><td><input type="text" class="form-control" name="Shadow_Last_Name[]" value="'.$row['physician_last_name'].'"></td><td><input type="text" class="form-control" name="Shadow_First_Name[]" value="'.$row['physician_first_name'].'"></td><td><input type="text" class="form-control" name="Shadow_Specialty[]" value="'.$row['specialty'].'"></td><td><input type="text" class="form-control" name="Shadow_Hours[]" value="'.$row['total_hours'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }    
            }
            
            $query = $conn->prepare($researchSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select') {
            if($count === 0) {
                $studentData['researchData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no research data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['researchData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Lab Name</th>
                        <th>Position</th>
                        <th>Mentor Last Name</th>
                        <th>Mentor First Name</th>
                        <th>Hours Per Week</th>
                        <th>Volunteer?</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['researchData'] .= '<tr><td>' .$row['lab_name']. '</td><td>' .$row['position']. '</td><td>' .$row['mentor_last_name']. '</td><td>' .$row['mentor_first_name']. '</td><td>' .$row['hours_per_week']. '</td><td>' .$row['volunteer']. '</td><td>' .$row['start_date']. '</td><td>' .$row['end_date']. '</td></tr>';
               }
               $studentData['researchData'] .= '</tbody></table>';
            }
            }
            if($formStatus == 'update') {
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['researchData'] .= '<tr><td><input type="text" class="form-control" name="Research_Lab[]" value="'.$row['lab_name'].'"></td><td><input type="text" class="form-control" name="Research_Pos[]" value="'.$row['position'].'"></td><td><input type="text" class="form-control" name="Research_Last_Name[]" value="'.$row['mentor_last_name'].'"></td><td><input type="text" class="form-control" name="Research_First_Name[]" value="'.$row['mentor_first_name'].'"></td><td><input type="text" class="form-control" name="Research_Hours[]" value="'.$row['hours_per_week'].'"></td><td><select id="choice" class="form-control" name="Research_Volunteer[]"><option value="'.$row['volunteer'].'">'.$row['volunteer'].'</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><input type="text" class="form-control" name="Research_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" name="Research_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder researchAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['researchData'] .= '<tr id="researchRow'.$i.'"><td><input type="text" class="form-control" name="Research_Lab[]" value="'.$row['lab_name'].'"></td><td><input type="text" class="form-control" name="Research_Pos[]" value="'.$row['position'].'"></td><td><input type="text" class="form-control" name="Research_Last_Name[]" value="'.$row['mentor_last_name'].'"></td><td><input type="text" class="form-control" name="Research_First_Name[]" value="'.$row['mentor_first_name'].'"></td><td><input type="text" class="form-control" name="Research_Hours[]" value="'.$row['hours_per_week'].'"></td><td><select id="choice" class="form-control" name="Research_Volunteer[]"><option value="'.$row['volunteer'].'">'.$row['volunteer'].'</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><input type="text" class="form-control" name="Research_Start[]" value="'.$row['start_date'].'"></td><td><input type="text" class="form-control" name="Research_End[]" value="'.$row['end_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }      
            }
            
            $query = $conn->prepare($hptestSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select') {
            if($count === 0) {
                $studentData['hptestData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no test data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['hptestData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Test Name</th>
                        <th>Test Date</th>
                        <th>Test Score</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['hptestData'] .= '<tr><td>' .$row['test_name']. '</td><td>' .$row['test_date']. '</td><td>' .$row['test_score']. '</td></tr>';
               }
               $studentData['hptestData'] .= '</tbody></table>';
            }
            }
            if($formStatus == 'update') {
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['hptestData'] .= '<tr><td><input type="text" class="form-control" id="testname" name="Test_Name[]" value="'.$row['test_name'].'"></td><td><input type="text" class="form-control" id="testdate" name="Test_Date[]" value="'.$row['test_date'].'"></td><td><input type="text" class="form-control" id="testscore" name="Test_Score[]" value="'.$row['test_score'].'"></td><td class="text-right"><a href="#"><img class="iconBorder testsAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['hptestData'] .= '<tr id="testsRow'.$i.'"><td><input type="text" class="form-control" id="testname" name="Test_Name[]" value="'.$row['test_name'].'"></td><td><input type="text" class="form-control" id="testdate" name="Test_Date[]" value="'.$row['test_date'].'"></td><td><input type="text" class="form-control" id="testscore" name="Test_Score[]" value="'.$row['test_score'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }   
            }
            
            $query = $conn->prepare($hpschoolSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            if($formStatus == 'select') {
            if($count === 0) {
                $studentData['hpschoolData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no school data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['hpschoolData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>School Name</th>
                        <th>Accepted?</th>
                        <th>Student Choice?</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['hpschoolData'] .= '<tr><td>' .$row['school_name']. '</td><td>' .$row['accepted']. '</td><td>' .$row['student_choice']. '</td></tr>';
               }
               $studentData['hpschoolData'] .= '</tbody></table>';
            }}
            if($formStatus == 'update'){
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['hpschoolData'] .= '<tr id="schoolRow'.$i.'"><td><input type="text" class="form-control" id="schoolname" name="School_Name[]" value="'.$row['school_name'].'"></td><td><select id="accepted" class="form-control" name="School_Accepted[]"><option value="'.$row['accepted'].'">'.$row['accepted'].'</option><option value="Waitlisted">Waitlisted</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><select id="choice" class="form-control" name="School_Choice[]"><option value="'.$row['student_choice'].'">'.$row['student_choice'].'</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td class="text-right"><a href="#"><img class="iconBorder schoolAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['hpschoolData'] .= '<tr id="schoolRow'.$i.'"><td><input type="text" class="form-control" id="schoolname" name="School_Name[]" value="'.$row['school_name'].'"></td><td><select id="accepted" class="form-control" name="School_Accepted[]"><option value="'.$row['accepted'].'">'.$row['accepted'].'</option><option value="Waitlisted">Waitlisted</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><select id="choice" class="form-control" name="School_Choice[]"><option value="'.$row['student_choice'].'">'.$row['student_choice'].'</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }    
            }
            
            $query = $conn->prepare($eventSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select'){
            if($count === 0) {
                $studentData['eventData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no event data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $studentData['eventData'] = '<table class="table table-striped tableBorder">
                  <thead>
                     <tr>
                        <th>Event Name</th>
                        <th>Date Completed</th>
                     <tr>
                  </thead>
                  <tbody>';
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['eventData'] .= '<tr><td>' .$row['event_name']. '</td><td>' .$row['date_completed']. '</td></tr>';
               }
               $studentData['eventData'] .= '</tbody></table>';
            }}
            if($formStatus == 'update'){
               $i = 1;
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  if($i == 1) {
                     $studentData['eventData'] .= '<tr><td><input type="text" class="form-control" name="Event_Name[]" value="'.$row['event_name'].'"></td><td><input type="text" class="form-control" name="Event_Completed[]" value="'.$row['date_completed'].'"></td><td class="text-right"><a href="#"><img class="iconBorder eventAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';   
                  } else {
                     $studentData['eventData'] .= '<tr id="eventRow'.$i.'"><td><input type="text" class="form-control" name="Event_Name[]" value="'.$row['event_name'].'"></td><td><input type="text" class="form-control" name="Event_Completed[]" value="'.$row['date_completed'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';   
                  }
                  $i++;
               }      
            }
            
            $query = $conn->prepare($interviewSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($formStatus == 'select'){
            if($count === 0) {
                $studentData['interviewData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no interview data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $row = $query->fetch(PDO::FETCH_ASSOC);
               $studentData['interviewData'] = '<table class="table table-striped tableBorder">
               <tbody>
               <tr><td>Contacted Student?:</td><td>' .$row['contacted_student']. '</td></tr>
               <tr><td>Interview Date:</td><td>' .$row['interview_date']. '</td></tr>
               <tr><td>Transmit Date:</td><td>' .$row['transmit_date']. '</td></tr>
               <tr><td>Committee Note:</td><td>' .$row['committee_note']. '</td></tr></tbody></table>';
            }}
            if($formStatus == 'update'){
               $row = $query->fetch(PDO::FETCH_ASSOC);
               $studentData['contacted_student'] = $row['contacted_student'];
               $studentData['interview_date'] = $row['interview_date'];
               $studentData['transmit_date'] = $row['transmit_date'];
               $studentData['committee_note'] = $row['committee_note'];
               
                   
            }
            
            $query = $conn->prepare($interviewerSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            
            if($count === 0) {
                $studentData['interviewerData'] = '<tr><td colspan="4">There are no interviewers for this interview in the database at this time!</td></tr></tbody></table>';
            } else {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['interviewerData'] .= '<tr><td><a class="recordSelect" href="#">' .$row['interviewerid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td><td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a><a href="#"><img class="iconBorder deleteIcon" src="Images/open-iconic/png/trash-2x.png" alt="Delete Icon"></a></td></tr>';
               }
               $studentData['interviewerData'] .= '</tbody></table>';
            }
            
            
            
            
            $query = $conn->prepare($writerSQL);
            $query->execute(array($ID));
            $count = $query->rowCount();
            
            if($count === 0) {
                $studentData['writerData'] = '<tr><td colspan="4">There are no writers for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['writerData'] .= '<tr><td><a class="recordSelect" href="#">' .$row['writerid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td><td>'.$row['reception_date'].'</td><td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a><a href="#"><img class="iconBorder deleteIcon" src="Images/open-iconic/png/trash-2x.png" alt="Delete Icon"></a></td></tr>';
               }
               $studentData['writerData'] .= '</tbody></table>';
            }
            
            break;
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
         case 'Letter Writer List':
            $sql = "SELECT * FROM Letter_Writer WHERE WriterID = ?";
            $sql2 = "SELECT S.StudentID, S.Last_Name, S.First_Name, LJ.Reception_Date 
                     FROM Letter_Join LJ, Letter_Writer LW, Student S
                     WHERE LJ.WriterID = LW.WriterID AND LJ.StudentID = S.StudentID AND LW.WriterID = ?
                     ORDER BY S.Last_Name ASC;";
            $group = 'Letter Writer';
            $tableSource = 'writerStudents';
            $query = $conn->prepare($sql2);
            $query->execute(array($ID));
            
            $count = $query->rowCount();
     
            if($count === 0) {
               $tableData = '<tr><td colspan="5">There are no students in the database at this time!</td></tr>';
            } else {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $tableData .= '<tr><td><a class="recordSelect" href="#">' .$row['studentid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td><td>' .$row['reception_date']. '</tr>';
               }
            }
            break;
            
         case 'Committee Member List':
            $sql = "SELECT * FROM Interviewer WHERE InterviewerID = ?";
            $sql2 = "SELECT S.StudentID, S.First_Name, S.Last_Name FROM Interview_Join AS IJ JOIN Student AS S ON S.StudentID = IJ.StudentID AND InterviewerID = ?
                     ORDER BY S.Last_Name ASC;";
            $group = 'Committee Member';
            $tableSource = 'memberStudents';
            $query = $conn->prepare($sql2);
            $query->execute(array($ID));
            $count = $query->rowCount();
     
            if($count === 0) {
               $tableData = '<tr><td colspan="4">There are no students in the database at this time!</td></tr>';
            } else {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $tableData .= '<tr><td><a class="recordSelect" href="#">' .$row['studentid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td></tr>';
               }
            }
            break;
      }

      $query = $conn->prepare($sql);
      $query->execute(array($ID));
      $recordData = $query->fetch(PDO::FETCH_ASSOC);
         
      $conn = null;
      $query = null;
   
      return array($recordData, $group, $tableData, $tableSource, $studentData);
   }
   
   
   
      private function deleteRecordData(){
      $conn = $this->initDatabaseConnection();
      
      $groupSelect = $_POST['groupSelect'];
      $ID = $_POST['ID'];
      $tableSource = '';
      $inner = $_POST['inner'];
      if($inner === 'true') {
         $groupSelect .= ' List';
      } 
      
      switch($groupSelect) {
         case 'Student List':
            $tableSource = "students";
            $sql = "DELETE FROM Student WHERE StudentID = ?";
            $query = $conn->prepare($sql);
            $query->execute(array($ID));
            break;
         case 'Letter Writer List':
            $tableSource = "writers";
            $sql = "DELETE FROM Letter_Writer WHERE WriterID = ?";
            $query = $conn->prepare($sql);
            $query->execute(array($ID));
            break;
         case 'Committee Member List':
            $tableSource = "committee members";
            $sql = "DELETE FROM Interviewer WHERE InterviewerID = ?";
            $query = $conn->prepare($sql);
            $query->execute(array($ID));
            break;
      }
   
      $conn = null;
      $query = null;
      
      return $tableSource;
   } 
   
   private function submitForm() {
      $status = $_POST['status'];
      
      
      $studentID = trim($_POST['StudentID']);
      $firstName = trim($_POST['First_Name']);
      $middleName = trim($_POST['Middle_Name']);
      $lastName = trim($_POST['Last_Name']);
      $medOppAdvisor = trim($_POST['MedOpp_Advisor']);
      $email = trim($_POST['Email']);
      $phone = trim($_POST['Phone']);
      if("" == trim($_POST['DOB'])){
         $dateOfBirth = null;
      } else $dateOfBirth = $_POST['DOB']; 
      $sex = trim($_POST['Sex']);
      $ethnicGroup = trim($_POST['Ethnic_Group']);
      $disadvantaged = trim($_POST['Disadvantaged']);
      $firstGeneration = trim($_POST['First_Generation']);
      $military = trim($_POST['Military_Service']);
      $address = trim($_POST['Local_Address']);
      $city = trim($_POST['City']);
      $state = trim($_POST['State']);
      $county = trim($_POST['Military_Service']);
      $postal = trim($_POST['Postal']);
      $country = trim($_POST['Country']);
      $applicationYear = trim($_POST['Application_Year']);
      $packetReceived = trim($_POST['Packet_Received']);
      if("" == trim($_POST['Date_Paid'])){
         $datePaid = null;
      } else $datePaid = $_POST['Date_Paid'];
      $firstTerm = trim($_POST['First_Term']);
      $applicationStatus = trim($_POST['Application_Status']);
      if("" == trim($_POST['HS_GPA'])){
         $hsGPA = null;
      } else $hsGPA = $_POST['HS_GPA'];
      if("" == trim($_POST['GPA'])){
         $cumGPA = null;
      } else $cumGPA = $_POST['GPA'];
      if("" == trim($_POST['Credits'])){
         $credits = null;
      } else $credits = $_POST['Credits'];
      
      $honorsEligible = trim($_POST['Honors_Eligible']);
      $honorsParticipating = trim($_POST['Participating']);
      if("" == trim($_POST['Credit_Hours'])){
         $honorsCredits = null;
      } else $honorsCredits = $_POST['Credit_Hours'];
      if("" == trim($_POST['Course_Count'])){
         $honorsCourses = null;
      } else $honorsCourses = $_POST['Course_Count'];
         
      
      
      
      
      
      
      
      $languageArray = array();
      foreach ($_POST['Language'] as $value) {
         if (" " == trim($value)) {
            array_push($languageArray, null);   
         } else array_push($languageArray, $value);
      }
      
      
      $programArray = array();
      foreach ($_POST['Academic_Program'] as $value) {
         if (" " == trim($value)) {
            array_push($programArray, null);   
         } else array_push($programArray, $value);
      }
      $degreeArray = array();
      foreach ($_POST['Degree'] as $value) {
         if (" " == trim($value)) {
            array_push($degreeArray, null);   
         } else array_push($degreeArray, $value);
      }
      
      
      $testNameArray = array();
      foreach ($_POST['Test_Name'] as $value) {
         if (" " == trim($value)) {
            array_push($testNameArray, null);   
         } else array_push($testNameArray, $value);
      }
      $testDateArray = array();
      foreach ($_POST['Test_Date'] as $value) {
         if (" " == trim($value)) {
            array_push($testDateArray, null);   
         } else array_push($testDateArray, $value);
      }
      $testScoreArray = array();
      foreach ($_POST['Test_Score'] as $value) {
         if (" " == trim($value)) {
            array_push($testScoreArray, null);   
         } else array_push($testScoreArray, $value);
      }
      
      
      $schoolNameArray = array();
      foreach ($_POST['School_Name'] as $value) {
         if ("" == trim($value)) {
            array_push($schoolNameArray, null);   
         } else array_push($schoolNameArray, $value);
      }
      $schoolAcceptedArray = array();
      foreach ($_POST['School_Accepted'] as $value) {
         if ("" == trim($value)) {
            array_push($schoolAcceptedArray, null);   
         } else array_push($schoolAcceptedArray, $value);
      }
      $schoolChoiceArray = array();
      foreach ($_POST['School_Choice'] as $value) {
         if ("" == trim($value)) {
            array_push($schoolChoiceArray, null);   
         } else array_push($schoolChoiceArray, $value);
      }
      
      
      $extraOrgArray = array();
      foreach ($_POST['Extra_Org'] as $value) {
         if ("" == trim($value)) {
            array_push($extraOrgArray, null);   
         } else array_push($extraOrgArray, $value);
      }
      $extraStartArray = array();
      foreach ($_POST['Extra_Start'] as $value) {
         if ("" == trim($value)) {
            array_push($extraStartArray, null);   
         } else array_push($extraStartArray, $value);
      }
      $extraEndArray = array();
      foreach ($_POST['Extra_End'] as $value) {
         if ("" == trim($value)) {
            array_push($extraEndArray, null);   
         } else array_push($extraEndArray, $value);
      }
      
      
      $groupOrgArray = array();
      foreach ($_POST['Group_Org'] as $value) {
         if ("" == trim($value)) {
            array_push($groupOrgArray, null);   
         } else array_push($groupOrgArray, $value);
      }
      $groupStartArray = array();
      foreach ($_POST['Group_Start'] as $value) {
         if ("" == trim($value)) {
            array_push($groupStartArray, null);   
         } else array_push($groupStartArray, $value);
      }
      $groupEndArray = array();
      foreach ($_POST['Group_End'] as $value) {
         if ("" == trim($value)) {
            array_push($groupEndArray, null);   
         } else array_push($groupEndArray, $value);
      }
      
      
      $leaderOrgArray = array();
      foreach ($_POST['Leader_Org'] as $value) {
         if ("" == trim($value)) {
            array_push($leaderOrgArray, null);   
         } else array_push($leaderOrgArray, $value);
      }
      $leaderPosArray = array();
      foreach ($_POST['Leader_Pos'] as $value) {
         if ("" == trim($value)) {
            array_push($leaderPosArray, null);   
         } else array_push($leaderPosArray, $value);
      }
      $leaderStartArray = array();
      foreach ($_POST['Leader_Start'] as $value) {
         if ("" == trim($value)) {
            array_push($leaderStartArray, null);   
         } else array_push($leaderStartArray, $value);
      }
      $leaderEndArray = array();
      foreach ($_POST['Leader_End'] as $value) {
         if ("" == trim($value)) {
            array_push($leaderEndArray, null);   
         } else array_push($leaderEndArray, $value);
      }
      
      
      $researchLabArray = array();
      foreach ($_POST['Research_Lab'] as $value) {
         if ("" == trim($value)) {
            array_push($researchLabArray, null);   
         } else array_push($researchLabArray, $value);
      }
      $researchPosArray = array();
      foreach ($_POST['Research_Pos'] as $value) {
         if ("" == trim($value)) {
            array_push($researchPosArray, null);   
         } else array_push($researchPosArray, $value);
      }
      $researchLastNameArray = array();
      foreach ($_POST['Research_Last_Name'] as $value) {
         if ("" == trim($value)) {
            array_push($researchLastNameArray, null);   
         } else array_push($researchLastNameArray, $value);
      }
      $researchFirstNameArray = array();
      foreach ($_POST['Research_First_Name'] as $value) {
         if ("" == trim($value)) {
            array_push($researchFirstNameArray, null);   
         } else array_push($researchFirstNameArray, $value);
      }
      $researchHoursArray = array();
      foreach ($_POST['Research_Hours'] as $value) {
         if ("" == trim($value)) {
            array_push($researchHoursArray, null);   
         } else array_push($researchHoursArray, $value);
      }
      $researchVolunteerArray = array();
      foreach ($_POST['Research_Volunteer'] as $value) {
         if ("" == trim($value)) {
            array_push($researchVolunteerArray, null);   
         } else array_push($researchVolunteerArray, $value);
      }
      $researchStartArray = array();
      foreach ($_POST['Research_Start'] as $value) {
         if ("" == trim($value)) {
            array_push($researchStartArray, null);   
         } else array_push($researchStartArray, $value);
      }
      $researchEndArray = array();
      foreach ($_POST['Research_End'] as $value) {
         if ("" == trim($value)) {
            array_push($researchEndArray, null);   
         } else array_push($researchEndArray, $value);
      }
      
      
      $workEmployerArray = array();
      foreach ($_POST['Work_Employer'] as $value) {
         if ("" == trim($value)) {
            array_push($workEmployerArray, null);   
         } else array_push($workEmployerArray, $value);
      }
      $workPositionArray = array();
      foreach ($_POST['Work_Pos'] as $value) {
         if ("" == trim($value)) {
            array_push($workPositionArray, null);   
         } else array_push($workPositionArray, $value);
      }
      $workHoursArray = array();
      foreach ($_POST['Work_Hours'] as $value) {
         if ("" == trim($value)) {
            array_push($workHoursArray, null);   
         } else array_push($workHoursArray, $value);
      }
      $workHealthcareArray = array();
      foreach ($_POST['Work_Healthcare'] as $value) {
         if ("" == trim($value)) {
            array_push($workHealthcareArray, null);   
         } else array_push($workHealthcareArray, $value);
      }
      $workStartArray = array();
      foreach ($_POST['Work_Start'] as $value) {
         if ("" == trim($value)) {
            array_push($workStartArray, null);   
         } else array_push($workStartArray, $value);
      }
      $workEndArray = array();
      foreach ($_POST['Work_End'] as $value) {
         if ("" == trim($value)) {
            array_push($workEndArray, null);   
         } else array_push($workEndArray, $value);
      }
          
      
      $shadowLastNameArray = array();
      foreach ($_POST['Shadow_Last_Name'] as $value) {
         if ("" == trim($value)) {
            array_push($shadowLastNameArray, null);   
         } else array_push($shadowLastNameArray, $value);
      }
      $shadowFirstNameArray = array();
      foreach ($_POST['Shadow_First_Name'] as $value) {
         if ("" == trim($value)) {
            array_push($shadowFirstNameArray, null);   
         } else array_push($shadowFirstNameArray, $value);
      }
      $shadowSpecialtyArray = array();
      foreach ($_POST['Shadow_Specialty'] as $value) {
         if ("" == trim($value)) {
            array_push($shadowSpecialtyArray, null);   
         } else array_push($shadowSpecialtyArray, $value);
      }
      $shadowHoursArray = array();
      foreach ($_POST['Shadow_Hours'] as $value) {
         if ("" == trim($value)) {
            array_push($shadowHoursArray, null);   
         } else array_push($shadowHoursArray, $value);
      }
      
      
      $volunteerOrgArray = array();
      foreach ($_POST['Volunteer_Org'] as $value) {
         if ("" == trim($value)) {
            array_push($volunteerOrgArray, null);   
         } else array_push($volunteerOrgArray, $value);
      }
      $volunteerHoursArray = array();
      foreach ($_POST['Volunteer_Hours'] as $value) {
         if ("" == trim($value)) {
            array_push($volunteerHoursArray, null);   
         } else array_push($volunteerHoursArray, $value);
      }
      $volunteerAvgArray = array();
      foreach ($_POST['Volunteer_Avg'] as $value) {
         if ("" == trim($value)) {
            array_push($volunteerAvgArray, null);   
         } else array_push($volunteerAvgArray, $value);
      }
      $volunteerHealthcareArray = array();
      foreach ($_POST['Volunteer_Healthcare'] as $value) {
         if ("" == trim($value)) {
            array_push($volunteerHealthcareArray, null);   
         } else array_push($volunteerHealthcareArray, $value);
      }
      $volunteerStartArray = array();
      foreach ($_POST['Volunteer_Start'] as $value) {
         if ("" == trim($value)) {
            array_push($volunteerStartArray, null);   
         } else array_push($volunteerStartArray, $value);
      }
      $volunteerEndArray = array();
      foreach ($_POST['Volunteer_End'] as $value) {
         if ("" == trim($value)) {
            array_push($volunteerEndArray, null);   
         } else array_push($volunteerEndArray, $value);
      }
      
      
      $abroadSchoolArray = array();
      foreach ($_POST['Abroad_School'] as $value) {
         if ("" == trim($value)) {
            array_push($abroadSchoolArray, null);   
         } else array_push($abroadSchoolArray, $value);
      }
      $abroadCityArray = array();
      foreach ($_POST['Abroad_City'] as $value) {
         if ("" == trim($value)) {
            array_push($abroadCityArray, null);   
         } else array_push($abroadCityArray, $value);
      }
      $abroadCountryArray = array();
      foreach ($_POST['Abroad_Country'] as $value) {
         if ("" == trim($value)) {
            array_push($abroadCountryArray, null);   
         } else array_push($abroadCountryArray, $value);
      }
      $abroadStartArray = array();
      foreach ($_POST['Abroad_Start'] as $value) {
         if ("" == trim($value)) {
            array_push($abroadStartArray, null);   
         } else array_push($abroadStartArray, $value);
      }
      $abroadEndArray = array();
      foreach ($_POST['Abroad_End'] as $value) {
         if ("" == trim($value)) {
            array_push($abroadEndArray, null);   
         } else array_push($abroadEndArray, $value);
      }
      
      
      $eventNameArray = array();
      foreach ($_POST['Event_Name'] as $value) {
          if ("" == trim($value)) {
            array_push($eventNameArray, null);   
         } else array_push($eventNameArray, $value);
      }
      $eventCompletedArray = array();
      foreach ($_POST['Event_Completed'] as $value) {
         if ("" == trim($value)) {
            array_push($eventCompletedArray, null);   
         } else array_push($eventCompletedArray, $value);
      }
      
      $letterWriterArray = array();
      foreach ($_POST['Letter_Writers'] as $value) {
         if ("" == trim($value)) {
            array_push($letterWriterArray, null);   
         } else array_push($letterWriterArray, $value);
      }
      $letterDateArray = array();
      foreach ($_POST['Letter_Date'] as $value) {
         if ("" == trim($value)) {
            array_push($letterDateArray, null);   
         } else array_push($letterDateArray, $value);
      }
      
      
      $contactedStudent = $_POST['Contacted_Student'];
      
      if("" == trim($_POST['Interview_Date'])){
         $interviewDate = null;
      } else $interviewDate = $_POST['Interview_Date'];
      
      if("" == trim($_POST['Transmit_Date'])){
         $transmitDate = null;
      } else $transmitDate = $_POST['Transmit_Date'];
      
      $committeeNote = $_POST['Committee_Note'];
      
      $committeeMemberArray = array();
      foreach ($_POST['Committee_Members'] as $value) {
         if ("" == trim($value)) {
            array_push($committeeMemberArray, null);   
         } else array_push($committeeMemberArray, $value);
      }
      
      
      if($status == 'update') {
         $_POST['ID'] = $studentID;
         $this->deleteRecordData();
      }
      
      
      $conn = $this->initDatabaseConnection();
      $sql = "INSERT INTO Student VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      if(!$query->execute(array($studentID, $firstName, $middleName, $lastName, $medOppAdvisor, $email, $phone, $dateOfBirth, $sex, $ethnicGroup, $disadvantaged, $firstGeneration, $military, $address, $city, $state, 
         $county, $postal, $country, $applicationYear, $packetReceived, $datePaid, $firstTerm, $applicationStatus, $hsGPA, $cumGPA, $credits, $honorsEligible))) {
         print_r($query->errorInfo());
      }
      
      $sql = "INSERT INTO Language_Fluency VALUES (?, ?)";
      $query = $conn->prepare($sql);
      foreach ($languageArray as $language) {
         $query->execute(array($studentID, $language));
      }
      
      $sql = "INSERT INTO Honors_Info VALUES (?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $query->execute(array($studentID, $honorsParticipating, $honorsCredits, $honorsCourses));
      
      $sql = "INSERT INTO Academic_Plan VALUES (?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($degreeArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $degreeArray[$x], $programArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Health_Profession_Test VALUES (?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($testNameArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $testNameArray[$x], $testDateArray[$x], $testScoreArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Health_Profession_School VALUES (?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($schoolNameArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $schoolNameArray[$x], $schoolAcceptedArray[$x], $schoolChoiceArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Extra_Curricular VALUES (?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($extraOrgArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $extraOrgArray[$x], $extraStartArray[$x], $extraEndArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Student_Groups VALUES (?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($groupOrgArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $groupOrgArray[$x], $groupStartArray[$x], $groupEndArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Leadership_Position VALUES (?, ?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($leaderOrgArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $leaderOrgArray[$x], $leaderPosArray[$x], $leaderStartArray[$x], $leaderEndArray[$x]));
         $x++;
      }
      
      
      
      $sql = "INSERT INTO Research VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($researchLabArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $researchLabArray[$x], $researchStartArray[$x], $researchEndArray[$x], $researchLastNameArray[$x], $researchFirstNameArray[$x], $researchPosArray[$x], $researchVolunteerArray[$x], $researchHoursArray[$x]));
         $x++;
      }
         
      $sql = "INSERT INTO Work_Experience VALUES (?, ?, ?, ?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($workEmployerArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $workEmployerArray[$x], $workPositionArray[$x], $workStartArray[$x], $workEndArray[$x], $workHoursArray[$x], $workHealthcareArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Shadow_Experience VALUES (?, ?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($shadowLastNameArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $shadowLastNameArray[$x], $shadowFirstNameArray[$x], $shadowSpecialtyArray[$x], $shadowHoursArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Volunteer_Experience VALUES (?, ?, ?, ?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($volunteerOrgArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $volunteerOrgArray[$x], $volunteerStartArray[$x], $volunteerEndArray[$x], $volunteerHoursArray[$x], $volunteerAvgArray[$x], $volunteerHealthcareArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Study_Abroad VALUES (?, ?, ?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($abroadSchoolArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $abroadSchoolArray[$x], $abroadStartArray[$x], $abroadEndArray[$x], $abroadCityArray[$x], $abroadCountryArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Event VALUES (?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($eventNameArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $eventNameArray[$x], $eventCompletedArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Letter_Join VALUES (?, ?, ?)";
      $query = $conn->prepare($sql);
      $count = count($letterWriterArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($letterWriterArray[$x], $studentID, $letterDateArray[$x]));
         $x++;
      }
      
      $sql = "INSERT INTO Interview VALUES (?, ?, ?, ?, ?)";
      $query = $conn->prepare($sql);
      $query->execute(array($studentID, $contactedStudent, $interviewDate, $transmitDate, $committeeNote));  
      
      $sql = "INSERT INTO Interview_Join VALUES (?, ?)";
      $query = $conn->prepare($sql);
      $count = count($committeeMemberArray);
      $x = 0;
      while($x < $count) {
         $query->execute(array($studentID, $committeeMemberArray[$x]));
         $x++;
      }
   }
  
   private function prepareFormOptions() {
      $formOptions = array();
      
      $conn = $this->initDatabaseConnection();
      $sql = "SELECT * FROM MedOpp_Advisor ORDER BY AdvisorID";
      $query = $conn->prepare($sql);
      $query->execute();
      
      $formOptions['Advisors'] = '<select class="form-control" name="MedOpp_Advisor" required><option value=" "></option>';
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $formOptions['Advisors'] .= '<option value="' .$row['advisorid']. '">' .$row['advisorid']. ': ' .$row['first_name']. ' ' .$row['last_name']. '</option>';
      }
      $formOptions['Advisors'] .= '</select>';
     
      $sql = "SELECT * FROM Letter_Writer ORDER BY WriterID";
      $query = $conn->prepare($sql);
      $query->execute();
      
 
      $formOptions['Writers'] = '<tr><td id="writerOptions"><select class="form-control" name="Letter_Writers[]"><option value=" "></option>';
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $formOptions['Writers'] .= '<option value="' .$row['writerid']. '">' .$row['writerid']. ': ' .$row['first_name']. ' ' .$row['last_name']. '</option>';
      }
      $formOptions['Writers'] .= '</select></td><td><input type="text" class="form-control" name="Letter_Date[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder writerAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';
      
      $sql = "SELECT * FROM Interviewer ORDER BY InterviewerID";
      $query = $conn->prepare($sql);
      $query->execute();
      
      
      $formOptions['Members'] = '<tr><td id="memberOptions"><select class="form-control" name="Committee_Members[]"><option value=" "></option>';
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $formOptions['Members'] .= '<option value="' .$row['interviewerid']. '">' .$row['interviewerid']. ': ' .$row['first_name']. ' ' .$row['last_name']. '</option>';
      }
      $formOptions['Members'] .= '</select></td><td class="text-right"><a href="#"><img class="iconBorder memberAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';

      
      return $formOptions;
   }
   
   private function prepareEditFormOptions() {
      $formOptions = array();
      $ID = $_POST['ID'];
      
      $conn = $this->initDatabaseConnection();
      
      $advisorSQL = 'SELECT AdvisorID FROM Student WHERE StudentID = ?';
      $query = $conn->prepare($advisorSQL);
      $query->execute(array($ID));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      $advisorID = $row['advisorid'];
      
      
      
      $sql = "SELECT * FROM MedOpp_Advisor ORDER BY AdvisorID";
      $query = $conn->prepare($sql);
      $query->execute();
      
      $formOptions['Advisors'] = '<select class="form-control" name="MedOpp_Advisor" required><option value=" "></option>';
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
         if($row['advisorid'] == $advisorID) 
            $formOptions['Advisors'] .= '<option value="' .$row['advisorid']. '" selected>' .$row['advisorid']. ': ' .$row['first_name']. ' ' .$row['last_name']. '</option>';   
         else
         $formOptions['Advisors'] .= '<option value="' .$row['advisorid']. '">' .$row['advisorid']. ': ' .$row['first_name']. ' ' .$row['last_name']. '</option>';
      }
      $formOptions['Advisors'] .= '</select>';
     
      
      
      $sql = "SELECT * FROM Letter_Join WHERE StudentID = ?";
      $query = $conn->prepare($sql);
      $query->execute(array($ID));
      
      $i = 1;
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $formOptions['Writers'] .= '<tr id="writerRow'.$i.'"><td id="writerOptions"><select class="form-control" name="Letter_Writers[]">';
         $subsql = "SELECT * FROM Letter_Writer ORDER BY WriterID";
         $subquery = $conn->prepare($subsql);
         $subquery->execute();
         while($subrow = $subquery->fetch(PDO::FETCH_ASSOC)) {
            if($row['writerid'] == $subrow['writerid']) 
               $formOptions['Writers'] .= '<option value="' .$subrow['writerid']. '" selected>' .$subrow['writerid']. ': ' .$subrow['first_name']. ' ' .$subrow['last_name']. '</option>';
            else 
               $formOptions['Writers'] .= '<option value="' .$subrow['writerid']. '">' .$subrow['writerid']. ': ' .$subrow['first_name']. ' ' .$subrow['last_name']. '</option>';  
         }
         if($i == 1) {
            $formOptions['Writers'] .= '</select></td><td><input type="text" class="form-control" name="Letter_Date[]" value="'.$row['reception_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder writerAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';
         }
         else {
            $formOptions['Writers'] .= '</select></td><td><input type="text" class="form-control" name="Letter_Date[]" value="'.$row['reception_date'].'"></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';
         }
         $i++;
      }
      

      
      
      
      
      $sql = "SELECT * FROM Interview_Join WHERE StudentID = ?";
      $query = $conn->prepare($sql);
      $query->execute(array($ID));
      
      $i = 1;
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $formOptions['Members'] .= '<tr id="memberRow'.$i.'"><td id="memberOptions"><select class="form-control" name="Committee_Members[]">';
         $subsql = "SELECT * FROM Interviewer ORDER BY InterviewerID";
         $subquery = $conn->prepare($subsql);
         $subquery->execute();
         while($subrow = $subquery->fetch(PDO::FETCH_ASSOC)) {
            if($row['interviewerid'] == $subrow['interviewerid']) 
               $formOptions['Members'] .= '<option value="' .$subrow['interviewerid']. '" selected>' .$subrow['interviewerid']. ': ' .$subrow['first_name']. ' ' .$subrow['last_name']. '</option>';
            else 
               $formOptions['Members'] .= '<option value="' .$subrow['interviewerid']. '">' .$subrow['interviewerid']. ': ' .$subrow['first_name']. ' ' .$subrow['last_name']. '</option>';  
         }
         if($i == 1) {
            $formOptions['Members'] .= '</select></td><td class="text-right"><a href="#"><img class="iconBorder memberAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a></td></tr>';
         }
         else {
            $formOptions['Members'] .= '</select></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>';
         }
         $i++;
      }
      
      

      
      return $formOptions;
   }
   
   
}   
   
   
   


//   private function updateStudentData($mysqli){
//      $First_Name = $_POST['First_Name'];
//      $Last_Name = $_POST['Last_Name'];
//      $StudentID = $_POST['StudentID'];
//      $Local_Address = $_POST['Local_Address'];
//      $Phone = $_POST['Phone'];
//      $Email = $_POST['Email'];
//      $State = $_POST['State'];
//      $Candidate = $_POST['Candidate'];
//      $Bryant_Status = $_POST['Bryant_Status'];
//      $ED_Status = $_POST['ED_Status'];
//      $MDPHD_Status = $_POST['MDPHD_Status'];
//      $MU_Status = $_POST['MU_Status'];
//      $First_Status = $_POST['First_Status'];
//      
//      $First_School = $_POST['First_School'];
//      $Second_School = $_POST['Second_School'];
//      $Third_School = $_POST['Third_School'];
//      $Fourth_School = $_POST['Fourth_School'];
//      $Fifth_School = $_POST['Fifth_School'];
//   
//      if(!$query = $mysqli->prepare("UPDATE Students SET First_NAME = ?, Last_Name = ?, Local_Address = ?, Phone = ?, Email = ?, State = ?, Candidate = ?, Bryant_Status = ?, ED_Status = ?, MDPHD_Status = ?, MU_Status = ?, First_Status = ? WHERE StudentID = ?")){
//         echo "Unable to update student data at this time. Try again!";
//         exit;  
//      }
//      
//      $query->bind_param("sssssssssssss", $First_Name, $Last_Name, $Local_Address, $Phone, $Email, $State, $Candidate, $Bryant_Status, $ED_Status, $MDPHD_Status, $MU_Status, $First_Status, $StudentID);
//      $query->execute();
//      
//      if(!$query = $mysqli->prepare("DELETE FROM Applications WHERE StudentID = ?")){
//         echo "Unable to update student school data at this time. Try again!";
//         exit;  
//      }
//      
//      $query->bind_param("s", $StudentID);
//      $query->execute();
//      
//      if(!$query = $mysqli->prepare("INSERT INTO Applications (StudentID, School_Name) VALUES (?, ?);")){
//         echo "Unable to update student school data at this time. Try again!";
//         exit; 
//      }
//      
//      if($First_School != 'N/A'){
//      $query->bind_param("ss", $StudentID, $First_School);
//      $query->execute();
//      }
//      if($Second_School != 'N/A'){
//      $query->bind_param("ss", $StudentID, $Second_School);
//      $query->execute();
//      }
//      if($Third_School != 'N/A'){
//      $query->bind_param("ss", $StudentID, $Third_School);
//      $query->execute();
//      }
//      if($Fourth_School != 'N/A'){
//      $query->bind_param("ss", $StudentID, $Fourth_School);
//      $query->execute();
//      }
//      if($Fifth_School != 'N/A'){
//      $query->bind_param("ss", $StudentID, $Fifth_School);
//      $query->execute();
//      }
//      
//      $query->close();
//      $mysqli->close();
//   }


   

?>




