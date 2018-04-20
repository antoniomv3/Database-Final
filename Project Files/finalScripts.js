$(document).ready(function(){ 
   $(".editIcon").hover(
      function(){$(this).parent().parent().parent().addClass("table-warning");},
      function(){$(this).parent().parent().parent().removeClass("table-warning");}
   );
   
   $(".editIcon").click(function(){
      event.preventDefault;
      $id = $(this).parent().parent().parent().children().children().html();
      var form = '<form id="editForm" action="index.php" method="post"><input type="hidden" name="action" value="editStudent"><input type="hidden" name="StudentID" value="' +$id+'"></form>';
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
      var form = '<form id="deleteForm" action="index.php" method="post"><input type="hidden" name="action" value="deleteStudent"><input type="hidden" name="StudentID" value="' +$id+'"></form>';
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
      var form = '<form id="masterStudentForm" action="index.php" method="get"><input type="hidden" name="nav" value="search"><input type="hidden" name="group" value="' +$group+ '"></form>';
      $(".hiddenSubmitDiv").html(form);
      $("#masterStudentForm").submit();
   });
   
   
   
   
   
   
   $(".editIconInner").click(function(){
      event.preventDefault();
      $id = $("#innerStudentID").html();
      var form = '<form id="masterStudentForm" action="index.php" method="post"><input type="hidden" name="action" value="editStudent"><input type="hidden" name="StudentID" value="' +$id+'"></form>';
      $(".hiddenSubmitDiv").html(form);
      $("#masterStudentForm").submit();
   });
   
   $(".deleteIconInner").click(function(){
      event.preventDefault();
      $id = $("#innerStudentID").html();
      $name = $("#innerName").html();
      var form = '<form id="deleteForm" action="index.php" method="post"><input type="hidden" name="action" value="deleteStudent"><input type="hidden" name="StudentID" value="' +$id+'"></form>';
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