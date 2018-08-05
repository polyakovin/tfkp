<?
	include("../blocks/db.php");
	
	//К случаю изменения информации об участниках
	if(isset($_POST['id']))
	{
		$id=$_POST['id'];
		if($id=="")
		{
			unset($id);
		}
	}
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
	if(isset($_POST['tasks']))
	{
		$tasks=$_POST['tasks'];
		if($tasks=="")
		{
			unset($tasks);
		}
	}
	if(isset($_POST['must_have']))
	{
		$must_have=$_POST['must_have'];
		if($must_have=="")
		{
			unset($must_have);
		}
	}
	if(isset($_POST['recomendation']))
	{
		$recomendation=$_POST['recomendation'];
		if($recomendation=="")
		{
			unset($recomendation);
		}
	}
	if(isset($_POST['brs']))
	{
		$brs=$_POST['brs'];
		if($brs=="")
		{
			unset($brs);
		}
	}
	if(isset($_POST['test']))
	{
		$test=$_POST['test'];
		if($test=="")
		{
			unset($test);
		}
	}
	if(isset($_POST['rating']))
	{
		$rating=$_POST['rating'];
		if($rating=="")
		{
			unset($rating);
		}
	}
	if(isset($_POST['result']))
	{
		$result=$_POST['result'];
		if($result=="")
		{
			unset($result);
		}
	}
	
	if(isset($surname)&&isset($name)&&isset($grp)&&isset($id)) 
	{
		$ae='e';//"add" or "edit"?
		
		//Создание массива из новых номеров решённых задач
		$new_tasks=$tasks;
		$new_tasks=explode(",",$new_tasks);
		$n_tasks=$new_tasks;
		
		//Создание массива из старых номеров решённых задач
		$r_participant=mysql_query("SELECT * FROM participants WHERE id='$id'",$db);
		$participant=mysql_fetch_array($r_participant);
		
		$old_tasks=$participant["tasks"];
		$old_tasks=explode(",",$old_tasks);
		
		$participant_query=mysql_query("UPDATE participants SET surname='$surname',name='$name',grp='$grp',tasks='$tasks',recomendation='$recomendation',brs='$brs',test='$test',rating='$rating',result='$result' WHERE id='$id'");
		
		if($participant_query)
		{
			//Формирование нового рейтинга задач
			for($i=0;$i<count($new_tasks);$i++)
				for($j=0;$j<count($old_tasks);$j++)
					if($new_tasks[$i]==$old_tasks[$j])
					{
						$new_tasks[$i]="";
						$old_tasks[$j]="";
					}
			
			//Повышение рейтинга за проставленные галочки
			for($i=0;$i<count($new_tasks);$i++)
				if($new_tasks[$i]!="")
				{
					$number=$new_tasks[$i];
					$r_solved=mysql_query("SELECT solved FROM tasks WHERE number='$number'",$db);
					$solved=mysql_fetch_array($r_solved);
					$amount=$solved["solved"];
					$amount++;
					if($amount!=0)
						$rating=1/$amount;
					else
						$rating="+&infin;";
					mysql_query("UPDATE tasks SET solved='$amount',rating='$rating' WHERE number='$number'");
				}
			
			//Понижение рейтинга за снятие галочек
			for($i=0;$i<count($old_tasks);$i++)
				if($old_tasks[$i]!="")
				{
					$number=$old_tasks[$i];
					$r_solved=mysql_query("SELECT solved FROM tasks WHERE number='$number'",$db);
					$solved=mysql_fetch_array($r_solved);
					$amount=$solved["solved"];
					$amount--;
					if($amount!=0)
						$rating=1/$amount;
					else
						$rating="+&infin;";
					mysql_query("UPDATE tasks SET solved='$amount',rating='$rating' WHERE number='$number'");
				}
			
			//Формирование нового рейтинга всех участников
			$r_prtcpnts=mysql_query("SELECT id,tasks FROM participants",$db);
			$prtcpnts=mysql_fetch_array($r_prtcpnts);
			do
			{
				$participant_id=$prtcpnts["id"];
				$n_tasks=$prtcpnts["tasks"];
				$n_tasks=explode(",",$n_tasks);
				
				$total_rating=0;
				for($i=0;$i<count($n_tasks);$i++)
				{
					$n=$n_tasks[$i];
					$r_rating=mysql_query("SELECT rating FROM tasks WHERE number='$n'",$db);
					$rating=mysql_fetch_array($r_rating);
					$total_rating+=$rating["rating"];
				}
				mysql_query("UPDATE participants SET rating='$total_rating' WHERE id='$participant_id'");
			}
			while($prtcpnts=mysql_fetch_array($r_prtcpnts));
			
			//Сообщение о положительном результате операции
			$massage=
			"
				<p class='success'>Изменения успешно вступили в силу!</p>
			";
			$last_update=date(d).".".date(m).".".date(Y);
			mysql_query("UPDATE common SET last_update='$last_update' WHERE year=201502");
		}
		else
		{
			//Сообщение об отрицательном результате операции
			$massage=
			"
				<p class='fail'>Ошибка! Не удалось внести изменения. База данных не приняла запрос.</p>
			";
		} 
	}
	
	if(isset($_GET['id']))//В случае изменения информации об участниках
	{
		$id=$_GET['id'];
		$r_participant=mysql_query("SELECT * FROM participants WHERE id='$id'",$db);
		$participant=mysql_fetch_array($r_participant);
		
		$ae='e';//"add" or "edit"?
		$surname=$participant["surname"];
		$name=$participant["name"];
		$grp=$participant["grp"];
		$tasks=$participant["tasks"];
		$must_have=$participant["must_have"];
		$recomendation=$participant["recomendation"];
		$brs=$participant["brs"];
		$test=$participant["test"];
		$rating=$participant["rating"];
		$result=$participant["result"];
	}
	else//В случае добавления нового участника
	{
		$ae='a';//"add" or "edit"?
		$surname='';
		$name='';
		$grp='';
		$tasks='';
		$must_have='0';
		$recomendation='0';
		$brs='0';
		$test='0';
		$rating='0';
		$result='0';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Участник конкурса</title>
		<meta charset="utf-8"/>
		<link href="../css/main_style.css" rel="stylesheet" type="text/css">
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
		<link href="../css/lightbox.css" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
	<body>
		<div id="body">
			<h1>
				Участник конкурса
			</h1>
			
			<?=$massage?>
			
			<form id="participant_form" method="post"<?if($ae=="a")echo' action="index.php"';?>>
				<?if(isset($id))echo'<input type="hidden" name="id" value="'.$id.'">';?>
				<table>
					<tr>
						<td align="right">
							<label for="surname">
								Фамилия:
							</label>
						</td>
						<td align="left">
							<input type="text" name="surname" id="surname" value="<?=$surname?>">
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="name">
								Имя:
							</label>
						</td>
						<td align="left">
							<input type="text" name="name" id="name" value="<?=$name?>">
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="grp">
								Номер группы:
							</label>
						</td>
						<td align="left">
							<input type="text" name="grp" id="grp" value="<?=$grp?>">
						</td>
					</tr>
					<?if($ae=="e")
					{
						echo'
							<tr>
								<td align="right">
									<label for="tasks">
										Номера решённых задач:
									</label>
								</td>
								<td align="left">
						';
						
						//Проставление галочек напротив номеров решённых задач
						$solved=$tasks;
						$solved=explode(",",$solved);
						
						$r_tasks=mysql_query("SELECT number,status FROM tasks",$db);
						$tasks=mysql_fetch_array($r_tasks);
						do
						{
							$n=$tasks["number"];
							
							$checked="";
							for($i=0;$i<count($solved);$i++)
							{
								if($solved[$i]==$n)
									$checked="checked";
							}
							
							//Отключение возможности проставлять галочки напротив номеров задач, конкурс на решение которых закрыт
							$disabled="";
							if(!$tasks["status"])
								$disabled="disabled";
							
							echo'<label>'.$n.'<input type="checkbox" name="task_'.$n.'" value="'.$n.'" '.$checked.' '.$disabled.'></label>';
						}
						while($tasks=mysql_fetch_array($r_tasks));
						
						if($recomendation=='1')$recomend=' selected';
						if($result=='1')$result=' selected';
						
						if($must_have==0)$must_have=" selected";

						echo'
									<input type="hidden" id="solved" name="tasks" value="'.$tasks.'">
								</td>
							</tr>
							<tr>
								<td align="right"><label for="must_have">Необходимые условия:</label></td>
								<td align="left">
									<select name="must_have" id="must_have">
										<option value="1">выполнены</option>
										<option value="0"'.$must_have.'>не выполнены</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right">
									<label for="recomendation">
										Рекомендация преподавателя, ведущего семинары:
									</label>
								</td>
								<td align="left">
									<select id="recomendation" name="recomendation">
										<option value="0">нет</option>
										<option value="1"'.$recomend.'>есть</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right">
									<label for="brs">
										БРС за семестр:
									</label>
								</td>
								<td align="left">
									<input type="text" name="brs" id="brs" value="'.$brs.'">
								</td>
							</tr>
							<tr>
								<td align="right">
									<label for="test">
										Оценка за семестровую контрольную работу:
									</label>
								</td>
								<td align="left">
									<input type="text" name="test" id="test" value="'.$test.'">
								</td>
							</tr>
							<tr>
								<td align="right">
									Текущий рейтинг:
								</td>
								<td align="left">
									'.$rating.'
									<input type="hidden" id="rating" name="rating" value="'.$rating.'">
								</td>
							</tr>
							<tr>
								<td align="right">
									<label for="result">
										Итог:
									</label>
								</td>
								<td align="left">
									<select id="result" name="result">
										<option value="0">не допущен</option>
										<option value="1"'.$result.'>допущен</option>
									</select>
								</td>
							</tr>
						';
					}
					?>
					<tr>
						<td colspan="2"><input type="submit" value="<?if($ae=="a")echo'Добавить участника';elseif($ae=="e")echo'Внести изменения';?>"></td>
					</tr>
				</table>
			</form>
		<a id="back" href="index.php"></a>
		</div>
		<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="js/participant.js"></script>
	</body>
</html>