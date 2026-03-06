<style type="text/css">
    
    #modal-schedule-exam {
        padding: 10px !important;
    }

    #tbl-filter-schedule-exam td {
        border-top: none;
    }
</style>
<div class="modal-dialog" style="width: 100%;max-width: 1850px;">
  <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Programación examen</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="formwraper">
                        <div style="padding: 8px;">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <b>Atención:</b> 
                                        <p>- En caso se necesite Cancelar o Reprogramar una cita y la opción esté deshabilitada, ponerse en contacto con el equipo de SSO y liberar la cita.</p>
                                        <p>- Si las citas no estan actualizadas con respecto a la bandeja de SSO, por favor haga clic en 'Refrescar' para actualizar.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <table id="tbl-filter-schedule-exam" 
                                           class="table" 
                                           width="100%">
                                        <tr>
                                            <td style="width: 33.33333%;">
                                                <label style="display: block;">Examen</label>
                                                <select id="filter-schedule-exam-doc" 
                                                        class="form-control filter-schedule-exam" 
                                                        multiple="multiple"
                                                        style="width: 100%;">
                                                    <option value="1" selected >EMPO</option>
                                                    <option value="2">COVID-19</option>                                              
                                                </select>
                                            </td>
                                            <td style="width: 33.33333%">
                                                <label style="display: block;">Etapa</label>
                                                <select id="filter-schedule-exam-stage" 
                                                        class="form-control filter-schedule-exam"
                                                        style="width: 100%;">
                                                    <option value="" <?php echo $stage == '' ? 'selected="selected"' : ''; ?>>Todas</option>
                                                    <option value="0" <?php echo $stage == '0' ? 'selected="selected"' : ''; ?>>
                                                        FILTRO CURRICULAR
                                                    </option>
                                                    <option value="1" <?php echo $stage == '1' ? 'selected="selected"' : ''; ?>>
                                                        FILTRO TELÉFONICO
                                                    </option>
                                                    <option value="2" <?php echo $stage == '2' ? 'selected="selected"' : ''; ?>>
                                                        LONG LIST
                                                    </option>
                                                    <option value="3" <?php echo $stage == '3' ? 'selected="selected"' : ''; ?>>
                                                        ENTREVISTA
                                                    </option>
                                                    <option value="4" <?php echo $stage == '4' ? 'selected="selected"' : ''; ?>>
                                                        EVALUACIÓN
                                                    </option>
                                                    <option value="5" <?php echo $stage == '5' ? 'selected="selected"' : ''; ?>>
                                                        TERNA O SHORT LIST
                                                    </option>
                                                    <option value="6" <?php echo $stage == '6' ? 'selected="selected"' : ''; ?>>
                                                        SELECCIÓN PERSONAL
                                                    </option>
                                                    <option value="7" <?php echo $stage == '7' ? 'selected="selected"' : ''; ?>>
                                                        CONTRATACIÓN
                                                    </option>
                                                </select>
                                            </td>
                                            <td style="width: 33.33333%">
                                                <label style="display: block;">Estado</label>
                                                <select id="filter-schedule-exam-quote"
                                                        class="form-control filter-schedule-exam"
                                                        style="width: 100%;">
                                                    <option value="">Todas</option>
                                                    <?php foreach ($exam_status as $row_status): ?>
                                                        <option value="<?php echo $row_status->id; ?>">
                                                            <?php echo $row_status->name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <br />
                                    <div align="right">     
                                        
                                        <button id="btn-notify-sso" 
                                                class="btn btn-sm btn-default" style="">
                                            <i class="glyphicon glyphicon-bell"></i>
                                            Notificar a SSO
                                        </button>
                                                  
                                        <button id="btn-remove-schedule" class="btn btn-sm btn-default" style="margin-left: 25px;">
                                            Cancelar
                                        </button>

                                        <button id="btn-sync-exams" class="btn btn-sm btn-default">
                                            Refrescar
                                        </button>
                                        
                                        <button id="open-assign-date" class="btn btn-sm btn-primary">
                                            Programar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="content-exam-schedule-seekers"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function(){
        $( '#btn-notify-sso' ).click(function(e){
            e.preventDefault();

            const url = "<?php echo site_url('employer/scheduled_exams/notify_to_sso'); ?>";
            const btnNotify = $(this);
            const data = {
            	job_id: "<?php echo $job_id; ?>",
            	stage: $( '#filter-schedule-exam-stage' ).val()
            };

            btnNotify.prop('disabled', true);

            $.post(url, data, function(response) {
                
				if (!response.success) {
                    toastr["error"](response.message);
					return;
				} 

                if (response.seekers_available > 0) {
					search({
                        'job_id': "<?php echo $job_id; ?>",
						'document_id': $( '#filter-schedule-exam-doc' ).val(),
						'stage': $( '#filter-schedule-exam-stage' ).val(),
						'quote_status': $( '#filter-schedule-exam-quote' ).val()
					});

                    toastr["success"]("¡Notificación enviada a SSO!");
					return;
				}
				
				if (response.seekers_available == 0) {
                    toastr["warning"]("¡No hay exámenes programados!");
					return;
				}

            }, 'json')
            .fail(function(e) {
                alert("¡Ha ocurrido un error!");
            }).always(function(){
                btnNotify.prop('disabled', false);
            });
        });

        $( '#btn-remove-schedule' ).click(function() {
			const length = $( '.check_schedule_seeker:checked' ).length;

			if (length == 0) {
				toastr["error"]('¡Debe selecionar al menos 1 postulante!');
				return;
			}

			if (!confirm('¿Desea quitar la asignación?')) {
				return;
			}

			const inputs = [];

			$( '.check_schedule_seeker:checked' ).each(function(index, input) {
				inputs.push($(input).val());
			});		

			const url = "<?php echo site_url('employer/scheduled_exams/remove_schedule'); ?>";
			
			const data = {
				'job_id': "<?php echo $job_id; ?>",
				'document_id': $( '#filter-schedule-exam-doc' ).val(),
				'seekers': inputs
			};

			$.post(url, data, function(response) {
				if (response.success) {
					search({
                        'job_id': "<?php echo $job_id; ?>",
						'document_id': $( '#filter-schedule-exam-doc' ).val(),
						'stage': $( '#filter-schedule-exam-stage' ).val(),
						'quote_status': $( '#filter-schedule-exam-quote' ).val()
					});
				}
			}, 'json');
		});

        $( '#btn-sync-exams' ).click(function(){
			search({
                'job_id': "<?php echo $job_id; ?>",
				'sync': 1,
				'document_id': $( '#filter-schedule-exam-doc' ).val(),
				'stage': $( '#filter-schedule-exam-stage' ).val(),
				'quote_status': $( '#filter-schedule-exam-quote' ).val()
			});	
		});
        
        $( document ).off('submit', '#form-exam-schedule-create');
        $( document ).on('submit', '#form-exam-schedule-create', function(e){
			e.preventDefault();

			const length = $( '.check_schedule_seeker:checked' ).length;

			if (length == 0) {
				alert('¡Debe selecionar al menos 1 postulante!');
				return;
			}

			const inputs = [];

			$( '.check_schedule_seeker:checked' ).each(function(index, input) {
				inputs.push($(input).val());
			});		

			const url = "<?php echo site_url('employer/scheduled_exams/save_schedule'); ?>";
			
			const dataParameters = {
				'job_id': "<?php echo $job_id; ?>",
				'document_id': $( '#filter-schedule-exam-doc' ).val(),
				'seekers': inputs
			};

			const data = $.param(dataParameters) + '&' + $(this).serialize();

			$.post(url, data, function(response) {
				if (response.success) {
					search({
                        'job_id': "<?php echo $job_id; ?>",
						'document_id': $( '#filter-schedule-exam-doc' ).val(),
						'stage': $( '#filter-schedule-exam-stage' ).val(),
						'quote_status': $( '#filter-schedule-exam-quote' ).val()
					});
					$( '#modal-assing-date' ).modal('hide');
				}
			}, 'json');

			return false;
		});

        $( '#open-assign-date ' ).click(function(){
			const length = $( '.check_schedule_seeker:checked' ).length;

			if (length == 0) {
				toastr["error"]('¡Debe selecionar al menos 1 postulante!');
				return;
			}

			const jobId = "<?php echo $job_id; ?>";
			const inputs = [];

			$( '.check_schedule_seeker:checked' ).each(function(index, input) {
				inputs.push($(input).val());
			});

			const url = "<?php echo site_url('employer/scheduled_exams/exam_schedule/'); ?>" + jobId;
			const data = {
				'schedule_seekers': inputs
			};

            $( "#modal-assing-date" ).remove();
            $( 'body' ).append($( `<div id="modal-assing-date" class="modal" role="dialog"></div>` ));

			$( '#modal-assing-date' ).load(url, data, function(response) {
				$(this).html(response).modal('show');
			});
		});

		$( '.filter-schedule-exam' ).change(function(){ 

			if (!$( '#filter-schedule-exam-doc' ).val()) {
				$( '#content-exam-schedule-seekers' ).html(`<span style="display:block;text-align:center;">Por favor seleccione un Examen</span>`);
				return;
			}

			search({
                job_id: "<?php echo $job_id; ?>",
				'document_id[]': $( '#filter-schedule-exam-doc' ).val(),
				stage: $( '#filter-schedule-exam-stage' ).val(),
				quote_status: $( '#filter-schedule-exam-quote' ).val()
			});
		});

        function search(data) {   
			const url = "<?php echo site_url('employer/scheduled_exams/search_seekers/'); ?>" + data.job_id
			const img_loading_url = "<?php echo img_loading_url(); ?>";

			$( '#content-exam-schedule-seekers' ).html(`
			    <span style="display:block;text-align:center;">
				<img src="${img_loading_url}" 
                     style="width:24px; height:24px;">
				</span>
			`);
			$.get(url, data, function(response) {
                $( '#modal-schedule-exam .modal-content' ).css('height', 'auto');
				$( '#content-exam-schedule-seekers' ).html(response);
			})
			.fail(function(){
				$( '#content-exam-schedule-seekers' ).html(`
                    <span style="display:block;text-align:center;">
                        Un error ha ocurrido, no se pueden traer los datos. Intente de nuevo.
                    </span>`
                );
			});
		}

        $(document).off('change keyup', '.field-value');
        $(document).on('change keyup', '.field-value', function(){
            saveFieldValue(this);
        });

        function saveFieldValue(input) {
            const url = "<?php echo site_url('employer/scheduled_exams/save_field_values'); ?>";
            const data = {
                'job_id': $(input).data('job-id'),
                'schedule_id': $(input).data('schedule-id'),
                'field': $(input).data('field'),
                'value': $(input).val()
            };

            $.post(url, data, function(response) {
                if (response.success && response.seekers_available > 0) {
                    toastr["success"]("¡Notificación enviada a SSO!");
                } else if (response.success && response.seekers_available == 0) {
                    toastr["warning"]("¡No hay exámenes programados!");
                }
            }, 'json')
            .fail(function(e) {
                alert("¡Ha ocurrido un error!");
            }).always(function(){
            });
        }

        search({
            job_id: "<?php echo $job_id; ?>",
            stage: $( '#filter-schedule-exam-stage' ).val()
        });

        $( '#filter-schedule-exam-doc' ).select2({
            closeOnSelect: false
        });
        $( '#filter-schedule-exam-stage' ).select2();
        $( '#filter-schedule-exam-quote' ).select2();
    });
</script>
