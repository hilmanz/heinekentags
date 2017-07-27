

function dragposition() {
  $(function() {
	$( "#sortable" ).sortable({
	   revert: true
	});
	$( "ul, li" ).disableSelection();
  });
}
function checkboxCamera() {
	$('#checkboxCamera').change(function(){
	  if (this.checked) {
		$('#camera-child').fadeIn('slow');
	  } else {
		$('#camera-child').fadeOut('slow');
	  }                   
	});
}
function chekboxCameraFb() {
	$('#chekboxCameraFb').change(function(){
	  if (this.checked) {
		$('#sharemessage-facebook').fadeIn('slow');
	  } else {
		$('#sharemessage-facebook').fadeOut('slow');
	  }                   
	});
}
function chekboxCameraTwitter() {
	$('#chekboxCameraTwitter').change(function(){
	  if (this.checked) {
		$('#sharemessage-twitter').fadeIn('slow');
	  } else {
		$('#sharemessage-twitter').fadeOut('slow');
	  }                   
	});
}
function chekboxVideo() {
	$('#chekboxVideo').change(function(){
	  if (this.checked) {
		$('#upload-video-addon').fadeIn('slow');
	  } else {
		$('#upload-video-addon').fadeOut('slow');
	  }                   
	});
}


$(document).ready(function() { 
	// popup
	$('.showPopup').magnificPopup({
	  type:'inline',
	  midClick: true 
	});
	 // Tab
	  $(function() {
		$( "#tabs" ).tabs();
	  });
	
	$('.colorpicker').colpick({
		layout:'hex',
		submit:0,
		colorScheme:'dark',
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			$(el).css('border-color','#'+hex);
			// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
			if(!bySetColor) $(el).val(hex);
		}
	}).keyup(function(){
		$(this).colpickSetColor(this.value);
	});
	checkboxCamera();
	chekboxCameraFb();
	chekboxCameraTwitter();
	dragposition();
	chekboxVideo();

});
$(function (){
	$("#wizard").steps({
		headerTag: "h2",
		bodyTag: "section",
		transitionEffect: "slideLeft",
   		onFinishing: function (event, currentIndex) { 
    		 window.location = "index.php?page=events-success";
		},	
		onStepChanging: function (event, currentIndex, newIndex) { 
			checkboxCamera();
			chekboxCameraFb();
			chekboxCameraTwitter();
			dragposition();
			chekboxVideo();
			return true; 
		},
	});
});
