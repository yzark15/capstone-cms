// make dates more readable for humans
function convert_date_to_human_readable(){
	var $this = $(this);
	var id = 'date-input-' + Math.random().toString().replace(/\./,'');
	var dparts = $this.val().split(/-/);
	
	$this.datepicker({
		dateFormat:'yy-mm-dd',
		modal:true,
		altField:'#'+id,
		altFormat: 'DD, d MM, yy',
		onSelect:function(dateText,inst){
			this.value = dateText;
		}
	});
	var $wrapper = $this.wrap('<div style="position:relative" />');
	var $input = $('<input id="'+id+'" class="date-human-readable" val="'+date_m2h($this.val())+'" />');
	$input.insertAfter($this);
	$this.css({
		'position':'absolute',
		'opacity':0
	});
	$this.datepicker('setDate', new Date(dparts[0],dparts[1]-1,dparts[2]));	
}
$(function(){
	$('input.date-human').each(convert_date_to_human_readable);
});