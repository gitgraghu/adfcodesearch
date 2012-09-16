<html>
<head>
<link rel="stylesheet" href="./styles/styles.css" type="text/css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {

$(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
});

$("#searchtext").keyup(function(){
var input = $(this).val();
var dataString = 'searchtext='+ input;

$.ajax({
type:"GET",
url:"codepatterns.php",
dataType:'json',
data:dataString,
success: function (jsondata) { 
  var htmlobject = '';
    $.each(jsondata, function(i, item) {
    htmlobject  = htmlobject + '<div class="resultobject">';
	htmlobject  = htmlobject + '<div class="resulttitle">' + item.title + '</div>';
	htmlobject  = htmlobject + '<div class="resultcontent">' + item.content + '</div>';
	htmlobject  = htmlobject + '</div>';
        });
		
	$("#searchresults").html(htmlobject).show();	
    }
});
});

});
</script>
<body>

<div class="wrapper">

<div class="searchbox">
	<form>
	<div class="searchcontainer">
	Search: 
	<input type="text" id="searchtext"></input>
	</div>
	</form>
</div>

<div id="searchresults"></div>

</div>
</body>
</html>