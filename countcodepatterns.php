<?php
function buildSqlString($component,$input,$filter){

$components = explode(" ", $component);
$parameters = explode(" ", $input);

if($component==''){
 $selectClause = 'SELECT COUNT(DISTINCT(cp.id)) codecount FROM codepattern cp, cptag ct, tag t ';
 $whereClause  = 'WHERE cp.id = ct.codepatternid AND t.id = ct.tagid AND t.name IN  (\''.implode("','",$parameters).'\') ';
 if($filter!='ALL')
 {$whereClause  = $whereClause . 'AND cp.type LIKE \''. $filter .'\' ';}
 $sql_query = $selectClause . $whereClause;
 }
  else if($input==''){
 $selectClause = 'SELECT COUNT(DISTINCT(cp.id)) codecount FROM codepattern cp, component c, cpcomponent cpc ';
 $whereClause  = 'WHERE c.shortcode IN  (\''.implode("','",$components).'\') AND cpc.codepatternid = cp.id AND c.id = cpc.componentid ';
 if($filter!='ALL')
 {$whereClause  = $whereClause . 'AND cp.type LIKE \''. $filter .'\' ';}
 $sql_query = $selectClause . $whereClause;
  }
  else{
 $selectClause = 'SELECT COUNT(DISTINCT(cp.id)) codecount FROM codepattern cp, cptag ct, tag t, component c, cpcomponent cpc ';
 $whereClause  = 'WHERE cp.id = ct.codepatternid AND t.id = ct.tagid AND t.name IN  (\''.implode("','",$parameters).'\') AND c.shortcode IN  (\''.implode("','",$components).'\') AND cpc.codepatternid = cp.id AND c.id = cpc.componentid ';
 if($filter!='ALL')
 {$whereClause  = $whereClause . 'AND cp.type LIKE \''. $filter .'\' ';}
 $sql_query = $selectClause . $whereClause;
  }
  return $sql_query;
  }
$input = isset($_GET['searchtext'])?$_GET['searchtext']:'';
$component = isset($_GET['component'])?$_GET['component']:'';
$components = explode(" ", $component);
$parameters = explode(" ", $input);
$filter = isset($_GET['filter'])?$_GET['filter']:'';
$con = mysql_connect("localhost","codesearchuser","codesearchpass");
if (!$con)
{
die('Could not connect: ' . mysql_error());
}

mysql_select_db("codesearch", $con);

$sqlString = buildSqlString($component,$input,$filter);
	
$sql_result = mysql_query($sqlString);
if($sql_result){
$return_arr = array();

while($row = mysql_fetch_array($sql_result,MYSQL_ASSOC))
{
      $row_array = array();
      $row_array['count']	= $row['codecount'];
	  
   array_push($return_arr,$row_array);
}
$json = json_encode($return_arr);
echo $json;
}
?>