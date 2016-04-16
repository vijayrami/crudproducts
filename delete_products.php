<?php
include_once("database/db_conection.php");
if($_POST['id'])
{
	$img_name = $_POST['img'];
	
	$tar_dir = "productuploads/";
	
	if ($img_name != 'no-image.jpg'){
        unlink("$tar_dir$img_name");
    }
    $id = mysqli_real_escape_string($db_conn,$_POST['id']);;
    $delete = "DELETE FROM product WHERE id='$id'";
    mysqli_query($db_conn,$delete);
}
?>