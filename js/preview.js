// JavaScript Document

var tooltip = new Array();
var cur_tooltip = 0;

$(function() {
	
	tooltip_show();
	
	$('#tooltip_next').live('click',function() {
		
		//turn back on functionality of killed input buttons
		if(tooltip[cur_tooltip-1][0] == '#input_submitter'){
			$(tooltip[cur_tooltip-1][0]).click(function() {
				return validate();
			})
		}
		
		/* to incorporate interactive tooltips */
		if(tooltip[cur_tooltip].length > 2){
			
			$(tooltip[cur_tooltip][2]).one('click',function() {
					
					tooltip_show();

			})
		}
		tooltip_show();
	});
	
	
	//hide tooltip and darken onclick of close button
	$('#tooltip_close').live('click',function() {
		
		//turn back on functionality of killed input buttons
		if(tooltip[cur_tooltip-1][0] == '#input_submitter'){
			$(tooltip[cur_tooltip-1][0]).click(function() {
				return validate();
			})
		}
		
		$('.tooltip_active').removeClass('tooltip_active');
		
		$('.tooltip,#tooltip').hide();
		//return tooltip to normal size
		$('#tooltip').removeClass('tooltip_large');
	})
	
	//if there are tooltips, show darken background
	if(tooltip.length > 0)
		$('#tooltip_darken').show();
})

function tooltip_show(){

	$('#tooltip').addClass('tooltip_large');
	var num = cur_tooltip;
	var top = parseInt($(tooltip[num][0]).first().offset().top,10) + parseInt($(tooltip[num][0]).height(),10);
	var left = parseInt($(tooltip[num][0]).first().offset().left,10) + parseInt($(tooltip[num][0]).width(),10);

	//if tooltip is off the screen, move it to the right of the element
	if(left > 900)
		left = left - (parseInt($(tooltip[num][0]).width(),10) + parseInt($('#tooltip').width(),10));
	
	$('.tooltip_active').removeClass('tooltip_active');
	$(tooltip[num][0]).addClass('tooltip_active');
	
	//if the active tooltip object is an input, do not let it be used
	if(tooltip[num][0] == '#input_submitter'){
		
		$(tooltip[num][0]).unbind('click').click(function() {
					
				return false;
		})
	}
	
	var text = tooltip[num][1];
	
	//if no interaction required, show next/close button
	if(tooltip[num].length < 3){
		//if last tooltip, show close button
		if(num == tooltip.length - 1)
			text += '</br><span id="tooltip_close">Close</span>';
		else
			text += '</br><span id="tooltip_next">Next</span>';
	}
	
	$('#tooltip').html(text)
					.css({'top': top + 'px',
							'left': left + 'px'})
					.show();
					
	cur_tooltip++;
}