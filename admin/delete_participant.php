<?
	include("../blocks/db.php");

	if(isset($_GET['id']))
		$id=$_GET['id'];
	
	mysql_query("DELETE FROM participants WHERE id='$id'");
	
	header('Location:index.php?delete=2');
?>