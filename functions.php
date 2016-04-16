<?php
function getMultiCatPath($cval) {
						global $db_conn;
						$select_cat_name_query="Select cat_name from category where id ='".$cval."'";
        				$cat_name =mysqli_query($db_conn,$select_cat_name_query);
						$cat_row=mysqli_fetch_row($cat_name);
						return($cat_row[0]);
					}

function createTreename($category_name,$parent_id) {
			  		global $db_conn;
					$cat_name = $category_name;
				
					$get_parentid_query="select cat_name,parent_id from category where id='$parent_id'";
		       		$getparenrun=mysqli_query($db_conn,$get_parentid_query);
		       		$getrow=mysqli_fetch_row($getparenrun);
					
					if($getrow['1'] == 0){
			   			$cat_name .= ">>".$getrow['0'];
			   		} else {
			   			$cat_name .= ">>".createTreename($getrow['0'],$getrow['1']);
			   		}
		
			  		return $cat_name;
			}
function reverse_string($string)
	{
	$explodestr = explode(">>", $string);
	$reversestr = array_reverse($explodestr);
	$impstring = implode(">>", $reversestr);
	$trimmed = trim($impstring, ">>");
	$finalstring = "<ul><li>".$trimmed."</ul></li>";
	return $finalstring;
	}
?>