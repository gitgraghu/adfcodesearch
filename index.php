<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<link href="assets/prettify/prettify.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="./css/styles.css" type="text/css">
<script type="text/javascript" src="assets/prettify/prettify.js"></script>
<script>
function build_video_object(htmlobject,item){
	htmlobject.str  = htmlobject.str + '<div class="resultobject">';
	htmlobject.str  = htmlobject.str + '<div class="resultheader">';
	htmlobject.str  = htmlobject.str + '<div class="resulttitle">' + item.title + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="editbutton">' + '<a href="adminedit.php?id='+item.id+'">Edit</a>' + '</div>';
	htmlobject.str  = htmlobject.str + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="resultvideocontent">' + '<iframe width="300" height="210" src="http://www.youtube.com/embed/' + item.content + '" frameborder="0" allowfullscreen></iframe>' + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="resultsummary videosummary">' + item.summary + '</div>';
	htmlobject.str  = htmlobject.str + '</div>';
}
function build_code_object(htmlobject,item){
	htmlobject.str  = htmlobject.str + '<div class="resultobject">';
	htmlobject.str  = htmlobject.str + '<div class="resultheader">';
	htmlobject.str  = htmlobject.str + '<div class="resulttitle">' + item.title + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="editbutton">' + '<a href="adminedit.php?id='+item.id+'">Edit</a>' + '</div>';
	htmlobject.str  = htmlobject.str + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="resultcontent">' + '<pre class="prettyprint lang-java">' + item.content + '</pre></div>';
	
	if(item.summary){
	var link_array =  item.summary.split(" ");
	htmlobject.str  = htmlobject.str + '<div class="resultsummary blogsummary"><b>References: </b><br>';
	for (var i = 0; i < link_array.length; i++) {	
	htmlobject.str  = htmlobject.str + '<a href="'+link_array[i]+'">' + link_array[i] + '</a><br>';
	}
	htmlobject.str  = htmlobject.str + '</div>';
	}
	htmlobject.str  = htmlobject.str + '</div>';
}
function build_blog_object(htmlobject,item){
	htmlobject.str  = htmlobject.str + '<div class="resultobject">';
	htmlobject.str  = htmlobject.str + '<div class="resultheader">';
	htmlobject.str  = htmlobject.str + '<div class="resulttitle">' + item.title + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="editbutton">' + '<a href="adminedit.php?id='+item.id+'">Edit</a>' + '</div>';
	htmlobject.str  = htmlobject.str + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="resultcontent">';
	var link_array =  item.content.split(" ");
	for (var i = 0; i < link_array.length; i++) {	
	htmlobject.str  = htmlobject.str + '<a href="'+link_array[i]+'">' + link_array[i] + '</a><br>';
	}
	htmlobject.str  = htmlobject.str + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="resultsummary blogsummary">' + item.summary + '</div>';
	htmlobject.str  = htmlobject.str + '</div>';
}
function query_code_patterns(dataString){

$.ajax({
type:"GET",
url:"codepatterns.php",
dataType:'json',
data:dataString,
success: function (jsondata) { 
  var htmlobject = {str:''};
  
    $.each(jsondata, function(i, item) {

	var filter = $(".filter li.selected").html().toUpperCase();
	
	if(filter!="VIDEO" && filter!="BLOG"){
	if(item.type=="CODE")
    {build_code_object(htmlobject,item);}
	}
	
	if(filter!="BLOG" && filter!="CODE"){
    if(item.type=="VIDEO")
	{build_video_object(htmlobject,item);}
	}
	
	if(filter!="VIDEO" && filter!="CODE"){
	if(item.type=="BLOG")
	{build_blog_object(htmlobject,item);}
	}
		});
	$("#searchresults").html(htmlobject.str).show();	
    }
});

}
function displaychildren(htmlobject,item){
htmlobject.str = htmlobject.str + '<li class="component"><span class="plus">+</span>';
htmlobject.str = htmlobject.str + item.name;
htmlobject.str = htmlobject.str + '<ul class="children hidden">'; 
$.each(item.children, function(j, childitem) {
	displaychildren(htmlobject,childitem);
	 })
htmlobject.str = htmlobject.str + '</ul>' 
htmlobject.str = htmlobject.str + '</li>';
}
function query_components(){

$.ajax({
type:"GET",
url:"getcomponents.php",
dataType:'json',
data:'',
success: function (jsondata) { 
  var htmlobject = {str:''};
    htmlobject.str = htmlobject.str + '<div class="componentlist">';
	htmlobject.str = htmlobject.str + '<ul>';
    $.each(jsondata, function(i, item) {
	htmlobject.str = htmlobject.str + '<li class="component">';
	htmlobject.str = htmlobject.str + '<span class="plus">+</span>';
	htmlobject.str = htmlobject.str + '<a href="#">' + item.name + '</a>';
	htmlobject.str = htmlobject.str + '<ul class="children hidden">';
	$.each(item.children, function(j, childitem) {
	   displaychildren(htmlobject,childitem)
	 });
	htmlobject.str = htmlobject.str + '</ul>' 
	htmlobject.str = htmlobject.str + '</li>';
  });
    htmlobject.str = htmlobject.str + '</ul>';
	htmlobject.str = htmlobject.str + '</div>';
	$("#components").html(htmlobject.str).show();	
    }
});

}
$(document).ready(function() {
query_components();
$(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
});

$("#searchtext").keyup(function(){
var input = $(this).val();
var dataString = 'searchtext='+ input;
query_code_patterns(dataString);
});

$(".filter li").click(function(e){

var clicked_link = e.target || e.srcElement;

$(".filter li").removeClass("selected");
$(clicked_link).addClass("selected");
var input = $("#searchtext").val();
var dataString = 'searchtext='+ input;
query_code_patterns(dataString);
});

$(".filterlink").click(function(){

$(".filterbox").show(300);

$(".filterlink").hide();
$(".hidefilterlink").show();

});

$(".hidefilterlink").click(function(){

$(".filterbox").hide(300);

$(".hidefilterlink").hide();
$(".filterlink").show();


});
$(".plus").live("click",function(e){
var clicked_link = e.target || e.srcElement;
var componentcollection = $(clicked_link).next('.children');

if($(componentcollection).hasClass('hidden')){
$(componentcollection).removeClass('hidden');
$(componentcollection).addClass('show');
$(clicked_link).html('-');
$(componentcollection).show(50);
}
else
if($(componentcollection).hasClass('show')){
$(componentcollection).removeClass('show');
$(componentcollection).addClass('hidden');
$(clicked_link).html('+');
$(componentcollection).hide(50);
}
});

});
</script>
<body onload="prettyPrint()">

<div class="wrapper">

<div class="leftcontainer">
<div id="components"></div>
</div>

<div class="midcontainer">
<div class="searchbox">
	<form>
	<div class="searchcontainer">
	Search
	<input type="text" id="searchtext" size="110"></input>
	</div>
	</form>
</div>
<div class="filter" >
<div class="filterlink">
Filter >>
</div>
<div class="hidefilterlink" style="display:none">
Hide Filter <<
</div>
<div class="filterbox" style="display:none">
	<ul>
	<li class="selected">All</li>
	<li>Code</li>
	<li>Video</li>
	<li>Blog</li>	
	</ul>
</div>
</div>

<div id="searchresults"></div>
</div>


</div>
</body>
</html>