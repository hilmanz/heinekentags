function dragposition() {
  $(function() {
	$( "#sortable" ).sortable({
	   revert: true,
	    update: function(event, ui) {
            var newOrder = $(this).sortable('toArray');
          	$.each(newOrder,function(k,v){
          		$('.hidden_order').find('input:eq('+k+')').val(v);
          	});
        }
	});
	$( "ul, li" ).disableSelection();
  });
}
function checkboxCamera() {
	$('.pluginCheck').change(function(){
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
	})
}


$(document).ready(function() { 
	checkboxCamera();
	chekboxCameraFb();
	chekboxCameraTwitter();
	dragposition();
	chekboxVideo();
	$( ".datepicker" ).datepicker({dateFormat:"dd-mm-yy"});
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
			var tlmc = $(el).data('tlmc');
			$('.'+tlmc).css('color','#'+hex);

			var fctc = $(el).data('fctc');
			$('.'+fctc).css('color','#'+hex);
			
			var fcb = $(el).data('fcb');
			$('.'+fcb).css('background-color','#'+hex);

			var fcbd = $(el).data('fcbd');
			$('.'+fcbd).css('background-color','#'+hex);

			var tlmbc = $(el).data('tlmbc');
			$('.'+tlmbc).css('color','#'+hex);

			var tlc = $(el).data('tlc');
			$('.'+tlc).css('color','#'+hex);

			var tmrc = $(el).data('tmrc');
			$('.'+tmrc).css('color','#'+hex);

			var ttcv = $(el).data('ttcv');
			$('.'+ttcv).css('color','#'+hex);
			// var fc = $(el).data('fc');
			// $('.'+fc).css('color','#'+hex);
			
			// var btnc = $(el).data('btnc');
			// $('.'+btnc).css('color','#'+hex);
			
			// var btncd = $(el).data('btncd');
			// $('.'+btncd).css('color','#'+hex);

			// var fbg = $(el).data('fbg');
			// $('.'+fbg).css('background-color','#'+hex);

			// var btnbg = $(el).data('btnbg');
			// $('.'+btnbg).css('background-color','#'+hex);

			// var btnbgd = $(el).data('btnbgd');
			// $('.'+btnbgd).css('background-color','#'+hex);
			// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
			if(!bySetColor) $(el).val(hex);
		}
	}).keyup(function(){
		$(this).colpickSetColor(this.value);
	});

});


$(function (){
	$("#wizard").steps({
		headerTag: "h2",
		bodyTag: "section",
		transitionEffect: "slideLeft",
   		onFinishing: function (event, currentIndex) { 
    		$('#forms').submit();
    		//$.post('server.php', $('#theform').serialize())
		},	
		onStepChanging: function (event, currentIndex, newIndex) {
			if(newIndex==1){
				var event_name = $('input[name="event_name"]').val();
			
				if(event_name==""){
					$('.eventerror').text('* Please enter your event name to continue'); 
					return false ;
					
					
				}
			}
			if(newIndex==2){
				var reg = $('#wizard :input[name="reg[]"]:checked');// console.log(reg.length);
				if(reg.length==0){
					$('.regerror').text('You have to choose the required user fields in order to create a registration form.'); 
					return false ;
				}
				
				$('.pluginCheck').click(function(){
					var div = $(this);
					var iddiv = $(this).val();
					var idx = $(this).data('idx');
					if($(this).parent().find('input').is(':checked')) {			  
					   $('.hidden_order').find('input:eq('+idx+')').val(iddiv);
					   $('#'+iddiv).show();
					   div.closest('.row').find('.plugin-child').show();
					}else{
						$('.hidden_order').find('input:eq('+idx+')').val('');
						$('#'+iddiv).hide();
						div.closest('.row').find('.plugin-child').hide();
					}
				});
			}
			if(newIndex==3){
				
				dragposition();
			}	
			
			return true; 
		},
	});
});


function format_angka(str,short){
	var n = ((isNaN(str))?0:parseFloat(str));
	var s = "";
	if(typeof short == 'undefined') short=0;
	if(n>=1000000&&short==1){
		s = Math.floor(n/1000000)+"M";
	}else if(n>=10000&&short==1){
		s = Math.floor(n/1000)+"K";
	}else if(n>=1000){
		s = number_format(n);
	}else{
		s = n;
	}
	return s;
}

function confirmation(param,msg) {
	var answer = confirm(msg);
	if (answer){ 
		window.location = param;
	}
}
