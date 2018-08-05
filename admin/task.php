<?
	include("../blocks/db.php");
	
	//Очистка кэша (чтобы нумерация задач изменялась)
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
	
	if(isset($_POST['id']))
	{
		$id=$_POST['id'];
		if($id=="")
		{
			unset($id);
		}
	}
	if(isset($_POST['number']))
	{
		$number=$_POST['number'];
		if($number=="")
		{
			unset($number);
		}
	}
	if(isset($_POST['name']))
	{
		$name=$_POST['name'];
		if($name=="")
		{
			unset($name);
		}
	}
	if(isset($_POST['status']))
	{
		$status=$_POST['status'];
		if($status=="")
		{
			unset($status);
		}
	}
	if(isset($_POST['closing_date']))
	{
		$closing_date=$_POST['closing_date'];
		if($closing_date=="")
		{
			unset($closing_date);
		}
	}
	if(isset($_POST['closing_time']))
	{
		$closing_time=$_POST['closing_time'];
		if($closing_time=="")
		{
			unset($closing_time);
		}
	}
	
	if(isset($name)&&isset($status)&&isset($id)) 
	{
		$ae='e';
		
		$task_query=mysql_query("UPDATE tasks SET name='$name',status='$status',closing_date='$closing_date',closing_time='$closing_time' WHERE id='$id'");
		
		if($task_query)
		{
			$massage=
			"
				<p class='success'>Изменения успешно вступили в силу!</p>
			";
			if(!$_FILES['statement_ext']['error'])
			{
				$statement_ext=preg_replace("/.*?\./", '', $_FILES['statement_ext']['name']);
				$file="../tasks/".$id.".".$statement_ext;
				move_uploaded_file($_FILES['statement_ext']['tmp_name'],$file);
				mysql_query("UPDATE tasks SET statement_ext='$statement_ext' WHERE id='$id'");
			}
			elseif($_FILES['statement_ext']['error']!=4)
			{
				$massage.=
				"
					<p class='fail'>Ошибка! Не удалось загрузить условие.</p>
				";
			}
			if(!$_FILES['solution_ext']['error'])
			{
				$solution_ext=preg_replace("/.*?\./", '', $_FILES['solution_ext']['name']);
				$file="../solutions/".$id.".".$solution_ext;
				move_uploaded_file($_FILES['solution_ext']['tmp_name'],$file);
				mysql_query("UPDATE tasks SET solution_ext='$solution_ext' WHERE id='$id'");
			}
			elseif($_FILES['solution_ext']['error']!=4)
			{
				$massage.=
				"
					<p class='fail'>Ошибка! Не удалось загрузить решение.</p>
				";
			}
			if(!$_FILES['comment_ext']['error'])
			{
				$comment_ext=preg_replace("/.*?\./", '', $_FILES['comment_ext']['name']);
				$file="../comments/".$id.".".$comment_ext;
				move_uploaded_file($_FILES['comment_ext']['tmp_name'],$file);
				mysql_query("UPDATE tasks SET comment_ext='$comment_ext' WHERE id='$id'");
			}
			elseif($_FILES['comment_ext']['error']!=4)
			{
				$massage.=
				"
					<p class='fail'>Ошибка! Не удалось загрузить комментарий.</p>
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
		$r_task=mysql_query("SELECT * FROM tasks WHERE id='$id'",$db);
		$task=mysql_fetch_array($r_task);
		
		$ae='e';
		$number=$task["number"];
		$name=$task["name"];
		$status=$task["status"];
		$closing_date=$task["closing_date"];
		$closing_time=$task["closing_time"];
		$solved=$task["solved"];
		$rating=$task["rating"];
		$statement_ext=$task["statement_ext"];
		$solution_ext=$task["solution_ext"];
		$comment_ext=$task["comment_ext"];
	}
	else
	{
		$r_count=mysql_query("SELECT number FROM tasks",$db);
		$count = mysql_fetch_row($r_count);
		
		$number=0;
		do
		{
			if($count[0]>$number)$number=$count[0];
		}
		while($count = mysql_fetch_row($r_count));
		$number++;
		
		$ae='a';
		$name='';
		$status='1';
		$closing_date='';
		$closing_time='23:59';
		$solved='';
		$rating='';
		$statement_ext='';
		$solution_ext='';
		$comment_ext='';
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
				Конкурсная задача по ТФКП
			</h1>
			
			<?=$massage?>
			
			<form id="task_form" enctype="multipart/form-data" method="post"<?if($ae=="a")echo' action="index.php"';?>>
				<?if(isset($id))echo'<input type="hidden" name="id" value="'.$id.'">';?>
				<table>
					<tr>
						<td align="right">
							<label for="number">
								Номер:
							</label>
						</td>
						<td align="left">
							<?
								if($ae=="a")
									echo'<input type="text" name="number" id="number" value="'.$number.'">';
								else
									echo$number;
							?>
							
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="name">
								Название:
							</label>
						</td>
						<td align="left">
							<input type="text" name="name" id="name" value="<?=$name?>">
						</td>
					</tr>
					
					<tr>
						<td align="right">
							<label for="statement_ext">
								Условие:
							</label>
						</td>
						<td align="left">
							<input type="file" name="statement_ext" id="statement_ext"><?if($statement_ext!='')echo"<label>(файл загружен)</label>"?>
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="solution_ext">
								Решение:
							</label>
						</td>
						<td align="left">
							<input type="file" name="solution_ext" id="solution_ext"><?if($solution_ext!='')echo"<label>(файл загружен)</label>"?>
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="comment_ext">
								Комментарии или подсказки:
							</label>
						</td>
						<td align="left">
							<input type="file" name="comment_ext" id="comment_ext"><?if($comment_ext!='')echo"<label>(файл загружен)</label>"?>
						</td>
					</tr>
					
					<tr>
						<td align="right">
							<label for="status">
								Текущее конкурсное состояние:
							</label>
						</td>
						<td align="left">
							<select name="status" id="status">
								<option value="1">конкурс открыт</option>
								<option value="0"<?if($status=='0')echo' selected';?>>конкурс закрыт</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="closing_date">
								Дата, до которой открыт конкурс:
							</label>
						</td>
						<td align="left">
							<input class="datepicker" type="text" name="closing_date" id="closing_date" value="<?=$closing_date?>">
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="closing_time">
								Время, до которого открыт конкурс:
							</label>
						</td>
						<td align="left">
							<input type="text" name="closing_time" id="closing_time" value="<?=$closing_time?>">
						</td>
					</tr>
					
					<?if($ae=="e")
						echo'
							<tr>
								<td align="right">
									Число студентов, решивших задачу:
								</td>
								<td align="left">
									'.$solved.'
								</td>
							</tr>
							<tr>
								<td align="right">
									Текущий рейтинг задачи:
								</td>
								<td align="left">
									'.$rating.'
								</td>
							</tr>
						';
					?>
					
					<tr>
						<td colspan="2"><input type="submit" value="<?if($ae=="a")echo'Добавить задачу';elseif($ae=="e")echo'Внести изменения';?>"></td>
					</tr>
				</table>
			</form>
		<a id="back" href="index.php"></a>
		</div>
		<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/task.js"></script>
	</body>
</html>