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


<style type="text/css">

<style>

form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px }

.progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>

</style>


<?php

require_once('util_op.php');

$g_vids = array();
$g_cats = array();
$g_vidcat = array();

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
		
		//!! what about alphabetizing?  numbering + css issues
				
	$g_vids = SQLGetData("SELECT * FROM vids"); 

	if (!$g_vids)
	{
		pr("GetInfo: SQLGetData from vids failed");
		return false;
	}

	$g_cats = SQLGetData("SELECT * FROM cats"); 

	if (!$g_cats)
	{
		pr("GetInfo: SQLGetData from cats failed");
		return false;
	}
	
	$g_vidcat = SQLGetData("SELECT * FROM vidcat"); 

	if (!$g_vidcat)
	{
		pr("GetInfo: SQLGetData from vidcat failed");
		return false;
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


var video_cats = new Array();
var video_files = new Array();
var video_names = new Array();
var g_sel_vid = new Object();
var g_sel_cat = new Object();
var g_sel_cat_vid = new Object(); 
 
var g_vids = new Object();
var g_cats = {};
var g_vidcat = {};

main_util();

function main_util()
{
	setinfo();
	
	
	
}
  
$(document).ready(function() 
{
	
	$("#tabs").tabs();
	
	GetSessionData();

	
	
	//click on video in main video list
	$('#video_list1 li').click(function(e) 
	{ 
		ClickVideo(this);

		g_sel_vid['indx'] = $(this).index();
		g_sel_vid['name'] = g_vids[$(this).index()]['filename'];;

		SetVideoFields(g_sel_vid['indx']);
	});
	
	//click on a category
	$('#cats_list li a').live('click', function() {
    
		//!! create a selectcat function
		
		$('.cat_video_list').eq(g_sel_cat['indx']).hide(1);
		$(this).parent().find('ul').toggle("slow", "swing");
		
		g_sel_cat['indx'] = $(this).parent().index();
		g_sel_cat['name'] = $(this).html();
		
		console.log("sel: " + g_sel_cat['indx']);
    });

	//click on video in a category
	$('.cat_video_list li').live('click', function(e) 
	{ 
		g_sel_cat_vid['indx'] = $(this).index();
		g_sel_cat_vid['name'] = $(this).html();
		console.log("cat vid " + g_sel_cat_vid['indx'] + "name " + g_sel_cat_vid['name']);
		//console.log("click " + $(this).find('ul').html());

		ClickVideo(this);

	});
	

	$("#btn2").click(function(){
		$("#videos_div").clone().appendTo("#fragment-2");
	});

	$("#btn33").click(function () 
  {
     $.ajax({     
		type: "GET",
      url: 'util_op.php',                  
      data: "jquery_op=getvideodata&row=" + 2,
                                       
      dataType: 'json',                
      success: function(data)          
      {
        $('#video_name').val(data['name']); 
		$('#video_client').val(data['client']);
		$('#video_director').val(data['director']);
		$('#video_production').val(data['production_co']);
		$('#video_agency').val(data['agency']);
      } 
    });
  });

    $("#btn3").click(function () 
	{
		SetSessionData();
	});

  	$("#btn4").click(function () 
	{
		DeleteCategory(g_sel_cat['name']);
		/*
		console.log("get session data");
		//var li = $('#cats_list.li')[1];
		$('.cat_video_list').eq(1).toggle(1);
		//GetSessionData();
		*/
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
		DeleteCategory(g_sel_cat['name']);
		
   });
   
	$('#del_video').click(function() 
   { 
		//!! mouse over pop up input box??
		//console.log("jjjjjjjjj");
		DelCatVideo(g_sel_cat_vid['indx'], g_sel_vid['name']);
		
   });
   
	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status');
   
	$('form').ajaxForm({
		data: {jquery_op: 'upload'},
		dataType: 'json', 
		beforeSend: function() {
			status.empty();
			var percentVal = '0%';
			bar.width(percentVal)
			percent.html(percentVal);
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			bar.width(percentVal)
			percent.html(percentVal);
			//console.log(percentVal, position, total);
		},
		complete: function(data) {
			//status.html(xhr.responseText);
			var percentVal = '100%';
			bar.width(percentVal)
			percent.html(percentVal);
		},
		success: function(data)          
		{
			AddVideo(data);
		}
      
	});
	
   
 
});

function AddVideo(filename)
{
	console.log("file :" + filename[0] + "  " + filename[1]);
	
	//$('#video_list1').prepend("<li><span class='name'></span><span class='file'>" + filename + "</span></li>");
	
//"<li><span class='name'>{$g_vids[$key]['name']}</span><span class='file'>{$g_vids[$key]['filename']}</span></li>"	
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

	//!! change to POST
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

	//convert php array to js array
	g_vids = <?php echo json_encode($g_vids ); ?>;
	g_cats = <?php echo json_encode($g_cats ); ?>;
	g_vidcat = <?php echo json_encode($g_vidcat ); ?>;
	
/*
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
*/	
	
}
  
function ClickVideo(li)
{
	var ul = li.parentNode;
	var li_elements = ul.getElementsByTagName('li');
	
	for (i=0; i < li_elements.length; i++)
	{
		li_elements[i].style.backgroundColor = 'white';
	}

	//li.style.backgroundColor = "#b8aea5";
	li.style.backgroundColor = "#e6dace";
}

function SetVideoFields(vid_indx)
{
	document.getElementById('video_name').value = g_vids[vid_indx].name;
	document.getElementById('video_client').value = g_vids[vid_indx].client;
	document.getElementById('video_director').value = g_vids[vid_indx].director;
	document.getElementById('video_production').value = g_vids[vid_indx].production_co;
	document.getElementById('video_agency').value = g_vids[vid_indx].agency;
}

function SaveVidInfo(vid_indx)
{
	var vid_id = g_vids[vid_indx]['video_id'];
	
	var name = document.getElementById('video_name').value;
	var cli = document.getElementById('video_client').value;
	var dir = document.getElementById('video_director').value;
	var prod = document.getElementById('video_production').value;
	var ag = document.getElementById('video_agency').value;
	
    $.ajax({     
		type: "GET",
		url: 'util_op.php',                  
		data: "jquery_op=savevideodata&id=" + vid_id + "&name=" + name + 
			  "&client=" + cli +	"&director=" + dir + "&production=" + 
			   prod + "&agency=" + ag,
		dataType: 'json',	 
		
		success: function(result)          
		{
			if (result != "error")
			{
				g_vids[vid_indx]['name'] = name;
				g_vids[vid_id]['client'] = cli;
				g_vids[vid_id]['director'] = dir;
				g_vids[vid_id]['production_co'] = prod;
				g_vids[vid_id]['agency'] = ag;
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
				//insert new cat to top of list
				$('#cats_list').prepend('<li><a>' + name + '</a><ul class="cat_video_list">');
			}
		} 
    });
}

function DeleteCategory(name)
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
				var indx = g_sel_cat['indx'];
				$('#cats_list li a:eq('+indx+')').parent().empty().remove();
			}
		} 
    });
}

function AddCatVideo(sel_cat, sel_vid)
{
	console.log("cat: " + sel_cat['indx'] + "vid " + sel_vid['indx']);

    $.ajax({     
		type: "GET",
		url: 'util_op.php',                  
		data: "jquery_op=addcatvideo&cat=" + sel_cat['name'] + "&file=" + sel_vid['name'],
		dataType: 'json',
		
		success: function(result)          
		{
			if (result != "error")
			{
				// selects all childs of cats_list
				$('#cats_list > li').eq(sel_cat['indx']).find('ul').append("<li>" + sel_vid['name'] + "</li>");
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
				$('#cats_list > li:eq(4) ul li:eq(1)').remove();
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
		<button id="btn3">set</button>
		<button id="btn4">get</button>
		
			
			
			
<div id="tabs">
    <ul>
        <li><a href="#fragment-1"><span>Videos</span></a></li>
        <li><a href="#fragment-2"><span>Photos</span></a></li>
    </ul>
	
	<!-- tab div -->
    <div id="fragment-1">
	
		<!-- file upload -->
		<form action="util_op.php" method="post" enctype="multipart/form-data">
			<input type="file" name="azcast[]" multiple><br>
			<input type="submit" value="Upload File to Server">
		</form>
    
		<div class="progress">
			<div class="bar"></div >
			<div class="percent">0%</div >
		</div>
		
		<div id="status"></div>

		<div id="videos_div">
			<div id="video_list1_div">
				<span class="video_name_hdr">Name</span><span class="video_file_hdr">File</span>
				<ul id="video_list1">
					
					<?php
						foreach($g_vids as $key=>$value)
						{
							echo "<li><span class='name'>{$g_vids[$key]['name']}</span><span class='file'>{$g_vids[$key]['filename']}</span></li>";
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
			<input type="text" id="video_client" class="vid_info_input" name="video_client"><br>		
			<label for="video_director">Director:</label>
			<input type="text" id="video_director" class="vid_info_input" name="video_director"><br>
			<label for="video_production">Production Co:</label>
			<input type="text" id="video_production" class="vid_info_input" name="video_production"><br>
			<label for="video_agency">Agency:</label>
			<input type="text" id="video_agency" class="vid_info_input" name="video_agency"><br>			
		</div>

		<div id="add_video_div">
		<span id="add_cat_video">Add Video</span><br>
		<span>></span>
		</div>
		<div id="del_video_div">
		<span>Delete</span>
		<span id="del_video">Video |</span>
		<span id="del_cat"> Category</span><br>
		</div>
		
		
	<div id="cats_list_div">
			<label for="new_cat_name" id="add_cat">Create New</label>
			<input type="text" id="new_cat_name"><br>
			<ul id="cats_list">
				<?php
				foreach($g_cats as $cat)
				{
					echo "<li>";
					echo "<a>{$cat['name']}</a>";
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
						foreach($g_vids as $key=>$value)
						{
							echo "<li><span class='name'>{$g_vids[$key]['name']}</span><span class='file'>{$key}</span></li>";
							print "\n";
							print "\t\t\t\t\t\t\t";
						}
					?>
				</ul>
			</div>
			
		
    </div>
	</div>
</div>
</body>   


</html>