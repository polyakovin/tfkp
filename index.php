<?
	include("blocks/db.php");

	//Закрытие конкурса просроченных задач
	$r_tasks=mysql_query("SELECT id,closing_date,closing_time FROM tasks WHERE status=1");
	$tasks=mysql_fetch_array($r_tasks);
	do
	{
		if($tasks["closing_date"]!="")
		{
			$id=$tasks["id"];
			
			$year=substr($tasks["closing_date"],6,4);
			$month=substr($tasks["closing_date"],3,2);
			$day=substr($tasks["closing_date"],0,2);
			
			if($tasks["closing_time"]!="")
			{
				$hour=substr($tasks["closing_time"],0,2);
				$minute=substr($tasks["closing_time"],3,2);
			}
			
			if($year<date(Y))
				mysql_query("UPDATE tasks SET status=0 WHERE id='$id'");
			elseif(($year==date(Y))&&($month<date(m)))
				mysql_query("UPDATE tasks SET status=0 WHERE id='$id'");
			elseif(($month==date(m))&&($day<date(d)))
				mysql_query("UPDATE tasks SET status=0 WHERE id='$id'");
			elseif($tasks["closing_time"]!="")
			{
				if(($day==date(d))&&($hour<date(G)))
					mysql_query("UPDATE tasks SET status=0 WHERE id='$id'");
				elseif(($hour==date(G))&&($minute<date(i)))
					mysql_query("UPDATE tasks SET status=0 WHERE id='$id'");
			}
		}
	}
	while($tasks=mysql_fetch_array($r_tasks));
	
	$r_common=mysql_query("SELECT * FROM common WHERE year=201502",$db);
	$common=mysql_fetch_array($r_common);
	
	$r_tasks=mysql_query("SELECT * FROM tasks ORDER BY number",$db);
	$tasks=mysql_fetch_array($r_tasks);
	
	$r_participants=mysql_query("SELECT * FROM participants ORDER BY rating DESC",$db);
	$participants=mysql_fetch_array($r_participants);
	
	$r_winners=mysql_query("SELECT surname,name,grp FROM participants WHERE result=1 ORDER BY surname",$db);
	$winners=mysql_fetch_array($r_winners);
	
	$r_examples=mysql_query("SELECT * FROM examples",$db);
	$examples=mysql_fetch_array($r_examples);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Конкурс за право попасть на досрочный экзамен по ТФКП</title>
		<meta charset="utf-8"/>
		<link href="css/main_style.css" rel="stylesheet" type="text/css">
		<!--[if lt IE 8]> 
			<style>
				ol {
					list-style-type: decimal;
				}
			</style
		<![endif]--> 
		<link href="css/lightbox.css" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
	<body>
		<div id="body">
			<h1>
				Конкурс за право попасть
				на досрочный экзамен по ТФКП</br>
				<span>(<?
					if($common["status"]==1)
						echo '<span class="cool">открыт до '.$common["finish_date"].'</span>';
					else
						echo '<span class="uncool">закрыт</span>';
				?>)</span>
			</h1>
			
			<a class="active_element" id="rules_button">Правила конкурса</a>
			<?=$common["rules"]?>
			
			<a class="active_element" id="tasks_list_button">Список задач</a>
			<ol id="tasks_list">
				<?
					do{
						if($tasks["statement_ext"]=="jpg"||$tasks["statement_ext"]=="jpeg"||$tasks["statement_ext"]=="png"||$tasks["statement_ext"]=="gif")
							$statement_lightbox='data-lightbox="task'.$tasks["id"].'"';
						else
							$statement_lightbox='';
						
						if($tasks["solution_ext"]!="")
						{
							if($tasks["solution_ext"]=="jpg"||$tasks["solution_ext"]=="jpeg"||$tasks["solution_ext"]=="png"||$tasks["solution_ext"]=="gif")
								$solution_lightbox='data-lightbox="solution'.$tasks["id"].'"';
							else
								$solution_lightbox='';
							
							$solution='<a href="solutions/'.$tasks["id"].'.'.$tasks["solution_ext"].'" '.$solution_lightbox.'>Посмотреть решение</a>.';
						}
						else
							$solution='';
						
						if($tasks["comment_ext"]!="")
						{
							if($tasks["comment_ext"]=="jpg"||$tasks["statement_ext"]=="jpeg"||$tasks["statement_ext"]=="png"||$tasks["statement_ext"]=="gif")
								$comment_lightbox='data-lightbox="comment'.$tasks["id"].'"';
							else
								$comment_lightbox='';
							
							$comment='<a href="comments/'.$tasks["id"].'.'.$tasks["comment_ext"].'" '.$comment_lightbox.'>Посмотреть комментарий</a>.';
						}
						else
							$comment='';
						
						if($tasks["status"]==1)
						{
							if($tasks["closing_date"]==""){$date="";}else{$date=" до ".$tasks["closing_time"]." ".$tasks["closing_date"];}
							$status='<span class="cool">открыт'.$date.'</span>';
						}
						else
							$status='<span class="uncool">закрыт</span>';
							
						echo'
							<li>
								<a class="active_element" href="tasks/'.$tasks["id"].'.'.$tasks["statement_ext"].'" '.$statement_lightbox.' title="Количество решивших задачу:&nbsp;'.$tasks["solved"].'. Рейтинг задачи:&nbsp;'.$tasks["rating"].'.">'.$tasks["name"].'</a>
								<span class="additional_info">
									Конкурс на решение этой задачи '.$status.'. '.$comment.' '.$solution.'
								</span>
							</li>
						';
					}
					while($tasks=mysql_fetch_array($r_tasks));
				?>
			</ol>
			
			<a class="active_element" id="participants_button">Список участников</a>
			<table id="participants">
				<tr valign="bottom">
					<th class="ac1">Фамилия</th>
					<th class="ac1">Имя</th>
					<th class="ac1">Группа</th>
					<th class="ac1">Номера решенных задач</th>
					<th class="ac2 active_element" id="additional_conditions">Необходимые условия</th>
					<th class="ac1">Рейтинг</th>
					<th class="ac1" width="100px">Итог</th>
				</tr>
				<tr valign="bottom" class="ac">
					<th>Рекомендация преподавателя, ведущего семинары</th>
					<th>БРС за&nbsp;семестр</th>
					<th>Оценка за&nbsp;семестровую контрольную работу</th>
				</tr>
				<?
					do{
						$r=0;
						$b=0;
						$t=0;
						
						if($participants["recomendation"]==1)
						{
							$recomendation='<td class="ac cool">да</td>';
							$r=1;
						}
						else
							$recomendation='<td class="ac uncool">нет</td>';
						
						if($participants["brs"]>=$common["pass_brs"])
						{
							$b=1;
							$brs='<td class="ac cool">'.$participants["brs"].'</td>';
						}
						else
							$brs='<td class="ac uncool">'.$participants["brs"].'</td>';
						
						if($participants["test"]>=$common["pass_test"])
						{
							$t=1;
							$test='<td class="ac cool">'.$participants["test"].'</td>';
						}
						else
							$test='<td class="ac uncool">'.$participants["test"].'</td>';
						
						if(($r&$b&$t)==1)
							$necessary_conditions='<td class="ac0 cool">выполнены</td>';
						else
							$necessary_conditions='<td class="ac0 uncool">не выполнены</td>';
						
						if($participants["rating"]>=$common["pass_rating"])
							$rating='<td class="cool">'.$participants["rating"].'</td>';
						else
							$rating='<td class="uncool">'.$participants["rating"].'</td>';
						
						if($participants["result"]==1)
							$result='<td class="cool">допущен</td>';
						else
							$result='<td class="uncool">не допущен</td>';
						
						echo'
							<tr>
								<td class="name">'.$participants["surname"].'</td>
								<td class="name">'.$participants["name"].'</td>
								<td>'.$participants["grp"].'</td>
								<td>'.$participants["tasks"].'</td>
								'.$necessary_conditions.$recomendation.$brs.$test.$rating.$result.'
							</tr>
						';
					}
					while($participants=mysql_fetch_array($r_participants));
				?>
			</table>
			
			<a class="active_element" id="the_ones_button">Итоги. Список допущенных к досрочному экзамену</a>
			<table id="the_ones">
				<tr valign="bottom">
					<th class="adc1">Фамилия</th>
					<th class="adc1">Имя</th>
					<th class="adc1">Группа</th>
				</tr>
				<tr valign="bottom" class="adc">
					<th>Рекомендация преподавателя, ведущего семинары</th>
					<th>БРС за&nbsp;семестр</th>
					<th>Оценка за&nbsp;семестровую контрольную работу</th>
				</tr>
				<?
					do{
						echo'
							<tr>
								<td class="name">'.$winners["surname"].'</td>
								<td class="name">'.$winners["name"].'</td>
								<td>'.$winners["grp"].'</td>
							</tr>
						';
					}
					while($winners=mysql_fetch_array($r_winners));
				?>
			</table>
			
			<center>Экзамен пройдёт <?=$common["exam_date"]?> в <?=$common["exam_place"]?> в <?=$common["exam_time"]?></center>
			
			<a class="active_element" href="./docs/exam_programm.<?=$common["programm_ext"]?>" id="exam_program_button">Скачать экзаменационную программу (.<?=$common["programm_ext"]?>)</a>
			
			<a class="active_element" id="exam_tasks_button">Примеры задач на досрочном экзамене</a>
			<ol id="exam_tasks">
				<?
					do{
						$example_lightbox='';
						if($examples["example_ext"]=="jpg"||$examples["example_ext"]=="jpeg"||$examples["example_ext"]=="png"||$examples["example_ext"]=="gif")
							$example_lightbox='data-lightbox="examples"';
						
						echo'
							<li>
								<a href="examples/'.$examples["id"].'.'.$examples["example_ext"].'" '.$example_lightbox.' title="'.$examples["name"].'">'.$examples["name"].'</a>
							</li>
						';
					}
					while($examples=mysql_fetch_array($r_examples));
					
					/* Вытаскиваем все файлы из папки
					$problems = glob("problems/*.*");
					for($i=1;$i<=count($problems);$i++)
					{
						$lbox='';
						$ext=preg_replace("/.*?\./",'',$problems[$i-1]);
						if($ext=="jpg"||$ext=="jpeg"||$ext=="png"||$ext=="gif")
							$lbox=' data-lightbox="problems"';
						echo'<li><a href="'.$problems[$i-1].'"'.$lbox.'>Пример '.$i.'</a></li>';
					}*/
				?>
			</ol>
		</div>

		<div id="footer">Разработка и дизайн <a href="mailto:igor_polyakov@phystech.edu">Игоря Полякова</a></div>
			
		<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="js/lightbox.js"></script>
		<script type="text/javascript" src="js/js.js"></script>
	</body>
</html>