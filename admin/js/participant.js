$(document).ready(function(){
	setTimeout(function(){$(".success,.fail").slideUp(1000)},3000);
	
	var i,j,x;
	j=1;
	x="";
	for(i=1;i<=$("[type=checkbox]").length;i++)
	{
		if($("[name=task_"+i+"]").is(':checked'))
		{
			x+=$("[name=task_"+i+"]").val();
			if(j<$("input:checked").length)
				x+=",";
			j++;
		}
	}
	$("#solved").val(x);
	$("[type=checkbox]").change(function(){
		j=1;
		x="";
		for(i=1;i<=$("[type=checkbox]").length;i++)
		{
			if($("[name=task_"+i+"]").is(':checked'))
			{
				x+=$("[name=task_"+i+"]").val();
				if(j<$("input:checked").length)
					x+=",";
				j++;
			}
		}
		$("#solved").val(x);
	});
	
	//Предупреждение о неполноте заполнения формы добавления/редактирования участников
	$("#participant_form").submit(function()
	{
		if($("[name=surname]").val()=="")
			{
				alert("Укажите, пожалуйста, фамилию конкурсанта!");
				return false;
			}
			
		if($("[name=name]").val()=="")
			{
				alert("Укажите, пожалуйста, имя конкурсанта!");
				return false;
			}
			
		if($("[name=grp]").val()=="")
			{
				alert("Укажите, пожалуйста, номер группы!");
				return false;
			}
	});
});