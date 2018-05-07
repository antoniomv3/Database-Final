$(document).ready(function(){ 
   $(".editIcon").hover(
      function(){$(this).parent().parent().parent().addClass("table-warning");},
      function(){$(this).parent().parent().parent().removeClass("table-warning");}
   );
   
   $(".editIcon").click(function(){
      event.preventDefault;
      $id = $(this).parent().parent().parent().children().children().html();
      $groupSelect = $(this).closest('div').find('.groupSelect').html();
      var form = '<form id="editForm" action="index.php" method="post"><input type="hidden" name="action" value="editRecord"><input type="hidden" name="ID" value="' +$id+'"><input type="hidden" name="groupSelect" value="'+$groupSelect+'"></form>';
      $(".hiddenSubmitDiv").html(form);
      $("#editForm").submit();
   });
   
   $(".deleteIcon").hover(
      function(){$(this).parent().parent().parent().addClass("table-danger");},
      function(){$(this).parent().parent().parent().removeClass("table-danger");}
   );
   
   $(".deleteIcon").click(function(){
      event.preventDefault();
      $id = $(this).parent().parent().parent().children().children().html();
      $last = $(this).parent().parent().parent().find("td.lastName").html();
      $first = $(this).parent().parent().parent().find("td.firstName").html();
      $groupSelect = $(this).closest('div').find('.groupSelect').html();
      
      var form = '<form id="deleteForm" action="index.php" method="post"><input type="hidden" name="action" value="deleteRecord"><input type="hidden" name="ID" value="' +$id+'"><input type="hidden" name="groupSelect" value="' +$groupSelect+'"></form>';
      $(".hiddenSubmitDiv").html(form);
      $(".modal-title").html($first + ' ' + $last + ' - ' + $id);
      $("#deleteModal").modal('toggle');
   });
   
   $(".submitDelete").click(function(){
      $("#deleteForm").submit();
   });
  
   
   
   
   //FUNCTIONAL!
   $(".recordSelect").hover(
      function(){$(this).parent().parent().addClass("table-success");},
      function(){$(this).parent().parent().removeClass("table-success");}
   );
   
   $(".recordSelect").click(function(){
      event.preventDefault();
      //$groupSelect = $(this).parent().parent().parent().parent().parent().find(".groupSelect").html();
      //$groupSelect = $(".groupSelect").html();
      $groupSelect = $(this).closest('div').find('.groupSelect').html();
      $id = $(this).html();
      var form = '<form id="recordSelectForm" action="index.php" method="post"><input type="hidden" name="action" value="selectRecord"><input type="hidden" name="ID" value="' +$id+'"><input type="hidden" name="groupSelect" value="' +$groupSelect+'"></form>';
      $(".hiddenSubmitDiv").html(form);
      $("#recordSelectForm").submit();
   });
   
   
   
   
   //FUNCTIONAL!
   $(".backIcon").click(function(){
      event.preventDefault();
      $group = $('#group').html();
      var form = '<form id="masterStudentForm" action="index.php" method="get"><input type="hidden" name="group" value="' +$group+ '"></form>';
      $(".hiddenSubmitDiv").html(form);
      $("#masterStudentForm").submit();
   });
   
   

   $(".languageAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#languageTable tr").length;
      $rowno = $rowno + 1;
      $("#languageTable tr:last").after('<tr id="languageRow' + $rowno + '"><td><input type="text" class="form-control" id="language" name="Language[]" value=" " required></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".academicAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#academicTable tr").length;
      $rowno = $rowno + 1;
      $("#academicTable tr:last").after('<tr id="academicRow' + $rowno + '"><td><input type="text" class="form-control" id="academicprogram" name="Academic_Program[]" value=" " required></td><td><input type="text" class="form-control" id="degree" name="Degree[]" value=" " required></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".testsAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#testsTable tr").length;
      $rowno = $rowno + 1;
      $("#testsTable tr:last").after('<tr id="testsRow' + $rowno + '"><td><input type="text" class="form-control" id="testname" name="Test_Name[]" value=" " required></td><td><input type="text" class="form-control" id="testdate" name="Test_Date[]" value=" " required></td><td><input type="text" class="form-control" id="testscore" name="Test_Score[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".schoolAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#schoolTable tr").length;
      $rowno = $rowno + 1;
      $("#schoolTable tr:last").after('<tr id="schoolRow' + $rowno + '"><td><input type="text" class="form-control" id="schoolname" name="School_Name[]" value=" " required></td><td><select id="accepted" class="form-control" name="School_Accepted[]"><option value=" "></option><option value="Waitlisted">Waitlisted</option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><select id="choice" class="form-control" name="School_Choice[]"><option value=" "></option><option value="Yes">Yes</option><option value="No">No</option></select></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".extraAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#extraTable tr").length;
      $rowno = $rowno + 1;
      $("#extraTable tr:last").after('<tr id="extraRow' + $rowno + '"><td><input type="text" class="form-control" id="extraorg" name="Extra_Org[]" value=" " required></td><td><input type="text" class="form-control" id="extrastart" name="Extra_Start[]" value=" " required></td><td><input type="text" class="form-control" id="extraend" name="Extra_End[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".groupAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#groupTable tr").length;
      $rowno = $rowno + 1;
      $("#groupTable tr:last").after('<tr id="groupRow' + $rowno + '"><td><input type="text" class="form-control" id="grouporg" name="Group_Org[]" value=" " required></td><td><input type="text" class="form-control" id="groupstart" name="Group_Start[]" value=" " required></td><td><input type="text" class="form-control" id="groupend" name="Group_End[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".leaderAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#leaderTable tr").length;
      $rowno = $rowno + 1;
      $("#leaderTable tr:last").after('<tr id="leaderRow' + $rowno + '"><td><input type="text" class="form-control" id="leaderorg" name="Leader_Org[]" value=" " required></td><td><input type="text" class="form-control" id="leaderpos" name="Leader_Pos[]" value=" " required></td><td><input type="text" class="form-control" id="leaderstart" name="Leader_Start[]" value=" " required></td><td><input type="text" class="form-control" id="leaderend" name="Leader_End[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".researchAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#researchTable tr").length;
      $rowno = $rowno + 1;
      $("#researchTable tr:last").after('<tr id="researchRow' + $rowno + '"><td><input type="text" class="form-control" name="Research_Lab[]" value=" " required></td><td><input type="text" class="form-control" name="Research_Pos[]" value=" "></td><td><input type="text" class="form-control" name="Research_Last_Name[]" value=" "></td><td><input type="text" class="form-control" name="Research_First_Name[]" value=" "></td><td><input type="text" class="form-control" name="Research_Hours[]" value=" "></td><td><select id="choice" class="form-control" name="Research_Volunteer[]"><option value=" "></option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><input type="text" class="form-control" name="Research_Start[]" value=" "></td><td><input type="text" class="form-control" name="Research_End[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".workAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#workTable tr").length;
      $rowno = $rowno + 1;
      $("#workTable tr:last").after('<tr id="workRow' + $rowno + '"><td><input type="text" class="form-control" name="Work_Employer[]" value=" " required></td><td><input type="text" class="form-control" name="Work_Pos[]" value=" " required></td><td><input type="text" class="form-control" name="Work_Hours[]" value=" "></td><td><select id="choice" class="form-control" name="Work_Healthcare[]"><option value=" "></option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><input type="text" class="form-control" name="Work_Start[]" value=" " required>   </td><td><input type="text" class="form-control" name="Work_End[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".shadowAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#shadowTable tr").length;
      $rowno = $rowno + 1;
      $("#shadowTable tr:last").after('<tr id="shadowRow' + $rowno + '"><td><input type="text" class="form-control" name="Shadow_Last_Name[]" value=" " required></td><td><input type="text" class="form-control" name="Shadow_First_Name[]" value=" " required></td><td><input type="text" class="form-control" name="Shadow_Specialty[]" value=" "></td><td><input type="text" class="form-control" name="Shadow_Hours[]" value=" ">   </td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".volunteerAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#volunteerTable tr").length;
      $rowno = $rowno + 1;
      $("#volunteerTable tr:last").after('<tr id="volunteerRow' + $rowno + '"><td><input type="text" class="form-control" name="Volunteer_Org[]" value=" " required></td><td><input type="text" class="form-control" name="Volunteer_Hours[]" value=" "></td><td><input type="text" class="form-control" name="Volunteer_Avg[]" value=" "></td><td><select id="choice" class="form-control" name="Volunteer_Healthcare[]"><option value=" "></option><option value="Yes">Yes</option><option value="No">No</option></select></td><td><input type="text" class="form-control" name="Volunteer_Start[]" value=" " required></td><td><input type="text" class="form-control" name="Volunteer_End[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   $(".abroadAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#abroadTable tr").length;
      $rowno = $rowno + 1;
      $("#abroadTable tr:last").after('<tr id="abroadRow' + $rowno + '"><td><input type="text" class="form-control" name="Abroad_School[]" value=" "></td><td><input type="text" class="form-control" name="Abroad_City[]" value=" "></td><td><input type="text" class="form-control" name="Abroad_Country[]" value=" "></td><td><input type="text" class="form-control" name="Abroad_Start[]" value=" "></td><td><input type="text" class="form-control" name="Abroad_End[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
    $(".eventAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#eventTable tr").length;
      $rowno = $rowno + 1;
      $("#eventTable tr:last").after('<tr id="eventRow' + $rowno + '"><td><input type="text" class="form-control" name="Event_Name[]" value=" "></td><td><input type="text" class="form-control" name="Event_Completed[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
    $(".writerAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#writerTable tr").length;
      $rowno = $rowno + 1;
      $options = $("#writerOptions").html();
      $("#writerTable tr:last").after('<tr id="writerRow' + $rowno + '"><td>' +$options+ '</td><td><input type="text" class="form-control" name="Letter_Date[]" value=" "></td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
    $(".memberAddRow").click(function() {
      event.preventDefault();
      $rowno = $("#memberTable tr").length;
      $rowno = $rowno + 1;
      $options = $("#memberOptions").html();
      $("#memberTable tr:last").after('<tr id="memberRow' + $rowno + '"><td>' +$options+ '</td><td class="text-right"><a href="#"><img class="iconBorder deleteRow" src="Images/open-iconic/png/minus-2x.png" alt="Delete Icon"></a></td></tr>');
   });
   
   
   
   
   
   
   $(document.body).on('click', '.deleteRow' ,function(){
      event.preventDefault();
      $rowid = $(this).closest("tr").attr("id");
      $("#" + $rowid).remove();
   });

   $(".editIconInner").click(function(){
      event.preventDefault();
      $id = $("#innerID").html();
      $groupSelect = $("#group").html();
      $groupSelect += " List";
      var form = '<form id="masterStudentForm" action="index.php" method="post"><input type="hidden" name="action" value="editRecord"><input type="hidden" name="ID" value="' +$id+'"><input type="hidden" name="groupSelect" value="'+$groupSelect+'"></form>';
      $(".hiddenSubmitDiv").html(form);
      $("#masterStudentForm").submit();
   });
   
   $(".deleteIconInner").click(function(){
      event.preventDefault();
      $id = $("#innerID").html();
      $name = $("#innerName").html();
      $groupSelect = $("#group").html();     
      var form = '<form id="deleteForm" action="index.php" method="post"><input type="hidden" name="action" value="deleteRecord"><input type="hidden" name="ID" value="' +$id+'"><input type="hidden" name="groupSelect" value="' +$groupSelect+'"><input type="hidden" name="inner" value="true"></form>';
      $(".hiddenSubmitDiv").html(form);
      $(".modal-title").html($name + ' - ' + $id);
      $("#deleteModal").modal('toggle');
   });
   
   $(".dropdown-item").click(function(){
      event.preventDefault();
      var newId = $(this).html();
      $("#dropdownSearch").html(newId);
   });
 });