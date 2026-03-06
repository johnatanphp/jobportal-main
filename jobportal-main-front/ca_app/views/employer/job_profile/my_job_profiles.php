<!DOCTYPE html>
<html lang="en">
	<head>
	<?php $this->load->view('common/meta_tags'); ?>
	<title>
		<?php echo $title;?>		
	</title>
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
						<?php $this->load->view('employer/common/menu/sidebar');?>
					</div>
				</div>
				<div class="col-md-9"> 
					<?php echo $this->session->flashdata('msg');?>
					<div class="formwraper">
						<div class="titlehead">
							<div class="row">
								<div class="col-md-12">
									<b>Perfiles de puestos creados</b>
								</div>
							</div>
						</div>
						
						<!-- Description -->
						<div class="table-search">
							<?php echo form_open('employer/job_profiles/my_job_profiles/search', array('method' => 'get')); ?>	
								<table width="100%">
									<tr>
										<td width="90%">
											<input type="text" name="query" class="form-control" value="<?php echo $filters['query']; ?>" placeholder="Buscar por código y puesto">			
										</td>
										<td width="10%">
											<button type="submit" class="btn btn-block btn-search">
												<i class="glyphicon glyphicon-search"></i>
											</button>			
										</td>
										<td>
											<div class="dropdown dropdown-options-job">
												<button class="btn btn-sm dropdown-toggle" style="text-decoration: underline;border:1px solid #ccc;background: #fff; font-weight: bold;" type="button" data-toggle="dropdown">
													<i class="glyphicon glyphicon-option-vertical"></i>
												</button>
												<ul class="dropdown-menu dropdown-menu-right">
													<li>
														<a href="#" data-toggle="modal" data-target="#modal-filter-profile">
															Filtrar
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
										<th>
											Puesto
										</th>
										<th>
											Consultora
										</th>
										<th>
											U. Negocio
										</th>
										<th>
											Cliente
										</th>
										<th>
											C. Costo
										</th>
										<th>
											Estado
										</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($job_profiles as $row_job_profile): ?>
										<tr>
						                    <td style="text-align: left;">
												<a href="<?php echo site_url('employer/job_profiles/profiles/show/' . $row_job_profile->ID); ?>">
													<?php echo $row_job_profile->job_title; ?>
												</a>
											  <div class="text-info">Id: <?php echo $row_job_profile->ID; ?></div>
											  <div class="text-info">Código: <?php echo $row_job_profile->code ? $row_job_profile->code : '-'; ?></div>
						                    </td>
						                    <td>
						                      <?php echo $row_job_profile->consultant_name; ?>    
						                    </td>
						                    <td>
						                      <?php echo $row_job_profile->business_unit_name; ?>
						                    </td>
						                    <td>
						                      <?php echo $row_job_profile->client_company_name; ?>
						                    </td>
						                    <td>
						                     <?php echo $row_job_profile->cost_center; ?>
						                    </td>
						                    <td>
												<?php echo $row_job_profile->active ? 'Activo' : 'Inactivo'; ?>
						                    </td>
						                    <td style="text-align: center;">

												<?php if ($this->session->userdata('current_profile_id') == 2 && $this->config->item('job_profile_module_enabled')): ?>
													<a href="<?php echo site_url('employer/job_profiles/profiles/edit/' . $row_job_profile->ID); ?>">
														Editar
													</a>
												<?php endif; ?>

												<?php if ($this->session->userdata('current_profile_id') == 4 && $this->config->item('job_profile_module_enabled')): ?>
													<a href="<?php echo site_url('employer/job_profiles/profiles/show/' . $row_job_profile->ID . '#section-resources'); ?>">
														Editar
													</a>
												<?php endif; ?>
			                    			</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>

						<?php if (count($job_profiles) == 0): ?>
							<div align="center" class="text-red" style="padding: 20px;">
								<h4>Sin resultados</h4>
							</div>				
						<?php endif; ?>
					</div>
					</div>
					<!--Pagination-->
					<div class="paginationWrap pag-wrap-v2"> <?php echo ($job_profiles) ? $links : '';?> </div>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div id="modal-filter-profile" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<?php echo form_open('employer/job_profiles/my_job_profiles/search', array('method' => 'get')); ?>
				<!-- Modal content-->
				<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Filtrar Perfil laboral</h4>
				</div>
				<div class="modal-body">
					<div class="panel-filter"> 
						<div class="filter-title">
							<h4>Consultora</h4>
						</div>

						<select id="consultant" 
								name="no_cia" 
								class="form-control"
								style="width:100%;">
							
							<option value="">Todas</option>
							<?php foreach ($consultants as $row): ?>
								<?php 
									$consultant_selected = $row->NO_CIA == $filters['no_cia'];
								?>
								<option value="<?php echo $row->NO_CIA; ?>" 
										<?php echo $consultant_selected ? 'selected="selected"' : ''; ?>>
									<?php echo $row->CONSULTORA; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="panel-filter"> 
						<div class="filter-title">
							<h4>Unidad de negocio</h4>
						</div>

						<select id="business-unit" name="cod_business_unit" class="form-control" style="width: 100%;">
							<?php $business_unit_code = $filters['cod_business_unit']; ?>
							<option value="">Todas</option>

							<?php foreach ($business_units as $row): ?>
								<option data-uni_neg="<?php echo $row->business_unit_code; ?>" 
										value="<?php echo $row->business_unit_code; ?>"
										<?php echo $business_unit_code == $row->business_unit_code ? 'selected="selected"' : ''; ?>>
									<?php echo $row->business_unit_name; ?>
								</option>
							<?php endforeach; ?>   
						</select>
					</div>
					<div class="panel-filter"> 
						<div class="filter-title">
							<h4>Cliente</h4>
						</div>

						<select id="client-company" 
								name="cod_clie" 
								class="form-control" 
								style="width: 100%;">
							<option value="">Todas</option>
							
							<?php foreach ($clients as $row): ?>
								<?php 
									$client_val =  $row->COD_CLIE;
									$client_selected = $client_val == $filters['cod_clie'];
								?>
								<option value="<?php echo $client_val; ?>"
										<?php echo $client_selected ? 'selected="selected"' : ''; ?>>
									<?php echo $row->CLIENTE; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="panel-filter"> 
						<div class="filter-title">
							<h4>Centro de costo</h4>
						</div>
						<select id="cost-center" 
								name="cost_center" 
								class="form-control" 
								style="width: 100%;">
							<option value="">Todos</option>
						<?php foreach ($cost_centers as $row): ?>
							<?php 
								$cost_center_val = $row->COD_CCOSTO;
								$cost_center_selected = $cost_center_val == $filters['cost_center'];
							?>
							<option value="<?php echo $cost_center_val; ?>"
									<?php echo $cost_center_selected ? 'selected="selected"' : ''; ?>>
							<?php echo $row->COD_CCOSTO; ?>
							</option>
						<?php endforeach; ?>
						</select>
					</div>
					<div class="panel-filter"> 
						<div class="filter-title">
							<h4>Estado</h4>
						</div>
						<select name="status" class="form-control">
							<option value="" <?php echo $filters['status'] == '' ? 'selected="selected"' : ''; ?>>
								Todos
							</option>
							<option value="1" <?php echo $filters['status'] == '1' ? 'selected="selected"' : ''; ?>>
								Activo
							</option>
							<option value="0" <?php echo $filters['status'] == '0' ? 'selected="selected"' : ''; ?>>
								Inactivo
							</option>
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

		<?php $this->load->view('common/bottom_ads');?>
		<!--Footer-->
		<?php $this->load->view('common/footer'); ?>
		<!-- Profile Popups -->
		<?php $this->load->view('common/before_body_close'); ?>
		<script type="text/javascript">
			$(function(){
				
				$( "#modal-filter-profile select").each(function(){
					$(this).select2();
				});

				$( "#consultant" ).change(function() {
					$( "#business-unit" ).val("");
				});

				$( "#business-unit" ).change(function() {
					getClientsCompany();
				});

				$( "#client-company" ).change(function() {
					getCostCenters()
				});
			});

			function getClientsCompany() {
				var url = "<?php echo site_url('general/overall_web_services/get_clients_company'); ?>";
				var data = {
				no_cia: $( "#consultant" ).val(),
				uni_neg: $( "#business-unit" ).val()
				}

				$( "#client-company" ).html('<option value="">Cargando...</option>').prop('disabled', true);
				
				$.ajax({
				type: 'POST',
				data: data,
				url: url,
				dataType: 'json',
				async:false,
				success: function(data){
					var clients =  data.MESSAGE == 'OK' ? data.CLIENTE : [];
					
					$.each(clients, function(i, row) {

					var clientValue = row.COD_CLIE;
					$( "#client-company" ).append('<option value="' + clientValue + '">' + row.CLIENTE + '</option>');
					});
				}
				})
				.fail(function() {
					alert('¡Ha ocurrido un error al tratar de listar las empresas clientes!');
				}).always(function() {
					$( "#client-company" ).find("option:eq(0)").text("Todas");
					$( "#client-company" ).prop('disabled', false);
				}); 
			}

			function getCostCenters() {
				var data  = {
					no_cia: $( "#consultant" ).val(),
					uni_neg: $( "#business-unit" ).val(),
					cod_clie: $( "#client-company" ).val(),
				}

				var url = "<?php echo site_url('general/overall_web_services/get_cost_centers'); ?>";
				$( "#cost-center" ).html('<option value="">Cargando...</option>').prop('disabled', true); 

				$.ajax({
				type: 'POST',
				data: data,
				url: url,
				dataType: 'json',
				async:false,
				success: function(data){
					var cost_centers = data.MESSAGE == 'OK' ? data.CENTROCOSTO : [];

					$.each(cost_centers, function(i, row) {
						$( "#cost-center" ).append('<option value="' + row.COD_CCOSTO + '">' + row.COD_CCOSTO + '</option>');
					});     
				}
				})
				.fail(function() {
					alert('¡Ha ocurrido un error al tratar de listar los centro de costo!');
				}).always(function() {
					$( "#cost-center" ).find("option:eq(0)").text("Todos");
					$( "#cost-center" ).prop('disabled', false);
				});      
			}
		</script>
	</body>
</html>