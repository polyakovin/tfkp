<?
	include("../blocks/db.php");
	
	if(isset($_POST['id']))
	{
		$id=$_POST['id'];
		if($id=="")
		{
			unset($id);
		}
	}
	if(isset($_POST['ыы']))
	{
		$name=$_POST['example_name'];
		if($name=="")
		{
			unset($name);
		}
	}
	
	if(isset($name)&&isset($id)) 
	{
		$ae='e';
		
		$example_query=mysql_query("UPDATE examples SET name='$name' WHERE id='$id'");
		
		if($example_query)
		{
			$massage=
			"
				<p class='success'>Изменения успешно вступили в силу!</p>
			";
			if(!$_FILES['example_ext']['error'])
			{
				$example_ext=preg_replace("/.*?\./", '', $_FILES['example_ext']['name']);
				$file="../examples/".$id.".".$example_ext;
				move_uploaded_file($_FILES['example_ext']['tmp_name'],$file);
				mysql_query("UPDATE examples SET example_ext='$example_ext' WHERE id='$id'");
			}
			elseif($_FILES['example_ext']['error']!=4)
			{
				$massage.=
				"
					<p class='fail'>Ошибка! Не удалось загрузить файл с примером.</p>
				";
			}
			
			$last_update=date(d).".".date(m).".".date(Y);
			mysql_query("UPDATE common SET last_update='$last_update' WHERE year=201502");
		}
		else
		{
			$massage=
			"
				<p class='fail'>Ошибка! Не удалось внести изменения. База данных не приняла запрос.</p>
			";
		} 
	}
	
	if(isset($_GET['id']))
	{
		$id=$_GET['id'];
		
		$r_example=mysql_query("SELECT * FROM examples WHERE id='$id'",$db);
		$example=mysql_fetch_array($r_example);
		
		$ae='e';
		$name=$example["name"];
		$example_ext=$example["example_ext"];
	}
	else
	{
		$ae='a';
		$name='';
		$example_ext='';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Конкурсная задача по ТФКП</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="../js/jquery-ui-1.11.4.custom/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="../css/lightbox.css">
		<link rel="stylesheet" type="text/css" href="../css/main_style.css">
			<!--[if lt IE 8]>
				<style>
					ol {
						list-style-type: decimal;
					}
				</style>
			<![endif]-->
			<style>
				tr:nth-child(odd) {background:#fff}
				table {border:none}
				th, td {border:none;width:400px}
				input[type="text"] {width:300px}
			</style>
		<link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
	<body>
		<div id="body">
			<h1>
				Пример задачи на досроке
			</h1>
			
			<?=$massage?>
			
			<form id="example_form" enctype="multipart/form-data" method="post"<?if($ae=="a")echo' action="index.php"';?>>
				<?if(isset($id))echo'<input type="hidden" name="id" value="'.$id.'">';?>
				<table>
					<tr>
						<td align="right">
							<label for="example_name">
								Название:
							</label>
						</td>
						<td align="left">
							<input type="text" name="example_name" id="name" value="<?=$name?>">
						</td>
					</tr>
					
					<tr>
						<td align="right">
							<label for="example_ext">
								Файл с примером:
							</label>
						</td>
						<td align="left">
							<input type="file" name="example_ext" id="example_ext"><?if($example_ext!='')echo"<label>(файл загружен)</label>"?>
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="<?if($ae=="a")echo'Добавить пример задачи';elseif($ae=="e")echo'Внести изменения';?>"></td>
					</tr>
				</table>
			</form>
		<a id="back" href="index.php"></a>
		</div>
		<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/example.js"></script>
	</body>
</html>