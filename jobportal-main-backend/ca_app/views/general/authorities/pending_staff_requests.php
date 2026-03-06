<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css"> 
	.formwraper p{
		font-size:13px;
	}

	#modal-filter-request .modal-content {
		max-width: 370px;
		margin:0 auto;
	}

	.label-check i,
	.label-radio i {
		color: #333;
		vertical-align:text-bottom;
		font-size: 20px;
	}

	.panel-filter {
		padding: 10px 0px;
	}
	.panel-filter label {
		display: block;
		font-size: 16px;
		font-weight: normal;
	}

	.panel-filter .filter-title {
		padding: 6px 0px;
		border-bottom: 2px solid #1ba6df;
		margin-bottom: 6px;
	}

	.panel-filter .filter-title h4 {
		font-weight: bold;
	}

	.text-info {
		color:#555;
		font-style: italic;
		display: block;
		font-size: 12px;
		margin-top: 2px;
	}

	.table thead th {
        text-align: center;
    }

    .table tbody td {
        text-align: center;
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
		<?php 
			if ($this->session->userdata('current_profile_id') == 1) {
				$this->load->view('employer/common/menu/sidebar');
			} else {
				$this->load->view('employer/common/menu/sidebar');
			}
		?>
	</div>
	</div>
		<div class="col-md-9"> 
			<?php echo $this->session->flashdata('msg');?>
			<div class="formwraper">
				<div class="titlehead">
					<div class="row">
						<div class="col-md-12">
							<b>Solicitudes pendientes por autorizar</b>
						</div>
					</div>
				</div>
				
				<!-- Description -->
				<div class="table-search">
		            <?php echo form_open('general/authorities/pending_staff_requests/search', array('method' => 'get')); ?> 
		              <table width="100%">
		                <tr>
		                  <td width="90%">
		                    <input type="text" name="query" class="form-control" value="<?php echo html_escape($filters['query']); ?>" placeholder="Buscar solicitudes pendientes por código o requerimiento ">      
		                  </td>
		                  <td width="10%">
		                    <button type="submit" class="btn btn-block btn-search">
		                      <i class="glyphicon glyphicon-search"></i>
		                    </button>     
		                  </td>
		                  <td align="right">
		                  </td>
		                </tr>
		              </table>
		            <?php echo form_close(); ?> 
		        </div>
		        <div class="table-responsive">
					<table width="100%" class="table table-striped">
						<thead>
							<tr>
								<th>
									Código
								</th>
								<th>
									Requerimiento
								</th>
								<th>
									Solicitante
								</th>
								<th>Solicitud</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($result_requests as $index => $row_request): ?>
								<tr>
									<td>
										<?php echo $row_request->ID; ?>
									</td>
									<td style="text-align: left;">
										<?php echo $row_request->job_title; ?>
										<span class="text-info">
											Creada el: <?php echo _date_locale_format(strtotime($row_request->creation_date), 'dd MMM y'); ?>
										</span>
									</td>
									<td>
										<?php echo $row_request->recruiter_first_name; ?>
									</td>
									<td> 
										<?php 
											$authorizations = $this->Staff_request_authoritation->get_authorizations_unanswered($row_request->ID, $row_request->personal_email);
										?>
										<?php foreach($authorizations as $key => $row): ?>
											<br />
											<a href="<?php echo site_url('general/authorities/staff_requests/show?t=' . $row->token); ?>" target="_blank">
												<?php echo ($key + 1) . ') '; ?>Responder petición
											</a>
										<?php endforeach; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<?php if (count($result_requests) == 0): ?>
					<div align="center" class="text-red" style="padding: 20px;">
						<h4>Ninguna solicitud encontrada</h4>
					</div>				
				<?php endif; ?>
				
			</div>
			<!--Pagination-->
			<div class="paginationWrap pag-wrap-v2"> <?php echo ($result_requests) ? $links : '';?> </div>
		</div>
	</div>
</div>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$( ".filter-radio" ).radio();
	});
</script>
</body>
</html>