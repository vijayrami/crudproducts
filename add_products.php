<?php
session_start();
include_once("database/db_conection.php");
if(!$_SESSION['admin_user'])  
{  
  header("Location: index.php"); 
}  
include_once("header.php");

if(isset($_POST['addproductbtn']))//this will tell us what to do if some data has been post through form with button.  
{
    
    $error_flag = false;
    $uploadOk = 1;
    $target_dir = "productuploads/";
    
    $add_productsku = mysqli_real_escape_string($db_conn,$_POST['addproductsku']);
    $skuname = strtolower(preg_replace('/\s+/', '-', $add_productsku));
    
    $add_productname = mysqli_real_escape_string($db_conn,$_POST['addproductname']);
    $add_productstartdate = mysqli_real_escape_string($db_conn,$_POST['addproductstartdate']);
    $add_productenddate = mysqli_real_escape_string($db_conn,$_POST['addproductenddate']);
    
    $add_productdesc=mysqli_real_escape_string($db_conn,$_POST['add_admin_productdesc']);
    $final_productdesc = stripslashes($_POST['add_admin_productdesc']);
    
    $selected_product_category = "";
    
    foreach ($_POST['productcategory'] as $names)
    {
            $selected_product_category = $selected_product_category.','.$names;
    }
   
    $product_category = trim($selected_product_category,',');
    
    
    if (strlen($_POST["optaddstatus"]) == 0) {
        $error_flag = true;
        echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'><strong>Status</strong> is required</div>";
    } else {
        $add_productstatus = mysqli_real_escape_string($db_conn,$_POST["optaddstatus"]);
    }
    
    $check_product_sku_query="select * from product WHERE sku='$add_productsku'"; 
    $addproductresult = mysqli_query($db_conn, $check_product_sku_query);   
    if(mysqli_num_rows($addproductresult)>0){
        $error_flag = true;
        echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'>Product SKU With <strong>$add_productsku</strong> is already exist in our database, Please try another one!</div>";       
    }
    if(is_uploaded_file($_FILES['productfile']['tmp_name'])){           
        
        $target_file = $target_dir . basename($_FILES["productfile"]["name"]);
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["productfile"]["tmp_name"]);
        if($check == false) {
            echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>File is not an image.</strong></div>";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["productfile"]["size"] > 5242888) {
            echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, your file is too large.</strong></div>";
            $uploadOk = 0;
        }
        // Allow certain file formats
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);     
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</strong></div>";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, your file was not uploaded.</strong></div>";
        }
        $imageFilename = pathinfo($target_file,PATHINFO_FILENAME);  
        $addfinalimagename = $target_dir.$imageFilename.'_'.time().'.'.$imageFileType;
        $addqueryimage = $imageFilename.'_'.time().'.'.$imageFileType;
            
    } else {
        $addfinalimagename = $target_dir.'no-image.jpg';
        $addqueryimage = 'no-image.jpg';
    }
    if (($error_flag == false) && ($uploadOk == 1)){
	
    move_uploaded_file($_FILES["productfile"]["tmp_name"], $addfinalimagename);
    $add_product_query="insert into product (sku,name,status,start_date,end_date,description,image,cat_id) VALUE ('$skuname','$add_productname','$add_productstatus','$add_productstartdate','$add_productenddate','$add_productdesc','$addqueryimage','$product_category')";  
    
    $run_addproductquery=mysqli_query($db_conn,$add_product_query); 
    if($run_addproductquery)  
    {
        $_SESSION['add_product_success'] = "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Product SKU $skuname </strong> has been added successfully.</div>";  
    }
    }  
}
?>
<body>
<div class="container">
	<div class="row">
	<div class="table-scrol"> 
		<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
	    		<h1 align="center">Add the Products</h1> 
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
		if(isset($_POST['addproducts']))//this will tell us what to do if some data has been post through form with button.  
		{  ?>
		    <div class="container">
		    	<div class="row">
			    <h2>Add Products</h2>
			    
			<form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post" enctype="multipart/form-data">
		      
			  <div class="form-group">
			    <label for="addproductsku11">Product Sku</label>			    
			    <input type="text" class="form-control" name="addproductsku" value="" placeholder="Product_SKU"  maxlength="100" required  autofocus>
			  </div>
			  <div class="form-group">
			    <label for="addproductname11">Product Name</label>
			    <input type="text" class="form-control" name="addproductname" value="" placeholder="Product name" required  autofocus>
			  </div>
			  <div class="form-group">
		    	<label for="addproductstatus">Status:</label>
		    	<label class="radio-inline"><input type="radio" name="optaddstatus" value="1" <?php if (isset($add_productstatus) && $add_productstatus=="1") echo "checked";?>required autofocus>Active</label>
				<label class="radio-inline"><input type="radio" name="optaddstatus" value="0" <?php if (isset($add_productstatus) && $add_productstatus=="0") echo "checked";?>>Inactive</label>
		  	  </div>
			  <div class="form-group form-inline">
			    <label for="addproductstartdatel11">Product Start Date</label>
			    <input type="date" class="form-control from_date" name="addproductstartdate" value="" placeholder="Start Date" autofocus required>
			  	
			    <label for="addproductenddatel11">Product End Date</label>
			    <input type="date" class="form-control to_date" name="addproductenddate" value="" placeholder="End Date" autofocus required>
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
                <select class="form-control" name='productcategory[]' multiple='multiple' style='height: 300px;' required>            
                        <?php foreach($categoryList as $cl) { ?>
                            <option value="<?php echo $cl["id"]; ?>"><?php echo $cl["name"]; ?></option>
                        <?php } ?>
            
                </select>
            </div>
            <div class="form-group">
                <label for="addproductdesc11">Product Description</label>
                <textarea class="ckeditor" rows="5" cols="100" id="register_editor" name="add_admin_productdesc"><?php echo (!empty($final_adduserdesc))?$final_adduserdesc:"";?></textarea>
              </div>  
              <div class="form-group">
                <label for="exampleProductFile">Product Image</label>
                <input type="file" name="productfile" id="exampleProductFile">       
            </div>
			  <input class="btn btn-lg btn-success btn-block" type="submit" value="Add Product" name="addproductbtn" >
			</form>
			</div>
		</div>
		<?php
		 }
		else {
			header("Location: view_products.php");
		}
		?>
		
	</div>
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
        $(".from_date").datepicker({
        startDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            format: 'yyyy-mm-dd',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var startDate = new Date(selected.date.valueOf());
            $('.to_date').datepicker('setStartDate', startDate);
        }).on('clearDate', function (selected) {
            $('.to_date').datepicker('setStartDate', null);
        });

$(".to_date").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    }).on('changeDate', function (selected) {
        var endDate = new Date(selected.date.valueOf());
        $('.from_date').datepicker('setEndDate', endDate);
    }).on('clearDate', function (selected) {
        $('.from_date').datepicker('setEndDate', null);
    });
});
</script>
<!----for datepicker ends---->    
  </body>
</html>