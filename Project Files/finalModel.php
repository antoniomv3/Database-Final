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
         case " (Student) ":
            $nav = 'allStudents';
            break;
         case " (MedOpp Advisor) ":
            $nav = 'allAdvisors';
            break;
         case " (Letter Writer) ":
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
               list($recordData, $group, $tableData, $tableSource, $studentData) = $this->prepareRecordData();
               break;
              
            case "deleteStudent": //WORKING ON THIS STILL
              $source = 'viewContent';
               $tableSource = 'students';
               $this->deleteStudentData();
               $tableData = $this->prepareAll($tableSource);
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
      
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $tableData .= '<tr><td><a class="recordSelect" href="#">' .$row[$id]. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td>
         <td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a><a href="#"><img class="iconBorder deleteIcon" src="Images/open-iconic/png/trash-2x.png" alt="Delete Icon"></a></td></tr>';
      }
      
      $conn = null;
      $query = null;
      $row = null;
      
      return $tableData;
   } 
   
   
   private function prepareRecordData() {
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
            $sql = "SELECT S.*, MA.Last_Name AS MA_Last_Name, MA.First_Name AS MA_First_Name, HI.participating, HI.credit_hours, HI.course_count 
                     FROM Student S JOIN MedOpp_Advisor MA ON S.AdvisorID = MA.AdvisorID JOIN Honors_Info HI ON S.StudentID = HI.StudentID WHERE S.StudentID = ? ";
            $languageSQL = "SELECT Language FROM Language_Fluency WHERE StudentID = ?";
            $degreeSQL = "SELECT Degree, Academic_Program FROM Academic_Plan WHERE StudentID = ?";
            $extraSQL = "SELECT * FROM Extra_Curricular WHERE StudentID = ?";
            $leadershipSQL = "SELECT * FROM Leadership_Position WHERE StudentID = ?";
            $stugroupsSQL = "SELECT * FROM Student_Groups WHERE StudentID = ?";
            $abroadSQL = "SELECT * FROM Study_Abroad WHERE StudentID = ?";
            $workSQL = "SELECT * FROM Work_Experience WHERE StudentID = ?";
            $volunteerSQL = "SELECT * FROM Volunteer_Experience WHERE StudentID = ?";
            $shadowSQL = "SELECT * FROM Shadow_Experience WHERE StudentID = ?";
            $researchSQL = "SELECT * FROM Research WHERE StudentID = ?";
            $hptestSQL = "SELECT * FROM Health_Profession_Test WHERE StudentID = ?";
            $hpschoolSQL = "SELECT * FROM Health_Profession_School WHERE StudentID = ?";
            $eventSQL = "SELECT * FROM Event WHERE StudentID = ?";
            $writerSQL = "SELECT LW.WriterID, LW.Last_Name, LW.First_Name FROM Letter_Join LJ JOIN Letter_Writer LW ON LJ.WriterID = LW.WriterID AND LJ.StudentID = ? ORDER BY LW.Last_Name ASC";
            $interviewSQL = "SELECT * FROM Interview WHERE StudentID = ?";
            $interviewerSQL = "SELECT I.interviewerid, I.first_name, I.last_name FROM Interview_Join AS IJ JOIN Interviewer AS I ON IJ.InterviewerID = I.InterviewerID WHERE StudentID = ?";
            $group = 'Student';
         
            
            $query = $conn->prepare($languageSQL);
            $query->execute(array($ID));
            
            if($count === 0) {
                $studentData['languageData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no language data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['languageData'] .= '<tr><td>' .$row['language']. '</td></tr>';
               }
            }
            
            $query = $conn->prepare($degreeSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($extraSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($stugroupsSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($leadershipSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($abroadSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($workSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($volunteerSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($shadowSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($researchSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($hptestSQL);
            $query->execute(array($ID));
            
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
            
            $query = $conn->prepare($hpschoolSQL);
            $query->execute(array($ID));
            
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
            }
            
            $query = $conn->prepare($eventSQL);
            $query->execute(array($ID));
            
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
            }
            
            $query = $conn->prepare($interviewSQL);
            $query->execute(array($ID));
            
            if($count === 0) {
                $studentData['interviewData'] = '<table class="table table-striped tableBorder"><tbody><tr><td colspan="4">There is no interview data for this student in the database at this time!</td></tr></tbody></table>';
            } else {
               $row = $query->fetch(PDO::FETCH_ASSOC);
               $studentData['interviewData'] = '<table class="table table-striped tableBorder">
               <tbody>
               <tr><td>Contacted Student?:</td><td>' .$row['contacted_student']. '</td></tr>
               <tr><td>Interview Date:</td><td>' .$row['interview_date']. '</td></tr>
               <tr><td>Committee Note:</td><td>' .$row['committee_note']. '</td></tr></tbody></table>';
            }
            
            $query = $conn->prepare($interviewerSQL);
            $query->execute(array($ID));
            
            if($count === 0) {
                $studentData['interviewerData'] = '<tr><td colspan="4">There are no interviewers for this interview in the database at this time!</td></tr>';
            } else {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['interviewerData'] .= '<tr><td><a class="recordSelect" href="#">' .$row['interviewerid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td><td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a><a href="#"><img class="iconBorder deleteIcon" src="Images/open-iconic/png/trash-2x.png" alt="Delete Icon"></a></td></tr>';
               }
               $studentData['interviewerData'] .= '</tbody></table>';
            }
            
            
            
            $query = $conn->prepare($writerSQL);
            $query->execute(array($ID));
            
            if($count === 0) {
                $studentData['writerData'] = '<tr><td colspan="4">There are no writers for this student in the database at this time!</td></tr>';
            } else {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $studentData['writerData'] .= '<tr><td><a class="recordSelect" href="#">' .$row['writerid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td><td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a><a href="#"><img class="iconBorder deleteIcon" src="Images/open-iconic/png/trash-2x.png" alt="Delete Icon"></a></td></tr>';
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
                  $tableData .= '<tr><td><a class="recordSelect" href="#">' .$row['studentid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td><td>' .$row['reception_date']. '<td class="text-right"><input type="checkbox" class="form-check-input"></td></tr>';
               }
            }
            break;
            
         case 'Committee Member List':
            $sql = "SELECT * FROM Interviewer WHERE InterviewerID = ?";
            //Change
            $sql2 = "SELECT S.StudentID, S.Last_Name, S.First_Name, LJ.Reception_Date 
                     FROM Letter_Join LJ, Letter_Writer LW, Student S
                     WHERE LJ.WriterID = LW.WriterID AND LJ.StudentID = S.StudentID AND LW.WriterID = ?
                     ORDER BY S.Last_Name ASC;";
            $group = 'Committee Member';
            //Change
            $tableSource = 'writerStudents';
            $query = $conn->prepare($sql2);
            $query->execute(array($ID));
            $count = $query->rowCount();
     
            if($count === 0) {
               $tableData = '<tr><td colspan="4">There are no students in the database at this time!</td></tr>';
            } else {
               while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $tableData .= '<tr><td><a class="recordSelect" href="#">' .$row['studentid']. '</a></td><td class="lastName">' .$row['last_name']. '</td><td class="firstName">' .$row['first_name']. '</td><td>' .$row['reception_date']. '<td class="text-right"><input type="checkbox" class="form-check-input"></td></tr>';
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
   
   
   
      private function deleteStudentData(){
         //MORE WORK NEEDS TO BE DONE TO DELETE IN ALL TABLES WITH STUDENT ID
      $conn = $this->initDatabaseConnection();
      
      $studentID = $_POST['StudentID'];
      $sql = "DELETE FROM Student WHERE StudentID = ?";
      $query = $conn->prepare($sql);
      $query->execute(array($studentID));
   
      
      $conn = null;
      $query = null;
      
      return $studentData;
      
   } 
}
   
   
   
   
   
   
   
   
   
   
   
   
   
   
//   private function prepareFormData($mysqli) {
//   //This function connects to the database and creates a string containing options for all of the schools obtained.
//      $query="SELECT School_Name FROM Schools";
//      
//      if (!$result = $mysqli->query($query)){
//         echo "There was an error processing the form data! Try again!";
//         $mysqli->close();
//         exit;
//      }
//      
//      $options = '<option value="N/A">N/A</option>';
//      while($row = $result->fetch_array()){
//         $rows[] = $row;
//      }
//      foreach($rows as $row){
//         $options .= '<option value = "' . $row['School_Name'] . '">' . $row['School_Name'] . '</option>';
//      }
//      
//      $result->free();
//      $mysqli->close();
//      return $options;
//   }   
//   
//   private function prepareTableData($mysqli) {
//   //This function prepares the table the user sees when selecting the master application list, it contains every student in the database.
//      $query="SELECT StudentID, Last_Name, First_Name FROM Students ORDER BY Last_Name ASC;";
//      
//      if(!$result = $mysqli->query($query)){
//         echo "There was an error processing the table data! Try again!";
//         $mysqli->close();
//         exit;
//      }
//      
//      if($result->num_rows === 0){
//         $tableData = '<tr><td colspan="4">There are no applications in the database at this time!</td></tr>';
//         return $tableData;
//      }
//      
//      $tableData = '';
//      
//      while($row = $result->fetch_array()){
//         $rows[] = $row;
//      }
//      
//      foreach($rows as $row){
//         $tableData .= '<tr><td><a class="studentSelect" href="#">' .$row['StudentID']. '</a></td><td class="lastName">' .$row['Last_Name']. '</td><td class="firstName">' .$row['First_Name']. '</td>
//         <td class="text-right"><a href="#"><img class="iconBorder editIcon" src="Images/open-iconic/png/cog-2x.png" alt="Edit Icon"></a><a href="#"><img class="iconBorder deleteIcon" src="Images/open-iconic/png/trash-2x.png" alt="Delete Icon"></a></td></tr>';
//      }
//      
//      $result->free();
//      $mysqli->close();
//      return $tableData;
//   }
//  
//
//   
//   private function prepareStudentSchools($mysqli) {
//   //This function grabs all the schools of an individual student based on their entry in the Applications table.
//      $studentID = $_POST['StudentID'];
//    
//      if(!$query = $mysqli->prepare("SELECT b.School_Name, b.City, b.State, b.School_Type FROM Students INNER JOIN Applications ON Students.StudentID = Applications.StudentID INNER JOIN Schools AS b ON b.School_Name = Applications.School_Name WHERE Applications.StudentID = ?")){
//         echo "There was an error processing the school data! Try again!";
//         exit;  
//      }
//      
//      $query->bind_param("s", $studentID);
//      $query->execute();
//      $result = $query->get_result();
//
//      $studentSchools = array();
//      while ($row = $result->fetch_assoc()){
//         $studentSchools[] = $row;
//      }
//      $result->free();
//      $query->close();
//      $mysqli->close();
//      return $studentSchools;
//   }
//
//
//   
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
//    
//   private function newStudentData($mysqli){
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
//      if(!$query = $mysqli->prepare("INSERT INTO Students (First_Name, Last_Name, StudentID, Local_Address, Phone, Email, State, Candidate, Bryant_Status, ED_Status, MDPHD_Status, MU_Status, First_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
//         echo "Unable to submit student data at this time. Try again!";
//         exit;
//      }
//      
//      $query->bind_param("sssssssssssss", $First_Name, $Last_Name, $StudentID, $Local_Address, $Phone, $Email, $State, $Candidate, $Bryant_Status, $ED_Status, $MDPHD_Status, $MU_Status, $First_Status);
//      $query->execute();
//        
//      if(!$query = $mysqli->prepare("INSERT INTO Applications (StudentID, School_Name) VALUES (?, ?)")){
//         echo "Unable to submit student school data at this time. Try again!";
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
//    }

   
//   These next five all handle student information in some way, and call similar functions to perform their tasks. They all also require the user to be logged in.
//            case "selectStudent":
//               if($logStatus === 'true'){
//                  $source = 'studentContent';
//                  $studentData = $this->prepareStudentData($this->initDatabaseConnection());
//                  //$studentSchools = $this->prepareStudentSchools($this->initDatabaseConnectionLogged());
//               }
//               break;
//            case "deleteStudent":
//               if($logStatus === 'true'){
//                  $source = 'viewContent';
//                  $this->deleteStudentData($this->initDatabaseConnectionLogged(), $studentID);
//                  $tableData = $this->prepareTableData($this->initDatabaseConnectionLogged());
//               }
//               break;
//            case "editStudent":
//               if($logStatus === 'true'){
//                  $source = 'formContent';
//                  $formOptions = $this->prepareFormData($this->initDatabaseConnectionLogged());
//                  $studentData = $this->prepareStudentData($this->initDatabaseConnectionLogged());
//                  $studentSchools = $this->prepareStudentSchools($this->initDatabaseConnectionLogged());
//               }
//               break;
//            case "updateStudent":
//               if($logStatus === 'true'){
//                  $source = 'studentContent';
//                  $this->updateStudentData($this->initDatabaseConnectionLogged());
//                  $studentData = $this->prepareStudentData($this->initDatabaseConnectionLogged());
//                  $studentSchools = $this->prepareStudentSchools($this->initDatabaseConnectionLogged());
//               }
//               break;
//            //When making a new application, if the user is logged in it will redirect them to the student's form page. If not, it will redirect them back to the home page.
//            case "newStudent":
//               if($logStatus === 'true'){
//                  $this->newStudentData($this->initDatabaseConnectionLogged());
//                  $source = 'studentContent';
//                  $studentData = $this->prepareStudentData($this->initDatabaseConnectionLogged());
//                  $studentSchools = $this->prepareStudentSchools($this->initDatabaseConnectionLogged());
//               }
//               if($logStatus === 'false'){
//                  $this->newStudentData($this->initDatabaseConnectionUnlogged());
//                  $source = 'homeContent';
//               }
//               break;
?>




