<html>
<head>
<link rel="stylesheet" href="./styles/styles.css" type="text/css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<body>

<div class="wrapper">

	<div class="insertform">
	<form action="insertcodepattern.php" method="POST">
	<div class="formelement">
	Title:
	<input type="text" name="title" size="100"></input>
	</div>
	<div class="formelement">
	Content: <br>
	<textarea rows="5" cols="100" name="content"></textarea>
	</div>
	<div class="formelement">
	Type:
	<input type="text" name="type"></input>
	</div>
	<div class="formelement">
	Tags:
	<input type="text" name="tags" size="100"></input>
	</div>
	<input type="submit" name="submit" />
	</form>
	</div>

</div>
</body>
</html>