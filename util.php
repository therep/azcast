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
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->
<!-- development version only -->
<script type="text/javascript" src="lib/jquery-1.8.2.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>

  

<style type="text/css">


</style>

<?php

$g_vid_info = array();
$g_cat_info = array();

util_main();

function util_main()
{
		SetInfo();

}

function SetInfo()
{
	global $g_vid_info;
	global $g_cat_info;
	
	for ($i=0; $i < 50; $i++)
	{
		$key = "video ".$i.".flv";
		
		$g_vid_info[$key]['name'] = "video ".$i." name";
		$g_vid_info[$key]['client'] = "video ".$i." client";
		$g_vid_info[$key]['director'] = "video ".$i." director";
		$g_vid_info[$key]['production'] = "video ".$i." production co";
		$g_vid_info[$key]['agency'] = "video ".$i." agency";
	}
	
	$files = array_keys($g_vid_info);
	
	for ($i=0; $i < 5; $i++)
	{
		$key = "category ".$i;
		
		for ($j=0; $j < 10; $j++)
		{
			$g_cat_info[$key][] = $files[$i*10+$j];
		}
	}
}


?>

<script type="text/javascript">
<!--
function startUpload(){
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}

function stopUpload(success){
      var result = '';
      if (success == 1){
         result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
      }
      else {
         result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      document.getElementById('f1_upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
      document.getElementById('f1_upload_form').style.visibility = 'visible';      
      return true;   
}
-->

$(document).ready(function() 
{
	$("#tabs").tabs();
	
   $('#video_list1 li').click(function(e) 
   { 
		
		//console.log(this.parentNode.nodeName + "   sssssssss");
		
		ClickVideo(this);

		var elms = this.getElementsByTagName('span');
		for(var i=0; i < elms.length; i++) 
		{
			if (elms[i].getAttribute('class')=='file')
			{
				SetVideoFields(elms[i].innerHTML);
			}
		}
		
		//$(this).attr('id').hide();
		//$("#video_list").css("background-color", "#202020");
		//$(this).css("background-color", "red");
   });
	
	$('#video_list2 li').click(function(e) 
	   { 
		ClickVideo(this);
	});   
	
	$("#btn2").click(function(){
		$("#videos_div").clone().appendTo("#fragment-2");
	});

	$('#cats_list > li a').click(function() {
        $(this).parent().find('ul').toggle("slow", "swing");
		
		//console.log("hello");
    });
	
	
});

var video_cats = new Array();
var video_files = new Array();
var video_names = new Array();

var vid_info = new Object();
var cat_info = {};

main_util();

function main_util()
{
	setinfo();
	
	
}
	
function setinfo()
{
	for (i=0; i < 50; i++)
	{
		var key = "video " + i + ".flv";
		
		vid_info[key] = {
			name: "video " + i + " name",
			client: "video " + i + " client",
			director: "video " + i + " director",
			production: "video " + i + " production co",
			agency: "video " + i + " agency"
		}
	}

	var keys = Object.keys(vid_info);
	
	for (i=0; i < keys.length; i++)
	{
		cat_info[i] = {
			file: []
		}
	}
	
	for (i=0; i < keys.length; i++)
	{
		var j = i % 10;
		cat_info[j].file.push(keys[i]);
	}
}
  
function ClickVideo(li)
{
	var ul = li.parentNode;
	var li_elements = ul.getElementsByTagName('li');
	
	for (i=0; i < li_elements.length; i++)
	{
		li_elements[i].style.backgroundColor = "#e6dace";
	}

	li.style.backgroundColor = "#b8aea5";
}

function SetVideoFields(vid_file)
{
	document.getElementById('video_name').value = vid_info[vid_file].name;
	document.getElementById('video_client').value = vid_info[vid_file].client;
	document.getElementById('video_director').value = vid_info[vid_file].director;
	document.getElementById('video_production').value = vid_info[vid_file].production;
	document.getElementById('video_agency').value = vid_info[vid_file].agency;
}

  
</script>   
</head>

<body>
 <!--      
		 <button id="btn1" onclick="main_util()">vid</button>
		 <button id="btn2">clone</button>
-->		 
			
			
			
			
<div id="tabs">
    <ul>
        <li><a href="#fragment-1"><span>Videos</span></a></li>
        <li><a href="#fragment-2"><span>Photos</span></a></li>
    </ul>
    <div id="fragment-1">
        <div id="videos_div">
            <div id="upload_div">
                <form action="upload.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
                    <p id="f1_upload_process">Loading...<br/><img src="loader.gif" /><br/></p>
                    <p id="f1_upload_form" align="center"><br/>
                        <label>File:  
                              <input name="myfile" type="file" size="30" />
                         </label>
                         <label>
                             <input type="submit" name="submitBtn" class="sbtn" value="Upload" />
                         </label>
                    </p>
                     <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                 </form>
             </div>

			<div id="video_list1_div">
				<span class="video_name_hdr">Name</span><span class="video_file_hdr">File</span>
				<ul id="video_list1">
					
					<?php
						foreach($g_vid_info as $key=>$value)
						{
							echo "<li><span class='name'>{$g_vid_info[$key]['name']}</span><span class='file'>{$key}</span></li>";
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
			<input type="text" id="video_name" class="vid_info_input" name="video_name"  value="txt"><br>
			<label for="video_client">Client:</label>
			<input type="text" id="video_client" class="vid_info_input" name="video_client" value="txt"><br>		
			<label for="video_director">Director:</label>
			<input type="text" id="video_director" class="vid_info_input" name="video_director"  value="txt"><br>
			<label for="video_production">Production Co:</label>
			<input type="text" id="video_production" class="vid_info_input" name="video_production"  value="txt"><br>
			<label for="video_agency">Agency:</label>
			<input type="text" id="video_agency" class="vid_info_input" name="video_agency"  value="txt"><br>			
		</div>

		<div id="add_video_div">
		<span>Add Video</span><br>
		<span>></span>
		</div>
		
	<div id="cats_list_div">
			<span id="add_cat_text">Create New</span>
			<ul id="cats_list">
				<?php
				foreach($g_cat_info as $catname=>$value)
				{
					echo "<li>";
					echo "<a>{$catname}</a>";
					echo "<ul class='cat_video_list'>";
											
					foreach($g_cat_info[$catname] as $filename)
					{
						echo "<li>{$filename}</li>";
						print "\n";
						print "\t\t\t\t\t\t\t";
					}
					echo "</ul>";
					echo "</li>";
				}
				?>
			</ul>
			
	</div>
		
    </div>
    <div id="fragment-2">
        <div id="videos_div">
            <div id="upload_div">
                <form action="upload.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
                    <p id="f1_upload_process">Loading...<br/><img src="loader.gif" /><br/></p>
                    <p id="f1_upload_form" align="center"><br/>
                        <label>File:  
                              <input name="myfile" type="file" size="30" />
                         </label>
                         <label>
                             <input type="submit" name="submitBtn" class="sbtn" value="Upload" />
                         </label>
                    </p>
                     <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                 </form>
             </div>

			<div id="video_list2_div">
				<span class="video_name_hdr">Name</span><span class="video_file_hdr">File</span>
				<ul id="video_list2">
					
					<?php
						foreach($g_vid_info as $key=>$value)
						{
							echo "<li><span class='name'>{$g_vid_info[$key]['name']}</span><span class='file'>{$key}</span></li>";
							print "\n";
							print "\t\t\t\t\t\t\t";
						}
					?>
				</ul>
			</div>
			
	<div id="cats_list_div">
			<ul id="cats_list">
				<?php
				foreach($g_cat_info as $catname=>$value)
				{
					echo "<li>";
					echo "<a>{$catname}</a>";
					echo "<ul class='cat_video_list'>";
											
					foreach($g_cat_info[$catname] as $filename)
					{
						echo "<li>{$filename}</li>";
						print "\n";
						print "\t\t\t\t\t\t\t";
					}
					echo "</ul>";
					echo "</li>";
				}
				?>
			</ul>
			
	</div>
				
    </div>
	</div>
</div>
</body>   


</html>
