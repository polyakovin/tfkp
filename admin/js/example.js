$(document).ready(function(){
	setTimeout(function(){$(".success,.fail").slideUp(1000)},3000);

	//Предупреждение о неполноте заполнения формы добавления/редактирования примеров задач
	$("#example_form").submit(function()
	{
		if($("[name=example_name]").val()=="")
			{
				alert("Укажите, пожалуйста, название примера!");
				return false;
			}
			
		if(($("[name=example_ext]").val()=="")&&($("#example_form").attr("action")=="index.php"))
			{
				alert("Загрузите, пожалуйста, файл с примером!");
				return false;
			}
	});
});