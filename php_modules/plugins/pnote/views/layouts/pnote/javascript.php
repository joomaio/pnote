<script>
	$(document).ready(function(){
		$('.button-shortcut').on('click', function(){
			var link = $(this).data('link');
			$('#form_shortcut').attr('action', link);
		});
	})
</script>