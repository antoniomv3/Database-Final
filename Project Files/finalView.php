<?php
class finalView{
   
   public function __construct(){
      session_start();
   }
   public function __destruct(){
   }
   
   public function pageView($source, $formOptions, $logStatus, $tableData, $url, $recordData, $group, $studentSchools, $tableSource, $studentData){
   //This function constructs the page based on the values passed from the controller. 
      
      if($source === 'formContent'){
         $this->createForm($formOptions, $studentData, $recordData); 
      }
      if($tableData){
         $this->addTableData($tableData, $tableSource);
      }
      
      if($source === 'recordContent'){
         $this->createRecordData($recordData, $group, $studentData);
      }
      
      if($source === 'loginContent'){
         $html = $this->presentLoginView();
      } else {

         
         
      $html = <<<EOT
<!doctype html>
<html>
    
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MedOpp Interview</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    
    <link rel="stylesheet" type="text/css" href="finalStyle.css">
    <script src="finalScripts.js"></script>
</head>

<body>
   <div id="headerBar"></div>
   <div id="bodyDiv">
      <div id="headerDiv">
         <img src="Images/logo.png" alt="MedOpp Logo" id="logo">
      </div>
      <div class="navBar">
         <ul class="nav nav-fill">
            <li class="nav-item navCSS">
               <a class="nav-link active" href="{$url}?nav=home">Home</a>
            </li>
            <li class="nav-item dropdown navCSS">
               <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Students</a>
               <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" onclick="window.location='{$url}?nav=newStudent';">New Student</a>
                  <a class="dropdown-item" href="#" onclick="window.location='{$url}?nav=allStudents';">View All Students</a>
               </div>
            </li>
            <li class="nav-item dropdown nav-fill navCSS">
               <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Advisors</a>
               <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" onclick="window.location='{$url}?nav=allAdvisors';">View All Advisors</a>
               </div>
            </li>
            <li class="nav-item dropdown navCSS">
               <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Letter Writers</a>
               <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" onclick="window.location='{$url}?nav=allWriters';">View All Writers</a>
               </div>
            </li>
            <li class="nav-item dropdown navCSS">
               <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Committee Members</a>
               <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" onclick="window.location='{$url}?nav=allMembers';">View All Committee Members</a>
               </div>
            </li>
            <li id="loginButton" class="nav-item dropdown navCSS">
               <a class="nav-link dropdown-toggle loginHover" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span><img class='keyIcon' src='Images/open-iconic/png/key-2x.png' alt='Logged In Icon'> Admin</span></a>
               <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" onclick="window.location='{$url}?nav=logout';">Logout</a>
               </div>
            </li>
            
         </ul>
      </div>

      <div id="contentDiv">{$this->{$source}}</div>
   </div>
   
   <div id="deleteModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
         <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this record?</p>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-danger submitDelete" data-dismiss="modal">Delete</button>
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </div>
      </div>
   </div>
   
   <div id="footerDiv">
      <h3>MedOpp Advising Office</h3>
      <p>Phone: 573.882.3893</p>
      <p>Site: <a href="http://premed.missouri.edu" target="_blank">premed.missouri.edu</a></p>   
   </div>
</body>
</html>
EOT;
      }
    
        return $html;
    }
   
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   private function presentLoginView() {
      
      $loginContent = '<h2 class="center">Welcome! Please Sign In</h2>';
      
      if($_SESSION['logError'] === "Yes") $loginContent .= '<h4 class="red">Login Failed, Try Again!</h4>';
      
      $loginContent .= '
      <form action="index.php" method="post">
      <input type="hidden" name="action" value="login">
      <div class="form-group">
      <label for="username">Username:</label>
      <input type="text" class="form-control" name="username" id="username" required>
      </div>
      <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" name="pwd" id="pwd" required>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
      </form>';
      
      
      $html = '
<!doctype html>
<html>
    
<head>
    <meta charset="utf-8">
    <title>MedOpp Interview</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" type="text/css" href="finalStyle.css">
    <script src="finalScripts.js"></script>
</head>

<body>
   <div id="headerBar"></div>
   <div id="bodyDiv">
      <div id="headerDiv">
         <img src="Images/logo.png" alt="MedOpp Logo" id="logo">
      </div>
      <div id="contentDiv">';
      
      $html .= $loginContent;
      
      $html .= '
      </div>
   </div>
   <div id="footerDiv">
      <h3>MedOpp Advising Office</h3>
      <p>Phone: 573.882.3893</p>
      <p>Site: <a href="http://premed.missouri.edu" target="_blank">premed.missouri.edu</a></p>   
   </div>
</body>
</html>';
      
   return $html;
   }
      
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   var $homeContent = 
      '<h1 class="center">Welcome to the MedOpp Advising Office Database Management System!</h1>
      <hr>
      <h4 class="center">On this page you can process searches on the records within the database.</h4>
      <br>
      <div class="searchRow">
         <form action="index.php" method="post">
         <input type="hidden" name="action" value="search">
         
            <select class="form-control displayInline" name="searchBy" id="searchBy">
               <option value="StudentID">StudentID</option>
               <option value="Student_Last_Name">Student Last Name</option>
            </select>   
         
         <input id="searchText" class="form-control displayInline" name="searchText" type="text" placeholder="Search" aria-label="Search">
         
         <button id="searchSubmit" type="submit" class="btn btn-default displayInline">Go!</button>
         </form>
      </div>';
   
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   var $viewStarter = 
'<hr>
<table id="applicationTable" class="table table-striped tableBorder">
   <thead>
      <tr>
         <th>ID</th>
         <th>Last Name</th>
         <th>First Name</th>';
   
   var $defaultCloser = '<th class="text-right">Edit/Delete</th></tr></thead><tbody>';
   
   var $writerCloser = '<th>Reception Date</th></tr></thead><tbody>'; 
   
   var $studentWriterCloser = '<th>Reception Date</th><th class="text-right">Edit/Delete</th></tr></thead><tbody>';
   
   var $advisorCloser = '<th class="text-right">Edit</th></tr></thead><tbody>';
   
   var $interviewCloser = '</tr></thead><tbody>';
   
   var $viewContent = '';
   
   
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   private function addTableData($tableData, $tableSource) {
   //This function adds the table data into the page.

      if($tableSource === 'students') {
         $this->viewContent .= '<h2 class="center groupSelect">Student List</h2>';
         $this->viewContent .= $this->viewStarter;
         $this->viewContent .= $this->defaultCloser;
      }
      if($tableSource === 'advisors') {
         $this->viewContent .= '<h2 class="center groupSelect">Advisor List</h2>';
         $this->viewContent .= $this->viewStarter;
         $this->viewContent .= $this->advisorCloser;
      }
      if($tableSource === 'writers') {
         $this->viewContent .= '<h2 class="center groupSelect">Letter Writer List</h2>';
         $this->viewContent .= $this->viewStarter;
         $this->viewContent .= $this->defaultCloser;
      }
      if($tableSource === 'committee members') {
         $this->viewContent .= '<h2 class="center groupSelect">Committee Member List</h2>';
         $this->viewContent .= $this->viewStarter;
         $this->viewContent .= $this->defaultCloser;
      }
      if($tableSource === 'memberStudents') {
         $this->viewContent .= '<h2 class="center groupSelect">Student List</h2>';
         $this->viewContent .= $this->viewStarter;
         $this->viewContent .= $this->interviewCloser;   
      }
      if($tableSource === 'noResult') {
         $this->viewContent .= '<h2 class="center">Empty Search!</h2>';
         $this->viewContent .= $this->viewStarter;
         $this->viewContent .= $this->defaultCloser;
      }
      if($tableSource === 'writerStudents') {
         $this->viewContent .= '<h2 class="center groupSelect">Student List</h2>';
         $this->viewContent .= $this->viewStarter;
         $this->viewContent .= $this->writerCloser;
         $this->viewContent .= $tableData;
         $this->viewContent .= '</tbody></table><div class="hiddenSubmitDiv"></div>';
         return;
      }
      $this->viewContent .= $tableData;
      $this->viewContent .= '</tbody></table><div class="hiddenSubmitDiv"></div>';
   }
   
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   var $recordContent = '';
   
   private function createRecordData($recordData, $group, $studentData){
   //This function constructs the student form page.
      $id = '';
      
      switch($group) {
         case 'Student':
            $this->recordContent .= '<div><h2><span id="innerName">' .$recordData['first_name']. ' ' .$recordData['middle_name']. ' ' .$recordData['last_name']. '</span> (<span id="group">' .$group. '</span>)<span id="formSpan"><a class="backIcon" href="#"><img class="iconBorder" src="Images/open-iconic/png/arrow-circle-left-4x.png" alt="Return Icon"></a><a class="editIconInner" href="#"><img class="iconBorder" src="Images/open-iconic/png/cog-4x.png" alt="Edit Icon"></a><a class="deleteIconInner" href="#"><img class="iconBorder" src="Images/open-iconic/png/trash-4x.png" alt="Return Icon"></a></span></h2></div><hr>';
            
            $this->recordContent .= '
         <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" role="tab" data-toggle="tab" href="#demo">Demographic Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#academicInfo">Academic Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#healthInfo">Health Profession Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#involvementInfo">Involvement</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#experienceInfo">Experience</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#eventInfo">Event Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#writerInfo">Recommendation Writer Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#interviewInfo">Interview Info</a>
            </li>
         </ul>

<div class="tab-content">
   <div id="demo" class="tab-pane fade show active" role="tabpanel">
      <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>StudentID:</td>
               <td id="innerID">' .$recordData['studentid']. '</td>
            </tr>
            <tr>
               <td>Email:</td>
               <td>' .$recordData['email']. '</td>
            </tr>
            <tr>
               <td>Phone:</td>
               <td>' .$recordData['phone']. '</td>
            </tr>
            <tr>
               <td>Date of Birth / Sex:</td>
               <td>' .$recordData['date_of_birth']. ' / ' .$recordData['sex']. '</td>
            </tr>
            <tr>
               <td>Ethnic Group / Disadvantaged?:</td>
               <td>' .$recordData['ethnic_group']. ' / ' .$recordData['disadvantaged']. '</td>
            </tr>
            <tr>
               <td>First Generation? / Military Service?:</td>
               <td>' .$recordData['first_generation']. ' / ' .$recordData['military_service']. '</td>
            </tr>
            <tr>
               <td>Local Address:</td>
               <td>' .$recordData['address']. '</td>
            </tr>
            <tr>
               <td>City, State:</td>
               <td>' .$recordData['city']. ', ' .$recordData['state'].'</td>
            </tr>
            <tr>
               <td>County:</td>
               <td>' .$recordData['county']. '</td>
            </tr>
            <tr>
               <td>Postal:</td>
               <td>' .$recordData['postal']. '</td>
            </tr>
            <tr>
               <td>Country:</td>
               <td>' .$recordData['country']. '</td>
            </tr>
         </tbody>
      </table>
      <hr><h4>Languages Spoken</h4>
      <table class="table table-striped tableBorder">
         <tbody>'
            .$studentData['languageData'].
        '</tbody> 
      </table>
   </div>
   
   <div id="academicInfo" class="tab-pane fade" role="tabpanel">
    <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>MedOpp Advisor:</td>
               <td>' .$recordData['ma_first_name']. ' ' .$recordData['ma_last_name']. '</td>
            </tr>
            <tr>
               <td>Application Year:</td>
               <td>' .$recordData['application_year']. '</td>
            </tr>
            <tr>
               <td>Packet Received / Date Paid:</td>
               <td>' .$recordData['packet_received']. ' / ' .$recordData['date_paid']. '</td>
            </tr>
            <tr>
               <td>First Term:</td>
               <td>' .$recordData['first_term']. '</td>
            </tr>
            <tr>
               <td>Application Status:</td>
               <td>' .$recordData['application_status']. '</td>
            </tr>
            <tr>
               <td>High School Core GPA:</td>
               <td>' .$recordData['hs_core_gpa']. '</td>
            </tr>
            <tr>
               <td>Cumulative GPA / Total Credits:</td>
               <td>' .$recordData['cum_gpa']. ' / ' .$recordData['total_credit']. '</td>
            </tr>
         </tbody>
      </table>
      <hr><h3>Honors Info</h3>
      <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>Honors Eligible:</td>
               <td>' .$recordData['honors_eligible']. '</td>
            </tr>
            <tr>
               <td>Participating?:</td>
               <td>' .$studentData['participating']. '</td>
            </tr>
            <tr>
               <td>Credit Hours:</td>
               <td>' .$studentData['credit_hours']. '</td>
            </tr>
            <tr>
               <td>Course Count:</td>
               <td>' .$studentData['course_count']. '</td>
            </tr>
         </tbody>
      </table>
      <hr><h3>Academic Plan</h3>' 
      .$studentData['degreeData']. '
  </div>
 
  <div id="involvementInfo" class="tab-pane fade" role="tabpanel">
      <br><h4>Extracurricular Activities</h4>' 
         .$studentData['extraData']. '
      <hr><h3>Student Groups</h3>'
         .$studentData['stugroupsData']. '
      <hr><h3>Leadership Positions</h3>' 
         .$studentData['leadershipData']. '
  </div>
  
  <div id="experienceInfo" class="tab-pane fade" role="tabpanel">
    <br><h3>Research</h3>' 
         .$studentData['researchData']. '
      <hr><h3>Work</h3>'
         .$studentData['workData']. '
      <hr><h3>Shadow</h3>' 
         .$studentData['shadowData']. '
      <hr><h3>Volunteer</h3>' 
         .$studentData['volunteerData']. '
      <hr><h3>Study Abroad</h3>' 
         .$studentData['abroadData']. '
  </div>
  
  <div id="healthInfo" class="tab-pane fade" role="tabpanel">
    <br><h3>Health Profession Tests</h3>' 
         .$studentData['hptestData']. '
      <hr><h3>Health Profession Schools</h3>'
         .$studentData['hpschoolData']. '
  </div>
  
  <div id="eventInfo" class="tab-pane fade" role="tabpanel">
      ' . $studentData['eventData']. '
  </div>
  <div id="writerInfo" class="tab-pane fade" role="tabpanel"><br>
      <h2 class="center groupSelect">Letter Writer List</h2>'
      .$this->viewStarter .$this->studentWriterCloser .$studentData['writerData']. '
  </div>
  <div id="interviewInfo" class="tab-pane fade" role="tabpanel">
      ' .$studentData['interviewData']. '
      <hr><h3 class="center groupSelect">Committee Member List</h3>
      ' .$this->viewStarter .$this->defaultCloser .$studentData['interviewerData']. '
  </div>
</div>
      <div class="hiddenSubmitDiv"></div>';
            break;
            
///////////////////////////////
            
         case 'MedOpp Advisor':
            $this->recordContent .= '<div><h2><span id="innerName">' .$recordData['first_name']. ' ' .$recordData['last_name']. '</span> (<span id="group">' .$group. '</span>)<span id="formSpan"><a class="backIcon" href="#"><img class="iconBorder" src="Images/open-iconic/png/arrow-circle-left-4x.png" alt="Return Icon"></a><a class="editIconInner" href="#"><img class="iconBorder" src="Images/open-iconic/png/cog-4x.png" alt="Edit Icon"></a></span></h2></div><hr>';
            
            $this->recordContent .= '
         <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" role="tab" data-toggle="tab" href="#demo">Demographic Info</a>
            </li>
            <li>
               <a class="nav-link" role="tab" data-toggle="tab" href="#studentInfo">Student Info</a>
            </li>
         </ul>

<div class="tab-content">
   <div id="demo" class="tab-pane fade show active" role="tabpanel">
      <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>ID:</td>
               <td id="innerID">' .$recordData['advisorid']. '</td>
            </tr>
            <tr>
               <td>Email:</td>
               <td>' .$recordData['email']. '</td>
            </tr>
         </tbody>
      </table>
   </div>
   <div id="studentInfo" class="tab-pane fade" role="tabpanel">'
      .$this->viewContent. '
  </div>
</div>
      <div class="hiddenSubmitDiv"></div>';
            break;
            
 ///////////////////////////////           
            
         case 'Letter Writer':
            $this->recordContent .= '<div><h2><span id="innerName">' .$recordData['first_name']. ' ' .$recordData['last_name']. '</span> (<span id="group">' .$group. '</span>)<span id="formSpan"><a class="backIcon" href="#"><img class="iconBorder" src="Images/open-iconic/png/arrow-circle-left-4x.png" alt="Return Icon"></a><a class="editIconInner" href="#"><img class="iconBorder" src="Images/open-iconic/png/cog-4x.png" alt="Edit Icon"></a><a class="deleteIconInner" href="#"><img class="iconBorder" src="Images/open-iconic/png/trash-4x.png" alt="Return Icon"></a></span></h2></div><hr>';
            
            $this->recordContent .= '
         <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" role="tab" data-toggle="tab" href="#demo">Demographic Info</a>
            </li>
            <li>
                <a class="nav-link" role="tab" data-toggle="tab" href="#studentInfo">Student Info</a>
            </li>
         </ul>

<div class="tab-content">
   <div id="demo" class="tab-pane fade show active" role="tabpanel">
      <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>ID:</td>
               <td id="innerID">' .$recordData['writerid']. '</td>
            </tr>
            <tr>
               <td>Email:</td>
               <td>' .$recordData['email']. '</td>
            </tr>
         </tbody>
      </table>
   </div>
   <div id="studentInfo" class="tab-pane fade" role="tabpanel"><br>'
      .$this->viewContent. '
  </div>
</div>
      <div class="hiddenSubmitDiv"></div>';
            break;
            
///////////////////////////////   
            
         case 'Committee Member':
            $this->recordContent .= '<div><h2><span id="innerName">' .$recordData['first_name']. ' ' .$recordData['last_name']. '</span> (<span id="group">' .$group. '</span>)<span id="formSpan"><a class="backIcon" href="#"><img class="iconBorder" src="Images/open-iconic/png/arrow-circle-left-4x.png" alt="Return Icon"></a><a class="editIconInner" href="#"><img class="iconBorder" src="Images/open-iconic/png/cog-4x.png" alt="Edit Icon"></a><a class="deleteIconInner" href="#"><img class="iconBorder" src="Images/open-iconic/png/trash-4x.png" alt="Return Icon"></a></span></h2></div><hr>';
            
            $this->recordContent .= '
         <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" role="tab" data-toggle="tab" href="#demo">Demographic Info</a>
            </li>
            <li>
                <a class="nav-link" role="tab" data-toggle="tab" href="#studentInfo">Student Info</a>
            </li>
         </ul>

<div class="tab-content">
   <div id="demo" class="tab-pane fade show active" role="tabpanel">
      <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>ID:</td>
               <td id="innerID">' .$recordData['interviewerid']. '</td>
            </tr>
            <tr>
               <td>Email:</td>
               <td>' .$recordData['email']. '</td>
            </tr>
         </tbody>
      </table>
   </div>
   <div id="studentInfo" class="tab-pane fade" role="tabpanel">'
      .$this->viewContent. '
  </div>
</div>
      <div class="hiddenSubmitDiv"></div>';
            break;
      }
   }
   
     private function createForm($formOptions, $studentData, $recordData){
   //This function adds the form options, finishing the $formContent source so it can be inserted completed.   
      
      $editStatus = '';
      $formStatus = 'new';
      $formTitle = 'New Student';
        
      $firstName = ' ';
      $middleName = ' ';
      $lastName = ' ';
      $studentID = ' ';
      $email = ' ';
      $phone = ' ';
      $dob = ' ';
      $sex = ' ';
      $ethnicGroup = ' ';
      $disadvantagedYes = '';
      $disadvantagedNo = '';
      $firstGenerationYes = '';
      $firstGenerationNo = '';
      $militaryYes = '';
      $militaryNo = '';
      $address = ' ';
      $city = ' ';
      $state = ' ';
      $county = ' ';
      $postal = ' ';
      $country = ' ';
      $languages = '
      <tr><td>  
         <input type="text" class="form-control" name="Language[]" value=" ">
      </td>
      <td class="text-right">
         <a href="#"><img class="iconBorder languageAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
      </td><tr>';
        
      $applicationYear = ' ';
      $packetYes = ' ';
      $packetNo = ' ';
      $datePaid = ' ';
      $firstTerm = ' ';
      $applicationStatus = ' ';
      $hsGPA = ' ';
      $GPA = ' ';
      $credits = ' ';
      $eligibleYes = '';
      $eligibleNo = '';
      $participatingYes = '';
      $participatingNo = '';
      $honorCredits = ' ';
      $courses = ' ';
      $degrees = '
      <tr>
      <td>
         <input type="text" class="form-control" name="Academic_Program[]" value=" ">   
      </td>
      <td>
         <input type="text" class="form-control" name="Degree[]" value=" ">   
      </td>
      <td class="text-right">
         <a href="#"><img class="iconBorder academicAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
      </td>
      </tr>';
      $extras = '
      <tr>
      <td>
         <input type="text" class="form-control" name="Extra_Org[]" value=" ">   
      </td>
      <td>
         <input type="text" class="form-control" name="Extra_Start[]" value=" ">   
      </td>
      <td>
         <input type="text" class="form-control" name="Extra_End[]" value=" ">   
      </td>
      <td class="text-right">
         <a href="#"><img class="iconBorder extraAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
      </td>
      </tr>';
      $groups = '
      <tr>
         <td>
            <input type="text" class="form-control" name="Group_Org[]" value=" ">   
         </td>
         <td>
            <input type="text" class="form-control" name="Group_Start[]" value=" ">   
         </td>
         <td>
            <input type="text" class="form-control" name="Group_End[]" value=" ">   
         </td>
         <td class="text-right">
            <a href="#"><img class="iconBorder groupAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
         </td>
      </tr>';
        
      $leadership = '
      <tr>
               <td>
                  <input type="text" class="form-control" name="Leader_Org[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Leader_Pos[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Leader_Start[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Leader_End[]" value=" ">   
               </td>
               <td class="text-right">
                  <a href="#"><img class="iconBorder leaderAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
               </td>
            </tr>';
      $work = '
      <tr>
               <td>
                  <input type="text" class="form-control" name="Work_Employer[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Work_Pos[]" value=" ">   
               </td>
                <td>
                  <input type="text" class="form-control" name="Work_Hours[]" value=" ">   
               </td>
               <td>
                  <select id="choice" class="form-control" name="Work_Healthcare[]">
                     <option value=" "></option>
                     <option value="Yes">Yes</option>
                     <option value="No">No</option>
                  </select>   
               </td>
               <td>
                  <input type="text" class="form-control" name="Work_Start[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Work_End[]" value=" ">   
               </td>
               <td class="text-right">
                  <a href="#"><img class="iconBorder workAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
               </td>
            </tr>';
      $studyAbroad = '
      <tr>
               <td>
                  <input type="text" class="form-control" name="Abroad_School[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Abroad_City[]" value=" ">   
               </td>
                <td>
                  <input type="text" class="form-control" name="Abroad_Country[]" value=" ">   
               </td>
               
               <td>
                  <input type="text" class="form-control" name="Abroad_Start[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Abroad_End[]" value=" ">   
               </td>
               <td class="text-right">
                  <a href="#"><img class="iconBorder abroadAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
               </td>
            </tr>';
        $volunteer = '
        <tr>
               <td>
                  <input type="text" class="form-control" name="Volunteer_Org[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Volunteer_Hours[]" value=" ">   
               </td>
                <td>
                  <input type="text" class="form-control" name="Volunteer_Avg[]" value=" ">   
               </td>
               <td>
                  <select id="choice" class="form-control" name="Volunteer_Healthcare[]">
                     <option value=" "></option>
                     <option value="Yes">Yes</option>
                     <option value="No">No</option>
                  </select>   
               </td>
               <td>
                  <input type="text" class="form-control" name="Volunteer_Start[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Volunteer_End[]" value=" ">   
               </td>
               <td class="text-right">
                  <a href="#"><img class="iconBorder volunteerAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
               </td>
            </tr>';
      $research = '
      <tr>
               <td>
                  <input type="text" class="form-control" name="Research_Lab[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Research_Pos[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Research_Last_Name[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Research_First_Name[]" value=" ">   
               </td>
                <td>
                  <input type="text" class="form-control" name="Research_Hours[]" value=" ">   
               </td>
               <td>
                  <select id="choice" class="form-control" name="Research_Volunteer[]">
                     <option value=" "></option>
                     <option value="Yes">Yes</option>
                     <option value="No">No</option>
                  </select>   
               </td>
               <td>
                  <input type="text" class="form-control" name="Research_Start[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Research_End[]" value=" ">   
               </td>
               <td class="text-right">
                  <a href="#"><img class="iconBorder researchAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
               </td>
            </tr>';
      $shadow = '
      <tr>
               <td>
                  <input type="text" class="form-control" name="Shadow_Last_Name[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Shadow_First_Name[]" value=" ">   
               </td>
                <td>
                  <input type="text" class="form-control" name="Shadow_Specialty[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Shadow_Hours[]" value=" ">   
               </td>
               <td class="text-right">
                  <a href="#"><img class="iconBorder shadowAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
               </td>
            </tr>';
        $tests = '
        <tr>
               <td>
                  <input type="text" class="form-control" name="Test_Name[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Test_Date[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Test_Score[]" value=" ">   
               </td>
               <td class="text-right">
                  <a href="#"><img class="iconBorder testsAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
               </td>
            </tr>';
        $schools = '
        <tr>
               <td>
                  <input type="text" class="form-control" name="School_Name[]" value=" ">   
               </td>
               <td>
                  <select id="accepted" class="form-control" name="School_Accepted[]">
                     <option value=" "></option>
                     <option value="Waitlisted" >Waitlisted</option>
                     <option value="Yes" >Yes</option>
                     <option value="No" >No</option>
                  </select>  
               </td>
               <td>
                  <select id="choice" class="form-control" name="School_Choice[]">
                     <option value=" "></option>
                     <option value="Yes" >Yes</option>
                     <option value="No">No</option>
                  </select>   
               </td>
               <td class="text-right">
                  <a href="#"><img class="iconBorder schoolAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
               </td>
            </tr>';
        $events = '
        <tr>
               <td>
                  <input type="text" class="form-control" name="Event_Name[]" value=" ">   
               </td>
               <td>
                  <input type="text" class="form-control" name="Event_Completed[]" value=" ">   
               </td>
               <td class="text-right">
                  <a href="#"><img class="iconBorder eventAddRow" src="Images/open-iconic/png/plus-2x.png" alt="Edit Icon"></a>
               </td>
            </tr>  ';
        
      $contactedYes = '';
      $contactedNo = '';
      $interviewDate = ' ';
      $transmitDate = ' ';
      $committeeNote = ' ';
        
      
        
      if($recordData){
         $editStatus = "readonly";
         $formStatus = 'update';
         $formTitle = 'Update Student';
         if($recordData['first_name']) $firstName = $recordData['first_name'];
         if($recordData['middle_name']) $middleName = $recordData['middle_name'];
         if($recordData['last_name']) $lastName = $recordData['last_name'];
         if($recordData['studentid']) $studentID = $recordData['studentid'];
         if($recordData['email']) $email = $recordData['email'];
         if($recordData['phone']) $phone = $recordData['phone'];
         if($recordData['date_of_birth']) $dob = $recordData['date_of_birth'];
         if($recordData['sex']) $sex = $recordData['sex'];
         if($recordData['ethnic_group']) $ethnicGroup = $recordData['ethnic_group'];
         if($recordData['disadvantaged']) $disadvantaged = $recordData['disadvantaged'];
         if($recordData['first_generation']) $firstGeneration = $recordData['first_generation'];
         if($recordData['military_service']) $military = $recordData['military_service'];
         if($recordData['address']) $address = $recordData['address'];
         if($recordData['city']) $city = $recordData['city'];
         if($recordData['state']) $state = $recordData['state'];
         if($recordData['county']) $county = $recordData['county'];
         if($recordData['postal']) $postal = $recordData['postal'];
         if($recordData['country']) $country = $recordData['country'];
         if($studentData['languageData']) $languages = $studentData['languageData'];
         
         if($recordData['application_year']) $applicationYear = $recordData['application_year'];
         if($recordData['packet_received']) $packet = $recordData['packet_received'];
         if($recordData['date_paid']) $datePaid = $recordData['date_paid'];
         if($recordData['first_term']) $firstTerm = $recordData['first_term'];
         if($recordData['application_status']) $applicationStatus = $recordData['application_status'];
         if($recordData['hs_core_gpa']) $hsGPA = $recordData['hs_core_gpa'];
         if($recordData['cum_gpa']) $GPA = $recordData['cum_gpa'];
         if($recordData['total_credit']) $credits = $recordData['total_credit'];
         if($recordData['honors_eligible']) $eligible = $recordData['honors_eligible'];
         if($studentData['participating']) $participating = $studentData['participating'];
         if($studentData['credit_hours']) $honorCredits = $studentData['credit_hours'];
         if($studentData['course_count']) $courses = $studentData['course_count'];
         if($studentData['degreeData']) $degrees = $studentData['degreeData'];
         
         if($studentData['extraData']) $extras = $studentData['extraData'];
         if($studentData['stugroupsData']) $groups = $studentData['stugroupsData'];
         if($studentData['leadershipData']) $leadership = $studentData['leadershipData'];
         if($studentData['workData']) $work = $studentData['workData'];
         if($studentData['abroadData']) $studyAbroad = $studentData['abroadData'];
         if($studentData['volunteerData']) $volunteer = $studentData['volunteerData'];
         if($studentData['shadowData']) $shadow = $studentData['shadowData'];
         if($studentData['researchData']) $research = $studentData['researchData'];
         
         if($studentData['hptestData']) $tests = $studentData['hptestData'];
         if($studentData['hpschoolData']) $schools = $studentData['hpschoolData'];
         
         if($studentData['eventData']) $events = $studentData['eventData'];
         
         if($studentData['contacted_student']) $contacted = $studentData['contacted_student'];
         if($studentData['interview_date']) $interviewDate = $studentData['interview_date'];
         if($studentData['transmit_date']) $transmitDate = $studentData['transmit_date'];
         if($studentData['committee_note']) $committeeNote = $studentData['committee_note'];
       }
      if($disadvantaged == 'Yes') $disadvantagedYes = 'checked';
      if($disadvantaged == 'No') $disadvantagedNo = 'checked';
      
      if($firstGeneration == 'Yes') $firstGenerationYes = 'checked';
      if($firstGeneration == 'No') $firstGenerationNo = 'checked';  
      
      if($military == 'Yes') $militaryYes = 'checked';
      if($military == 'No') $militaryNo = 'checked';  
    
      if($packet == 'Yes') $packetYes = 'checked';
      if($packet == 'No') $packetNo = 'checked';  
        
      if($eligible == 'Yes') $eligibleYes = 'checked';
      if($eligible == 'No') $eligibleNo = 'checked';  
        
      if($participating == 'Yes') $participatingYes = 'checked';
      if($participating == 'No') $participatingNo = 'checked';  
        
      if($contacted == 'Yes') $contactedYes = 'checked';
      if($contacted == 'No') $contactedNo = 'checked';
        
      $this->formContent .= '
      <h2 class="center">'.$formTitle.'</h2>
      <hr>
      <form action="index.php" method="post">
      <input type="hidden" name="action" value="submitForm"><input type="hidden" name="status" value="' .$formStatus. '">
      <input type="hidden" name="groupSelect" value="Student List">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" role="tab" data-toggle="tab" href="#demo">Demographic Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#academicInfo">Academic Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#healthInfo">Health Profession Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#involvementInfo">Involvement</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#experienceInfo">Experience</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#eventInfo">Event Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#writerInfo">Recommendation Writer Info</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" role="tab" data-toggle="tab" href="#interviewInfo">Interview Info</a>
            </li>
         </ul>

<div class="tab-content">
   <div id="demo" class="tab-pane fade show active" role="tabpanel">
      <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>First / Middle / Last Name:</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="text" class="form-control" id="first_name" name="First_Name"  value="'.$firstName.'" required>
                     </div>
                     <div class="col div-inline">
                       <input type="text" class="form-control" name="Middle_Name" value="'.$middleName.'">
                     </div>
                     <div class="col div-inline">
                       <input type="text" class="form-control" name="Last_Name" value="'.$lastName.'" required>
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td>StudentID:</td>
               <td><input type="text" class="form-control" name="StudentID" value="'.$studentID.'" required '.$editStatus.'></td>
            </tr>
            <tr>
               <td>MedOpp Advisor:</td>
               <td>' .$formOptions['Advisors']. '</td>
            </tr>
            <tr>
               <td>Email:</td>
               <td><input type="text" class="form-control" name="Email" value="'.$email.'"></td>
            </tr>
            <tr>
               <td>Phone:</td>
               <td><input type="text" class="form-control" name="Phone" value="'.$phone.'"></td>
            </tr>
            <tr>
               <td>Date of Birth / Sex:</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="text" class="form-control" name="DOB" value="'.$dob.'">
                     </div>
                     <div class="col div-inline">
                        <select id="sex" class="form-control" name="Sex">
                           <option value="'.$sex.'">'.$sex.'</option>
                           <option value="Male">Male</option>
                           <option value="Female">Female</option>
                           <option value="Other">Other</option>
                           <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td>Ethnic Group / Disadvantaged?:</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="text" class="form-control" name="Ethnic_Group" value="'.$ethnicGroup.'">
                     </div>
                     <div class="col div-inline">
                        <input type="radio" class="" name="Disadvantaged" value="Yes"'.$disadvantagedYes.'> Yes
                        <input type="radio" class="" name="Disadvantaged" value="No"'.$disadvantagedNo.'> No 
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td>First Generation? / Military Service?:</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="radio" class="" name="First_Generation" value="Yes"'.$firstGenerationYes.'> Yes 
                        <input type="radio" class="" name="First_Generation" value="No"'.$firstGenerationNo.'> No 
                     </div>
                     <div class="col div-inline">
                        <input type="radio" class="" name="Military_Service" value="Yes"'.$militaryYes.'> Yes
                        <input type="radio" class="" name="Military_Service" value="No"'.$militaryNo.'> No 
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td>Local Address:</td>
               <td><input type="text" class="form-control" name="Local_Address" value="'.$address.'"></td>
            </tr>
            <tr>
               <td>City, State:</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="text" class="form-control" name="City" value="'.$city.'">
                     </div>
                     <div class="col div-inline">
                       <input type="text" class="form-control" name="State" value="'.$state.'">
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td>County:</td>
               <td><input type="text" class="form-control" name="County" value="'.$county.'"></td>
            </tr>
            <tr>
               <td>Postal:</td>
               <td><input type="text" class="form-control" name="Postal" value="'.$postal.'"></td>
            </tr>
            <tr>
               <td>Country:</td>
               <td><input type="text" class="form-control" name="Country" value="'.$country.'"></td>
            </tr>
         </tbody>
      </table>
      <hr>
      <h4>Languages Spoken</h4>
      <table id="languageTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th style="width: 85%">Language</th>
               <th class="text-right" style="width: 15%">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$languages.'    
         </tbody>
      </table>
   </div>
   

   
   
   
   <div id="academicInfo" class="tab-pane fade" role="tabpanel">
    <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>Application Year:</td>
               <td><input type="text" class="form-control" name="Application_Year" value="'.$applicationYear.'"></td>
            </tr>
            <tr>
               <td>Packet Received / Date Paid:</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="radio" class="" name="Packet_Received" value="Yes" '.$packetYes.'> Yes 
                        <input type="radio" class="" name="Packet_Received" value="No" '.$packetNo.'> No 
                     </div>
                     <div class="col div-inline">
                        <input type="text" class="form-control" name="Date_Paid" value="'.$datePaid.'">
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td>First Term:</td>
               <td><input type="text" class="form-control" name="First_Term" value="'.$firstTerm.'"></td>
            </tr>
            <tr>
               <td>Application Status:</td>
               <td>
               <select id="applicationstatus" class="form-control" name="Application_Status">
                  <option value="'.$applicationStatus.'">'.$applicationStatus.'</option>
                  <option value="Application Submitted">Application Submitted</option>
                  <option value="Applying this Cycle">Applying this Cycle</option>
                  <option value="Gap Year">Gap Year</option>
               </select>
               </td>
            </tr>
            <tr>
               <td>High School Core GPA:</td>
               <td><input type="text" class="form-control" name="HS_GPA" value="'.$hsGPA.'"></td>
            </tr>
            <tr>
               <td>Cumulative GPA / Total Credits:</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="text" class="form-control" name="GPA" value="'.$GPA.'">
                     </div>
                     <div class="col div-inline">
                       <input type="text" class="form-control" name="Credits" value="'.$credits.'">
                     </div>
                  </div>
               </td>
            </tr>
         </tbody>
      </table>
      <hr><h3>Honors Info</h3>
      <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>Honors Eligible:</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="radio" class="" name="Honors_Eligible" value="Yes" '.$eligibleYes.'> Yes 
                        <input type="radio" class="" name="Honors_Eligible" value="No" '.$eligibleNo.'> No 
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td>Participating?:</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="radio" class="" name="Participating" value="Yes" '.$participatingYes.'> Yes 
                        <input type="radio" class="" name="Participating" value="No"  '.$participatingNo.'> No 
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td>Credit Hours:</td>
               <td><input type="text" class="form-control" name="Credit_Hours" value="'.$honorCredits.'"></td>
            </tr>
            <tr>
               <td>Course Count:</td>
               <td><input type="text" class="form-control" name="Course_Count" value="'.$courses.'"></td>
            </tr>
         </tbody>
      </table>
      <hr><h3>Academic Plan</h3>
      <table id="academicTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th style="width=40%">Academic Program</th>
               <th style="width=40%">Degree</th>
               <th class="text-right" style="width: 15%">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$degrees.'
         </tbody>
      </table>
  </div>
  
  <div id="healthInfo" class="tab-pane fade" role="tabpanel">
      <br><h3>Health Profession Tests</h3> 
      <table id="testsTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th style="width=28%">Test Name</th>
               <th style="width=28%">Test Date</th>
               <th style="width=28%">Test Score</th>
               <th class="text-right" style="width: 15%">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$tests.'       
         </tbody>
      </table>       
      <hr><h3>Health Profession Schools</h3>
      <table id="schoolTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th style="width=28%">School Name</th>
               <th style="width=28%">Accepted?</th>
               <th style="width=28%">Student Choice?</th>
               <th class="text-right" style="width: 15%">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$schools.'       
         </tbody>
      </table>   
  </div>
  
  <div id="involvementInfo" class="tab-pane fade" role="tabpanel">
      <br><h4>Extracurricular Activities</h4>
         <table id="extraTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th style="width=28%">Organization</th>
               <th style="width=28%">Start Date</th>
               <th style="width=28%">End Date</th>
               <th class="text-right" style="width: 15%">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$extras.'   
         </tbody>
      </table>   
      <hr><h3>Student Groups</h3>
      <table id="groupTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th style="width=28%">Organization</th>
               <th style="width=28%">Start Date</th>
               <th style="width=28%">End Date</th>
               <th class="text-right" style="width: 15%">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$groups.'
         </tbody>
      </table>   
      <hr><h3>Leadership Positions</h3>
      <table id="leaderTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th style="width=21%">Organization</th>
               <th style="width=21%">Position</th>
               <th style="width=21%">Start Date</th>
               <th style="width=21%">End Date</th>
               <th class="text-right" style="width: 15%">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$leadership.'       
         </tbody>
      </table>   
  </div>
  
  <div id="experienceInfo" class="tab-pane fade" role="tabpanel">
    <br><h3>Research</h3>
      <table id="researchTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th>Lab Name</th>
               <th>Position</th>
               <th>Mentor Last Name</th>
               <th>Mentor First Name</th>
               <th>Hours Per Week</th>
               <th>Volunteer</th>
               <th>Start Date</th>
               <th>End Date</th>
               <th class="text-right">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$research.'       
         </tbody>
      </table>
   <hr><h3>Work</h3>
      <table id="workTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th>Employer</th>
               <th>Position</th>
               <th>Hours Per Week</th>
               <th>Health Care Related</th>
               <th>Start Date</th>
               <th>End Date</th>
               <th class="text-right">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$work.'       
         </tbody>
      </table>
   <hr><h3>Shadow</h3>
      <table id="shadowTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th>Physician Last Name</th>
               <th>Physician First Name</th>
               <th>Specialty</th>
               <th>Total Hours</th>
               <th class="text-right">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$shadow.'       
         </tbody>
      </table>
   <hr><h3>Volunteer</h3>
      <table id="volunteerTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th>Organization</th>
               <th>Total Hours</th>
               <th>Average Hours Per Week</th>
               <th>Health Care Related</th>
               <th>Start Date</th>
               <th>End Date</th>
               <th class="text-right">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$volunteer.'       
         </tbody>
      </table>
   <hr><h3>Study Abroad</h3>
      <table id="abroadTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th>School Abroad</th>
               <th>City</th>
               <th>Country</th>
               <th>Start Date</th>
               <th>End Date</th>
               <th class="text-right">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$studyAbroad.'       
         </tbody>
      </table>
  </div>
  
  <div id="eventInfo" class="tab-pane fade" role="tabpanel">
      <table id="eventTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th>Event Name</th>
               <th>Date Completed</th>
               <th class="text-right">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$events.'     
         </tbody>
      </table>   
  </div>
  <div id="writerInfo" class="tab-pane fade" role="tabpanel"><br>
      <h3>Recommendation Writers</h3>
         <table id="writerTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th>Writer</th>
               <th>Reception Date</th>
               <th class="text-right">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$formOptions['Writers'].'       
         </tbody>
      </table>   
  </div>
  <div id="interviewInfo" class="tab-pane fade" role="tabpanel">
      <table class="table table-striped tableBorder">
         <tbody>
            <tr>
               <td>Contacted Student</td>
               <td>
                  <div class="form-row">
                     <div class="col div-inline">
                        <input type="radio" class="" name="Contacted_Student" value="Yes" '.$contactedYes.'> Yes 
                        <input type="radio" class="" name="Contacted_Student" value="No" '.$contactedNo.'> No 
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td>Interview Date:</td>
               <td><input type="text" class="form-control" name="Interview_Date" value="'.$interviewDate.'"></td>
            </tr>
            <tr>
               <td>Transmit Date:</td>
               <td><input type="text" class="form-control" name="Transmit_Date" value="'.$transmitDate.'"></td>
            </tr>
            <tr>
               <td>Committee Note:</td>
               <td><input type="text" class="form-control" name="Committee_Note" value="'.$committeeNote.'"></td>
            </tr>
         </tbody>
      </table>
     <hr><h3 class="center groupSelect">Committee Member List</h3>
         <table id="memberTable" class="table table-striped tableBorder">
         <thead>
            <tr>
               <th>Committee Member</th>
               <th class="text-right">Add/Remove Row</th>
            <tr>
         </thead> 
         <tbody>
            '.$formOptions['Members'].'
         </tbody>
      </table>   
  </div>
  
</div>


<input type="submit" class="btn btn-primary" value="Submit">
</form>';
        
                       
        
        
        
        
//        
//        <div id="interviewInfo" class="tab-pane fade" role="tabpanel">
//      ' .$studentData['interviewData']. '
//      <hr><h3 class="center groupSelect">Committee Member List</h3>
//      ' .$this->viewStarter .$this->defaultCloser .$studentData['interviewerData']. '
//  </div>
        
        
        
//      $this->formContent .= '
//      <div><h2 class="center">New Student</h2>
//      <hr>
//      <form action="index.php" method="post">
//      <input type="hidden" name="action" value="submitForm"><input type="hidden" name="status" value="' .$formStatus. '">
//      <ul class="nav nav-tabs" role="tablist">
//            <li class="nav-item">
//               <a class="nav-link active" role="tab" data-toggle="tab" href="#demo">Demographic Info</a>
//            </li>
//            <li class="nav-item">
//               <a class="nav-link" role="tab" data-toggle="tab" href="#academicInfo">Academic Info</a>
//            </li>
//            <li class="nav-item">
//               <a class="nav-link" role="tab" data-toggle="tab" href="#healthInfo">Health Profession Info</a>
//            </li>
//            <li class="nav-item">
//               <a class="nav-link" role="tab" data-toggle="tab" href="#involvementInfo">Involvement</a>
//            </li>
//            <li class="nav-item">
//               <a class="nav-link" role="tab" data-toggle="tab" href="#experienceInfo">Experience</a>
//            </li>
//            <li class="nav-item">
//               <a class="nav-link" role="tab" data-toggle="tab" href="#eventInfo">Event Info</a>
//            </li>
//            <li class="nav-item">
//               <a class="nav-link" role="tab" data-toggle="tab" href="#writerInfo">Recommendation Writer Info</a>
//            </li>
//            <li class="nav-item">
//               <a class="nav-link" role="tab" data-toggle="tab" href="#interviewInfo">Interview Info</a>
//            </li>
//         </ul>
//   <br>
//   <div class="tab-content">
//      <div id="demo" class="tab-pane fade show active" role="tabpanel">
//         <div class="form-row">
//            <div class="form-group col">
//               <label for="fname">First Name:</label>
//               <input type="text" class="form-control" id="fname" name="First_Name" value="' .$studentData['First_Name']. '" required>
//         </div>
//         <div class="form-group col">
//            <label for="lname">Last Name:</label>
//            <input type="text" class="form-control" id="lname" name="Last_Name" value="' .$studentData['Last_Name']. '" required>
//         </div>
//      </div>
//      <div class="form-row">
//         <div class="form-group col-md-4">
//            <label for="stuID">MU Student ID#:</label>
//            <input type="text" pattern="[0-9]{8}" class="form-control" id="stuID" name="StudentID" value="' .$studentData['StudentID']. '" ' .$editStatus. ' required>
//            <small id="stuIDHelp" class="form-text text-muted">8 digit MU Student ID</small>
//         </div>
//         <div class="form-group col-md-8">
//            <label for="address">Local Address:</label>
//            <input type="text" class="form-control" id="address" name="Local_Address" value="' .$studentData['Local_Address']. '" required>
//         </div>
//      </div>
//      <div class="form-row">
//         <div class="form-group col-md-4">
//            <label for="phone">Phone:</label>
//            <input type="text" pattern="[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}" class="form-control" id="phone" name="Phone" value="' .$studentData['Phone']. '" required>
//            <small id="phoneInfo" class="form-text text-muted">Please supply in XXX-XXX-XXXX format.</small>
//         </div>
//         <div class="form-group col-md-8">
//            <label for="email">Email:</label>
//            <input type="email" class="form-control" id="email" name="Email" value="' .$studentData['Email']. '" required>
//         </div>
//      </div>
//   </div>
//   
//   <div id="academicInfo" class="tab-pane fade" role="tabpanel">
//      
//      <hr><h3>Honors Info</h3>
//      
//      <hr><h3>Academic Plan</h3>
//   </div>
//   
//   <div id="involvementInfo" class="tab-pane fade" role="tabpanel">
//      <br><h4>Extracurricular Activities</h4>
//      <hr><h3>Student Groups</h3>
//      <hr><h3>Leadership Positions</h3>
//  </div>
//  
//  <div id="experienceInfo" class="tab-pane fade" role="tabpanel">
//    <br><h3>Research</h3>
//      <hr><h3>Work</h3>
//      <hr><h3>Shadow</h3>
//      <hr><h3>Volunteer</h3>
//      <hr><h3>Study Abroad</h3>
//  </div>
//  
//  <div id="healthInfo" class="tab-pane fade" role="tabpanel">
//    <br><h3>Health Profession Tests</h3>
//      <hr><h3>Health Profession Schools</h3>
//  </div>
//  
//  <div id="eventInfo" class="tab-pane fade" role="tabpanel">
//      <h2>eventTab</h2>
//  </div>
//  
//  <div id="writerInfo" class="tab-pane fade" role="tabpanel"><br>
//      <h2 class="center groupSelect">Letter Writer List</h2>
//  </div>
//  
//  <div id="interviewInfo" class="tab-pane fade" role="tabpanel">
//      <hr><h3 class="center groupSelect">Committee Member List</h3>
//  </div>
//  
//</div>
//<input type="submit" class="btn btn-primary" value="Submit">
//</form>';
      
      
      
      
      
      
      
      
      
   
//   <div class="form-row">
//      <div class="form-group col">
//         <label for="fname">First Name:</label>
//         <input type="text" class="form-control" id="fname" name="First_Name" value="' .$studentData['First_Name']. '" required>
//      </div>
//      <div class="form-group col">
//         <label for="lname">Last Name:</label>
//         <input type="text" class="form-control" id="lname" name="Last_Name" value="' .$studentData['Last_Name']. '" required>
//      </div>
//   </div>
//   <div class="form-row">
//      <div class="form-group col-md-4">
//         <label for="stuID">MU Student ID#:</label>
//         <input type="text" pattern="[0-9]{8}" class="form-control" id="stuID" name="StudentID" value="' .$studentData['StudentID']. '" ' .$editStatus. ' required>
//         <small id="stuIDHelp" class="form-text text-muted">8 digit MU Student ID</small>
//      </div>
//      <div class="form-group col-md-8">
//         <label for="address">Local Address:</label>
//         <input type="text" class="form-control" id="address" name="Local_Address" value="' .$studentData['Local_Address']. '" required>
//      </div>
//   </div>
//   <div class="form-row">
//      <div class="form-group col-md-4">
//         <label for="phone">Phone:</label>
//         <input type="text" pattern="[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}" class="form-control" id="phone" name="Phone" value="' .$studentData['Phone']. '" required>
//         <small id="phoneInfo" class="form-text text-muted">Please supply in XXX-XXX-XXXX format.</small>
//      </div>
//      <div class="form-group col-md-8">
//         <label for="email">Email:</label>
//         <input type="email" class="form-control" id="email" name="Email" value="' .$studentData['Email']. '" required>
//      </div>
//   </div>
//   
//   <input type="submit" class="btn btn-primary" value="Submit">
//   </form>';
   }

   
}







//  private function createForm($formOptions, $studentData, $studentSchools){
//   //This function adds the form options, finishing the $formContent source so it can be inserted completed.   
//      
//      $editStatus = '';
//      $formStatus = 'new';
//      
//      $alloStatus = '';
//      $osteoStatus = '';
//      $dentStatus = '';
//      $podStatus = '';
//      $bryantYes = '';
//      $bryantNo = '';
//      $edYes = '';
//      $edNo = '';
//      $mdYes = '';
//      $mdNo = '';
//      $muYes = '';
//      $muNo = '';
//      $firstYes = '';
//      $firstNo = '';
//      
//      if($studentData){
//         $editStatus = "readonly";
//         $formStatus = "update";
//         if($studentData['Candidate'] === 'Allopathic Medicne') $alloStatus = "selected";
//         if($studentData['Candidate'] === 'Osteopathic Medicine') $osteoStatus = "selected";
//         if($studentData['Candidate'] === 'Dentistry') $dentStatus = "selected";
//         if($studentData['Candidate'] === 'Podiatry') $podStatus = "selected";
//      
//         if($studentData['Bryant_Status'] === 'Yes') $bryantYes = 'checked';
//         else $bryantNo = 'checked';
//         if($studentData['ED_Status'] === 'Yes') $edYes = 'checked';
//         else $edNo = 'checked';
//         if($studentData['MDPHD_Status'] === 'Yes') $mdYes = 'checked';
//         else $mdNo = 'checked';
//         if($studentData['MU_Status'] === 'Yes') $muYes = 'checked';
//         else $muNo = 'checked';
//         if($studentData['MU_Status'] === 'Yes') $muYes = 'checked';
//         else $muNo = 'checked';
//         if($studentData['First_Status'] === 'Yes') $firstYes = 'checked';
//         else $firstNo = 'checked';
//         if($studentSchools[0]) 
//            $selectedOption1 = '<option value="' .$studentSchools[0]["School_Name"]. '" selected>' .$studentSchools[0]["School_Name"]. '</option>';
//         if($studentSchools[1]) 
//            $selectedOption2 = '<option value="' .$studentSchools[1]["School_Name"]. '" selected>' .$studentSchools[1]["School_Name"]. '</option>';
//         if($studentSchools[2]) 
//            $selectedOption3 = '<option value="' .$studentSchools[2]["School_Name"]. '" selected>' .$studentSchools[2]["School_Name"]. '</option>';
//         if($studentSchools[3]) 
//            $selectedOption4 = '<option value="' .$studentSchools[3]["School_Name"]. '" selected>' .$studentSchools[3]["School_Name"]. '</option>';
//         if($studentSchools[4]) 
//            $selectedOption5 = '<option value="' .$studentSchools[4]["School_Name"]. '" selected>' .$studentSchools[4]["School_Name"]. '</option>';
//      }
//    
//      $this->formContent .= '
//   <h2 class="center">DEADLINE: MAY 18, 2018 BY 5 PM</h2>
//   <h1 class="center">Committee Interview Applicant Information Form</h1>
//   <hr>
//   <form action="index.php" method="post">
//   <input type="hidden" name="action" value="submitForm"><input type="hidden" name="status" value="' .$formStatus. '">
//   <div class="form-row">
//      <div class="form-group col">
//         <label for="fname">First Name:</label>
//         <input type="text" class="form-control" id="fname" name="First_Name" value="' .$studentData['First_Name']. '" required>
//      </div>
//      <div class="form-group col">
//         <label for="lname">Last Name:</label>
//         <input type="text" class="form-control" id="lname" name="Last_Name" value="' .$studentData['Last_Name']. '" required>
//      </div>
//   </div>
//   <div class="form-row">
//      <div class="form-group col-md-4">
//         <label for="stuID">MU Student ID#:</label>
//         <input type="text" pattern="[0-9]{8}" class="form-control" id="stuID" name="StudentID" value="' .$studentData['StudentID']. '" ' .$editStatus. ' required>
//         <small id="stuIDHelp" class="form-text text-muted">Your 8 digit MU Student ID</small>
//      </div>
//      <div class="form-group col-md-8">
//         <label for="address">Local Address:</label>
//         <input type="text" class="form-control" id="address" name="Local_Address" value="' .$studentData['Local_Address']. '" required>
//      </div>
//   </div>
//   <div class="form-row">
//      <div class="form-group col-md-4">
//         <label for="phone">Phone:</label>
//         <input type="text" pattern="[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}" class="form-control" id="phone" name="Phone" value="' .$studentData['Phone']. '" required>
//         <small id="phoneInfo" class="form-text text-muted">Please supply in XXX-XXX-XXXX format.</small>
//      </div>
//      <div class="form-group col-md-8">
//         <label for="email">Email:</label>
//         <input type="email" class="form-control" id="email" name="Email" value="' .$studentData['Email']. '" required>
//         <small id="emailInfo" class="form-text text-muted">Include where you can be reached during Summer/Fall Semeseter. If this information changes, be sure to notify the MedOpp Office.</small>
//      </div>
//   </div>
//   <div class="form-row">
//      <div class="form-group col">
//         <label for="state">Legal resident of what state:</label>
//         <input type="text" class="form-control" id="state" name="State" value="' .$studentData['State']. '" required>
//      </div>
//      <div class="form-group col">
//         <label for="candidate">Candidate For:</label>
//         <select id="candidate" class="form-control" name="Candidate">
//            <option value="Allopathic Medicine"' .$alloStatus. '>Allopathic Medicine</option>
//            <option value="Osteopathic Medicine"' .$osteoStatus. '>Osteopathic Medicine</option>
//            <option value="Dentistry"' .$dentStatus. '>Dentistry</option>
//            <option value="Podiatry"' .$podStatus. '>Podiatry</option>
//         </select>
//      </div>
//   </div>
//   <div class="form-group row">
//      <label class="col-md-3 form-control-label label-inline">Bryant Scholar:</label>
//      <div class="col-md-3 div-inline">
//         <label class="radio-inline">
//            <input type="radio" class="" name="Bryant_Status" value="Yes" ' .$bryantYes. ' required> Yes 
//         </label>
//         <label class="radio-inline">
//            <input type="radio" class="" name="Bryant_Status" value="No" ' .$bryantNo. ' required> No 
//         </label>
//      </div>
//      <label class="col-md-3 form-control-label label-inline">Early Decision:</label>
//      <div class="col-md-3 div-inline">
//         <label class="radio-inline">
//            <input type="radio" class="" name="ED_Status" value="Yes" ' .$edYes. ' required > Yes 
//         </label>
//         <label class="radio-inline">
//            <input type="radio" class="" name="ED_Status" value="No" ' .$edNo. ' required> No 
//         </label>
//      </div>
//   </div>
//   <div class="form-group row">
//      <label class="col-md-3 form-control-label label-inline">MD/PHD Applicant:</label>
//      <div class="col-md-3 div-inline">
//         <label class="radio-inline">
//            <input type="radio" class="" name="MDPHD_Status" value="Yes"' .$mdYes. ' required > Yes 
//         </label>
//         <label class="radio-inline">
//            <input type="radio" class="" name="MDPHD_Status" value="No"' .$mdNo. ' required> No 
//         </label>
//      </div>
//      <label class="col-md-3 form-control-label label-inline">Enrolled at MU for FS2017?</label>
//      <div class="col-md-3 div-inline">
//         <label class="radio-inline">
//            <input type="radio" class="" name="MU_Status" value="Yes"' .$muYes. ' required > Yes 
//         </label>
//         <label class="radio-inline">
//            <input type="radio" class="" name="MU_Status" value="No"' .$muNo. ' required> No 
//         </label>
//      </div>
//   </div>
//   <div class="form-group row">
//      <label class="col-md-9 form-control-label label-inline">Is this the first time you are applying to health professions schools?</label>
//      <div class="col-md-3 div-inline">
//         <label class="radio-inline">
//            <input type="radio" class="" name="First_Status" value="Yes"' .$firstYes. ' required > Yes 
//         </label>
//         <label class="radio-inline">
//            <input type="radio" class="" name="First_Status" value="No"' .$firstNo. ' required> No 
//         </label>
//      </div>
//   </div>
//   <h3 class="center">List of Schools</h3>
//    <p>Please remember:</p>
//    <ul>
//        <li>All letters must be received before the packet will be transmitted.</li>
//        <li class="bold">Once a packet of letters is sent, any additional submissions will cost $10.</li>
//        <li>Letters will not be sent until payment is recieved and you have submitted your AMCAS/AACOMAS/TMDSAS/AADSAS application.</li>
//    </ul>
//   <div class="form-group">
//      <label>School 1</label>
//      <select class="form-control" name="First_School">' .$selectedOption1. 
//      ' ' .$formOptions. '
//      </select>
//   </div>
//   <div class="form-group">
//      <label>School 2</label>
//      <select class="form-control" name="Second_School">' .$selectedOption2. 
//      ' ' .$formOptions. '
//      </select>
//   </div>
//   <div class="form-group">
//      <label>School 3</label>
//      <select class="form-control" name="Third_School">' .$selectedOption3. 
//      ' ' .$formOptions. '
//      </select>
//   </div>
//   <div class="form-group">
//      <label>School 4</label>
//      <select class="form-control" name="Fourth_School">' .$selectedOption4. 
//      ' ' .$formOptions. '
//      </select>
//   </div>
//   <div class="form-group">
//      <label>School 5</label>
//      <select class="form-control" name="Fifth_School">' .$selectedOption5. 
//      ' ' .$formOptions. '
//      </select>
//   </div>
//   <input type="submit" class="btn btn-primary" value="Submit">
//   </form>';
//   }
?>