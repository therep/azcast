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
var g_video1;

var g_sel_vid = {};
var g_sel_cat = {};
var g_sel_cat_vid = {}; 
 
var g_vids = {};
var g_cats = {};
var g_vidcat = {};

//!! UT
var g_count = [];
var g_intvl = [];


main_util();

function main_util()
{
	//SetVideoFields(0);
	
	setinfo();
	
	
	
}
  
$(document).ready(function() 
{
	$("#tabs").tabs();
	
	//initialize video player
	//g_video1 = _V_("video_player", { "controls": true, "autoplay": false, "preload": "auto" });
	
	GetSessionData();


	$('.cat_video_list').css('list-style-type', 'circle');
	
	$('#video_list1 li').addClass('shadow1');
	
	SelectCategory(0);
	
	//click on video in main video list
	$('#video_list1 li').live('click', function() { 

		SelectVideo($(this).index());

		g_sel_vid['indx'] = $(this).index();
		g_sel_vid['name'] = g_vids[$(this).index()]['filename'];

		SetVideoFields(g_sel_vid['indx']);
		
		
				
		//!!allow other types (look at file extension)
		//g_video1.pause();
		//$('.vjs-poster').attr("src","images/Coca-Cola-belly-120X90.jpg");
		//$('.vjs-poster').show();
	});

	$('#del_video').click(function() 
	{ 
		DeleteVideo(g_sel_vid['indx'], g_sel_vid['name']);
	});
	
	//click on a category
	$('.cat_name').live('click', function() {
    
		SelectCategory($(this).parent().index());
    });

	//click on video in a category
	$('.cat_video_list li').live('click', function(e) 
	{ 
		g_sel_cat_vid['indx'] = $(this).index();
		g_sel_cat_vid['name'] = $(this).html();
		console.log("cat vid " + g_sel_cat_vid['indx'] + "name " + g_sel_cat_vid['name']);
		//console.log("click " + $(this).find('ul').html());

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

	$('#add_cat').click(function() 
   { 
		var name = document.getElementById('new_cat_name').value;
		CreateCategory(name);
   });

	$('#add_cat_video').click(function() 
   { 
		//!! mouse over pop up input box??
		
		AddCatVideo(g_sel_cat, g_sel_vid);
		
   });

	$('#del_cat').click(function() 
   { 
		//!! mouse over pop up input box??
		//console.log("lllllllllllllll");
		DeleteCategory(g_sel_cat['indx'], g_sel_cat['name']);
		
   });
   
	$('#del_cat_video').click(function() 
   { 
		//!! mouse over pop up input box??
		//console.log("jjjjjjjjj");
		DelCatVideo(g_sel_cat, g_sel_cat_vid);
		
   });
   
	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status');
   
	$('#form_video_upload').ajaxForm({
		data: {jquery_op: 'upload'},
		dataType: 'json', 
		//!! disable submit button
		beforeSend: function() {
			status.empty();
			bar.css({'backgroundColor':'white', 'visibility':'visible'});
			percent.css({'visibility':'visible'});
			var percentVal = '0%';
			bar.width(percentVal);
			percent.html(percentVal);
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			bar.width(percentVal);
			percent.html(percentVal);
			//console.log(percentVal, position, total);
		},
		/*
		complete: function(data) {
			//status.html(xhr.responseText);
			var percentVal = '100%';
			bar.width(percentVal);
			//percent.html(percentVal);
		},*/
		success: function(data)          
		{
			
			if (!data || data['error'])
			{
				//!! need to reset back again
								
				percent.css({'font-size':'10px', 'left':'0px'});
				
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
	
	//!! UT  --------------------------------------
    $("#btn3").click(function () 
	{
		
		
		
		
		//$('.cat_video_list li:eq('+g_sel_cat["indx"]+')').show();
		
		//$('.cat_video_list:eq(7)').show();
		
		//g_count[0] = 0;
		//g_intvl[0] = setInterval(function(){UTSetVid()},1000);
	});
	
    $("#btn9").click(function () 
	{
		g_count[1] = 0;
		g_intvl[1] = setInterval(function(){UTSetVidInfo()},1000);
	});
	

  	$("#btn4").click(function () 
	{
		UTDeleteVid();
		//clearInterval(g_intvl[0]);
		//g_intvl[0] = setInterval(function(){UTDeleteVid()},10);
	});
	
    $("#btn5").click(function () 
	{
		 g_count2 = 0;
		UTCreateCat();
		
	});
	
    $("#btn6").click(function () 
	{
		 
		UTDeleteCat();
		
	});

    $("#btn7").click(function () 
	{
		 g_count3 = 0;
		 g_count4 = 0;
		UTCreateCatVid();
		
	});

    $("#btn8").click(function () 
	{	
		
	});
	
});

function UTCreateCatVid()
{

	console.log("count3 " + g_count3 + " count4 " + g_count4 + " vids name " + g_vids[0].filename);
	

	if (g_count4 == g_vids.length)
	{
		g_count4 = 0;
		g_count3++;
	}
	
	if (g_count3 == g_cats.length)
		return
	
		g_sel_cat['indx'] = g_count3;
		g_sel_cat['name'] = g_cats[g_count3].name;
		g_sel_vid['indx'] = g_count4;
		g_sel_vid['name'] = g_vids[g_count4].filename;
	
		AddCatVideo(g_sel_cat, g_sel_vid);
		
		g_count4++;
}

function UTCreateCat()
{
	console.log("count " + g_count2);
	
	if (g_count2 > 1)
		return;

		var name = "cat " + g_count2;
	
		CreateCategory(name);
}

function UTDeleteCat()
{
	var len = g_cats.length;
	
	//for (i=0; i < g_vids.length; i++)
	//{
		g_sel_cat['indx'] = 0;
		g_sel_cat['name'] = g_cats[0]['name'];
		
		console.log("cat " + g_sel_cat['name']);
		DeleteCategory(g_sel_cat['indx'], g_sel_cat['name']);
}

function UTSetVid()
{
	var num = 2;
	
	if (g_count[0] > g_vids.length)
	{
		return;
	}
	
	if (g_count[0])
	{
		if (g_count[0] == g_vids.length)
		{
			console.log("count " + g_count[0]);
			
			var i = g_count[0]-1;
		
			document.getElementById('video_name').value = "video " + i;
			document.getElementById('video_client').value = "client " + i;
			document.getElementById('video_director').value = "director " + i;
			document.getElementById('video_production').value = "production " + i;
			document.getElementById('video_agency').value = "agency " + i;
			
			SaveVidInfo(g_count[0]-1);
		}
	}
	
	if (g_vids.length == num)
	{
		clearInterval(g_intvl[0]);
		return;
	}
	
	g_count[0] = g_vids.length + 1;
	
	var filename = "video " + g_vids.length + ".flv";
	
	AddVideo(filename);
}

function UTSetVidInfo()
{
	if (g_vids.length == g_count[1])
	{
		clearInterval(g_intvl[1]);
		return;
	}
	
	if (document.getElementById('video_name').value.indexOf('video') === -1) 
	{
		return;
	}
	
	var i = g_count[1];
	
		document.getElementById('video_name').value = "video " + i;
		document.getElementById('video_client').value = "client " + i;
		document.getElementById('video_director').value = "director " + i;
		document.getElementById('video_production').value = "production " + i;
		document.getElementById('video_agency').value = "agency " + i;
		
		var filename = "video " + i + ".flv";

		g_count[1]++;

		SaveVidInfo(g_count[1]-1);
}

function UTDeleteVid()
{
	var len = g_vids.length;
	
	//for (i=0; i < g_vids.length; i++)
	//{
		g_sel_vid['indx'] = 0;
		g_sel_vid['name'] = g_vids[0]['filename'];
		
		console.log("vid " + g_sel_vid['name']);
		DeleteVideo(g_sel_vid['indx'], g_sel_vid['name']);
	//}
}

function SelectCategory(indx)
{		
	$('.cat_video_list').hide(1);
	
	$('#cats_list ul:eq('+indx+')').show("slow", "swing", function() {

		var pos = $('#cats_list li .cat_name:eq('+indx+')').position();
	
			$('#cat_circle').animate({
				top: pos.top+25,
				left: pos.left+25,
			});

  });
  

	g_sel_cat['indx'] = indx;
	g_sel_cat['name'] = $('#cats_list li .cat_name:eq('+indx+')').text();
		
}

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
				
				//add to top of list
				//!! test, double check this usage
				//!! use splice
				//g_vids.unshift({});
				//g_vids[0].filename = filename;
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
				//remove in html list 
				$('#video_list1 li:eq('+indx+')').remove();
				
				//remove object 
				g_vids.splice(indx, 1);
				
				//remove from vidcat array
				//for (i=0; i < g_vi
				//$('#cats_list > li:eq('+sel_cat["indx"]+') ul li:eq('+sel_vid["indx"]+')').remove();
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
	$('#video_list1 li').removeClass('shadow2').addClass('shadow1');
	$('#video_list1 li:eq('+indx+')').addClass('shadow2');
}

function SelectCatVideo(catindx, vidindx)
{
	$('.cat_video_list li').removeClass('shadow2')
	$('.cat_video_list:eq('+catindx+') li:eq('+vidindx+')').addClass('shadow2');
}

function SetVideoFields(indx)
{
	if (g_vids[indx].name == 'unset') 
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
	var filename = g_vids[indx]['filename'];
	
	var name = document.getElementById('video_name').value;
	var cli = document.getElementById('video_client').value;
	var dir = document.getElementById('video_director').value;
	var prod = document.getElementById('video_production').value;
	var ag = document.getElementById('video_agency').value;
	var img = document.getElementById('video_image').value;
	
    $.ajax({     
		type: "GET",
		url: 'util_op.php',                  
		data: "jquery_op=savevideodata&filename=" + filename + "&name=" + name + 
			  "&client=" + cli +	"&director=" + dir + "&production=" + 
			   prod + "&agency=" + ag + "&image=" + img,
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
				$('#video_list1 > li span.vid_name:eq('+indx+')').text(" " + name);
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
				$('#cats_list').append('<li><span class="cat_name">' + name + '</span><ul class="cat_video_list">');
				
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
				$('#cats_list li .cat_name:eq('+indx+')').parent().empty().remove();
				
				//remove from array
				g_cats.splice(indx, 1);
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
				
				console.log("sel cat " + sel_cat["indx"]);
				
				$('.cat_video_list:eq('+sel_cat['indx']+')').show();
			}
		} 
    }); 

}

function DelCatVideo(sel_cat, sel_vid)
{
    $.ajax({     
		type: "GET",
		url: 'util_op.php',                  
		data: "jquery_op=delcatvideo&cat=" + sel_cat['name'] + "&file=" + sel_vid['name'],
		dataType: 'json',
		
		success: function(result)          
		{
			if (result != "error")
			{
				//!! also works
				//$('#cats_list > li').eq(4).find('ul').find('li').eq(1).remove();
				$('#cats_list > li:eq('+sel_cat["indx"]+') ul li:eq('+sel_vid["indx"]+')').remove();
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
			
			
<div id="tabs">
    <ul>
        <li><a href="#fragment-1"><span>Videos</span></a></li>
        <li><a href="#fragment-2"><span>Photos</span></a></li>
    </ul>
	
	<!-- tab div -->
    <div id="fragment-1">
	
		<!-- file upload -->
		<div id="video_upload_div">
		<form action="util_op.php" method="POST" enctype="multipart/form-data" id="form_video_upload">
		<div id="upload_button" ><span>Upload Videos/Photos</span></div>
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
	
	<!--
		<div class="progress">
			<div class="bar"></div >
			<div class="percent">0%</div >
		</div>
		-->
		<div id="status"></div>

		<div id="videos_div">
			<div id="video_list1_div">
				<span class="video_name_hdr">Name</span><span class="video_file_hdr">File</span>
				<div id="del_video_div"><span>Delete</span><span id="del_video"> Video</span></div>
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
			<!--  use hide/show to replace select and hide drop arrow
			<label for="video_image_input">Agency:</label>
			<input type="text" id="video_image_input" class="vid_info_input" name="video_image_input" ><br>			
			-->
				
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
					echo "<li>";
					echo "<span class='cat_name'>{$cat['name']}</span>";
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