<?
	include("../blocks/db.php");

	if(isset($_GET['id']))
		$id=$_GET['id'];
	
	$r_extensions=mysql_query("SELECT statement_ext,solution_ext,comment_ext FROM tasks WHERE id='$id'",$db);
	$extensions=mysql_fetch_array($r_extensions);
	
	mysql_query("DELETE FROM tasks WHERE id='$id'");
	$statement='../tasks/'.$id.'.'.$extensions["statement_ext"];
	$solution='../solutions/'.$id.'.'.$extensions["solution_ext"];
	$comment='../comments/'.$id.'.'.$extensions["comment_ext"];

	if(file_exists($statement))
		unlink($statement);

	if(file_exists($solution))
		unlink($solution);

	if(file_exists($comment))
		unlink($comment);

	header('Location:index.php?delete=1');
?>