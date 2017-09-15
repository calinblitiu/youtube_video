jQuery(document).ready(function(){
	$('#jumpto, #order-list').sSelect();
	
	jQuery('#order-list').change( function() {
		window.location = jQuery(this).val();
	});
	
	
});

function submit_search() {
	document.frmSearch.submit();
}