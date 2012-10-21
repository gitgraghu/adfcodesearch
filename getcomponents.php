<?php
function getChildren($array,$id){
$sql_result= mysql_query('SELECT id,name,parentid,shortcode FROM component WHERE parentid ='. $id );
while($row = mysql_fetch_array($sql_result,MYSQL_ASSOC)){
$row_array = array();
$child_array = array();
$row_array['id'] = $row['id'];
$row_array['name'] = $row['name'];
$row_array['parentid'] = $row['parentid'];
$row_array['shortcode'] = $row['shortcode'];
$row_array['children'] = getChildren($child_array,$row['id']);
array_push($array,$row_array);	 
}

return $array;
}
$con = mysql_connect("localhost","codesearchuser","codesearchpass");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("codesearch", $con);

 $sql_result = mysql_query('SELECT id,name,parentid,shortcode FROM component WHERE parentid = 0');
 
if($sql_result){
$return_arr = array();

while($row = mysql_fetch_array($sql_result,MYSQL_ASSOC))
{
      $row_array = array();
	  $child_array = array();
      $row_array['id']		= $row['id'];
      $row_array['name'] 	= $row['name'];
	  $row_array['parentid'] = $row['parentid'];
	  $row_array['shortcode'] = $row['shortcode'];
	  $row_array['children'] = getChildren($child_array,$row['id']);
		
	  
   array_push($return_arr,$row_array);
}
$json = json_encode($return_arr);
echo $json;
}
?>