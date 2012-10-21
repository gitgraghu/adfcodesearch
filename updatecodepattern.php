<?php
$id 		= isset($_POST['id'])?$_POST['id']:'';
$title 		= isset($_POST['title'])?$_POST['title']:'';
$content 	= isset($_POST['content'])?$_POST['content']:'';
$type 		= isset($_POST['type'])?$_POST['type']:'';
$summary 		= isset($_POST['summary'])?$_POST['summary']:'';
$tags 		= isset($_POST['tags'])?$_POST['tags']:'';
$components	= isset($_POST['components'])?$_POST['components']:'';
$tag_array  = explode(" ",$tags);
$components_array =explode(" ",$components);

	$con = mysql_connect("localhost","codesearchuser","codesearchpass");
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("codesearch", $con);

 mysql_query('DELETE FROM codepattern WHERE id='.$id);
 mysql_query('DELETE FROM cptag WHERE codepatternid ='.$id);
 mysql_query('DELETE FROM cpcomponent WHERE codepatternid ='.$id);
 mysql_query('INSERT INTO codepattern (title,content,type,summary) VALUES (\''.$title.'\',\''.$content.'\',\''.$type.'\',\''.$summary.'\')');
 $cpid = mysql_insert_id();
 
 foreach ($tag_array as $tag)
{
	$tag_results = mysql_query('SELECT id FROM tag WHERE name LIKE \''.$tag.'\'');
	$num = mysql_num_rows($tag_results);
	
	if($num >0){
		while($row = mysql_fetch_array($tag_results)){
			$tag_id =  $row['id'];
			mysql_query('INSERT INTO cptag (codepatternid,tagid) VALUES ('.$cpid.','.$tag_id.')');
			}
	}
	else{
	mysql_query('INSERT INTO tag (name) VALUES (\''.$tag.'\')');
	$tag_id = mysql_insert_id();
	mysql_query('INSERT INTO cptag (codepatternid,tagid) VALUES ('.$cpid.','.$tag_id.')');
	}
 }
 
 
  foreach ($components_array as $component)
{
	$component_results = mysql_query('SELECT id FROM component WHERE shortcode LIKE \''.$component.'\'');
	$num = mysql_num_rows($component_results);
	
	if($num >0){
		while($row = mysql_fetch_array($component_results)){
			$component_id =  $row['id'];
			mysql_query('INSERT INTO cpcomponent (codepatternid,componentid) VALUES ('.$cpid.','.$component_id.')');
			}
	}
 }
 
 
mysql_close($con);
?>