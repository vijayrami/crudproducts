<?php
session_start();
include_once("database/db_conection.php");
if(!$_SESSION['admin_user'])  
{  
  header("Location: index.php"); 
}  
include_once("header.php");

?>
<?php
if(isset($_POST['editproduct']))//this will tell us what to do if some data has been post through form with button.  
{  
    $edit_product_id=$_POST['editproductid']; 
    $get_product_query="SELECT * FROM product where id='$edit_product_id'";
    $run_getproductquery=mysqli_query($db_conn,$get_product_query);
    $product=mysqli_fetch_row($run_getproductquery); 
}
if(isset($_POST['saveproductbtn']))//this will tell us what to do if some data has been post through form with button.  
{  
    $error_flag = false;
    $uploadOk = 1;
    $target_dir = "productuploads/";
    $update_productid=$_POST['updateproductid']; 
    $update_productimage=$_POST['updateproductimage']; 
    $update_productname = mysqli_real_escape_string($db_conn,$_POST['editproductname']);
    $get_updated_productsku = mysqli_real_escape_string($db_conn,$_POST['editproductsku']);
    $update_productsku = strtolower(preg_replace('/\s+/', '-', $get_updated_productsku));
    $update_productstartdate = mysqli_real_escape_string($db_conn,$_POST['editproductstartdate']);
    $update_productenddate = mysqli_real_escape_string($db_conn,$_POST['editproductenddate']);
    $update_productdesc = mysqli_real_escape_string($db_conn,$_POST['edit_admin_productdesc']);
    $update_final_productdesc = mysqli_real_escape_string($db_conn,$_POST['edit_admin_productdesc']);
    
    $updated_product_category = "";
    
    foreach ($_POST['updateproductcategory'] as $names)
    {
            $updated_product_category = $updated_product_category.','.$names;
    }
   
    $product_category = trim($updated_product_category,',');
    
    if (strlen($_POST["opteditstatus"]) == 0) {
        $error_flag = true;
        $_SESSION['update_product_statuserror'] = "<div role='alert' class='alert alert-warning alert-dismissible fade in'><strong>Status</strong> is required</div>";
    } else {
        $update_productstatus = mysqli_real_escape_string($db_conn,$_POST["opteditstatus"]);
    }
    
    $check_update_sku_query="select * from product WHERE sku='$update_productsku' AND ID !='$update_productid' ";    
    $updateresult = mysqli_query($db_conn, $check_update_sku_query);   
    if(mysqli_num_rows($updateresult)>0){
        $error_flag = true;
        $_SESSION['update_product_skuerror'] = "<div role='alert' class='alert alert-warning alert-dismissible fade in'>Product SKU <strong>$update_productsku</strong> is already exist in our database, Please try another one!</div>";        
    }
    
    if(is_uploaded_file($_FILES['editproductimage']['tmp_name'])){         
        
        $target_file = $target_dir . basename($_FILES["editproductimage"]["name"]);
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["editproductimage"]["tmp_name"]);
        if($check == false) {
            $_SESSION['update_product_imageerror'] = "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>File is not an image.</strong></div>";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["editproductimage"]["size"] > 5242888) {
            $_SESSION['update_product_imageerror'] = "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, your file is too large.</strong></div>";
            $uploadOk = 0;
        }
        // Allow certain file formats
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);     
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $_SESSION['update_product_imageerror'] = "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</strong></div>";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $_SESSION['update_product_imageerror'] = "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, your file was not uploaded.</strong></div>";
        }
        $imageFilename = pathinfo($target_file,PATHINFO_FILENAME);  
        $savefinalimagename = $target_dir.$imageFilename.'_'.time().'.'.$imageFileType;
        $savequeryimage = $imageFilename.'_'.time().'.'.$imageFileType;
            
    } else {
        $uploadOk = 2;
    }
    
    if (($error_flag == false)&&($uploadOk == 1)){
    move_uploaded_file($_FILES["editproductimage"]["tmp_name"], $savefinalimagename);
    if ($update_productimage != 'no-image.jpg'){
        unlink("$target_dir$update_productimage");
    }
    $update_query="UPDATE product SET sku='$update_productsku',name='$update_productname',status='$update_productstatus',start_date='$update_productstartdate',end_date='$update_productenddate',description='$update_productdesc',image='$savequeryimage',cat_id='$product_category' where id='$update_productid'";  
    
    $run_updatequery=mysqli_query($db_conn,$update_query); 
    if($run_updatequery)  
    {  
       $_SESSION['update_product_success'] = "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Product ID $update_productid </strong> has been Updated successfully.</div>";
    }
    }  
    if (($error_flag == false) && ($uploadOk == 2)){  
     
    $update_query="UPDATE product SET sku='$update_productsku',name='$update_productname',status='$update_productstatus',start_date='$update_productstartdate',end_date='$update_productenddate',description='$update_productdesc',image='$update_productimage',cat_id='$product_category' where id='$update_productid'";  
    
    $run_updatequery=mysqli_query($db_conn,$update_query); 
    if($run_updatequery)  
    {
        $_SESSION['update_product_success'] = "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Product ID $update_productid </strong> has been Updated successfully.</div>";
    }
    }
}
?>
<body>
    <div class="container">
        <div class="row">
        <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <h1 align="center">Edit The Products</h1> 
        </div>  
        </div>
        <p></p>
        <div class="row">   
        <div class="pull-right offset-0">
        <a href="logout.php?logout" class="btn btn-danger" role="button"><i class="fa fa-sign-out"></i> Logout</a>
        </div>
        </div>
        <p></p>
        <?php
        if(isset($_POST['editproduct']))
        {  ?>
        <div class="container">
            <div class="row">
            <h2>Edit Products</h2>
        <form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post" enctype="multipart/form-data">
        <div class="form-group">
        <label for="editproductsku11">Product SKU</label>
        <input type="text" class="form-control" name="editproductsku" value="<?php echo $product[1];?>" placeholder="productsku" required  autofocus>
      </div>
      <div class="form-group">
        <label for="editproductname11">Product Name</label>
        <input type="text" class="form-control" name="editproductname" value="<?php echo $product[2];?>" placeholder="Productname" required  autofocus>
      </div>
      <div class="form-group">
            <label for="editproductstatus11">Status:</label>
            <label class="radio-inline"><input type="radio" name="opteditstatus" value="1" <?php if (strlen($product[3]) > 0 && $product[3]=="1") echo "checked";?>>Active</label>
            <label class="radio-inline"><input type="radio" name="opteditstatus" value="0" <?php if (strlen($product[3]) > 0 && $product[3]=="0") echo "checked";?>>Inactive</label>
      </div>
      <div class="form-group form-inline">
        <label for="editproductstartdatel11">Product Start Date</label>
        <input type="date" class="form-control edit_from_date" name="editproductstartdate" value="<?php echo (!empty($product[4])?$product[4]:'')  ;?>" placeholder="Start Date" autofocus required>
        
        <label for="editproductenddatel11">Product End Date</label>
        <input type="date" class="form-control edit_to_date" name="editproductenddate" value="<?php echo (!empty($product[5])?$product[5]:'') ?>" placeholder="End Date" autofocus required>
      </div>
     
      <div class="form-group">
        <label for="editproductimage11">Product Image</label>
        <input type="file" name="editproductimage">
        <img alt="<?php echo $product[2];?>" height="100px" width="100px" src="productuploads/<?php echo $product[7];?>">
      </div>
      <div class="form-group">
            <label for="exampleInputcategory">Select Category:</label>
            <?php
                    function fetchCategoryTree($parent = 0, $spacing = '', $user_tree_array = '') {
                      global $db_conn;
                      if (!is_array($user_tree_array))
                        $user_tree_array = array();
                      $select_categorydropdown_query="Select * from category where 1 AND parent_id ='".$parent."' ORDER BY id ASC";
                     
                      $query = mysqli_query($db_conn,$select_categorydropdown_query);
                      if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_object($query)) {
                        $user_tree_array[] = array("id" => $row->id, "name" => $spacing . $row->cat_name);
                        $user_tree_array = fetchCategoryTree($row->id, $spacing . '&nbsp;&nbsp;', $user_tree_array);
                        }
                      }
                      return $user_tree_array;
                    }
                ?>
                <?php 
                    $categoryList = fetchCategoryTree();           
                ?>
                <select class="form-control" name='updateproductcategory[]' multiple='multiple' style='height: 300px;' required>            
                        <?php foreach($categoryList as $cl) {
                            $cat_selected_id = $product[8]; 
                            $cat_selected_id_array = explode(",",$cat_selected_id);
                            
                            $selected = (in_array($cl["id"],$cat_selected_id_array) ? 'selected' : ''); 
                            ?>
                            <option value="<?php echo $cl["id"]; ?>"<?php echo $selected;?>><?php echo $cl["name"]; ?></option>
                        <?php } ?>
            
                </select>
      </div>
      <div class="form-group">
                <label for="editproductdesc11">Description</label>
                <textarea class="ckeditor" rows="5" cols="100" id="register_editor" name="edit_admin_productdesc"><?php echo stripslashes($product[6]);?></textarea>
      </div>
      
      <input type="hidden" name="updateproductid" value="<?php echo $product[0];?>">
      <input type="hidden" name="updateproductimage" value="<?php echo $product[7];?>">
      <input class="btn btn-lg btn-success btn-block" type="submit" value="Save" name="saveproductbtn" >
    </form>
    </div>
    </div>
    <?php  }
        else {
            header("Location: view_products.php");
        }
        ?>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="lib/js/bootstrap.min.js"></script>
    <script src="lib/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
	$(function() {
	$(".btn.btn-danger.delete").click(function(){
	var element = $(this);
	var del_id = element.attr("id");
	var info = 'id=' + del_id;
	if(confirm("Are you sure you want to delete this?"))
	{
	 $.ajax({
	   type: "POST",
	   url: "delete.php",
	   data: info,
	   success: function(){
	   	
	 }
	});
      //$(this).parents("tr").animate({backgroundColor: "#003" }, "slow").animate({opacity: "hide"}, "slow").remove();
     // $(this).parents("tr").remove(); 
       	$( this ).parents("tr").hide( 1200, function() {
    	$( this ).remove();
  		});
	 }
	return false;
	});
	});
</script>


<!----for datepicker start---->
<script>
$(document).ready(function(){
        var today = new Date();
        $(".edit_from_date").datepicker({
        startDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            format: 'yyyy-mm-dd',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var startDate = new Date(selected.date.valueOf());
            $('.edit_to_date').datepicker('setStartDate', startDate);
        }).on('clearDate', function (selected) {
            $('.edit_to_date').datepicker('setStartDate', null);
        });

$(".edit_to_date").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    }).on('changeDate', function (selected) {
        var endDate = new Date(selected.date.valueOf());
        $('.edit_from_date').datepicker('setEndDate', endDate);
    }).on('clearDate', function (selected) {
        $('.edit_from_date').datepicker('setEndDate', null);
    });
});
</script>
<!----for datepicker ends---->
     
  </body>
</html>