<?php
include('../config.php');

$id= $_REQUEST['id'];

$query = " delete from users where id='".$id."'";

if (mysqli_query($conn,$query)) {
  $_SESSION['success_message'] = 'Deleted successfully.';
  header('Location: manageroles.php');
  exit();
}else
{
	echo "error";
}
mysqli_close($conn);
 ?>
