<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css"> 
	.formwraper p {
		font-size: 13px;
	}
	.formint {
		font-weight: normal;
	}

	.msj-info {
		position: relative;
		background: #fff;
		font-style: italic;
		font-size: 14px;
		margin-bottom: 20px;
		padding: 10px;
		border: 1px solid #0d7daa;
		border-left: 3px solid #0d7daa;
	}

	.input-group.has-error .text-info-error {
		color: #a94442;
	}
</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header-->
<div class="container detailinfo">
	<div class="row">
	<div class="col-md-3">
	<div class="dashiconwrp">
		<?php $this->load->view('employer/common/menu/sidebar');?>
	</div>
	</div>
		<div class="col-md-9"> 
			<?php echo $this->session->flashdata('msg'); ?>
			<div class="formwraper">
				<div class="titlehead">
					<div class="row">
						<div class="col-md-12"><b>
						<a class="_link-back" style="color:#fff;" href="#">
							<i class="fa fa-arrow-left" aria-hidden="true"></i>
						</a>
						Rechazar solicitud
						</div>
					</div>
				</div>
				<div class="formint">	
					<?php echo form_open_multipart('', array('id' => 'form-reject-request')); ?>
					<div style="margin-top:15px;">
						<div class="msj-info">
							Por favor ingrese un motivo para poder rechazar la solicitud.							
						</div>	
						<p><b>Solicitud:</b> <?php echo $request->job_title; ?> </p>
						<p><b>Creada el: </b> <?php echo _date_locale_format(strtotime($request->creation_date), 'dd MMMM y'); ?></p>
						<p><b>Tipo de solicitud:</b> <?php echo request_type_text($request->request_type); ?></p>
						<br />
						<div class="input-group <?php echo (form_error('reason'))?'has-error':'';?>">
							<label>Motivo del rechazo <span style="color: red;">*</span></label>
							<textarea name="reason" class="form-control" rows="6"><?php echo set_value('reason'); ?></textarea>
							<span class="text-info-error"><?php echo form_error('reason'); ?></span>
						</div>	
					</div>
					<div style="text-align: center;">
						<input type="submit" value="Rechazar solicitud" class="btn btn-primary">
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
</body>
<script type="text/javascript">
	$(document).ready(function(){
		$( "#form-reject-request" ).submit(function(e) {
			e.preventDefault();
			var confirm = window.confirm("¿Está seguro de rechazar la solicitud?");
			if (confirm) {
				this.submit();
			}
		});
	});
</script>
</html>