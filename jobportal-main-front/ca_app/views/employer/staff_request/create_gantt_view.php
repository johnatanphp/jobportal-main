<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css"> 
.formwraper p{font-size:13px;}

#table-gantt-chart th {
	background: #eee;
	padding: 8px 5px;
}

#table-gantt-chart tr td {
	padding: 8px 5px;
}

#table-gantt-chart tr:nth-child(even) {
	background-color: #eee;
}

#table-gantt-chart tr:nth-child(odd) {
	background-color: #fff;
}

.btn-remove-item {
  margin:0;
  padding: 0;
  border:0;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  background: #e76767;
  color: #fff;
  font-size: 10px;
}

#modal-add-activities ul {
	list-style: none;
}

.content-recipients {
	padding: 10px 0;
}

#tbl-recipients tr td {
	padding: 5px 3px;
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
						<div class="col-md-12"><b>
						<a href="<?php echo base_url('employer/staff_requests/show/' . $request->ID); ?>" style="color:#fff;">
							<i class="fa fa-arrow-left" aria-hidden="true"></i>
						</a>
						Gantt de actividades de la solicitud</b>
						</div>
					</div>
				</div>
				<div class="formint">					
					<div>
						<button id="show-activities" class="btn btn-xs btn-primary">
							Agregar actividades
						</button>
						<?php if (!empty($request_gantt)): ?>
							<a class="btn btn-xs btn-primary pull-right" href="<?php echo site_url('employer/staff_request/gantt_activities/export/' . $request_gantt->ID); ?>">
								<i class=" glyphicon glyphicon-save-file"></i>
								Exportar a excel
							</a>
							<button id="open-modal-send-email" style="margin-right: 5px;" class="btn btn-xs btn-primary pull-right">
								<i class="glyphicon glyphicon-send"></i>
								Enviar por email
							</button>
						<?php endif; ?>
					</div>
					<br>
					<div>
						<label>Solicitud Código:</label>
						<?php echo $request->ID; ?>
					</div>
					<div>
						<label>Solicitud Nombre:</label>
						<?php echo $request->job_title; ?>
					</div>
					<div id="type_gantt_lbl">
						<label>Tipo Gantt: </label> <?php e($gantt_type->name);  ?></label>
					</div>
					<div>
						<?php echo form_open('employer/staff_request/gantt_activities/' . $request->ID . '/' . $type_id); ?>
							<div class="pull-right">
								<label>
									<input type="checkbox" name="ignore_weekend" value="1" <?php echo @$request_gantt->ignore_weekend ? 'checked="checked"' : ''; ?>>
									Ignorar fines de semana
								</label>
							</div>

							<div style="margin-top:15px;">
								<table id="table-gantt-chart" width="100%">
									<thead>
										<tr>
											<th></th>
											<th style="min-width: 250px;">Actividad</th>
											<th>Fecha de inicio</th>
											<th>Fecha final</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach (@$data_gantt_chart as $index => $row_data): ?>
											<tr>
												<td>
													<button class="btn-remove-item" type="button" onclick="$(this).closest('tr').remove();">
														<i class="glyphicon glyphicon-remove"></i>
													</button>
												</td>
												<td>
													<label><?php echo ($row_data->other_activity ? $row_data->other_activity : $row_data->activity_name); ?></label>
													<input class="form-control input-activity" type="hidden" name="gantt_tasks[<?php echo $index; ?>][activity_id]" value="<?php echo $row_data->activity_id; ?>"/>	
													<input class="form-control input-other-activity" type="hidden" name="gantt_tasks[<?php echo $index; ?>][other_activity]" value="<?php echo $row_data->other_activity; ?>"/>	
												</td>
												<td>
													<input class="form-control start-date" type="text" name="gantt_tasks[<?php echo $index; ?>][start_date]" value="<?php echo _date_locale_format(strtotime($row_data->start_date), 'dd/MM/y'); ?>" readonly="true"/>
												</td>
												<td>
													<input class="form-control end-date" type="text" name="gantt_tasks[<?php echo $index; ?>][end_date]" value="<?php echo _date_locale_format(strtotime($row_data->end_date), 'dd/MM/y'); ?>" readonly="true"/>
												</td>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
							</div>
							<div style="margin-top:20px;text-align: center;">
								<input type="submit" value="Guardar" class="btn btn-primary">
							</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="modal-add-activities" class="modal fade" role="dialog">
  <div class="modal-dialog" style="max-width: 400px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <!-- <h4 class="modal-title">Actividades</h4> -->
		<label for="">
			<?php e('Tipo gantt: ' . $gantt_type->name); ?>
		</label>
      </div>
      <div class="modal-body">
      	<ul>
	      	<?php foreach($gantt_activities as $row_activity): ?>
	      		<li>
	      			<label>
	      				<input id="option-activity-<?php echo $row_activity->ID; ?>" type="checkbox" data-activity-id="<?php echo $row_activity->ID; ?>" data-activity-name="<?php echo $row_activity->activity_name; ?>" class="option-activity">
	      				<?php echo $row_activity->activity_name; ?>
	      			</label>
	      		</li>
	      	<?php endforeach; ?>
      	</ul>
		<input type="text" name="other_activity" id="other_activity" class="form-control" style="display: none;">
      </div>
      <div class="modal-footer">
        <button id="add-activities" type="button" class="btn btn-default">Agregar</button>
      </div>
    </div>
  </div>
</div>

<?php if ($request_gantt): ?>
	<div id="modal-send-by-email" class="modal fade" role="dialog">
		<div class="modal-dialog" style="max-width: 450px;">
			<!-- Modal content-->
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Enviar Gantt por email</h4>
			</div>
			<div class="modal-body">
				<button style="margin-bottom: 10px;" id="add-recipient" class="btn btn-primary btn-sm pull-right">Agregar destinatario</button>
				<div class="content-recipients">
					<input type="hidden" name="gantt_id" value="<?php echo $request_gantt->ID; ?>">
					<div>
						<table id="tbl-recipients" width="100%">
							<tr>
								<td>
									<input type="text" name="recipients[]" class="form-control" placeholder="Email destinatario">
								</td>
								<td>
									<button class="btn btn-xs remove-recipient">
										<i class="glyphicon glyphicon-remove"></i>
									</button>
								</td>
							</tr>
						</table>
					</div>
					<div style="text-align: center;padding-top: 10px;">
						<button id="send-gantt-email" class="btn btn-primary">Enviar</button>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<script id="tpl-add-recipient" type="text/template">
	<tr>
		<td>
			<input type="text" name="recipients[]" class="form-control" placeholder="Email destinatario">
		</td>
		<td>
			<button class="btn btn-xs remove-recipient">
				<i class="glyphicon glyphicon-remove"></i>
			</button>
		</td>
	</tr>
</script>

<script type="text/template" id="tpl-row-activity">
	<tr>
		<td>
		<button class="btn-remove-item" type="button" onclick="$(this).closest('tr').remove();">
			<i class="glyphicon glyphicon-remove"></i>
		</button>
		</td>
		<td>
		<label>{{activity_name}}</label>
			<input class="form-control input-activity" type="hidden" name="gantt_tasks[{{index}}][activity_id]" value="{{activity_id}}"/>	
			<input class="form-control input-other-activity" type="hidden" name="gantt_tasks[{{index}}][other_activity]" value="{{other_activity}}"/>	
		</td>
		<td>
			<input class="form-control start-date" type="text" name="gantt_tasks[{{index}}][start_date]" value="" readonly="true"/>
		</td>
		<td>
			<input class="form-control end-date" type="text" name="gantt_tasks[{{index}}][end_date]" value="" readonly="true"/>
		</td>
	</tr>
</script>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>

<script>
	$(document).ready(function(){

		function addRecipient() {
			var row_recipient = $( "#tpl-add-recipient" ).html();
			$( "#tbl-recipients tbody" ).prepend(row_recipient);
		}

		function sendGanttByEmail() {

			const url = "<?php echo site_url('employer/staff_request/gantt_activities/send_by_email'); ?>";
			const data = $( ".content-recipients :input").serialize();

			$( "#send-gantt-email" ).prop('disabled', true);

			$.post(url, data, function(response){
				if (response.success) {
					toastr["success"]("¡El gantt se envió correctamente!");
				} else {
					toastr["error"]("¡El gantt no se pudo enviar correctamente!");
				}
			}, 'json')
			.fail(function(){
				alert("Ha ocurrido un error");
			})
			.always(function(){
				$( "#send-gantt-email" ).prop('disabled', false);
			});
		}

		function showActivities() {
			
			$( "#add-activities" ).prop('disabled', false);
			$( ".option-activity" ).prop('disabled', false);
			$( ".option-activity" ).prop('checked', false);

			$( ".input-activity" ).each(function(i, input) {

				var activity_id = $(input).val();	
				$( "#option-activity-" + activity_id).prop('disabled', true).prop('checked', true);
			});

			$( "#modal-add-activities" ).modal('show');
		}

		function addActivities() {

			$( "#add-activities" ).prop('disabled', false);
			$( "#modal-add-activities" ).modal('hide');

			$( ".option-activity:enabled" ).each(function(i, option) {
				var option  = $(option);

				if (option.is(":checked")) {
					var activityId = option.data('activity-id');
					var activityName = option.data('activity-name');
					
					if (activityName == 'OTROS') {
						activityName = $('#other_activity').val();
					}

					var index = $( "#table-gantt-chart" ).generateSequence() * -1;

					var template = $( "#tpl-row-activity" ).html();
					var row_activity = Mustache.render(template, {
							index: index,
							activity_id: activityId,
							activity_name: activityName,
							other_activity: $('#other_activity').val()
						}
					);

					$( "#table-gantt-chart tbody" ).append(row_activity);

					addEventPicker();
				}
			});
		}

		$('.option-activity').change(function(){
			if ($(this).is(":checked") && $(this).data('activity-name') == 'OTROS') {
				
				$('#other_activity').show();
			} else {
				$('#other_activity').hide();
			}
		});

		function addEventPicker() {

			$( "#table-gantt-chart tbody tr" ).each(function(i, objRow) {

				var from = $(objRow).find(".start-date");
				var to = $(objRow).find(".end-date");
				
				from.attr('required', true);
				to.attr('required', true);

				from.datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				dateFormat: "dd/mm/yy",
				dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
				dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
				monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
				monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec" ],

			})
			.on( "change", function() {
				to.datepicker( "option", "minDate", getDate(this)) ;
			});
			
			to.datepicker({
				defaultDate: "+1w",
				changeMonth: true,				
				dateFormat: "dd/mm/yy",
                dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
				dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
				monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
				monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec" ],

			})
			.on( "change", function() {
				from.datepicker( "option", "maxDate", getDate(this));
			});

			function getDate(element) {
				var date;
				var dateFormat = "dd/mm/yy";
		
				try {
					date = $.datepicker.parseDate(dateFormat, element.value);
				} catch( error ) {
					date = null;
				}

				return date;
			}

			});
		}

		addEventPicker();
		
		$(document).on("click", ".remove-recipient", function(){
			$(this).closest('tr').remove();
		});

		$( "#add-recipient" ).click(function(){
			addRecipient();
		});

		$( "#send-gantt-email" ).click(function(){
			sendGanttByEmail();
		});

		$( "#open-modal-send-email" ).click(function(){
			$( "#tbl-recipients tr" ).remove();
			$( "#modal-send-by-email" ).modal('show');
			addRecipient();
		});

		$( "#add-activities" ).click(function(){
			addActivities();
		});

		$( "#show-activities" ).click(function(){
			showActivities();
		});
	});

</script>

</body>
</html>