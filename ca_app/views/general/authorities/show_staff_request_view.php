<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/css/app/styles/staff_request/staff_request_detail.css?t=1595698816'); ?>">

<style type="text/css"> 
  
  .btn-status {
    font-size:13px;
    background: #fff;
    padding: 3px 7px;
    color: #0c408f;
    border:1px solid #0c408f;
    text-align: center;
    border-radius: 10px;
  }

  .msj-info {
  	position: relative;
	background: #fff;
	font-size: 16px;
	margin-bottom: 20px;
	padding: 15px 10px;
	border: 1px solid #0d7daa;
	border-left: 4px solid #0d7daa;
  }

  .header-authorization {
  	margin-bottom: 30px;
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
	</div>
		<div class="col-md-9"> 
			<?php echo $this->session->flashdata('msg');?>
			<div class="formwraper">
				<div class="titlehead">
					<div class="row">
						<div class="col-md-12"><b>Autorizar solicitud interna</b>					
						</div>
					</div>
				</div>
				<div style="padding: 10px;">
					<div class="header-authorization">
						<?php if ($authorization->is_authorized == null): ?>
							<div class="msj-info">
								Hola <b><?php echo $authorization->personal_name; ?></b>, se creó una solicitud interna y se necesita tu autorización para habilitarla.
								<div class="clearfix">
									<div class="pull-right" style="padding-top: 10px;">
										<?php echo form_open('', array('id' => 'form-request-answer')); ?>
											<input type="hidden" name="t" value="<?php echo $authorization->token; ?>">
											<button id="request-authorize" type="submit" class="btn btn-primary">Autorizar</button>
											<button id="request-deny" type="submit" name="deny" class="btn btn-primary">Denegar</button>
										<?php echo form_close(); ?>
									</div>
								</div>
							</div>
						<?php  endif; ?>

						<?php if ($authorization->is_authorized != null): ?>
							<div class="msj-info">
								Hola <b><?php echo $authorization->personal_name; ?></b>, esta solicitud ya fue respondida.
							</div>
						<?php endif; ?>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php $this->load->view('general/staff_request/common/detail_internal_staff_request'); ?>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('employer/common/employers_popup_forms'); ?>
<?php $this->load->view('common/before_body_close'); ?>

<script type="text/javascript">
	
	$(document).ready(function(){

		$( "#request-authorize" ).click(function(){
			var confirm = window.confirm("¿Está seguro de 'Autorizar la solicitud?'");
			var url = "<?php echo site_url('general/authorities/staff_requests/authorize'); ?>";

			if (confirm) {
				$( "#form-request-answer" ).prop('action', url);
				this.submit();
				return true;
			}

			return false;
		});

		$( "#request-deny" ).click(function(){

			var confirm = window.confirm("¿Está seguro de 'Denegar la solicitud?'");
			var url = "<?php echo site_url('general/authorities/staff_requests/deny'); ?>";

			if (confirm) {
				$( "#form-request-answer" ).prop('action', url);
				this.submit();
				return true;
			}

			return false;
		});

		$( ".btn-resend-email" ).remove();
	});
</script>
</body>
</html>