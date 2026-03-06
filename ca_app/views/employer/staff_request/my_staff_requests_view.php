<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css"> 
	.formwraper p {
		font-size:13px;
	}

	.text-info {
		color:#555;
		font-style: italic;
		display: block;
		font-size: 12px;
		margin-top: 2px;
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
			<?php echo $this->session->flashdata('msg');?>
			<div class="formwraper">
				<div class="titlehead">
					<div class="row">
						<div class="col-md-12">
							<b>Mis solicitudes creadas</b>
						</div>
					</div>
				</div>
				
				<!-- Description -->
				<div>
					<div class="table-search">
						<?php echo form_open('employer/staff_request/my_staff_requests/search', array('method' => 'get')); ?>	
							<table width="100%">
								<tr>
									<td width="90%">
										<input type="text" name="query" class="form-control" value="<?php echo $filters['query']; ?>" placeholder="Buscar por código y nombre de la solicitud">			
									</td>
									<td width="10%">
										<button type="submit" class="btn btn-block btn-search">
											<i class="glyphicon glyphicon-search"></i>
										</button>			
									</td>
									<td align="right">
										<div class="dropdown dropdown-options-job">
											<button class="btn btn-sm dropdown-toggle" style="text-decoration: underline;border:1px solid #ccc;background: #fff; font-weight: bold;" type="button" data-toggle="dropdown">
												<i class="glyphicon glyphicon-option-vertical"></i>
											</button>
											<ul class="dropdown-menu dropdown-menu-right">
												<li>
													<a href="#" data-toggle="modal" data-target="#modal-filter-request">
														Filtrar
													</a>
												</li>
												<li>
													<a href="<?php echo site_url('employer/staff_request/my_staff_requests/export_excel?' . $_SERVER['QUERY_STRING']); ?>">
														Exportar a excel
													</a>
												</li>
											</ul>
										</div>
									</td>
								</tr>
							</table>
						<?php echo form_close(); ?>
					</div>
					<div class="table-responsive">
						<table width="100%" class="table table-striped">
							<thead>
								<tr>
									<th></th>
									<th>
										Código
									</th>
									<th>
										Solicitud
									</th>
									<th>
										Tipo
									</th>
									<th>
										Estado
									</th>
									<th>
										R&S
									</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($result_requests as $row_request): ?>

									<tr>
										<th style="text-align: left;">
											<?php if ($row_request->request_model_id == 1 || $row_request->request_model_id == 2): ?>
												<div class="dropdown dropdown-options-job">
													<button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" style="background: transparent;">
														<span class="glyphicon glyphicon-option-vertical"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-left">
														<?php if ($row_request->request_model_id == 2 && $row_request->sts_process == 'unassigned'): ?>
															<li>
																<a href="<?php echo site_url('employer/staff_request/edit_external_staff_request/index/' . $row_request->ID); ?>">Editar</a>
															</li>
														<?php endif; ?>
														<?php if ($row_request->request_model_id == 1): ?>
															<li>
																<a href="<?php echo site_url('employer/staff_request/create_internal_staff_request?clone=' . $row_request->ID); ?>">Clonar</a>
															</li>
														<?php endif; ?>
														<?php if ($row_request->request_model_id == 2): ?>
															<li>
																<a href="<?php echo site_url('employer/staff_request/create_external_staff_request?clone=' . $row_request->ID); ?>">Clonar</a>
															</li>
														<?php endif; ?>
													</ul>
												</div>
											<?php endif; ?> 
										</th>
										<td>
											<?php echo $row_request->ID; ?>
										</td>
										<td style="text-align: left;">
											<a href="<?php echo site_url('employer/staff_request/staff_requests/show/' . $row_request->ID); ?>"><?php echo $row_request->job_title; ?></a>
											<span class="text-info">
												Creada el: <?php echo _date_locale_format(strtotime($row_request->creation_date), 'dd MMM y'); ?>
											</span>
										</td>
										<td>
											<?php
												echo request_type_text($row_request->request_type);
											?>
											<span class="text-info">
												Modelo: <?php echo $row_request->request_model_id; ?>
											</span>
										</td>
										<td>
											<?php echo status_process_request_text($row_request->sts_process); ?>	
										</td>
										<td style="text-transform: uppercase;">
											<?php if ($row_request->process_id != null): ?>
												<a href="#" class="js-show-rs-process" data-process-id="<?php echo $row_request->process_id; ?>">
													<?php echo rs_stage_status_process($row_request->rs_process_status); ?>
												</a>
											<?php else: ?>
												<?php 
													echo rs_stage_status_process($row_request->rs_process_status);
												?>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				
					<?php if (count($result_requests) == 0): ?>
						<div align="center" class="text-red" style="padding: 20px;">
							<h4>Sin resultados</h4>
						</div>				
					<?php endif; ?>
				</div>
			</div>
			<!--Pagination-->
			<div class="paginationWrap pag-wrap-v2"> <?php echo ($result_requests) ? $links : '';?> </div>
		</div>
	</div>
</div>
<!-- Modal -->
<!-- Modal -->
<div id="modal-filter-request" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open('employer/staff_request/my_staff_requests/search', array('method' => 'get')); ?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mostrar Solicitudes de personal</h4>
      </div>
      <div class="modal-body">
        <div class="panel-filter"> 
          <div class="filter-title">
          	<h4>Por Estado RQ</h4>
          </div>
          <select name="status_rq" class="form-control">
          	<option value="all" <?php echo $filters['status_rq'] == 'all' ? 'selected="selected"' : ''; ?>>
          		Todas
          	</option>
          	<option value="unassigned" <?php echo $filters['status_rq'] == 'unassigned' ? 'selected="selected"' : ''; ?>>
          		Sin asignar
          	</option>
          	<option value="assigned" <?php echo $filters['status_rq'] == 'assigned' ? 'selected="selected"' : ''; ?>>
          		Asignadas
          	</option>
          	<option value="published" <?php echo $filters['status_rq'] == 'published' ? 'selected="selected"' : ''; ?>>
          		Publicada
          	</option>
          	<option value="pending" <?php echo $filters['status_rq'] == 'pending' ? 'selected="selected"' : ''; ?>>
          		Pendiente
          	</option>
          	<option value="rejected" <?php echo $filters['status_rq'] == 'rejected' ? 'selected="selected"' : ''; ?>>
          		Rechazada
          	</option>
          	<option value="canceled" <?php echo $filters['status_rq'] == 'canceled' ? 'selected="selected"' : ''; ?>>
          		Cancelada
          	</option>
          </select>
        </div>
        <div class="panel-filter"> 
          <div class="filter-title">
          	<h4>Por Estado R&S</h4>
          </div>
          <select name="status_rs" class="form-control">
          	<option value="all" <?php echo $filters['status_rs'] == 'all' ? 'selected="selected"' : ''; ?>>
          		Todas
          	</option>
          	<option value="0" <?php echo $filters['status_rs'] == '0' ? 'selected="selected"' : ''; ?>>
          		FILTRO CURRICULAR
          	</option>
          	<option value="1" <?php echo $filters['status_rs'] == '1' ? 'selected="selected"' : ''; ?>>
          		FILTRO TELEFÓNICO
          	</option>
          	<option value="2" <?php echo $filters['status_rs'] == '2' ? 'selected="selected"' : ''; ?>>
				LONG LIST          		
          	</option>
          	<option value="3" <?php echo $filters['status_rs'] == '3' ? 'selected="selected"' : ''; ?>>
          		ENTREVISTA
          	</option>
          	<option value="4" <?php echo $filters['status_rs'] == '4' ? 'selected="selected"' : ''; ?>>
          		EVALUACIÓN
          	</option>
          	<option value="5" <?php echo $filters['status_rs'] == '5' ? 'selected="selected"' : ''; ?>>
          		TERNA O SHORT LIST
          	</option>
          	<option value="6" <?php echo $filters['status_rs'] == '6' ? 'selected="selected"' : ''; ?>>
          		SELECCIÓN DE PERSONAL
          	</option>
          	<option value="7" <?php echo $filters['status_rs'] == '7' ? 'selected="selected"' : ''; ?>>
          		PROCESO DE CONTRATACIÓN
          	</option>
          </select>
        </div>
        <div class="panel-filter">
          <div class="filter-title"><h4>Por Tipo</h4></div>
          <label>
            <input class="filter-radio" type="radio" name="type" value="all" <?php echo $filters['type'] == 'all' ? 'checked="checked"' : ''; ?>>Todas
          </label>
          <label>
            <input class="filter-radio" type="radio" name="type" value="internal" <?php echo $filters['type'] == 'internal' ? 'checked="checked"' : ''; ?>>Internas
          </label>
          <label>
            <input class="filter-radio" type="radio" name="type" value="external" <?php echo $filters['type'] == 'external' ? 'checked="checked"' : ''; ?>>Externas
          </label>
        </div>

        <div class="panel-filter">
        	<div class="filter-title">
        		<h4>Asignada a</h4>
        	</div>
        	<select name="employer" class="form-control">
				<option value="all">Todos</option>
				<?php foreach ($result_employers as $employer): ?>
					<option value="<?php echo $employer->ID; ?>" <?php echo $employer->ID == $filters['employer'] ? 'selected="selected"' : ''; ?>>
						<?php echo $employer->first_name . ' ' . $employer->last_name; ?>
					</option>	
				<?php endforeach ?>
        	</select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" >Filtrar</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<div id="modal-show-rs-process" class="modal fade" role="dialog"></div>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('employer/common/employers_popup_forms'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script type="text/javascript">
$(function(){
	function init() {
		$( ".filter-radio" ).radio();

		$( ".js-show-rs-process" ).click(function(){
			var processId = $(this).data('process-id');
			var url = "<?php echo site_url('employer/recruitment_candidates/show_rs_process/'); ?>" + processId;
			
			$( "#modal-show-rs-process" ).load(url, {}, function(html){
				$( "#modal-show-rs-process" ).html(html).modal('show');
			});
		})
	}

	init();
});
</script>
</body>
</html>