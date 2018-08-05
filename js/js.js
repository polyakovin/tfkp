$(document).ready(function(){
	$(".success").hide();
	$(".ac").hide();
	$(".adc").hide();
	$("#rules").hide();
	$("#tasks_list").hide();
	$("#participants").hide();
	$("#the_ones").hide();
	$("#exam_tasks").hide();
	
	$("#rules_button").click(function () {
		$("#rules").toggle();
	});
	$("#tasks_list_button").click(function () {
		$("#tasks_list").toggle();
	});
	$("#participants_button").click(function () {
		$("#participants").toggle();
	});
	$("#the_ones_button").click(function () {
		$("#the_ones").toggle();
	});
	$("#exam_tasks_button").click(function () {
		$("#exam_tasks").toggle();
	});
	
	$("#additional_conditions").click(function () {
		var ac1 = $(".ac1");
		if(ac1.attr('rowspan') == 2)
			ac1.attr({rowspan:"1"});
		else
			ac1.attr({rowspan:"2"});
	
		var ac2 = $(".ac2");
		if(ac2.attr('colspan') == 3)
			ac2.attr({colspan:"1"});
		else
			ac2.attr({colspan:"3"});
		
		$(".ac").toggle();
		$(".ac0").toggle();
	});
	
	$("#add_conds").click(function () {
		var ac1 = $(".adc1");
		if(ac1.attr('rowspan') == 2)
			ac1.attr({rowspan:"1"});
		else
			ac1.attr({rowspan:"2"});
	
		var ac2 = $(".adc2");
		if(ac2.attr('colspan') == 3)
			ac2.attr({colspan:"1"});
		else
			ac2.attr({colspan:"3"});
		
		$(".adc").toggle();
		$(".adc0").toggle();
	});
});