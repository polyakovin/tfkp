<?
	$db=mysql_connect("server", "login", "password") or die("MySQL Error: ".mysql_error());//реквизиты для доступа к MySql
	mysql_select_db ("db", $db) or die("MySQL Error: ".mysql_error());//подключение к базе данных

	mysql_query("SET NAMES utf8");//перекодировка всего сайта в utf-8

  date_default_timezone_set("Europe/Moscow");
?>