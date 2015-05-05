<!-- BEGIN: main -->
<div id="newsletters">
	<p>{LANG.newsletterNote}.</p>
	<form class="form-inline" method="post">
		<input type="email" placeholder="{LANG.newsletterEmailType}" required="required">
		<button type="submit"><i class="icon-ok">&nbsp;</i></button>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#newsletters form').submit(function(){
		var email = $('#newsletters [type="email"]').val();
		$.ajax({
			type: 'POST',
			url: nv_siteroot + 'index.php',
			data: nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '={MODULE_NAME}&' + nv_fc_variable + '=newsletters&newsletters=' + encodeURIComponent(email) + '&checksess={CHECKSESS}',
			success: function(data){
				alert(data);
				$('#newsletters [type="email"]').val('');
			}
		});
		
		return false;
	});
});
</script>
<!-- END: main -->