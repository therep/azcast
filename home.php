<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Arizona Casting Connection</title>
<meta name="Title" content="Arizona Casting Connection" />
<meta name="Description" content="Arizona Casting Connection" />
<meta name="Keywords" content="Arizona Casting Connection" />
<meta name="robots" content="index,follow" />

<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="skins/tango/skin.css" />
<link rel="stylesheet" href="style-carousel.css" type="text/css" />

<!-- jCarousel library -->
<script type="text/javascript" src="jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="jquery.jcarousel.min.js"></script>

<!--<script src="jquery-1.3.2.js" type="text/javascript"></script>-->
<script src="jquery.scrollTo.js" type="text/javascript"></script>
<script src="jquery.localscroll.js" type="text/javascript" charset="utf-8"></script>
<script src="jquery.serialScroll.js" type="text/javascript" charset="utf-8"></script>
<script src="coda-slider.js" type="text/javascript" charset="utf-8"></script>

<!-- vid player http://videojs.com/-->
<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/c/video.js"></script>

<?php

require_once('util_op.php');

$g_vids = array();
$g_cats = array();
$g_vidcat = array();
$g_vidcatarray = array();

util_main();

function util_main()
{
	GetInfo();
}

function GetInfo()
{
	global $g_vids;
	global $g_cats;
	global $g_vidcat;
	global $g_vidcatarray;
		
	$vids = SQLGetData("SELECT * FROM vids"); 

	if ($vids)
	{
		$g_vids = $vids;
	}

	$cats = SQLGetData("SELECT * FROM cats"); 

	if ($cats)
	{
		$g_cats = $cats;
	}
	
	$vidcat = SQLGetData("SELECT * FROM vidcat"); 

	if ($vidcat)
	{
		$g_vidcat = $vidcat;
	}

	foreach($g_cats as $cat)
	{
		foreach($g_vidcat as $vidcat)
		{
			if ($cat['cat_id'] == $vidcat['cat_id'])
			{
				foreach($g_vids as $vid)
				{
					if ($vid['video_id'] == $vidcat['vid_id'])
					{
						$g_vidcatarray[$cat['name']][] = $vid;
						break;
					}
				}
			}
		}
	}
}

?>

<style type="text/css">

/**
 * Overwrite for having a carousel with dynamic width.
 */
.jcarousel-skin-tango .jcarousel-container-horizontal {
    bottom: 30px;
	width: 800px;
	height: 25px;
	border: none;
		margin-left: auto ;
	margin-right: auto ;
	
}

.jcarousel-skin-tango .jcarousel-clip-horizontal {
    width: 100%;
}

.jcarousel-skin-tango .jcarousel-item {
    
	width: 100px;
    height: 75px;
}

	p.category:link {color:#969696;}      
      p.category:visited {color:#969696;}  
      p.category:hover {color:#3cbaeb;}  
      p.category:active {color:#3cbaeb;}
	  
p.category {
    color: #969696;
	text-align: center;
	vertical-align: middle;
	cursor: pointer;
}

.jcarousel-skin-tango .jcarousel-container {
    background: #FFFFFF;
    /*border: none;*/
}


</style>


<script type="text/javascript">
/*
jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel({
        visible: 3, buttonNextHTML:"<div>Next</div>", 
		buttonPrevHTML:"<div>Prev</div>"
    });
});
*/

var g_vids = {};
var g_cats = {};
var g_vidcat = {};
var g_vidcatarray = {};
var g_sel_cat_vid = {}; 
var g_video;

main_util();

function main_util()
{
	setinfo();
	

}

function setinfo()
{
	g_vids = <?php echo json_encode($g_vids ); ?>;
	g_cats = <?php echo json_encode($g_cats ); ?>;
	g_vidcat = <?php echo json_encode($g_vidcat ); ?>;
	g_vidcatarray = <?php echo json_encode($g_vidcatarray ); ?>;
	
}

jQuery(document).ready(function() {
	
    jQuery('#mycarousel').jcarousel({
        visible: 3
    });

	g_sel_cat_vid['name'] = g_cats[0]['name'];
	
	$('.content_change').hide();
	$('#bio_div').show();
	
	$('#slider li a img').attr("alt", "");
	
	$('p.category:eq(0)').css('color', '#3cbaeb');
		
	UpdateImages($('p.category:eq(0)').text());	
	
	//initialize video player
	g_video = _V_("video_player", { "controls": true, "autoplay": false, "preload": "auto" });

	$('.video_image').live('click', function() { 

		g_sel_cat_vid['indx'] = $(this).parent('li').index();

		$('.content_change').hide();
		$('#video_player_div').show();

		$('#menu_list li a').css('color', '#969696');
		$('#spots').css('color', '#3cbaeb');

		var img = $(this).attr('src');
		console.log("src " + img);
		
		g_video.pause();
		$('.vjs-poster').attr("src", img);
		$('.vjs-poster').show();
		
		
		var vid = g_vidcatarray[g_sel_cat_vid['name']][g_sel_cat_vid['indx']]['filename'];
		
		g_video.src({ type: "video/flv", src: "uploads/"+vid });
		
		$('.video_info').remove();
		$('.video_info_change').remove();
		
		var cli = g_vidcatarray[g_sel_cat_vid['name']][g_sel_cat_vid['indx']]['client'];
		var dir = g_vidcatarray[g_sel_cat_vid['name']][g_sel_cat_vid['indx']]['director'];
		var prod = g_vidcatarray[g_sel_cat_vid['name']][g_sel_cat_vid['indx']]['production_co'];
		var ag = g_vidcatarray[g_sel_cat_vid['name']][g_sel_cat_vid['indx']]['agency'];

		if (cli)
			$('#video_player_div').append($("<span class='video_info'>Client: </span><span class='video_info_change'>"+cli+"<br></span>"));
		if (dir)
			$('#video_player_div').append($("<span class='video_info'>Director: </span><span class='video_info_change'>"+dir+"<br></span>"));
		if (prod)
			$('#video_player_div').append($("<span class='video_info'>Production Co: </span><span class='video_info_change'>"+prod+"<br></span>"));			
		if (ag)
			$('#video_player_div').append($("<span class='video_info'>Agency: </span><span class='video_info_change'>"+ag+"<br></span>"));
		
	});

	$('#spots, #bio, #actors').click(function() 
	{
		$('.content_change').hide();
		
		if ($(this).is('#bio'))
			$('#bio_div').show();
		if ($(this).is('#spots'))
			$('#video_player_div').show();
		if ($(this).is('#actors'))
			$('#actors_div').show();
		
		$('#menu_list li a').css('color', '#969696');
		$(this).css('color', '#3cbaeb');
	});
	
	
	$('.category').click(function() 
	{
		$('p.category').css('color', '#969696');
		$(this).css('color', '#3cbaeb');
		
		g_sel_cat_vid['name'] = $(this).text();
		
		UpdateImages($(this).text());
	});
	
    $("#btn1").click(function () 
	{
		$('#s0').fadeOut();
		$('#s1').fadeIn();
	});
	
    $("#btn2").click(function () 
	{
		$('#s1').fadeOut();
		$('#s0').fadeIn();
	});

    $("#btn3").click(function () 
	{
		//$('#first ul li:eq(0) a img').attr("src","images/F-150-Truck-worker-120X90.jpg");
		//$('#first ul li a img').show();
		
		UpdateImages();
		
		//	var x = g_cats.indexOf('cat 1');
		//console.log("found " + x);

		
	});
	
});

function UpdateImages(cat)
{
	
	
	//$('#slider li').fadeOut('slow');
	$('#slider li').hide();
	$('#slider li img')
		.attr("src","")
		.attr("alt","");
	
	for (var i=0; i < g_vidcatarray[cat].length; i++)
	{
		console.log("found " + g_vidcatarray[cat][i]['image']);
		$('#first ul li:eq('+i+') img')
				.attr("src","uploads/" + g_vidcatarray[cat][i]['image'])
				.attr("class", "video_image");
					
		
		//$('#first ul li:eq('+i+') a img').attr("alt","images/" + g_vidcatarray[cat][i]);
	}
	
	$('#slider li').show();
	//$('#slider li').fadeIn('slow');
	
}


</script>

</head>

<body>
<div id="main_div">
<div id="main_title_div"><img src="images/title.jpg" /></div>
<a id="home_link" href="http://www.arizonacastingconnection.com/home.html">Home</a>

<div id="content_div">
<!--
		<button id="btn1">1</button>
		<button id="btn2">2</button>
		<button id="btn3">3</button>
-->
<ul id="menu_list">
	<li><a href="#" id="bio">BIO</a></li>
	<li><a href="#" id="spots">SPOTS</a></li>
	<li><a href="#" id="services">SERVICES</a></li>
	<li><a href="#" id="credits">CREDITS</a></li>
	<li><a href="#" id="actors">ACTORS/MODELS</a></li>
	<li><a href="#" id="contact">CONTACT/AFFILIATES</a></li>
	<br>
	<li><a href="#" id="projects">PROJECT LOG-IN</a></li>
</ul>

<div id="video_player_div" class="content_change">
	<video id="video_player" class="video-js vjs-default-skin" width="500" height="300" ></video>
	<br>
	<!--
	<span class="video_info">Client: </span><span id="client" class="video_info_change"></span><br>
	<span class="video_info">Director: </span><span id="director" class="video_info_change"></span><br>
	<span class="video_info">Production Co: </span><span id="production" class="video_info_change"></span><br>
	<span class="video_info">Agency: </span><span id="agency" class="video_info_change"></span>
	-->
</div>
<div id="bio_div" class="content_change">
    <img id="home_img" src="images/homepage-left.jpg"/>

    <p id="home_text">
      <font color="#3cbaeb" size="5">Need Talent?</font><br><br>
      Need "Real People or Real Customers"?<br>
        A Fresh Face to speak a mouthful of dialogue?<br>
        or is an alluring model on your list?<br>
        Principles or extras?<br>
        <br>
        Broadcast, Industrial, or Print.<br>
        SAG or Non Union.<br>
        <br>
        Your production relies on finding the best talent for<br>
        the part. <font color="#3cbaeb" size="+1">ACC</font> is your connection to that vital talent<br>
        pool. No more "what if" Factor. We'll put you with<br>
        the best talent in Arizona.
    </p>
</div>
<div id="actors_div" class="content_change">



</div>  
  
  
  
</div>

<div id="wrap">
        <div id="slider">
          <div class="scroll">
            <div class="scrollContainer">
              <div class="panel" id="first">
                <ul>
                  <li><img src="images/Coca-Cola-belly-120X90.jpg" alt="Coca Cola" /></li>
                  <li><img src="images/F-150-Truck-worker-120X90.jpg" alt="Ford F-150" /></li>
                  <li><img src="images/VW-Autobahn-1-3guys-120X90.jpg" alt="Volkswagon" /></li>
                  <li><img src="images/Culvers-Happy-family-120X90.jpg" alt="Culver's" /></li>
                  <li><img src="images/Rexona-1-bullLick-120X90.jpg" alt="Rexona" /></li>
				  
                </ul>
              </div>
			  
              <div class="panel" id="second">
                <ul>
                  <li><a href="spots.html"><img src="images/FLF Classic Sampler-moped-couple-120X90.jpg" alt="Fazoli's" /></a></li>
                  <li><a href="spots.html"><img src="images/Justin & Kelly_HD-DogBottle-120X90.jpg" alt="Desert Schools Federal Credit Union" /></a></li>
                  <li><a href="spots.html"><img src="images/Ak Chin-Norm-120X90.jpg" alt="Ak Chin" /></a></li>
                  <li><a href="spots.html"><img src="images/Prop200_JustTheFacts-120X90.jpg" alt="Proposition 200" /></a></li>
                  <li><a href="spots.html"><img src="images/FLF Pizzettis-3teens-120X90.jpg" alt="Fazoli's" /></a></li>
                </ul>
              </div>
              <div class="panel" id="third">
                <ul>
                  <li><a href="spots.html"><img src="images/M&I-82109-katieCU-120X90.jpg" alt="M & I Bank" /></a></li>
                  <li><a href="spots.html"><img src="images/Lance-120X90.jpg" alt="Desert Schools Federal Credit Union" /></a></li>
                  <li><a href="spots.html"><img src="images/Ricky & Daz cowboys2-120X90.jpg" alt="Ricky & Daz" /></a></li>
                  <li><a href="spots.html"><img src="images/Tchaikovsky-CD-82009-3guys3-120X90.jpg" alt="Tchaikovsky" /></a></li>
                  <li><a href="spots.html"><img src="images/The_Address4X3-12090.jpg" alt="The Address" /></a></li>
                </ul>
              </div>
			  
              <div class="panel" id="fourth">
                <ul>
                  <li><a href="spots.html"><img src="images/PowerPuffGirls-opening-120X90.jpg" /></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>


	<div id="carousel_wrap">

	  <ul id="mycarousel" class="jcarousel-skin-tango">
				<?php
				foreach($g_cats as $cat)
				{
					echo "<li><br><p class='category'>{$cat['name']}</p></li>";
				}
				?>
		<!--
		<li><br><p class="category">cat 1</p></li>
		<li><br><p class="category">cat 2</p></li>
		<li><br><p class="category">cat 3</p></li>
		<li><br><p class="category">cat 4</p></li>
		<li><br><p class="category">cat 5</p></li>
		<li><br><p class="category">cat 6</p></li>
		<li><br><p class="category">cat 7</p></li>
		-->
	  </ul>

	</div>
	  
</div>




</body>
</html>