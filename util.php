<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Arizona Casting Connection</title>
<meta name="Title" content="Arizona Casting Connection" />
<meta name="Description" content="Arizona Casting Connection" />
<meta name="Keywords" content="Arizona Casting Connection" />
<meta name="robots" content="index,follow" />

<link rel="stylesheet" href="style.css" type="text/css" />

<!-- release version -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<!-- development version only -->
<!-- <script type="text/javascript" src="lib/jquery-1.8.2.js"></script> -->
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<!-- !! link to github file or download ? -->
<script type="text/javascript" src="http://malsup.github.com/jquery.form.js"></script>

<!-- vid player http://videojs.com/-->
<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/c/video.js"></script>

<style type="text/css">


</style>


<?php

require_once('util_op.php');

$g_vids = array();
$g_cats = array();
$g_vidcat = array();
$g_image_files = array();

util_main();

function util_main()
{
	global $g_max_upload;
	global $g_max_post;
	
	//phpinfo(INFO_CONFIGURATION);
	
	$g_max_upload = ini_get('upload_max_filesize');
	$g_max_post = ini_get('post_max_size');
		
	GetInfo();
	
	GetImageFiles();
	
	
	//CreateTable();
	
}

function GetImageFiles()
{
    global $g_image_files;

    //get all filenames in project directory
    $file_names = scandir("uploads");

    if ($file_names === false)
    {
        print "GetProjectNames:scandir failed<br />";
        return false;
    }

    //remove '.' and '..'
    $file_names = array_diff($file_names, array('.', '..'));

    foreach($file_names as $name)
    {
        $info = pathinfo($name);
		
		if ($info['extension'] != "jpg")
		{
			continue;
		}
		
        $g_image_files[] = $name;
    }

    return true;
}

function GetInfo()
{
	global $g_vids;
	global $g_cats;
	global $g_vidcat;
		
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
	
	
/*		
	$data = SQLGetData("SELECT cats.name, vids.filename, vidcat.cat_id, vidcat.vid_id
								FROM vids, cats, vidcat
								WHERE (vidcat.vid_id=vids.video_id && vidcat.cat_id=cats.cat_id)
								ORDER BY cats.name"); 

    //!! will be null if vidcat empty										
	if (!$data)
	{
		pr("GetInfo: SQLGetData from cats and vids failed");
		return false;
	}
	
	foreach($data as $key=>$value)
	{
		$g_cats[$data[$key]['name']][] = $data[$key]['filename'];
	}
*/

}

?>

<script type="text/javascript">

var g_max_upload;
var g_max_post;
var g_video;

var g_sel_vid = {};
var g_sel_cat = {};
var g_sel_cat_vid = {}; 
 
var g_vids = {};
var g_cats = {};
var g_vidcat = {};

//!! UT
var g_count = [];
var g_intvl = [];

//!! demo
var g_vidinfo = [];
var g_catinfo = [];
var g_intvl;
var g_count = 0;

main_util();

function main_util()
{
	//SetVideoFields(0);
	
	setinfo();
	
	
	
}

function SetDemoCatInfo()
{
	CreateCategory('Autos');
	CreateCategory('Food');
	CreateCategory('Business');
	CreateCategory('Government');
	CreateCategory('Personal');
	CreateCategory('Entertainment');
}

//!! if ' in name, data not saved!!!!!!!!
function SetDemoVidInfo()
{
	var i = 0;
	
	g_vidinfo[i] = {};
	
	g_vidinfo[i]['name'] = 'Coca Cola';
	g_vidinfo[i]['client'] =  'Coca Cola';
	g_vidinfo[i]['director'] =  'Vogel Villar-Rios';
	g_vidinfo[i]['production'] =  'Believe Media';
	g_vidinfo[i]['agency'] =  'Berlin Cameron';
	g_vidinfo[i]['image'] = 'Coca-Cola-belly-120X90.jpg';
	g_vidinfo[i]['filename'] = '06 Coca Cola.flv';

	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Ford F-150';
	g_vidinfo[i]['client'] =  'Ford';
	g_vidinfo[i]['director'] =  'Will van der Vlugt';
	g_vidinfo[i]['production'] =  'Caramel Pictures/VuWest';
	g_vidinfo[i]['agency'] =  'Zubi Advertising';
	g_vidinfo[i]['image'] = 'F-150-Truck-worker-120X90.jpg';
	g_vidinfo[i]['filename'] = '07 F-150_HardWork_82109.flv';

	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Volkswagen';
	g_vidinfo[i]['client'] =  'Volkswagen';
	g_vidinfo[i]['director'] =  'Matthieu Mantovani';
	g_vidinfo[i]['production'] =  'Films Traffik';
	g_vidinfo[i]['agency'] =  'Palm Communication';
	g_vidinfo[i]['image'] = 'VW-Autobahn-1-3guys-120X90.jpg';
	g_vidinfo[i]['filename'] = '020 VW-82109.flv';

	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Culvers';
	g_vidinfo[i]['client'] =  'Culvers';
	g_vidinfo[i]['director'] =  'Steve Diller';
	g_vidinfo[i]['production'] =  'MJZ Productions';
	g_vidinfo[i]['agency'] =  'Marc USA';
	g_vidinfo[i]['image'] = 'Culvers-Happy-family-120X90.jpg';
	g_vidinfo[i]['filename'] = '019 Culvers-Happy-4-82609.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Rexona';
	g_vidinfo[i]['client'] =  'Rexona';
	g_vidinfo[i]['director'] =  'Pucho';
	g_vidinfo[i]['production'] =  'Believe Media';
	g_vidinfo[i]['agency'] =  'Vega Olmos Ponce / Lowe';
	g_vidinfo[i]['image'] = 'Rexona-1-bullLick-120X90.jpg';
	g_vidinfo[i]['filename'] = '014 Rexona-1-82109.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = "Fazzolis";
	g_vidinfo[i]['client'] =  "Fazzolis";
	g_vidinfo[i]['director'] =  ' Clay Peres';
	g_vidinfo[i]['production'] =  'Lankford Films';
	g_vidinfo[i]['agency'] =  'Meridian Communications ';
	g_vidinfo[i]['image'] = 'FLF Classic Sampler-moped-couple-120X90.jpg';
	g_vidinfo[i]['filename'] = '08 FLF New Classic-82109.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Desert Schools';
	g_vidinfo[i]['client'] =  'Desert Schools Federal Credit Union';
	g_vidinfo[i]['director'] =  'Will Hartman';
	g_vidinfo[i]['production'] =  'Will Hartman';
	g_vidinfo[i]['agency'] =  'MMA advertising';
	g_vidinfo[i]['image'] = 'Justin & Kelly_HD-DogBottle-120X90.jpg';
	g_vidinfo[i]['filename'] = '010 JustinKelly-82109.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Ak Chin';
	g_vidinfo[i]['client'] =  'Ak-Chin';
	g_vidinfo[i]['director'] =  'Cary Hunter Cook';
	g_vidinfo[i]['production'] =  'VuWest';
	g_vidinfo[i]['agency'] =  'Sassaman & Bateman';
	g_vidinfo[i]['image'] = 'Ak Chin-Norm-120X90.jpg';
	g_vidinfo[i]['filename'] = 'Ak Chin-82009.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Proposition 200';
	g_vidinfo[i]['client'] =  'Prop 200';
	g_vidinfo[i]['director'] =  'Cary Cook';
	g_vidinfo[i]['production'] =  'Airwave';
	g_vidinfo[i]['agency'] =  'R&R Partners';
	g_vidinfo[i]['image'] = 'Prop200_JustTheFacts-120X90.jpg';
	g_vidinfo[i]['filename'] = '013 Prop200-Just the facts-82009.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = "Fazolis";
	g_vidinfo[i]['client'] =  "Fazolis";
	g_vidinfo[i]['director'] =  'Clay Peres';
	g_vidinfo[i]['production'] =  'Lankford Films';
	g_vidinfo[i]['agency'] =  'Meridian Communications';
	g_vidinfo[i]['image'] = 'FLF Pizzettis-3teens-120X90.jpg';
	g_vidinfo[i]['filename'] = '09 FLF Pizzettis-2-82109.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'M&I Bank';
	g_vidinfo[i]['client'] =  'M&I Bank';
	g_vidinfo[i]['director'] =  'Steve Farr';
	g_vidinfo[i]['production'] =  'Purple Union';
	g_vidinfo[i]['agency'] =  'M&I';
	g_vidinfo[i]['image'] = 'M&I-82109-katieCU-120X90.jpg';
	g_vidinfo[i]['filename'] = '012 MI-82109.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Desert Schools';
	g_vidinfo[i]['client'] =  'Desert Schools';
	g_vidinfo[i]['director'] =  'Will Hartman';
	g_vidinfo[i]['production'] =  'MMA Production';
	g_vidinfo[i]['agency'] =  'MMA Advertising';
	g_vidinfo[i]['image'] = 'Lance-120X90.jpg';
	g_vidinfo[i]['filename'] = '011 Lance-82109.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Tchaikovsky';
	g_vidinfo[i]['client'] =  'Aeon & AscII CD';
	g_vidinfo[i]['director'] =  'Gary Hunter Cook';
	g_vidinfo[i]['production'] =  'VuWest';
	g_vidinfo[i]['agency'] =  'Dentsu Japan';
	g_vidinfo[i]['image'] = 'Tchaikovsky-CD-82009-3guys3-120X90.jpg';
	g_vidinfo[i]['filename'] = '016 Tchaikovsky-CD-82009.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'The Address';
	g_vidinfo[i]['client'] =  'Address';
	g_vidinfo[i]['director'] =  'Gerald George';
	g_vidinfo[i]['production'] =  'VuWest, Inc.';
	g_vidinfo[i]['agency'] =  'The George Partnership';
	g_vidinfo[i]['image'] = 'The_Address4X3-12090.jpg';
	g_vidinfo[i]['filename'] = '017 The_Address.flv';
	
	i++;
	g_vidinfo[i] = {};
	g_vidinfo[i]['name'] = 'Powerpuff';
	g_vidinfo[i]['client'] =  'Powerpuff Girls';
	g_vidinfo[i]['director'] =  'Jake Hausworth';
	g_vidinfo[i]['production'] =  'VuWest, Inc.';
	g_vidinfo[i]['agency'] =  'Cartoon Network';
	g_vidinfo[i]['image'] = 'PowerPuffGirls-opening-120X90.jpg';
	g_vidinfo[i]['filename'] = '03 PowerPuffGirls-82109.flv';

	
	for (i=0; i < g_vidinfo.length; i++)
	{
		for (j=0; j < g_vids.length; j++)
		{
			if (g_vids[j].filename == g_vidinfo[i]['filename'])
			{
				
				console.log("name " + g_vids[j].filename);
				$('#video_name').val(g_vidinfo[i]['name']);
				$('#video_client').val(g_vidinfo[i]['client']);
				$('#video_director').val(g_vidinfo[i]['director']);
				$('#video_production').val(g_vidinfo[i]['production']);
				$('#video_agency').val(g_vidinfo[i]['agency']);
				$('#video_image').val(g_vidinfo[i]['image']);
		
				SaveVidInfo(j);
				
				break;
			}
		}
	}
}
  
function DemoSaveInfo()
{
	var i = g_count;
	
	for (j=0; j < g_vids.length; j++)
	{
		if (g_vids[j].filename == g_vidinfo[i]['filename'])
		{
			
			console.log("name " + g_vids[j].filename);
			$('#video_name').val(g_vidinfo[i]['name']);
			$('#video_client').val(g_vidinfo[i]['client']);
			$('#video_director').val(g_vidinfo[i]['director']);
			$('#video_production').val(g_vidinfo[i]['production']);
			$('#video_agency').val(g_vidinfo[i]['agency']);
			$('#video_image').val(g_vidinfo[i]['image']);
	
			SaveVidInfo(j);
			
			break;
		}
	}
	
	g_count++;
	
	if (g_count == g_vidinfo.length)
		clearInterval(g_intvl);
}
  
$(document).ready(function() 
{
	//!!
	//SetDemoVidInfo();
	//SetDemoCatInfo();
	//g_intvl = setInterval(function(){DemoSaveInfo()},1000);
		
	$("#tabs").tabs();

	//initialize video player
	g_video = _V_("video_player", { "controls": true, "autoplay": false, "preload": "auto" });	
	
	//GetSessionData();

	$('.cat_video_list').css('list-style-type', 'circle');
	
	$('#video_list1 li').addClass('shadow1');

	$('#cat_circle').hide();
	
	SelectCategory(0);
	SelectVideo(0);
	

	//click on video in main video list
	$('#video_list1 li').live('click', function() { 

		var indx = $(this).index();
		
		SelectVideo(indx);
		
	});

	$('#video_image').change(function() {
		
		g_video.pause();
		$('.vjs-poster').attr("src", "uploads/" + $('#video_image').val());
		$('.vjs-poster').show();
		
	});

	$('#del_video').click(function() 
	{ 
		DeleteVideo(g_sel_vid['indx'], g_sel_vid['name']);
	});
	
	//click on a category
	$('.cat_name').live('click', function() {
    
		if ($(this).parent().index() != g_sel_cat['indx'])
		{
			SelectCategory($(this).parent().index());
		}
    });

	//click on video in a category
	$('.cat_video_list li').live('click', function(e) 
	{ 
		var name = $(this).html();
		
		SelectCatVideo(g_sel_cat['indx'], $(this).index());
	});
	
	$('#upload_button').click(function() 
   { 
		bar.css({'visibility':'hidden'});
		percent.css({'visibility':'hidden'});
		$('#upfile').click();
   });
   
	$('#upfile').change(function() {
		
		//http://labs.abeautifulsite.net/archived/jquery-alerts/demo/
		
		/*
		var r=confirm("Press a button!");
		if (r == true)
  {
  x="You pressed OK!";
  }
else
  {
  x="You pressed Cancel!";
  }*/
		$('#form_video_upload').submit();
		
		//alert('Handler for .change() called.');
	});

	$('#save_vid_info').click(function() 
   { 
		SaveVidInfo(g_sel_vid['indx']);
		
   });

   	$('.cat_name').change(function() {
	
		var indx = $(this).parent().index();
		UpdateCategory(g_cats[indx]['name'], $(this).val());
	});
	
	$('#add_cat').click(function() 
   { 
		var name = document.getElementById('new_cat_name').value;
		CreateCategory(name);
   });

	//add video to a category
	$('#add_cat_video').click(function() 
	{ 
		AddCatVideo(g_sel_cat, g_sel_vid);
	});

	$('#del_cat').click(function() 
   { 
		DeleteCategory(g_sel_cat['indx'], g_sel_cat['name']);
		
   });
   
	$('#del_cat_video').click(function() 
   { 
		DelCatVideo(g_sel_cat, g_sel_cat_vid);
		
   });
   
	var bar = $('.bar');
	var percent = $('.percent');
	//var status = $('#status');
   
	$('#form_video_upload').ajaxForm({
		
		data: {jquery_op: 'upload'},
		dataType: 'json', 
		//!! disable submit button
		beforeSend: function() {
			
			//cancel file upload
			//!!cancelBtn.click(xhr.abort);
			
			//status.empty();
			bar.css({'backgroundColor':'white', 'visibility':'visible'});
			percent.css({'visibility':'visible', 'width':'400px'});
			var percentVal = '0%';
			bar.width(percentVal);
			percent.html(percentVal);
			bar.show();
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			bar.width(percentVal);
			percent.html(percentVal);
		},
		error: function(request, status, error) {
            console.log(request.responseText);  
			//status.html(request.responseText);
			var max = (g_max_upload < g_max_post) ? g_max_upload : g_max_post;
			percent.html("error: total max upload size possibly exceeded. Choose fewer files.");
			bar.hide();
			//bar.html("error uploading, total max upload size is " + max + "M bytes");
			//bar.html("error: total max upload size possibly exceeded. Choose fewer files.");

		},
		complete: function(data) {
			//status.html(xhr.responseText);
			//var percentVal = '100%';
			//bar.width(percentVal);
			//percent.html(percentVal);
		},
		success: function(data)          
		{
			
			if (!data || data['error'])
			{
				//!! need to reset back again
								
				//percent.css({'font-size':'10px', 'left':'0px'});
				//percent.css({'font-size':'10px'});
				
				if (!data)
				{
					var max = (g_max_upload < g_max_post) ? g_max_upload : g_max_post;
						
					percent.html("error uploading, total max upload size is " + max + "M bytes");
				}
				else
				{
					percent.html(data['error']);
				}
			}
			else
			{
				var percentVal = '100%';
				bar.width(percentVal);
				percent.html(percentVal);
				
				//!! update progress bar here
				for (i=0; i < data.length; i++)
				{
					//!! multi posts ok??
					if (data[i].split('.').pop() == "jpg")
					{
						AddImage(data[i]);
					}
					
					if (data[i].split('.').pop() == "flv")
					{
						AddVideo(data[i]);
					}
				}
			}
		}
	});
});


function AddImage(filename)
{
	$('#video_image')
         .append($("<option></option>")
         .attr("value", filename)
         .text(filename));
}

function AddVideo(filename)
{
    $.ajax({     
		type: "POST",
		url: 'util_op.php',    
		data: {jquery_op:'savevideo', filename:filename}, 
		dataType: 'json',	 
		
		success: function(result)          
		{
			if (result != "error")
			{
				//add to html list
				$('#video_list1').append("<li><span class='vid_name'></span><span class='vid_file'>" + filename + "</span></li>");
				$('#video_list1 li:last').addClass('shadow1');
				
				//add to js objects
				g_vids[g_vids.length] = {};
				g_vids[g_vids.length-1].name = "unset";
				g_vids[g_vids.length-1].filename = filename;
				
				SelectVideo(g_vids.length-1);
			}
		} 
    });
}

function DeleteVideo(indx, name)
{
    $.ajax({     
		type: "POST",
		url: 'util_op.php',                  
		data: {jquery_op:'deletevideo', filename:name},
		dataType: 'json',
		
		success: function(result)          
		{
			if (result != "error")
			{
				//remove from html video list 
				$('#video_list1 li:eq('+indx+')').remove();
				
				//remove from html cats list
				for (i=0; i < $('#cats_list ul').length; i++)
				{					
					for (j=0; j < $('.cat_video_list:eq('+i+') li').length; j++)
					{
						if ($('.cat_video_list:eq('+i+') li:eq('+j+')').text() == name)
						{
							$('.cat_video_list:eq('+i+') li:eq('+j+')').remove();

							break;
						}
					}	
				}
				
				//remove object 
				g_vids.splice(indx, 1);
								
				SelectVideo(indx-1);
			}
		} 
    });
}

function ReloadPage()
{
	SetSessionData();
}

function SetSessionData()
{
	console.log("catsel :" + g_sel_cat['name']);
	
    $.ajax({     
		type: "POST",
		url: 'util_op.php',                  
		data: {jquery_op:'setsessiondata', sel_cat:g_sel_cat, 
			   sel_vid:g_sel_vid, sel_cat_vid:g_sel_cat_vid},
		
		success: function(data)          
		{
			//!!location.reload();
		} 
    });
}

function GetSessionData()
{
	$.ajax({     
	  type: "GET",
      url: 'util_op.php',                  
      data: "jquery_op=getsessiondata",
	  dataType: 'json',
    
		success: function(data)          
		{
			console.log("sess " + data['sel_cat']['indx']);
			$('.cat_video_list').eq(data['sel_cat']['indx']).show(1);
		} 
	});
}
	
function setinfo()
{
	//get php server upload max 
	g_max_upload = <?php echo json_encode($g_max_upload); ?>;
	g_max_post = <?php echo json_encode($g_max_post); ?>;

	g_max_upload = parseInt(g_max_upload);
	g_max_post = parseInt(g_max_post);
	
	//convert php array to js array
	
	g_vids = <?php echo json_encode($g_vids ); ?>;
	g_cats = <?php echo json_encode($g_cats ); ?>;
	g_vidcat = <?php echo json_encode($g_vidcat ); ?>;
}
  
function SelectVideo(indx)
{
	if (g_vids.length == 0)
	{
		g_sel_vid['indx'] = -1;
		SetVideoFields(-1);
		return;
	}
	
	//account for index 0 video deleted
	if (indx == -1)
		indx = 0;
	
	g_sel_vid['indx'] = indx;
	g_sel_vid['name'] = g_vids[indx]['filename'];
		
	$('#video_list1 li').removeClass('shadow2').addClass('shadow1');
	$('#video_list1 li:eq('+indx+')').addClass('shadow2');
	
	SetVideoFields(indx);
	
	g_video.pause();
	$('.vjs-poster').attr("src", "uploads/" + g_vids[indx]['image']);
	$('.vjs-poster').show();
		
	g_video.src({ type: "video/flv", src: "uploads/" + g_vids[indx].filename });
	
}

function SelectCategory(indx)
{	
	//category list is empty
	if (g_cats.length == 0)
	{
		g_sel_cat['indx'] = -1;
		return;
	}
		
	$('.cat_name').blur();
	$('.cat_name').css('cursor', 'pointer');
	
	$('.cat_video_list').hide(1);
	
	$('#cats_list ul:eq('+indx+')').show("slow", "swing", function() {

		//!! get rid of #cats_list li
		var pos = $('#cats_list li .cat_name:eq('+indx+')').position();
	
		$('#cat_circle').animate({
			top: pos.top+27,
			left: pos.left+25,
		}, function() {
			//$('.cat_name:eq('+indx+')').css('cursor', 'text');
			$('#cat_circle').show();
		});
  });
	
	g_sel_cat['indx'] = indx;
	g_sel_cat['name'] = $('.cat_name:eq('+indx+')').val();
	
	SelectCatVideo(indx, 0);
}

function SelectCatVideo(catindx, vidindx)
{
	var len = $('.cat_video_list:eq('+catindx+')').length;
	
	if (len == 0)
	{
		g_sel_cat_vid['indx'] = -1;
		return;
	}
		
	g_sel_cat_vid['indx'] = vidindx;
	g_sel_cat_vid['name'] = $('.cat_video_list:eq('+catindx+') li:eq('+vidindx+')').text();

	$('.cat_video_list li').removeClass('shadow2')
	$('.cat_video_list:eq('+catindx+') li:eq('+vidindx+')').addClass('shadow2');
}

function SetVideoFields(indx)
{
	//if video list empty or new video just added
	if ((g_sel_vid['indx'] == -1) || (g_vids[indx].name == 'unset')) 
	{
		$('.vid_info_input').val("");
		return;
	}

	document.getElementById('video_name').value = g_vids[indx].name;
	document.getElementById('video_client').value = g_vids[indx].client;
	document.getElementById('video_director').value = g_vids[indx].director;
	document.getElementById('video_production').value = g_vids[indx].production_co;
	document.getElementById('video_agency').value = g_vids[indx].agency;
	document.getElementById('video_image').value = g_vids[indx].image;
}

function SaveVidInfo(indx)
{
	console.log("vid indx " + indx);
	
	var filename = g_vids[indx]['filename'];
	
	var name = document.getElementById('video_name').value;
	var cli = document.getElementById('video_client').value;
	var dir = document.getElementById('video_director').value;
	var prod = document.getElementById('video_production').value;
	var ag = document.getElementById('video_agency').value;
	var img = document.getElementById('video_image').value;
	
    $.ajax({     
		type: "POST",
		url: 'util_op.php',                  
		data: {jquery_op:'savevideodata', filename:filename, name:name, 
			   client:cli, director:dir, production:prod, 
			   agency:ag, image:img},
		dataType: 'json',	 
		
		success: function(result)          
		{
			if (result != "error")
			{
				g_vids[indx]['name'] = name;
				g_vids[indx]['client'] = cli;
				g_vids[indx]['director'] = dir;
				g_vids[indx]['production_co'] = prod;
				g_vids[indx]['agency'] = ag;
				g_vids[indx]['image'] = img;
				
				//update video list name
				$('#video_list1 > li span.vid_name:eq('+indx+')').val(" " + name);
			}
		} 
    });
}

function CreateCategory(name)
{
    $.ajax({     
		type: "GET",
		url: 'util_op.php',                  
		data: "jquery_op=createcategory&name=" + name,
		dataType: 'json',
		
		success: function(result)          
		{
			if (result != "error")
			{
				$('.cat_video_list').hide(1);
				
				//insert new cat to top of list
				$('#cats_list').append("<li class='cat_head'><input type='text' class='cat_name' value='" + name + "'</input><ul class='cat_video_list'></ul></li>");
				
				//add to js objects
				g_cats[g_cats.length] = {};
				g_cats[g_cats.length-1].name = name;
				
				SelectCategory(g_cats.length - 1);
			}
		} 
    });
}

function DeleteCategory(indx, name)
{
    $.ajax({     
		type: "GET",
		url: 'util_op.php',                  
		data: "jquery_op=deletecategory&name=" + name,
		dataType: 'json',
		
		success: function(result)          
		{
			if (result != "error")
			{ 
				//remove in html list 
				$('.cat_head:eq('+indx+') > *').remove();
				$('.cat_head:eq('+indx+')').remove();
				
				
				//remove from array
				g_cats.splice(indx, 1);
				
				SelectCategory(indx - 1);
			}
		} 
    });
}

function UpdateCategory(orig, name)
{
	console.log("orig " + orig + "new " + name);
	
    $.ajax({     
		type: "POST",
		url: 'util_op.php',                  
		data: {jquery_op:'updatecategory', name:name, orig:orig},
		dataType: 'json',
		
		success: function(result)          
		{
			if (result != "error")
			{
				//update array
				for (i=0; i < g_cats.length; i++)
				{
					if (g_cats[i]['name'] == orig)
					{
						g_cats[i].name = name;
						break;
					}
				}
			}
		} 
    });
}

function AddCatVideo(sel_cat, sel_vid)
{
    $.ajax({     
		type: "GET",
		url: 'util_op.php',                  
		data: "jquery_op=addcatvideo&cat=" + sel_cat['name'] + "&file=" + sel_vid['name'],
		dataType: 'json',
		
		success: function(result)          
		{
			if (result != "error")
			{
				//dynamic add video to html 
				$('#cats_list > li').eq(sel_cat['indx']).find('ul').append("<li>" + sel_vid['name'] + "</li>");
								
				$('.cat_video_list:eq('+sel_cat['indx']+')').show();
				
				vid_indx = $('.cat_video_list:eq('+sel_cat['indx']+') li').length - 1;
				SelectCatVideo(sel_cat['indx'], vid_indx);
			}
		} 
    }); 

}

function DelCatVideo(sel_cat, sel_vid)
{
    $.ajax({     
		type: "POST",
		url: 'util_op.php',  
		data: {jquery_op:'delcatvideo', cat:sel_cat['name'], filename:sel_vid['name']},
		dataType: 'json',
		
		success: function(result)          
		{
			if (result != "error")
			{
				//!! also works
				//$('#cats_list > li').eq(4).find('ul').find('li').eq(1).remove();
				$('#cats_list > li:eq('+sel_cat["indx"]+') ul li:eq('+sel_vid["indx"]+')').remove();
				
				//$('.cat_name:eq('+indx+')')
				
				SelectCatVideo(sel_cat['indx'], sel_vid['indx']-1);
			}
			
			
		} 
    }); 
}


  
</script>   
</head>

<body>
 <!--      
		 <button id="btn1" onclick="main_util()">vid</button>
		 <button id="btn2">clone</button>
		 
-->		 
<!--
		<button id="btn3">set vid</button>
		<button id="btn4">del vid</button>
		<button id="btn5">set cat</button>
		<button id="btn6">del cat</button>
		<button id="btn7">set catvid</button>
		<button id="btn8">um...</button>
	-->		
			
			
<div id="tabs" style='width: 900px; left:100px'>
    <ul>
        <li><a href="#fragment-1"><span>Videos</span></a></li>
        <li><a href="#fragment-2"><span>Photos</span></a></li>
    </ul>
	
	<!-- tab div -->
    <div id="fragment-1">
	
		<!-- file upload -->
		<div id="video_upload_div">
		<form action="util_op.php" method="POST" enctype="multipart/form-data" id="form_video_upload">
		<div id="upload_button" ><span>Upload Videos/Images</span></div>
		<div style='height: 0px;width:0px; overflow:hidden;'>
		<input id="upfile" type="file" value="upload" name="videos[]" multiple />
		</div>
		<!--<input type="submit" value='submit' >-->
		</form>
		
		<div class="progress">
			<div class="bar"></div >
			<div class="percent">0%</div >
		</div>
		
		</div>
					
		<div id="video_player_util_div">
			<video id="video_player" class="video-js vjs-default-skin" width="300" height="200" ></video>
		</div>


		<!--<div id="status"></div>-->

		<div id="videos_div">
			<div id="video_list1_div">
				<span class="video_name_hdr">Name</span><span class="video_file_hdr">File</span>
				<div id="del_video_div"><span id="del_video">Delete Video</span></div>
				<ul id="video_list1">
					
					<?php
						foreach($g_vids as $key=>$value)
						{
							echo "<li><span class='vid_name'>&nbsp;{$g_vids[$key]['name']}</span><span class='vid_file'>{$g_vids[$key]['filename']}</span></li>";
							print "\n";
							print "\t\t\t\t\t\t\t";
						}
					?>
				</ul>
			</div>

		</div>
			
		<div id="video_info_div">
			<span id="save_vid_info">Save</span><br>
			<label for="video_name">Video Name:</label>
			<input type="text" id="video_name" class="vid_info_input" name="video_name"  value="" ><br>
			<label for="video_client">Client:</label>
			<input type="text" id="video_client" class="vid_info_input" name="video_client" value="" ><br>		
			<label for="video_director">Director:</label>
			<input type="text" id="video_director" class="vid_info_input" name="video_director" value="" ><br>
			<label for="video_production">Production Co:</label>
			<input type="text" id="video_production" class="vid_info_input" name="video_production" value="" ><br>
			<label for="video_agency">Agency:</label>
			<input type="text" id="video_agency" class="vid_info_input" name="video_agency" value="" ><br>
			<label for="video_image">Image:</label>
			<select id="video_image" class="vid_info_input">
				<option></option>
				<?php
					foreach($g_image_files as $file)
					{
						echo "<option value='{$file}'>{$file}</option>";
					}
				?>	
			</select>
				
		</div>

		<div id="add_video_div">
		<span id="add_cat_video">Add Video</span><br>
		<span>></span>
		</div>
		<div id="del_cat_video_div">
		<span>Delete</span>
		<span id="del_cat_video">Video </span><span> | </span>
		<span id="del_cat"> Category</span><br>
		</div>
		
		
	<div id="cats_list_div">
			<div id="cat_circle"></div>
			<label for="new_cat_name" id="add_cat">Create New</label>
			<input type="text" id="new_cat_name" value=""><br>
			<ul id="cats_list">
				<?php
				foreach($g_cats as $cat)
				{
					echo "<li class='cat_head'>";
					//echo "<span class='cat_name'>{$cat['name']}</span>";
					echo "<input type='text' class='cat_name' value='{$cat['name']}'</input>";
					echo "<ul class='cat_video_list'>";
											
					foreach($g_vidcat as $vidcat)
					{
						if ($cat['cat_id'] == $vidcat['cat_id'])
						{
							foreach($g_vids as $vid)
							{
								if ($vid['video_id'] == $vidcat['vid_id'])
								{
									echo "<li>{$vid['filename']}</li>";
									print "\n";
									print "\t\t\t\t\t\t\t";
									
									break;
								}
							}
						}
					}
					echo "</ul>";
					echo "</li>";
				}
				?>
			</ul>
			
	</div>
		
    </div>
    <div id="fragment-2">
	
	
			<!--
		<div id="video_upload_div">
		<form action="util_op.php" method="post" enctype="multipart/form-data" id="form_video_upload">
			<input type="file" name="videos[]" id="video_upload" multiple>
			<input type="submit" value="upload" id="video_upload_button">
		</form>
				
			<input type="submit" value="upload" id="video_upload_button">
		
			<div class="bar"></div >
			<div class="percent">0%</div >
		</div>
		-->

			
		
    </div>
	
</div>

<!--
		<div id="video_play">
		
			<video id="video_player" class="video-js vjs-default-skin" width="400" height="300">
				</video>
			
		
		</div>
-->
</body>   


</html>