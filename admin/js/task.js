$(document).ready(function(){
	setTimeout(function(){$(".success,.fail").slideUp(1000)},3000);
	
	$(".datepicker").datepicker();
	$(".datepicker").datepicker("option","showAnim","clip");
	
	//Предупреждение о неполноте заполнения формы добавления/редактирования задач
	$("#task_form").submit(function()
	{
		if($("[name=number]").val()=="")
			{
				alert("Укажите, пожалуйста, номер задачи!");
				return false;
			}
			
		if($("[name=name]").val()=="")
			{
				alert("Укажите, пожалуйста, название задачи!");
				return false;
			}
			
		if(($("[name=statement_ext]").val()=="")&&($("#task_form").attr("action")=="index.php"))
			{
				alert("Загрузите, пожалуйста, условие задачи!");
				return false;
			}
	});
});