<?php
session_start();
include_once("database/db_conection.php");
include_once("functions.php");
if(!$_SESSION['admin_user'])  
{  
  header("Location: index.php"); 
}  
include_once("header.php");
if(isset($_SESSION['update_product_success'])){
    echo $_SESSION['update_product_success'];
    unset ($_SESSION['update_product_success']);
}
if(isset($_SESSION['add_product_success'])){
    echo $_SESSION['add_product_success'];
    unset ($_SESSION['add_product_success']);
}
if(isset($_SESSION['update_product_statuserror'])){
    echo $_SESSION['update_product_statuserror'];
    unset ($_SESSION['update_product_statuserror']);
}
if(isset($_SESSION['update_product_skuerror'])){
    echo $_SESSION['update_product_skuerror'];
    unset ($_SESSION['update_product_skuerror']);
}
if(isset($_SESSION['update_product_imageerror'])){
    echo $_SESSION['update_product_imageerror'];
    unset ($_SESSION['update_product_imageerror']);
}
?>
<body>
<div class="container">
	<div class="row">
	<div class="table-scrol"> 
		<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
	    		<h1 align="center">All the Products</h1> 
	    </div>  
	    </div>
	    <p></p>
	    <div class="row">
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
        	<form action='add_products.php' method="post">      				
			<input type="submit" class="btn btn-success" name="addproducts" value="Add Products">
			</form>
        </div> <!--btn btn-danger is a bootstrap button to show danger-->
		<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
        <a href="view_categories.php" class="btn btn-success" role="button">Categories</a>
        </div>	
		<div class="pull-right offset-0">		
		<!-- Small log out modal start -->
		<button class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-sign-out"></i>Logout</button>

		<div class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
		  <div class="modal-dialog modal-sm">
			<div class="modal-content">
			  <div class="modal-header"><h4>Logout <i class="fa fa-lock"></i></h4></div>
			  <div class="modal-body"><i class="fa fa-question-circle"></i> Are you sure you want to log-off?</div>
			  <div class="modal-footer"><a href="logout.php?logout" class="btn btn-danger btn-block">Logout</a></div>
			</div>
		  </div>
		</div>
		<!-- Small log out modal ends -->
		</div>
		</div>
		<p></p>
		
		<div class="row">
		<div class="table-responsive"><!--this is used for responsive display in mobile and other devices-->  
	  
	  
	    <table id="example" class="table table-bordered table-hover table-striped" style="table-layout: fixed">  
	        <thead>
	        <tr>  
                <th style="width: 2%;">Id</th> 
                <th class="col-md-1 col-sm-1">Product SKU</th>  
	            <th class="col-md-1 col-sm-1">Product Name</th>
	            <th class="col-md-1 col-sm-1">Product Status</th>  
	            <th style="width: 6%;">Product Start Date</th> 
	            <th style="width: 6%;">Product End Date</th> 
	            <th class="col-md-1 col-sm-1">Product Description</th>   
	            <th class="col-md-1 col-sm-1">Product Image</th>
	            <th class="col-md-1 col-sm-1">Product Category</th>    
	            <th class="col-md-1 col-sm-1">Category Tree</th> 
	            <th class="col-md-1 col-sm-1">Action</th> 
            </tr>  
	        </thead> 
	        <tfoot>
            <tr>  
                <th style="width: 2%;">Id</th> 
                <th class="col-md-1 col-sm-1">Product SKU</th>  
                <th class="col-md-1 col-sm-1">Product Name</th>
                <th class="col-md-1 col-sm-1">Product Status</th>  
                <th style="width: 6%;">Product Start Date</th> 
	            <th style="width: 6%;">Product End Date</th> 
                <th class="col-md-1 col-sm-1">Product Description</th>   
                <th class="col-md-1 col-sm-1">Product Image</th>
                <th class="col-md-1 col-sm-1">Product Category</th>    
                <th class="col-md-1 col-sm-1">Category Tree</th> 
                <th class="col-md-1 col-sm-1">Action</th>	             
            </tr>  
            </tfoot>  
	        <tbody>
	        <?php  
	        $num_rec_per_page=10;
	        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
	        $start_from = ($page-1) * $num_rec_per_page; 
	        $view_product_query="select * from product LIMIT $start_from, $num_rec_per_page";//select query for viewing users. 
	        $run=mysqli_query($db_conn,$view_product_query);//here run the sql query.  
	  		if(mysqli_num_rows($run)>0){
	        while($row=mysqli_fetch_array($run))//while look to fetch the result and store in a array $row.  
	        {  
	            $product_id=$row[0];  
	            $product_sku=$row[1];
                $product_name=$row[2];   
	            $product_status=$row[3];
	            $product_start_date=$row[4];  
	            $product_end_date=$row[5]; 
                $product_des=$row[6];   
                $product_image=$row[7];  
                $product_category=$row[8]; 
	             
				//get category name code starts
				$cat_name = explode(",", $product_category);
				$cat_name_count = count($cat_name);
				if ($cat_name_count > 1){					
					
					for($mc=0;$mc<count($cat_name);$mc++){
						global $multi_category_name;
						$multi_category_name .= "<ul><li>";									
				    	$cval = $cat_name[$mc];		
						$multi_category_name .= (getMultiCatPath($cval))."</li></ul><br/>";
					}					
				} else {
					for($c=0;$c<count($cat_name);$c++){
					$select_cat_name_query="Select cat_name from category where id ='".$cat_name[$c]."'";
        			$cat_name =mysqli_query($db_conn,$select_cat_name_query);
					$cat_row=mysqli_fetch_row($cat_name);
					$category_name = "<ul><li>".$cat_row[0]."</li></ul>";
					}
				}
				//get category name code ends
                
				//get category tree code start
				$tree_name = explode(",", $product_category);
				$cat_count = count($tree_name);
				if ($cat_count > 1){					
					
                	for($i=0;$i<count($tree_name);$i++)
					{	
						global $tree_array;								
						//$tree_array .= "<ul><li>";		
				    	$val = $tree_name[$i];		
						$select_multi_name_query="Select cat_name,parent_id from category where id ='".$val."'";
						$multi_name =mysqli_query($db_conn,$select_multi_name_query);
						$multi_row=mysqli_fetch_row($multi_name);	
						
				   		$tree_array .= createTreename($multi_row['0'],$multi_row['1'])."//";
						$words = rtrim($tree_array,"//");
				   		$words = explode('//',$words);
						
						$arr = array_map('reverse_string', $words);
						$tree_array_path = implode("<br/>", $arr);
					}
				} else {
					for($s=0;$s<count($tree_name);$s++){
						$select_sin_name_query="Select cat_name,parent_id from category where id ='".$tree_name[$s]."'";
						$sin_name =mysqli_query($db_conn,$select_sin_name_query);
						$sin_row=mysqli_fetch_row($sin_name);
						
						if($sin_row['1'] == 0){
			   				$stree_array = ">>".$sin_row['0'];
				   		} else {
				   			$stree_array = ">>".createTreename($sin_row['0'],$sin_row['1']);
				   		}
						
						// Format Category name
					   $strtoarray = explode(">>",$stree_array);
					   $reversed = array_reverse($strtoarray);
					   $sireturn_cat = implode(" >> ",$reversed);
					   $sreturn_cat = "<ul><li>".rtrim($sireturn_cat," >> ")."</li></ul>";
					   	
					}
				}
				
				// get category tree code ends
            ?>  
	  		
	        <tr>  
	            <!--here showing results in the table -->  
	            <td style="width: 2%;"><?php echo $product_id;  ?></td>  
	            <td class="col-md-1 col-sm-1"><?php echo $product_sku;  ?></td>
	            <td class="col-md-1 col-sm-1"><?php echo $product_name;  ?></td> 
	            <td class="col-md-1 col-sm-1"><?php echo ($product_status == 1)?'Active':'Inactive';  ?></td>  
	            <td style="width: 6%;"><?php echo $product_start_date;  ?></td>
	            <td style="width: 6%;"><?php echo $product_end_date;  ?></td>    
	            <td class="col-md-2 col-sm-2"><?php echo $product_des;  ?></td>
	            
	            <td class="col-md-1 col-sm-1"><img alt="<?php echo $product_name;?>" height="100px" width="100px" src="productuploads/<?php echo $product_image;?>"></td>  
	            <td class="col-md-1 col-sm-1">
                    <?php if ($cat_name_count > 1){
                    	echo $multi_category_name;
					} else {
						echo $category_name;
					}
					?>
                </td>  
                <td class="col-md-1 col-sm-1">
                    <?php if ($cat_count > 1){
                    	echo $tree_array_path;
					} else {
						echo $sreturn_cat;
					}
					?>
                </td>  
                
	            <td class="col-md-1">	            	
	            	<form action='edit_products.php' method="post">
      				<input type="hidden" name="editproductid" value="<?php echo $product_id ?>">
					
      				<input type="submit" class="btn btn-success" name="editproduct" value="Edit">
      				</form>
      				<p></p>					
					<input type="hidden" id="deleteproductimg" name="deleteproductimg" value="<?php echo $product_image ?>">
      				<input type="submit" class="btn btn-danger delete" id="<?php echo $product_id; ?>" name="deleteproduct" value="Delete">
					
	            </td> <!--btn btn-danger is a bootstrap button to show danger-->	             
	            
	            
	        </tr>  
	  
	        <?php 
				if (isset($tree_array)) {
					unset($tree_array);
				}
				if (isset($multi_category_name)) {
					unset($multi_category_name);
				} 
			}
	        } else {
				echo "<tr><td colspan='11'><h3 class='text-center'>No Products Found</h3></tr></td>";
			}
	        
	        ?>  
	        </tbody>
	    </table> 
	    <?php 
		$paginationsql = "SELECT * FROM product"; 
		$pagination_result = mysqli_query($db_conn,$paginationsql); //run the query
		$total_records = mysqli_num_rows($pagination_result);  //count number of records
		$total_pages = ceil($total_records / $num_rec_per_page); 
		
		echo "<a href='view_products.php?page=1'>".'|<'."</a> "; // Goto 1st page  

		for ($i=1; $i<=$total_pages; $i++) { 
		    echo "<a href='view_products.php?page=".$i."'>".$i."</a> "; 
		}; 
		echo "<a href='view_products.php?page=$total_pages'>".'>|'."</a> "; // Goto last page
		?> 
	    </div>  
	    </div>
	</div>
	</div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="lib/js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
	$(function() {
	$(".btn.btn-danger.delete").click(function(){
	var element = $(this);
	var del_id = element.attr("id");	
	var img_name = $(this).parent().find('input[type="hidden"][name="deleteproductimg"]').val();
	var info = 'id=' + del_id + '&img='+img_name;
	if(confirm("Are you sure you want to delete this?"))
	{
	 $.ajax({
	   type: "POST",
	   url: "delete_products.php",
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
     
  </body>
</html>