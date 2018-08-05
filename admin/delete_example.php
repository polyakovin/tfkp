<?
	include("../blocks/db.php");

	if(isset($_GET['id']))
		$id=$_GET['id'];
	
	$r_extensions=mysql_query("SELECT example_ext FROM examples WHERE id='$id'",$db);
	$extensions=mysql_fetch_array($r_extensions);
	
	mysql_query("DELETE FROM examples WHERE id='$id'");
	$example='../examples/'.$id.'.'.$extensions["example_ext"];

	if(file_exists($example))
		unlink($example);

	header('Location:index.php?delete=1');
?>