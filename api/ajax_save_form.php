<?php

  require '../inc/connect_db.php';

  $project_name = $_POST['project_name'];
  $employee_ids  = $_POST['employee_id'];
  
  foreach ($employee_ids as $id) {
    $result = checkProject($id, $project_name);
    if($result < 1){
      $msg = "Project saved scuccessfully!";
      $status = "Saved";
    }else{
      $msg = "Already saved!";
      $status = "Error";
    }
  }
  echo json_encode(array(
                "msg"     => $msg, 
                "status"  => $status,
                "result"  => $result
              ));
?>