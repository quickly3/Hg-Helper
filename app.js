$(function(){
	var checked_branches = [];

	if(localStorage.checked_branches){
		checked_branches = localStorage.checked_branches.split(",");
		for(i in checked_branches){
			$(".branches").map(function(index,d){
				if($(d).val() == checked_branches[i]){
					$(d).prop({
						"checked":"true"
					});
				}
			}); 
		}
	}

	var handleCheck = function(handleObj){
		var $_this = $(handleObj);
		var handle = $_this.val();
		var checked = $_this.prop("checked");
		var sub_zone = $("."+handle+"_zone");

		if(checked){
			sub_zone.slideDown();
			sub_zone.find("input").removeAttr("disabled");
		}else{
			sub_zone.slideUp();
			sub_zone.find("input").attr({"disabled":"true"});
		}
	}

	var modelCheck = function(handleObj){
		var $_this = $(handleObj);
		var handle = $_this.val();
		var checked = $_this.prop("checked");
		var sub_zone = $("."+handle+"_model");
		$(".model").hide();
		$(".model").find("input").attr({"disabled":"true"});
		if(checked){
			sub_zone.show();
			sub_zone.find("input").removeAttr("disabled");
		}
	}


	$("body").on("change",".branches",function(){
		checked_branches = [];
		$(".branches:checked").map(function(){
			checked_branches.push($(this).val());
		});

		// console.log(checked_branches.toString());
		localStorage.checked_branches = checked_branches;
	});


	$("#refresh").click(function(){
		location.href = location.href;
	});

	$("input.extraction").map(function(){
		handleCheck(this);
	});

	$("input.extraction").change(function(){
		handleCheck(this);
	});

	$("input[name=version_model]").map(function(){
		modelCheck(this);
	});

	$("input[name=version_model]").change(function(){
		modelCheck(this);
	});


});