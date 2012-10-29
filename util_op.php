<?php
session_start();

if ($_POST['jquery_op'] == upload)
{

	foreach ($_FILES["azcast"]["error"] as $key => $error) 
	{
		if ($error == UPLOAD_ERR_OK) 
		{
			$name = $_FILES["azcast"]["name"][$key];
			move_uploaded_file( $_FILES["azcast"]["tmp_name"][$key], "uploads/" . $name);
		
			pr("filename: " . $name);
			
			$data[] = $name;
		}
    }
	
	echo json_encode($data);
	
	/*   copy location, need getcwd?
	$destination_path = getcwd().DIRECTORY_SEPARATOR;

   $result = 0;
   
   $target_path = $destination_path . basename( $_FILES['myfile']['name']);
   */
	

/* !! http://jquery.malsup.com/form/#file-upload
The following PHP snippet shows how you can be sure to return content successfully
<?php                 
$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 
if (!$xhr)  
    echo '<textarea>'; 
?> 
 
// main content of response here 
                 
<?php 
if (!$xhr)   
    echo '</textarea>'; 
?> 
*/
}

if ($_POST['jquery_op'] == setsessiondata)
{
	//$_SESSION['sel_cat'] = $_POST['sel_cat'];
	//$_SESSION['sel_vid'] = $_POST['sel_vid'];
	//$_SESSION['sel_cat_vid'] = $_POST['sel_cat_vid'];
	
	$_SESSION = $_POST;

	
	//pr("name: ".$_SESSION['sel_cat']['name']." sess ".$_POST['jquery_op']);
	
	//pr("name: ".$cat);
	return true;
}

if ($_GET['jquery_op'] == getsessiondata)
{
/*
	$data['cat_sel'] = $_SESSION['cat_sel'];
	$data['cat_sel'] = $_SESSION['cat_sel'];
	$data[$_SESSION['sel_cat'] = $_POST['sel_cat'];
	$data[$_SESSION['sel_vid'] = $_POST['sel_vid'];
	$data[$_SESSION['sel_cat_vid'] = $_POST['sel_cat_vid'];
	*/

	$data = $_SESSION;

	echo json_encode($data);
	
	return true;
}

if ($_GET['jquery_op'] == addcatvideo)
{
	$query = "INSERT INTO `vidcat`(`vid_id`, `cat_id`) 
			  SELECT vids.video_id, cats.cat_id
			  FROM cats, vids
			  WHERE cats.name='".$_GET['cat']."' 
			  and vids.filename='".$_GET['file']."'";
			  
	if (!SQLSetData($query))
	{
		pr("Can't insert data:addcatvideo : " . mysql_error());
		echo json_encode("error");
		return false;
	}
	
	return true;
}

if ($_GET['jquery_op'] == delcatvideo)
{
	//!!!!!!!!!!  finish
	/*
	//delete video entry from this category
	$query = "DELETE a 
			  FROM vidcat a
			  INNER JOIN cats b
			  ON  a.cat_id = b.cat_id
			  WHERE b.name='".$_GET['name']."'"; 

	if (!SQLSetData($query))
	{
		pr("Can\'t delete data : " . mysql_error());
		return false;
	}
	*/
	return true;
}

if ($_GET['jquery_op'] == createcategory)
{
	$query = "INSERT INTO cats (name) VALUES ('".$_GET['name']."')";	 

	if (!SQLSetData($query))
	{
		pr("Can\'t insert data : " . mysql_error());
		echo json_encode("error");
		return false;
	}
	
	return true;
}

if ($_GET['jquery_op'] == deletecategory)
{
	//delete all entries with this category
	$query = "DELETE a 
			  FROM vidcat a
			  INNER JOIN cats b
			  ON  a.cat_id = b.cat_id
			  WHERE b.name='".$_GET['name']."'"; 

	if (!SQLSetData($query))
	{
		pr("Can't delete data : " . mysql_error());
		echo json_encode("error");
		return false;
	}

	//delete category
	$query = "DELETE FROM cats
			  WHERE name='".$_GET['name']."'"; 

	if (!SQLSetData($query))
	{
		pr("Can't delete data : " . mysql_error());
		echo json_encode("error");
		return false;
	}
	
	return true;
}

if ($_GET['jquery_op'] == savevideodata)
{
	$query = "UPDATE vids
			 SET name='".$_GET['name']."', client='".$_GET['client']."', director='".$_GET['director'].
			 "', production_co='".$_GET['production']."', agency='".$_GET['agency'].
			 "' WHERE video_id=".$_GET['id'];
			 
	if (!SQLSetData($query))
	{
		pr("Can't set data : " . mysql_error());
		echo json_encode("error");
		return false;
	}
	
	return true;
}

//!! not used
if ($_GET['jquery_op'] == getvideodata)
{
	$id = $_GET['id'];
	
	$query = "SELECT * FROM vids WHERE (vids.video_id = $id)";
	
	$row = SQLGetData($query);
			  
	if (!$row)
	{
		pr("Can't get data : " . mysql_error());
		echo json_encode("error");
		return false;
	}
	
	echo json_encode($row);
	
	return true;
}

function SQLSetData($query)
{
	if (!OpenDatabase())
	{
		print "could not open database";
		return false;
	}

	if (!($hdl = mysql_query($query)))          
	{	
		pr("Can't set values: " . mysql_error());
		return false;
	}
	
	return true;
}

function SQLGetData($query)
{
	if (!OpenDatabase())
	{
		pr("could not open database" . mysql_error());
		return false;
	}

	if (!($hdl = mysql_query($query)))          
	{	
		pr("Can't get values: " . mysql_error());
		return false;
	}
	
	while ($row = mysql_fetch_array($hdl)) 
	{
       $return[] = $row;
	}
	
	//!! return if table is empty?
	return $return;
}
   
function OpenDatabase()
{
	$host = "localhost";
	$user = "root";
	$pass = "princess";
	$database = "azcasting";

	if (!($con = mysql_connect($host,$user,$pass)))
	{
		pr('Could not connect: ' . mysql_error());
		return false;
	}
	
	if (!($dbs = mysql_select_db($database, $con)))
	{
		pr("Can't use db : " . mysql_error());
		return false;
	}
	
	return true;
}

function pr($data, $mode)
{
	if (!isset($mode))
		$mode = 'a';
		
    //open the search file
    $handle = fopen("log_file.txt", $mode);

    if ( $handle === false )
    {
        print "ClearSearchResults:failed to open file $search_file<br />";
        return false;
    }

	date_default_timezone_set('MST');
	
    fwrite($handle, date("Y m j, g:i a: ").$data);
	fwrite($handle, "\n");
	
    fclose($handle);

    return true;
}




?>