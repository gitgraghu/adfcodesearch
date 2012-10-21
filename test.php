<!DOCTYPE html>
<html lang="en">
  <head>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <title>ADF Code Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">
	<link rel="stylesheet" href="./css/styles2.css" type="text/css">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="./img/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="./img/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="./img/logo.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="./img/logo.png">
    <link rel="apple-touch-icon-precomposed" href="./img/logo.png">
	<script>
	function build_video_object(htmlobject,item){
	htmlobject.str  = htmlobject.str + '<div class="resultobject">';
	htmlobject.str  = htmlobject.str + '<div class="resultheader">';
	htmlobject.str  = htmlobject.str + '<div class="resulttitle"><h4>' + item.title + '</h4></div>';	
	htmlobject.str  = htmlobject.str + '<div class="editbutton">' + '<a href="adminedit.php?id='+item.id+'">Edit</a>' + '</div>';
	htmlobject.str  = htmlobject.str + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="row-fluid">';
	htmlobject.str  = htmlobject.str + '<div class="resultvideocontent pull-left span4">' + '<iframe width="300" height="210" src="http://www.youtube.com/embed/' + item.content + '" frameborder="0" allowfullscreen></iframe>' + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="resultsummary videosummary pull-right span8">' + item.summary + '</div>';
	htmlobject.str  = htmlobject.str + '</div>';
	htmlobject.str  = htmlobject.str + '</div>';
}
function build_code_object(htmlobject,item){
	htmlobject.str  = htmlobject.str + '<div class="resultobject">';
	htmlobject.str  = htmlobject.str + '<div class="resultheader">';
	htmlobject.str  = htmlobject.str + '<div class="resulttitle"><h4>' + item.title + '</h4></div>';
	htmlobject.str  = htmlobject.str + '<div class="editbutton">' + '<a href="adminedit.php?id='+item.id+'">Edit</a>' + '</div>';
	htmlobject.str  = htmlobject.str + '</div>';
	htmlobject.str  = htmlobject.str + '<div class="resultcontent">' + '<pre class="prettyprint lang-java pre-scrollable">' + item.content + '</pre></div>';
	
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
	htmlobject.str  = htmlobject.str + '<div class="resulttitle"><h4>' + item.title + '</h4></div>';
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

var filterstring = $(".filter li.active a").html().toUpperCase();
dataString = dataString + '&filter=' + filterstring;
$.ajax({
type:"GET",
url:"codepatterns.php",
dataType:'json',
data:dataString,
beforeSend:function(){
             $("body").css("cursor", "progress");
        },
success: function (jsondata) { 
  var htmlobject = {str:''};
  
    $.each(jsondata, function(i, item) {

	var filter = $(".filter li.active a").html().toUpperCase();
	
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
	
    },
complete: function(){
          $("body").css("cursor", "default");
        }	
});

}
function query_count_code_patterns(dataString){

var filterstring = $(".filter li.active a").html().toUpperCase();
dataString = dataString + '&filter=' + filterstring;
$.ajax({
type:"GET",
url:"countcodepatterns.php",
dataType:'json',
data:dataString,
beforeSend:function(){
             $("body").css("cursor", "progress");
        },
success: function (jsondata) { 
  var htmlobject = {str:''};
  
    $.each(jsondata, function(i, item) {
	var pages = Math.ceil((item.count/5));
	if(pages>1){
	htmlobject.str = htmlobject.str + '<div class="pagination pagination-right">';
	htmlobject.str = htmlobject.str + '<ul>';
	htmlobject.str = htmlobject.str + '<li class="pageleft"><a href="#"><</a></li>';
	for(var i=1;i<=pages;i++)
    {
	if(i==1)
	htmlobject.str = htmlobject.str + '<li class="active"><a href="#">'+i+'</a></li>';
	else
	htmlobject.str = htmlobject.str + '<li><a href="#">'+i+'</a></li>';
	}
	htmlobject.str = htmlobject.str + '<li class="pageright"><a href="#">></a></li>';
    htmlobject.str = htmlobject.str + '</ul>';
    htmlobject.str = htmlobject.str + '</div>';
	}
		});
	$("#paging").html(htmlobject.str).show();	
	
    },
complete: function(){
          $("body").css("cursor", "default");
        }	
});

}
function displaychildren(htmlobject,item){
htmlobject.str = htmlobject.str + '<li class="component">';
htmlobject.str = htmlobject.str + '<a href="'+ item.shortcode + '" class="componentlink"><span class="plus">+</span>' + item.name + '</a>';
htmlobject.str = htmlobject.str + '<ul class="nav nav-list children hidden">'; 
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
	htmlobject.str = htmlobject.str + '<ul class="nav nav-list"><li class="nav-header component active"><a href="#" class="componentheader">Components</a></li>';
    $.each(jsondata, function(i, item) {
	htmlobject.str = htmlobject.str + '<li class="nav-header component">';
	htmlobject.str = htmlobject.str + '<a href="'+ item.shortcode + '" class="componentlink"><span class="plus">+</span>' + item.name + '</a>';
	htmlobject.str = htmlobject.str + '<ul class="nav nav-list children hidden">';
	$.each(item.children, function(j, childitem) {
	   displaychildren(htmlobject,childitem)
	 });
	htmlobject.str = htmlobject.str + '</ul>' 
	htmlobject.str = htmlobject.str + '</li>';
  });
    htmlobject.str = htmlobject.str + '</ul>';
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

$("#searchtext").keyup(function(e){ 
var code = (e.keyCode ? e.keyCode : e.which);
if(code == 13) {
var input = $(this).val();
var dataString = 'searchtext='+ input;
var component = $("li.component.active a").attr("href");
if(component!='#'){
dataString = dataString + '&component=' + component;
}
query_count_code_patterns(dataString);
query_code_patterns(dataString);
}

});

$(".filter li a").click(function(e){
e.preventDefault();
var clicked_link = e.target || e.srcElement;

$(".filter li").removeClass("active");
$(clicked_link).parent().addClass("active");
var input = $("#searchtext").val();
var dataString = 'searchtext='+ input; 
var component = $("li.component.active a").attr("href");
if(component!='#'){
dataString = dataString + '&component=' + component;
}
query_count_code_patterns(dataString);
query_code_patterns(dataString);
});

$(".component a").live("click",function(e){
e.preventDefault();
var clicked_link = e.target || e.srcElement;  
$(".component").removeClass("active");
$(clicked_link).closest('li').addClass("active");
var component = $("li.component.active a").attr("href");
var input = $("#searchtext").val();
var dataString = 'searchtext='+ input;
dataString = dataString + '&component='+ component;
query_count_code_patterns(dataString);
query_code_patterns(dataString);
});
$(".pagination li a").live("click",function(e){
e.preventDefault();
var clicked_link = e.target || e.srcElement;
if($(clicked_link).parent().hasClass('pageleft')){
var to_activate = $(".pagination li.active a").parent().prev("li");
if((!$(to_activate).hasClass("pageleft"))){
$(".pagination li").removeClass("active");
$(to_activate).addClass("active");
}
}
else
if($(clicked_link).parent().hasClass('pageright')){
var to_activate = $(".pagination li.active a").parent().next("li");
if((!$(to_activate).hasClass("pageright"))){
$(".pagination li").removeClass("active");
$(to_activate).addClass("active");
}
}
else
{
$(".pagination li").removeClass("active");
$(clicked_link).parent().addClass("active");
}
var input = $("#searchtext").val();
var dataString = 'searchtext='+ input; 
var component = $("li.component.active a").attr("href");
if(component!='#'){
dataString = dataString + '&component=' + component;
}
var page = $(".pagination li.active a").html().toUpperCase();
dataString = dataString + '&page=' + page;
query_code_patterns(dataString);
});
$(".plus").live("click",function(e){
e.preventDefault();
var clicked_link = e.target || e.srcElement;
var componentcollection = $(clicked_link).parent().siblings('.children');

if($(componentcollection).hasClass('hidden')){
$(componentcollection).removeClass('hidden');
$(componentcollection).addClass('show');
$(clicked_link).html('- ');
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
  </head>

<body onload="prettyPrint()">

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><b>ADF Code Search</b></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	
	
	 <div class="container-fluid">
      <div class="row-fluid">
	  
        <div class="span3">
          <div class="well sidebar-nav">
		  <div id="components" class="screen-only"></div>
          </div><!--/.well -->
        </div><!--/span-->
		
		
		 <div class="span9">
          <div class="hero-unit">
            <h3>Search: <input type="text" id="searchtext" class="span10"></input></h3>
				<div class="row-fluid">
				  <div class="span11 filter">
					
						<ul class="nav nav-pills pull-left">
							<li class="active"><a href="#">All</a></li>
							<li><a href="#">Code</a></li>
							<li><a href="#">Video</a></li>
							<li><a href="#">Blog</a></li>
						</ul>	
					<div id="paging"></div>
				</div>
				
				
				</div>
			
          </div>
		  
		  <div class="row-fluid">
            <div class="span12">
				<div id="searchresults"></div>
            </div><!--/span-->
		  
		  </div>
		</div>
	  </div>
	  
	</div>
</body>  
</html>