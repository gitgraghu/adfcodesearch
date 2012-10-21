<?php
function buildSqlString($component,$input,$filter,$pagenum){

$components = explode(" ", $component);
$parameters = explode(" ", $input);
$size = 5;

$pagestart= ($pagenum-1) * $size;

if($component==''){
 $selectClause = 'SELECT cp.id id,  COUNT(ct.tagid) count, cp.title title, cp.content content, cp.type type,cp.summary summary FROM codepattern cp, cptag ct, tag t ';
 $whereClause  = 'WHERE cp.id = ct.codepatternid AND t.id = ct.tagid AND t.name IN  (\''.implode("','",$parameters).'\') ';
 if($filter!='ALL')
 {$whereClause  = $whereClause . 'AND cp.type LIKE \''. $filter .'\' ';}
 $orderByClause = 'GROUP BY cp.id ORDER BY count desc LIMIT '. $pagestart . ','. $size;
 $sql_query = $selectClause . $whereClause . $orderByClause;
 }
  else if($input==''){
 $selectClause = 'SELECT cp.id id, cp.title title, cp.content content, cp.type type,cp.summary summary FROM codepattern cp, component c, cpcomponent cpc ';
 $whereClause  = 'WHERE c.shortcode IN  (\''.implode("','",$components).'\') AND cpc.codepatternid = cp.id AND c.id = cpc.componentid ';
 if($filter!='ALL')
 {$whereClause  = $whereClause . 'AND cp.type LIKE \''. $filter .'\' ';}
 $orderByClause = 'GROUP BY cp.id LIMIT '. $pagestart . ','. $size;
 $sql_query = $selectClause . $whereClause . $orderByClause;
  }
  else{
 $selectClause = 'SELECT cp.id id,  COUNT(ct.tagid) count, cp.title title, cp.content content, cp.type type,cp.summary summary FROM codepattern cp, cptag ct, tag t, component c, cpcomponent cpc ';
 $whereClause  = 'WHERE cp.id = ct.codepatternid AND t.id = ct.tagid AND t.name IN  (\''.implode("','",$parameters).'\') AND c.shortcode IN  (\''.implode("','",$components).'\') AND cpc.codepatternid = cp.id AND c.id = cpc.componentid ';
 if($filter!='ALL')
 {$whereClause  = $whereClause . 'AND cp.type LIKE \''. $filter .'\' ';}
 $orderByClause ='GROUP BY cp.id ORDER BY count desc LIMIT '. $pagestart . ','. $size;
 $sql_query = $selectClause . $whereClause . $orderByClause;
  }
  return $sql_query;
  }
$input = isset($_GET['searchtext'])?$_GET['searchtext']:'';
$component = isset($_GET['component'])?$_GET['component']:'';
$components = explode(" ", $component);
$parameters = explode(" ", $input);
$filter = isset($_GET['filter'])?$_GET['filter']:'';
$page = isset($_GET['page'])?$_GET['page']:1;


$con = mysql_connect("localhost","codesearchuser","codesearchpass");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("codesearch", $con);

$sqlString = buildSqlString($component,$input,$filter,$page);
 /*if($component==''){
 $sql_result = mysql_query('SELECT DISTINCT (cp.id) id,  COUNT(ct.tagid) count, cp.title title, cp.content content, cp.type type,cp.summary summary FROM codepattern cp, cptag ct, tag t WHERE cp.id = ct.codepatternid AND t.id = ct.tagid AND t.name IN  (\''.implode("','",$parameters).'\') GROUP BY cp.id ORDER BY count desc LIMIT '. $page_num . ','. $size);
 }
 else if($input==''){
 $sql_result = mysql_query('SELECT DISTINCT (cp.id) id, cp.title title, cp.content content, cp.type type,cp.summary summary FROM codepattern cp, component c, cpcomponent cpc WHERE c.shortcode IN  (\''.implode("','",$components).'\') AND cpc.codepatternid = cp.id AND c.id = cpc.componentid GROUP BY cp.id LIMIT '. $page_num . ','. $size);
	}
	else{
  $sql_result = mysql_query('SELECT DISTINCT (cp.id) id,  COUNT(ct.tagid) count, cp.title title, cp.content content, cp.type type,cp.summary summary FROM codepattern cp, cptag ct, tag t, component c, cpcomponent cpc WHERE cp.id = ct.codepatternid AND t.id = ct.tagid AND t.name IN  (\''.implode("','",$parameters).'\') AND c.shortcode IN  (\''.implode("','",$components).'\') AND cpc.codepatternid = cp.id AND c.id = cpc.componentid GROUP BY cp.id ORDER BY count desc LIMIT '. $page_num . ','. $size);
	}
	*/
	
 $sql_result = mysql_query($sqlString);
 
if($sql_result){
$return_arr = array();

while($row = mysql_fetch_array($sql_result,MYSQL_ASSOC))
{
      $row_array = array();
      $row_array['id']		= $row['id'];
      $row_array['title'] 	= $row['title'];
	  $row_array['content'] = $row['content'];
	  $row_array['type'] 	= $row['type'];
      $row_array['summary'] = $row['summary'];
	 
   array_push($return_arr,$row_array);
}
$json = json_encode($return_arr);
echo $json;
}
?>