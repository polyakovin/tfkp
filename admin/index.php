<?
	include("../blocks/db.php");
	
	/*
	//Эксперименты над данными
	for($i=0;$i<count($x);$i++)
	$massage.=
	"
		<p class='success'>".$x[$i]."</p>
	";
	*/
		
	//Загрузка примера задачи на досроке
	if(isset($_POST['example_name']))
	{
		$example_name=$_POST['example_name'];
		if($example_name=="")
		{
			unset($example_name);
		}
	}
	
	if(isset($example_name))
	{
		$example_ext=preg_replace("/.*?\./", '', $_FILES['example_ext']['name']);

		$example_query=mysql_query("INSERT INTO examples (name,example_ext) VALUES ('$example_name','$example_ext')");
		
		$id=mysql_insert_id();
		
		if($example_query)
		{
			if(!$_FILES['example_ext']['error'])
			{	
				$file="../examples/".$id.".".$example_ext;
				move_uploaded_file($_FILES['example_ext']['tmp_name'],$file);
				
				$massage=
				"
					<p class='success'>Пример задачи был успешно загружен!</p>
				";
			}
			elseif($_FILES['example_ext']['error']!=4)
			{
				$massage.=
				"
					<p class='fail'>Ошибка! Не удалось загрузить файл примера задачи.</p>
				";
			}
		}
		else
		{
			$massage.=
			"
				<p class='fail'>Ошибка! Не удалось добавить пример задачи.</p>
			";
		}
	}
	
	if($_GET['delete']=="1")
		$massage="<p class='success'>Задача была успешно удалена!</p>";
	if($_GET['delete']=="2")
		$massage="<p class='success'>Участник был исключён из списка!</p>";
	
	//Обработка основных изменений
	if(isset($_POST['status']))
	{
		$status=$_POST['status'];
		if($status=="")
		{
			unset($status);
		}
	}
	if(isset($_POST['finish_date']))
	{
		$finish_date=$_POST['finish_date'];
		if($finish_date=="")
		{
			unset($finish_date);
		}
	}
	if(isset($_POST['exam_date']))
	{
		$exam_date=$_POST['exam_date'];
		if($exam_date=="")
		{
			unset($exam_date);
		}
	}
	if(isset($_POST['exam_time']))
	{
		$exam_time=$_POST['exam_time'];
		if($exam_time=="")
		{
			unset($exam_time);
		}
	}
	if(isset($_POST['exam_place']))
	{
		$exam_place=$_POST['exam_place'];
		if($exam_place=="")
		{
			unset($exam_place);
		}
	}
	if(isset($_POST['contest_rules']))
	{
		$contest_rules=$_POST['contest_rules'];
		if($contest_rules=="")
		{
			unset($contest_rules);
		}
	}
	if(isset($_POST['pass_brs']))
	{
		$pass_brs=$_POST['pass_brs'];
		if($pass_brs=="")
		{
			unset($pass_brs);
		}
	}
	if(isset($_POST['pass_test']))
	{
		$pass_test=$_POST['pass_test'];
		if($pass_test=="")
		{
			unset($pass_test);
		}
	}
	if(isset($_POST['pass_rating']))
	{
		$pass_rating=$_POST['pass_rating'];
		if($pass_rating=="")
		{
			unset($pass_rating);
		}
	}
	
	if(isset($status)&&isset($contest_rules)) 
	{
		$last_update=date(d).".".date(m).".".date(Y);
		$common_query=mysql_query("UPDATE common SET status='$status',finish_date='$finish_date',exam_date='$exam_date',exam_time='$exam_time',exam_place='$exam_place',rules='$contest_rules',last_update='$last_update',pass_brs='$pass_brs',pass_test='$pass_test',pass_rating='$pass_rating' WHERE year=201502");
		
		if($common_query)
		{
			$massage=
			"
				<p class='success'>Изменения успешно вступили в силу!</p>
			";
			if(!$_FILES['exam_programm']['error'])
			{
				$file_extension=preg_replace("/.*?\./", '', $_FILES['exam_programm']['name']);
				mysql_query("UPDATE common SET programm_ext='$file_extension' WHERE year=201502");
				$file="../docs/exam_programm.".$file_extension;
				move_uploaded_file($_FILES['exam_programm']['tmp_name'],$file);
			}
			elseif($_FILES['exam_programm']['error']!=4)
			{
				$massage.=
				"
					<p class='fail'>Ошибка! Не удалось загрузить экзаменационную программу.</p>
				";
			}
			
		}
		else
		{
			$massage=
			"
				<p class='fail'>Ошибка! Не удалось внести изменения. База данных не приняла запрос.</p>
			";
		} 
	}
	
	//Обработка новой конкурсной задачи
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
	
	if(isset($number)&&isset($name)&&isset($status)) 
	{
		if(!$_FILES['statement_ext']['error'])
			$statement_ext=preg_replace("/.*?\./", '', $_FILES['statement_ext']['name']);
		else
			$statement_ext='';
		
		if(!$_FILES['solution_ext']['error'])
			$solution_ext=preg_replace("/.*?\./", '', $_FILES['solution_ext']['name']);
		else
			$solution_ext='';
		
		if(!$_FILES['comment_ext']['error'])
			$comment_ext=preg_replace("/.*?\./", '', $_FILES['comment_ext']['name']);
		else
			$comment_ext='';
		
		$task_query=mysql_query("INSERT INTO tasks (number,name,status,closing_date,closing_time,statement_ext,solution_ext,comment_ext,solved,rating) VALUES ('$number','$name','$status','$closing_date','$closing_time','$statement_ext','$solution_ext','$comment_ext','0','+&infin;')");
		
		$id=mysql_insert_id();
		
		if($task_query)
		{
			$massage=
			"
				<p class='success'>Задача успешно добавлена!</p>
			";
			if(!$_FILES['statement_ext']['error'])
			{
				$file="../tasks/".$id.".".$statement_ext;
				move_uploaded_file($_FILES['statement_ext']['tmp_name'],$file);
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
				$file="../solutions/".$id.".".$solution_ext;
				move_uploaded_file($_FILES['solution_ext']['tmp_name'],$file);
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
				$file="../comments/".$id.".".$comment_ext;
				move_uploaded_file($_FILES['comment_ext']['tmp_name'],$file);
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
	
	//Обработка нового конкурсанта
	if(isset($_POST['surname']))
	{
		$surname=$_POST['surname'];
		if($surname=="")
		{
			unset($surname);
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
	if(isset($_POST['grp']))
	{
		$grp=$_POST['grp'];
		if($grp=="")
		{
			unset($grp);
		}
	}

	if(isset($surname)&&isset($name)&&isset($grp)) 
	{
		$a=mysql_query("SELECT name,grp FROM participants WHERE surname='$surname'");
		if($a)
		{
			$bc=mysql_fetch_array($a);
			do
			{
				if($bc["name"]==$name&&$bc["grp"]==$grp)$ifissets=1;
			}
			while($bc=mysql_fetch_array($a));
		}

		if($ifissets)
		{
			$massage=
			"
				<p class='fail'>Ошибка! Студент ".$surname." ".$name." из ".$grp." группы уже участвует в конкурсе.</p>
			";
		}
		else
		{
			$participant_query=mysql_query("INSERT INTO participants (surname,name,grp) VALUES ('$surname','$name','$grp')");
			
			if($participant_query)
			{
				$massage=
				"
					<p class='success'>Теперь ".$surname." ".$name." из ".$grp." группы тоже участвует в конкурсе!</p>
				";
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
	}
	
	$r_common=mysql_query("SELECT * FROM common WHERE year=201502",$db);
	$common=mysql_fetch_array($r_common);
	
	$r_tasks=mysql_query("SELECT id,name FROM tasks ORDER BY number",$db);
	$tasks=mysql_fetch_array($r_tasks);
	
	$r_participants=mysql_query("SELECT id,surname,name,grp FROM participants ORDER BY surname",$db);
	$participants=mysql_fetch_array($r_participants);
	
	$r_examples=mysql_query("SELECT * FROM examples",$db);
	$examples=mysql_fetch_array($r_examples);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Админка сайта-конкурса по ТФКП</title>
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
				input[type="text"] {width:70px}
				textarea {
				margin:0 auto;
				height:660px;
				width:1024px;
				min-width:1024px;
				max-width:1024px;
				}
				center{font-size:12pt}
			</style>
		<link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
	<body>
		<div id="body">
			<h1>
				Админка сайта-конкурса по ТФКП</br>
				<span>(последнее обновление <?=$common["last_update"]?>)</span>
			<center>
				<input type="submit" value="< Предыдущий семестр">
				Осенний семестр 2015
				<input type="submit" value="Следующий семестр >">
			</center>
			</h1>
			
			<?=$massage?>
			
			<table>
				<tr>
					<td align="right">
						<a href="task.php">Добавить</a> / 
						<a id="edit_task" href="task.php?id=">редактировать</a> / 
						<a id="delete_task" href="delete_task.php?id=">удалить</a>
						<label for="tasks">
							задачу:
						</label>
					</td>
					<td align="left">
						<select name="task" id="tasks">
							<?
								do{
									echo'<option value="'.$tasks[0].'">'.$tasks[1].'</option>';
								}
								while($tasks=mysql_fetch_array($r_tasks));
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right">
						<a href="participant.php">Добавить</a> / 
						<a id="edit_participant" href="participant.php?id=">редактировать</a> / 
						<a id="delete_participant" href="delete_participant.php?id=">удалить</a>
						<label for="participants">
							участника:
						</label>
					</td>
					<td align="left">
						<select name="participant" id="participants">
							<?
								do{
									echo'<option value="'.$participants["id"].'">'.$participants["surname"].' '.$participants["name"].', '.$participants["grp"].'</option>';
								}
								while($participants=mysql_fetch_array($r_participants));
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right">
						<a href="example.php">Добавить</a> / 
						<a id="edit_example" href="example.php?id=">редактировать</a> / 
						<a id="delete_example" href="delete_example.php?id=">удалить</a>
						<label for="tasks">
							задачу:
						</label>
					</td>
					<td align="left">
						<select name="example" id="examples">
							<?
								do{
									echo'<option value="'.$examples["id"].'">'.$examples["name"].'</option>';
								}
								while($examples=mysql_fetch_array($r_examples));
							?>
						</select>
					</td>
				</tr>
				<?/*
				<form enctype="multipart/form-data" method="post">
					<input type="hidden" name="exmpl" value="1">
					<tr>
						<td align="right"><input type="submit" value="Добавить"> пример задачи на досроке:</td>
						<td align="left"><input type="file" name="example"></td>
					</tr>
				</form>
				<tr>
					<td align="right">
						<label for="task_examples">
							<a href="">Удалить</a>
							пример задачи на досроке:
						</label>
					</td>
					<td align="left">
						<select id="task_examples">
							<?
								$problems = glob("../problems/*.*");
								for($i=1;$i<=count($problems);$i++)
									echo'<option value="'.$i.'">Пример '.$i.'</option>';
							?>
						</select>
					</td>
				</tr>
				*/?>
			</table>
			<form enctype="multipart/form-data" method="post">
				<table>
					<tr>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td align="right"><label for="status">Статус конкурса:</label></td>
						<td align="left">
							<select name="status" id="status">
								<option value="1">открыт</option>
								<option value="0"<?if($common["status"]==0)echo" selected";?>>закрыт</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><label for="finish_date">Дата окончания конкурса:</label></td>
						<td align="left"><input class="datepicker" type="text" name="finish_date" id="finish_date" value="<?=$common["finish_date"]?>"></td>
					</tr>
					<tr>
						<td align="right"><label for="exam_date">Дата проведения экзамена:</label></td>
						<td align="left"><input class="datepicker" type="text" name="exam_date" id="exam_date" value="<?=$common["exam_date"]?>"></td>
					</tr>
					<tr>
						<td align="right"><label for="exam_time">Время проведения экзамена:</label></td>
						<td align="left"><input type="text" name="exam_time" id="exam_time" value="<?=$common["exam_time"]?>"></td>
					</tr>
					<tr>
						<td align="right"><label for="exam_place">Место проведения экзамена:</label></td>
						<td align="left"><input type="text" name="exam_place" id="exam_place" value="<?=$common["exam_place"]?>"></td>
					</tr>
					<tr>
						<td align="right"><label for="exam_programm">Программа экзамена:</label></td>
						<td align="left"><input type="file" name="exam_programm" id="exam_programm"><?if($common["programm_ext"]!='')echo"<label>(файл загружен)</label>"?></td>
					</tr>
					<tr>
						<td align="right"><label for="pass_brs">Минимальный балл БРС:</label></td>
						<td align="left"><input type="text" name="pass_brs" id="pass_brs" value="<?=$common["pass_brs"]?>"></td>
					</tr>
					<tr>
						<td align="right"><label for="pass_test">Минимальный балл за семестровую контрольную работу:</label></td>
						<td align="left"><input type="text" name="pass_test" id="pass_test" value="<?=$common["pass_test"]?>"></td>
					</tr>
					<tr>
						<td align="right"><label for="pass_rating">Минимальный рейтинг:</label></td>
						<td align="left"><input type="text" name="pass_rating" id="pass_rating" value="<?=$common["pass_rating"]?>"></td>
					</tr>
				</table>
				<center>
					<strong>Правила конкурса</strong>
					<textarea name="contest_rules" id="contest_rules"><?=$common["rules"]?></textarea>
					<input type="submit" value="Внести изменения">
				</center>
			</form>
		</div>
		<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/js.js"></script>
		<?
			include("../blocks/cleditor.php");
		?>
	</body>
</html>