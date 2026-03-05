<style type="text/css">
    .list-options {
        list-style: none;
        padding-bottom: 3px;
    }

    .table .row-selected td {
        background: #d8f1fd;
    }
</style>
<div class="modal-dialog" style="width: 99%;">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Crear Solicitud - <?php echo $job->job_title; ?></h4>
        </div>
        <div class="modal-body">
            <div class="formwraper">
                <div class="formint">
                   <div>
                        <?php echo form_open('employer/exam_requests/requests/do_create', ['id' => 'form-create-request']); ?>
                            <input type="hidden" name="schedule_id" value="<?php echo $exam_schedule->id; ?>">
                            <div style="padding: 12px 0;">
                                <h5><b>Programación para <?php echo $exam_type->name; ?></b></h5>
                                <div id="list-seekers">
                                    <h4 style="text-align: center;">
                                        ¡Seleccione un documento!
                                    </h4>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        var jobId = "<?php echo $job->ID; ?>";
        var scheduleId = "<?php echo $exam_schedule->id; ?>";
        var minDate = "<?php echo date('Y-m-d'); ?>";

        $( "#list-documents" ).change(function() {

            if ($(this).val() == '') {
                $( "#list-seekers" ).html(
                    '<h4 style="text-align:center;">¡Por favor seleccione un documento para listar los postulantes!</h4>'
                    );
                return;
            }

            search_seekers(jobId, $(this).val());
        });

        function search_seekers(scheduleId) {
            var url = "<?php echo site_url('employer/exam_requests/requests/search_seekers'); ?>/" + scheduleId;

            $( "#list-seekers" ).html('<div style="text-align:center;padding-top:15px;">Cargando...</div>');
            $.get(url, {}, function(response) {
                $( "#list-seekers" ).html(response.list_seekers);
                $( "#modal-selected-medical-center" ).html(response.medical_centers);
            }, 'json');
        }

        function updateRow(check) {
            
            var row = $(check).closest('tr');

            //actualizar atributos para validaciones
            if ($(check).is(':checked')) {
                $( '.seeker-exam-date', row).attr('min', minDate);
            } else {
                $( '.seeker-exam-date', row).removeAttr('min');
            }

            //Pintar fila seleccionada
            row.removeClass('row-selected');
        
            if ($(check).is(':checked')) {
                row.addClass('row-selected');
            }

            //Marcar check principal si
            //todos los check estan seleccionados
            var checkAll = $( '.check-seeker' ).length == $( '.check-seeker:checked' ).length;

            $( '#check-seekers' ).prop('checked', checkAll);
        }

        function validateRowsSelected() {
            var countSeekerSelected = $( '.check-seeker:checked' ).length;

            for (var i = 0; i < countSeekerSelected; i++) {

                medicalCenter = $( '.check-seeker:checked' ).eq(i)
                                                            .closest('tr')
                                                            .find('.input-medical-center');

                if (medicalCenter.length == 0) {
                    return "¡Hay postulantes seleccionados sin centro médicos!";
                }
            }

            return true;
        }

        $( '#form-create-request' ).submit(function(e){

            e.preventDefault();

            var countSeekerSelected = $( '.check-seeker:checked' ).length;

            if (countSeekerSelected == 0) {
                toastr["error"]('¡Debe elegir al menos 1 postulante!'); 
                return;
            }
  
            var resultValidation = validateRowsSelected();

            if (resultValidation !== true) {
                toastr["error"](resultValidation);
                return;
            }

            if (!window.confirm('¿Esta seguro de crear la solicitud?')) {
                return;
            }
        
            var action = $(this).prop('action');
            var data = $(this).serialize();

            $.post(action, data, function(response) {

                if (!response.success) {
                    toastr["error"](response.error);
                    return;
                }

                var requestId = response.request_id;

                window.location = "<?php echo site_url('employer/exam_requests/requests/list/'); ?>" + scheduleId;

            }, 'json')
            .fail(function (){
                toastr["error"]('¡Ha ocurrido un error al enviar la solicitud!');
            });

            return false;
        });

        $(document).on('change', "#check-seekers", function() {
            $( ".check-seeker" ).prop('checked', $(this).is(':checked')).change();
        });

        $(document) .on('change', '.check-seeker', function(){
            updateRow(this);
        });

        $(document).off('click', "#open-medical-centers");
        $(document).on('click', "#open-medical-centers", function(){

            if ($( '.check-seeker:checked' ).length == 0) {
                toastr["error"]('¡Debe seleccionar al menos 1 postulante!');
                return;
            }

            $( "#modal-selected-medical-center" ).modal('show');
        });

        $(document).off("click", "#selected-medical-center");
        $(document).on("click", "#selected-medical-center", function(){

            var selectValue = $( "#medical-center" ).val();
            var selectText = $.trim($( "#medical-center" ).find('option:selected').text());

            if (selectValue == '') {
                toastr["error"]('¡Debe seleccionar un Centro médico!');
                return;
            }

            var medicalCenterLocationValue = $( "#medical-center-location" ).val();
            var medicalCenterLocationText = $.trim($( "#medical-center-location" ).find('option:selected').text());

            if (medicalCenterLocationValue == '') {
                toastr["error"]('¡Debe seleccionar una sede!');
                return;
            }

            if (selectText !== medicalCenterLocationText) {
                selectText = selectText + ' - ' + medicalCenterLocationText;
            }

            $( '.check-seeker:checked' ).each(function(i, check){
                var row = $(check).closest('tr');
                var key = row.data('key');

                medicalCenterHtml = `
                    <input type="hidden" 
                           class="input-medical-center"
                           name="seekers[${key}][medical_center]" 
                           value="${selectValue}">

                    <input type="hidden" 
                           class="input-medical-center-location"
                           name="seekers[${key}][medical_center_location]" 
                           value="${medicalCenterLocationValue}">
                    ${selectText}
                `;

                row.find('td:eq(5)').html(medicalCenterHtml);
            });

            $( "#check-seekers" ).prop('checked', false).change(); 
            $( "#modal-selected-medical-center" ).modal('hide');
        });

        $(document).off("change", "#medical-center");
        $(document).on("change", "#medical-center", function(){
            
            $( "#medical-center-location" ).prop('disabled', false).empty();

            var centerMedicalData = $(this).val().split("|");
            var centerMedicalCode = centerMedicalData[0];

            var url = "<?php echo site_url('employer/exam_requests/requests/get_medical_center_locations/'); ?>" + centerMedicalCode;
            $.post(url, {}, function(response){
                var locations = response.locations;

                var options = `<option value="">Seleccione</option>`;
            
                for (index in locations) {
                    var location = locations[index];
                    options+=`
                        <option value="${location.id}" >${location.location}</option>
                    `;
                }

                $( "#medical-center-location" ).html(options);

                if (locations.length == 1) {
                    $( "#medical-center-location option" ).eq(1).prop('selected', true);
                    $( "#medical-center-location" ).prop('disabled', true);
                }
            }, 'json');
        });

        search_seekers(scheduleId);
    });
</script>