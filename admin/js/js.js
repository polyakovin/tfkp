$(document).ready(function(){
	setTimeout(function(){$(".success,.fail").slideUp(1000)},3000);
	
	$(".datepicker").datepicker();
	$(".datepicker").datepicker("option","showAnim","clip");
	
	//Выбор задачи на редактирование/удаление
	var task_id=$("#tasks").val();
	
	var edit_task=$("#edit_task").attr("href");
	$("#edit_task").attr("href",edit_task+task_id);
	$("#tasks").change(function () {
		task_id=$("#tasks").val();
		$("#edit_task").attr("href",edit_task+task_id);
	});
	
	var delete_task=$("#delete_task").attr("href");
	$("#delete_task").attr("href",delete_task+task_id);
	$("#tasks").change(function () {
		task_id=$("#tasks").val();
		$("#delete_task").attr("href",delete_task+task_id);
	});
	
	//Выбор участника на редактирование/удаление
	var participant_id=$("#participants").val();
	
	var edit_participant=$("#edit_participant").attr("href");
	$("#edit_participant").attr("href",edit_participant+participant_id);
	$("#participants").change(function () {
		participant_id=$("#participants").val();
		$("#edit_participant").attr("href",edit_participant+participant_id);
	});
	
	var delete_participant=$("#delete_participant").attr("href");
	$("#delete_participant").attr("href",delete_participant+participant_id);
	$("#participants").change(function () {
		participant_id=$("#participants").val();
		$("#delete_participant").attr("href",delete_participant+participant_id);
	});
	
	//Выбор примера задачи на редактирование/удаление
	var example_id=$("#examples").val();
	
	var edit_example=$("#edit_example").attr("href");
	$("#edit_example").attr("href",edit_example+example_id);
	$("#examples").change(function () {
		example_id=$("#examples").val();
		$("#edit_example").attr("href",edit_example+example_id);
	});
	
	var delete_example=$("#delete_example").attr("href");
	$("#delete_example").attr("href",delete_example+example_id);
	$("#examples").change(function () {
		example_id=$("#examples").val();
		$("#delete_example").attr("href",delete_example+example_id);
	});
});