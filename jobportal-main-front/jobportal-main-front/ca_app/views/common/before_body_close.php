
<div id="modal-file-send" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<div class="ref-content"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	window.app = {
		siteUrl: function(url) {
			url = url || "";
			return "<?php echo site_url('" + url +"'); ?>";
		}
	};
</script>

<script src="<?php echo base_url('public/js/lib/intl-tel-input/i18n_es.js'); ?>"></script>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="<?php echo base_url('public/js/jquery-1.11.0.js');?>"></script> 
<script src="<?php echo base_url('public/js/jquery.cookie.js');?>"></script> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php echo base_url('public/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('public/js/bootbox.min.js');?>"></script>
<script src="<?php echo base_url('public/js/toastr/toastr.min.js');?>"></script>
<script src="<?php echo base_url('public/js/select2/select2.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/functions.js?t=1640097925');?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/validation.js?t=1640097922');?>" type="text/javascript"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>

<script type="text/javascript">
$(function() {
	var menu = $( "#menu-user-session" ).html();
	$( "#modal-menu-mobile .modal-body" ).html(menu);

	$(window).resize(function(){

		width = $(document).width();
		if (width > 991) {
			$( "#modal-menu-mobile" ).modal('hide');
		}

		$( ".navbar-header-mobile" ).css({'float' : 'none'});

		$( ".navbar-toggle-mobile" ).hide();
		$( ".dashiconwrp" ).show();

		if (width < 992) {
			$( ".navbar-toggle-mobile" ).show();
			$( ".navbar-toggle" ).prop('disabled', false);
			
			$( ".dashiconwrp" ).hide();
		}	

		$( ".navbar-brand-mobile" ).show();

		if (width < 768) {
			$( ".navbar-brand-mobile" ).hide();
		}
	});

	$( ".navbar-toggle-mobile" ).click(function() {
		$( "#modal-menu-mobile" ).modal('show');
	});

	$(window).resize();
});
</script>

<script>
$(function(){
	$( '.js-file-send-email' ).click(function(e){

		e.preventDefault();

		var description = $(this).data('description');
		var url = $(this).data('url');
		var seeker_id = $(this).data('seeker-id');

		$( '#modal-file-send .ref-content' ).html(`
			Para poder visualizar el documento se le enviara a su correo un enlace de descarga del archivo.
			<br>
			<br>
			<button type="button" 
					data-description="${description}"
					data-url="${url}"
					data-seeker-id="${seeker_id}"
					class="btn btn-sm btn-primary js-btn-doc-send-email">
					Enviar
			</button> 
			<button type="button"
					class="btn btn-sm btn-default" 
					data-dismiss="modal">
					Cancelar
			</button>
		`);

		$( '#modal-file-send' ).modal('show');

		return false;
	});

	$(document).on('click', '.js-btn-doc-send-email', function(e){

		e.preventDefault();

		$( '#modal-file-send .ref-content' ).html(`
			<p>Enviando...</p>
		`);
		
		var url = "<?php echo site_url('embed/files/send_to_email'); ?>";
		var data = {
			"<?php echo $this->config->item('embed_token_name')?>": "<?php echo $this->config->item('embed_token_value'); ?>",
			"description": $(this).data('description'),
			"url": $(this).data('url'),
			'seeker_id' : $(this).data('seeker-id')
		};

		$.post(url, data, function(data) {
			
			if (!data.success) {
			$( '#modal-file-send .ref-content' ).html(`
				<i class="fa fa-exclamation-circle">&nbsp;</i>
				<p>${data.message}</p>
				<br>
				<br>
				<button class="btn btn-sm btn-primary" data-dismiss="modal">
				OK
				</button>
			`);
			return;
			}

			$( '#modal-file-send .ref-content' ).html(`
			<i class="fa fa-check">&nbsp;</i>
			<p>${data.message}</p>
			<br>
				<br>
				<button class="btn btn-sm btn-primary" data-dismiss="modal">
				OK
				</button>
				
			`);
		}, 'json')
		.fail(function() {

			$( '#modal-file-send .ref-content' ).html(`
				<i class="fa fa-exclamation-circle">&nbsp;</i>
				<p>Ha ocurrido un error al enviar el documento</p>
				<br>
				<br>
				<button class="btn btn-sm btn-primary" data-dismiss="modal">
				OK
				</button>
			`);
		
		}).always(function() {}); 
	});
});
</script>

<script>
$(function(){
	$('.accordion-notifications').on('show.bs.collapse', function () {  
		
		$( '.glyphicon-chevron-right', $(this).find('.panel-heading a'))
			.removeClass('glyphicon-chevron-right')
			.addClass('glyphicon-chevron-down');
	});

	$('.accordion-notifications').on('hide.bs.collapse', function () {
		$( '.glyphicon-chevron-down', $(this).find('.panel-heading a'))
			.removeClass('glyphicon-chevron-down')
			.addClass('glyphicon-chevron-right'); 
	});
	
});
</script>