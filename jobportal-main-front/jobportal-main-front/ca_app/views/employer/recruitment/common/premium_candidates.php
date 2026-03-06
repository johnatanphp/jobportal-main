<div>
	<style type="text/css">
		#tbl-premium-candidates tr td {
			padding: 5px;
		}

		#tbl-premium-candidates tr {
			border-bottom: 1px solid #ccc;
		}
		
		#tbl-premium-candidates_filter {
			text-align: right;
			padding: 10px 0;
		}

		#tbl-premium-candidates_paginate {
			text-align: right;
			padding: 5px 0;
		}

		#tbl-premium-candidates_paginate a {
			padding: 5px 6px;
		}
	</style>

	<div class="row">
		<div class="col-md-6">
			(<?php echo count($results); ?>) Candidatos Premium		
		</div>
		<div class="col-md-6">
			<a id="show-rs-search-candidates" href="#" class="pull-right">
				Volver a la búsqueda
			</a>			
		</div>
	</div>

	<table id="tbl-premium-candidates" width="100%">
		<thead>
			<tr>
				<th width="50"></th>
				<th width="250"></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($results as $candidate): ?>
				<?php 
					$url_pic_candidate = img_pic_candidate($candidate->photo);
				?>
				<tr>
				  <td style="vertical-align: top;">
				  	<img src="<?php echo $url_pic_candidate; ?>">
				  </td>
				  <td style="vertical-align: top;">
				  	<div>
				  		<?php echo $candidate->first_name . ' ' . $candidate->last_name; ?>		
				  	</div>
				  </td>
				  <td>
				  	<div style="font-size: 12px;">
				  		<div>
		  					<?php echo $candidate->job_title; ?>	
				  		</div>
				  		<div style="color: #666;font-style: italic;">
				  			<?php echo _date_locale_format(strtotime($candidate->last_stage_date), 'dd-MMM-y'); ?>
				  		</div>
				  	</div>
				  </td>
				  <td>
				    <button class="btn btn-xs btn-primary js-rs-add-candidate-premium" data-from-job-id="<?php echo $candidate->last_job_id; ?>" data-candidate-id="<?php echo $candidate->candidate_id; ?>">
				    	Agregar
					</button>
				  </td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	$( "#tbl-premium-candidates" ).dataTable({
		"oLanguage": {
			"sEmptyTable": "No hay registros disponibles",
			"sInfo": "Hay _TOTAL_ registros. Mostrando de (_START_ a _END_)",
			"sLoadingRecords": "Por favor espera - Cargando...",
			"sSearch": "Buscar:",
			"sLengthMenu": "Mostrar _MENU_",
			"oPaginate": {
				"sLast": "Última página",
				"sFirst": "Primera",
				"sNext": "Siguiente",
				"sPrevious": "Anterior"
			}
		},
		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": true,
		"bInfo": false,
		"bAutoWidth": false,
	});
</script>