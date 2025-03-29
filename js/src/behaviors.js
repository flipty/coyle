Zepto(function($){
	// Functions
	function calculateModalPlacement(calculateWidth){
		// Divide width
		calculateWidth = calculateWidth / 2;

		// Find the current window width
		return ((parseInt($("body").width()) / 2) - calculateWidth) / 16;
	}

	function closeModal(){
		// Clear All Fields
		clearFields();

		// Hide Modal
		$(".modal").css("display", "none");

		// Hide Page Fade
		$(".pageFade").css("display", "none");

		// Change body visibility
		$("body").css({
			overflow: "visible"
		});
	}

	function clearModal(){
		// Clear All Fields
		clearFields();
	}

	function clearFields(){
		$("#name, #address, #phone, #alternatePhone, #email, #problem, #preferredTime").val("");
	}

	$(function(){
		// Modal placement in response to window resize
		$(window).resize(function(){
			$(".modal, .loader, .statusModal").each(function(){
				$(this).css({
					left: calculateModalPlacement(parseInt($(this).width())) + "rem"
				});
			});
		});

		// Modal open
		$("#schedule").click(function(){
			// Scroll to the top of the page
			window.scroll(0, 0);

			// Turn on the page fader
			$(".pageFade").show();

			// Show the modal
			$(".modal").show();

			// Change body visibility
			$("body").css({
				overflow: "hidden"
			});

			// Position Modal
			$(".modal, .loader, .statusModal").each(function(){
				$(this).css({
					left: calculateModalPlacement(parseInt($(this).width())) + "rem"
				});
			});
		});

		// Modal close via click
		$(".closeModal, .pageFade").click(function(){
			// Close the modal
			closeModal();
		});

		// Modal close via pressing escape
		$(window).keyup(function(e){
			// Check if the modal is open and the escape key was pressed
			if(e.which === 27 && $(".modal").css("display") === "block"){
				// Close the modal
				closeModal();
			}
		});

		// Appointment submit behavior
		$(".submitAppointment a").click(function(){
			$.ajax({
				url: "submit_appointment.php",
				type: "POST",
				data: {
					name: $("#name").val(),
					address: $("#address").val(),
					phone: $("#phone").val(),
					alternatePhone: $("#alternatePhone").val(),
					email: $("#email").val(),
					problem: $("#problem").val(),
					preferredTime: $("#preferredTime").val()
				},
				dataType: "json",
				beforeSend: function(xhr, settings){
					// Fade out the modal
					$(".modal").hide();

					// Fade in the loader
					$(".loader").show();
				},
				success: function(data, textStatus, xhr){
					// Fade in the loader
					$(".loader").hide();

					// Populate the status
					$("#status").text(data.message);

					// Fade in the status
					$(".statusModal").css({
						"display": "flex"
					});

					// Change the rel attribute of the status button
					if(data.status === true){
						$("#okayButton").attr("rel", "success");
						$("#headerStatus").text("Thank You!");
						clearModal();
					}
					else{
						$("#okayButton").attr("rel", "failure");
						$("#headerStatus").text("Error");
					}
				}
			});
		});

		$("#okayButton").click(function(){
			if($(this).attr("rel") === "failure"){
				// Fade out the modal
				$(".statusModal").css({
					"display": "none"
				});

				// Fade in the loader
				$(".modal").show();
			}
			else{
				// Fade out the status modal and page fade
				$(".statusModal, .pageFade").css({
					"display": "none"
				});
			}
		});
	});

	window.onorientationchange = function(){
		$(".modal, .loader, .statusModal").each(function(){
			$(this).css({
				left: calculateModalPlacement(parseInt($(this).width())) + "rem"
			});
		});
	};
});