//Show and hide order details

$(".summary-arrow").click(function() {
	var thisIdObj = $(this).parents().parents().attr("id");
	var thisArray = thisIdObj.split("summary");
	var thisId = thisArray[1];
	
	if ($("#detailed"+thisId).is(":visible")) {
		$("#detailed"+thisId).fadeOut("fast");
		$(this).removeClass("icon-arrow-up")
			.addClass("icon-arrow-down");
	} else {
		$("#detailed"+thisId).fadeIn("fast");
		$(this).removeClass("icon-arrow-down")
			.addClass("icon-arrow-up");
	}
});

//Launch modals and enter IDs upon button click

$(".order-accept").click(function() {
	var thisIdObj = $(this).parents().parents().attr("id");
	var thisArray = thisIdObj.split("summary");
	var thisId = thisArray[1];
	
	document.getElementById("accept-hidden").value = thisId;
	
	$("#modal-accept").modal();
});

$(".order-deny").click(function() {
	var thisIdObj = $(this).parents().parents().attr("id");
	var thisArray = thisIdObj.split("summary");
	var thisId = thisArray[1];
	
	document.getElementById("deny-hidden").value = thisId;
	
	$("#modal-deny").modal();
});

$(".order-shipping-add").click(function() {
	var thisIdObj = $(this).parents().parents().parents().parents().attr("id");
	var thisArray = thisIdObj.split("summary");
	var thisId = thisArray[1];
	
	document.getElementById("shipping-hidden").value = thisId;
	
	$("#modal-shipping").modal();
});

//Process AJAX and reload

$("#accept-order-button").click(function() {
	var orderId = document.getElementById("accept-hidden").value;
	
	$.post("php/admin-actions.php", {
		action: "accept",
		orderNumber: orderId	
	}, function() {
		$("#modal-accept").modal('hide');
		location.reload();
	});
});

$("#deny-order-button").click(function() {
	var orderId = document.getElementById("deny-hidden").value;
	
	$.post("php/admin-actions.php", {
		action: "deny",
		orderNumber: orderId	
	}, function() {
		$("#modal-accept").modal('hide');
		location.reload();
	});
});

$("#change-prices-button").click(function() {
	var changeAmount = $("#change-prices").val();
	var changeDir = $("#change-prices-select").val();
	
	$.post("php/admin-settings.php", {
		method: "write",
		action: "change-price",
		changeNum: changeAmount,
		changeDirection: changeDir 
	}, function(data) {
		ajaxData = $.trim(data);
		if (ajaxData == "ok") {
			$("#modal-price-change-confirm").modal("show");
		} else {
			alert("There was an error processing the request");
		}
	});
});