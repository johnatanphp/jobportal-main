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
									<b>MOF creados</b>
								</div>
							</div>
						</div>
						
						<!-- Description -->
						<div class="table-search">
							<?php echo form_open('employer/mofs/mofs/search', array('method' => 'get')); ?>	
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
														<a href="#" data-toggle="modal" data-target="#modal-filter-mof">
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
										<th style="text-align: left;">
											Puesto
										</th>
										<th style="text-align: left;">Áreas</th>
										<th>
											Estado
										</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($results as $row): ?>
										<tr>
						                    <td style="text-align: left;">
												<a href="<?php echo site_url('employer/mofs/mofs/show/' . $row->ID); ?>">
													<?php echo $row->job_title; ?>
												</a>
											  	<div class="text-info">Id: <?php echo $row->ID; ?>  </div>
											  	<div class="text-info">Código: <?php echo $row->code ? $row->code : '-'; ?></div>
						                    </td>
											<td style="text-align: left;">
												<?php echo $row->belonging_areas; ?>
											</td>
						                    <td>
												<?php echo $row->active ? 'Activo' : 'Inactivo'; ?>
											</td>
						                    <td style="text-align: center;">
												<?php if ($this->session->userdata('current_profile_id') == 2 && $this->config->item('mof_module_enabled')): ?>
													<a href="<?php echo site_url('employer/mofs/mofs/edit/' . $row->ID); ?>">
														Editar
													</a>
												<?php endif; ?>

												<?php if ($this->session->userdata('current_profile_id') == 4 && $this->config->item('mof_module_enabled')): ?>
													<a href="<?php echo site_url('employer/mofs/mofs/show/' . $row->ID . '#section-resorces'); ?>">
														Editar
													</a>
												<?php endif; ?>

			                    			</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>

						<?php if (count($results) == 0): ?>
							<div align="center" class="text-red" style="padding: 20px;">
								<h4>Sin resultados</h4>
							</div>				
						<?php endif; ?>
					</div>
					</div>
					<!--Pagination-->
					<div class="paginationWrap pag-wrap-v2"> <?php echo ($results) ? $links : '';?> </div>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div id="modal-filter-mof" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<?php echo form_open('employer/mofs/mofs/search', array('method' => 'get')); ?>
				<!-- Modal content-->
				<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Filtros MOF</h4>
				</div>
				<div class="modal-body">
				<div class="panel-filter"> 
						<div class="filter-title">
							<h4>Áreas pertenecientes</h4>
						</div>
						<select name="area_id" class="form-control" style="width:100%;">
							<option value="">Todas las áreas</option>
							<?php foreach ($belonging_areas as $row): ?>
								<option value="<?php echo $row->ID; ?>" <?php echo $filters['area_id'] == $row->ID ? 'selected="selected"' : ''; ?>>
									<?php echo $row->area_name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="panel-filter"> 
						<div class="filter-title">
							<h4>Estado</h4>
						</div>
						<select name="status" class="form-control" style="width:100%;">
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
				$( ".filter-radio" ).radio();

				$( "#modal-filter-mof select").each(function(){
					$(this).select2();
				});
			});
		</script>
	</body>
</html>