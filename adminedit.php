<html>
<head>
<link rel="stylesheet" href="./styles/styles.css" type="text/css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<body>
<?php

$id = isset($_GET['id'])?$_GET['id']:'';

	    $con = mysql_connect("localhost","codesearchuser","codesearchpass");
		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("codesearch", $con);


$code_results = mysql_query('SELECT title,content,type,summary FROM codepattern WHERE id ='.$id);
while ($row = mysql_fetch_array($code_results)){
	$title = $row['title'];
	$content = $row['content'];
	$type = $row['type'];
	$summary = $row['summary'];
}
$tag_results = mysql_query('SELECT t.name tagname FROM tag t,cptag cpt WHERE t.id=cpt.tagid AND cpt.codepatternid ='.$id);
$tagstring = '';
while ($row = mysql_fetch_array($tag_results)){
	$tag = $row['tagname'];
	$tagstring = $tagstring.' '.$tag;
}

$component_results = mysql_query('SELECT c.shortcode shortcode FROM component c,cpcomponent cpc WHERE c.id=cpc.componentid AND cpc.codepatternid ='.$id);
$componentsstring = '';
while ($row = mysql_fetch_array($component_results)){
	$component= $row['shortcode'];
	$componentsstring = $componentsstring.' '.$component;
}
?>

<div class="wrapper">

	<div class="insertform">
	<form action="updatecodepattern.php" method="POST">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<div class="formelement">
	Title:
	<input type="text" name="title" size="100" value="<?php echo $title; ?>"></input>
	</div>
	<div class="formelement">
	Content: <br>
	<textarea rows="5" cols="100" name="content"><?php echo $content; ?></textarea>
	</div>
	<div class="formelement">
	Type:
	<select name="type">
	<? 
	if($type=="CODE")
    echo '<option value="CODE" selected="selected">Code</option>';
	else
	echo '<option value="CODE">Code</option>';
	if($type=="VIDEO")
    echo '<option value="VIDEO" selected="selected">Video</option>';
	else
	echo '<option value="VIDEO">Video</option>';
	if($type=="BLOG")
    echo '<option value="BLOG" selected="selected">Blog</option>';
	else
	echo '<option value="BLOG">Blog</option>';
  ?>
  </select>
	</div>
	<div class="formelement">
	Tags:
	<input type="text" name="tags" size="100" value="<?php echo $tagstring; ?>"></input>
	</div>
	<div class="formelement">
	Components:
	<input type="text" name="components" size="100" value="<?php echo $componentsstring; ?>"></input>
	</div>
	<div class="formelement">
	Summary: <br>
	<textarea rows="5" cols="100" name="summary"><?php echo $summary; ?></textarea>
	</div>
	<input type="submit" name="submit" />
	</form>
	</div>

</div>
</body>
</html>