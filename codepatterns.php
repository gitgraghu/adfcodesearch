<?php

$input = isset($_GET['searchtext'])?$_GET['searchtext']:'';

$parameters = explode(" ", $input);

$con = mysql_connect("localhost","codesearchuser","codesearchpass");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("codesearch", $con);

 
 $sql_result = mysql_query('SELECT DISTINCT (cp.id) id, cp.title title, cp.content content, cp.type type FROM codepattern cp, cptag ct, tag t WHERE cp.id = ct.codepatternid AND t.id = ct.tagid AND t.name IN  (\''.implode("','",$parameters).'\')');

 
if($sql_result){
$return_arr = array();

while($row = mysql_fetch_array($sql_result,MYSQL_ASSOC))
{
      $row_array = array();
      $row_array['id']		= $row['id'];
      $row_array['title'] 	= $row['title'];
	  $row_array['content'] = $row['content'];
	  $row_array['type'] 	= $row['type'];
   
	 
   array_push($return_arr,$row_array);
}
$json = json_encode($return_arr);
echo $json;
}
?>