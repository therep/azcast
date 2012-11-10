<?php
//session_start();

//CX466500728CA

//multiple row insert
//http://stackoverflow.com/questions/779986/insert-multiple-rows-via-a-php-array-into-mysql
/*
$sql = array(); 
foreach( $data as $row ) {
    $sql[] = '("'.mysql_real_escape_string($row['text']).'", '.$row['category_id'].')';
}
mysql_query('INSERT INTO table (text, category) VALUES '.implode(',', $sql));
*/


if ($_POST['jquery_op'] == upload)
{
	$error_msg[UPLOAD_ERR_OK] = "There is no error, the file uploaded with success.";
	$error_msg[UPLOAD_ERR_INI_SIZE] = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
	$error_msg[UPLOAD_ERR_FORM_SIZE] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
	$error_msg[UPLOAD_ERR_PARTIAL] = "The uploaded file was only partially uploaded.";
	$error_msg[UPLOAD_ERR_NO_FILE] = "No file was uploaded.";
	$error_msg[UPLOAD_ERR_NO_TMP_DIR] = "Missing a temporary folder.";
	$error_msg[UPLOAD_ERR_CANT_WRITE] = "Failed to write file to disk.";
	$error_msg[UPLOAD_ERR_EXTENSION] = "A PHP extension stopped the file upload.";

	foreach ($_FILES["videos"]["error"] as $key => $error) 
	{
		if ($error == UPLOAD_ERR_OK) 
		{
			$name = $_FILES["videos"]["name"][$key];
			
			move_uploaded_file( $_FILES["videos"]["tmp_name"][$key], "uploads/" . $name);

			$data[] = $name;
		}
		else
		{
			$name = $_FILES["videos"]["name"][$key];
			pr($error_msg[$error].": ".$name);
			$data['error'] = $error_msg[$error].": ".$name;
			break;
		}
    }
	
	echo json_encode($data);
	
	/*   copy location, need getcwd?
	$destination_path = getcwd().DIRECTORY_SEPARATOR;

   $result = 0;
   
   $target_path = $destination_path . basename( $_FILES['myfile']['name']);
   */
	
	return true;

}

if ($_POST['jquery_op'] == setsessiondata)
{
	$_SESSION = $_POST;

	return true;
}

if ($_GET['jquery_op'] == getsessiondata)
{
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

if ($_POST['jquery_op'] == delcatvideo)
{
	//delete from vidcat
	$query = "DELETE vidcat
			  FROM vidcat
			  INNER JOIN cats
			  ON  vidcat.cat_id = cats.cat_id
			  INNER JOIN vids
			  ON  vidcat.vid_id = vids.video_id
			  WHERE cats.name='".$_POST['cat']."' 
			  and vids.filename='".$_POST['filename']."'";

	//!! not error? just empty
	if (!SQLSetData($query))
	{
		pr("Can't delete data : " . mysql_error());
		echo json_encode("error");
		return false;
	}
	
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

	//!! not error? just empty
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

if ($_POST['jquery_op'] == savevideo)
{
	$query = "INSERT INTO vids (filename) VALUES ('".$_POST['filename']."')";	 

	if (!SQLSetData($query))
	{
		pr("Can't insert data : " . mysql_error());
		echo json_encode("error");
		return false;
	}
}

if ($_POST['jquery_op'] == deletevideo)
{
	//delete all entries from categories
	$query = "DELETE a 
			  FROM vidcat a
			  INNER JOIN vids b
			  ON  a.vid_id = b.video_id
			  WHERE b.filename='".$_POST['filename']."'"; 

	//!! not error? just empty
	if (!SQLSetData($query))
	{
		pr("Can't delete data : " . mysql_error());
		echo json_encode("error");
		return false;
	}

	//delete video
	$query = "DELETE FROM vids
			  WHERE filename='".$_POST['filename']."'"; 

	if (!SQLSetData($query))
	{
		pr("Can't delete data : " . mysql_error());
		echo json_encode("error");
		return false;
	}
	
	return true;
}

if ($_POST['jquery_op'] == updatecategory)
{
	$query = "UPDATE cats
			 SET name='".$_POST['name']."'
			 WHERE name='".$_POST['orig']."'";
			 
	if (!SQLSetData($query))
	{
		pr("Can't set data : " . mysql_error());
		echo json_encode("error");
		return false;
	}
	
	return true;
}

if ($_GET['jquery_op'] == savevideodata)
{
	$query = "UPDATE vids
			 SET name='".$_GET['name']."', client='".$_GET['client']."', director='".$_GET['director'].
			 "', production_co='".$_GET['production']."', image='".$_GET['image']."', agency='".$_GET['agency'].
			 "' WHERE filename='".$_GET['filename']."'";
			 
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

function CreateTable($name)
{
	$query = "CREATE TABLE cats
			(cat_id int,
			 name varchar(100))";
			 
	if (!SQLSetData($query))
	{
		pr("Can't set data : " . mysql_error());
		return false;
	}
	
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

	if (strstr($_SERVER['SERVER_NAME'], "localhost"))
	{
		$host = "localhost";
		$user = "root";
		$pass = "princess";
		$database = "azcasting";
	}
	else if (strstr($_SERVER['SERVER_NAME'], "jlynnecosmetics"))
	{
		$host = "localhost";
		$user = "jlynneco_ward";
		$pass = "big20mac";
		$database = "jlynneco_db1";
	}
	
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

function pr($data)
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
	
    fwrite($handle, date("Y m j, g:i:s a: ").$data);
	fwrite($handle, "\n");
	
    fclose($handle);

    return true;
}




?>