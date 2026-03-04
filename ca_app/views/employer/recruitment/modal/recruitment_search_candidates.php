<!-- Modal -->
<style>
    #modal-rys-search-candidates .dt-buttons {    
        text-align: right;
        padding: 5px 0;
    }
</style>
<div id="modal-rys-search-candidates" 
     class="modal"
     role="dialog">
    <div class="modal-dialog" style="min-width: 80%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buscar candidatos</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="table-rys-search-candidates" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>Doc. Tipo</th>
                                <th>Doc. Número</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Email</th>
                                <th>Ubicación</th>
                                <th>Teléfono</th>
                                <th>Etapa</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>

<script type="text/javascript">
    $(function(){

        $( "#rys-form-search-candidates" ).submit(function(e){
            e.preventDefault();

            $( "#modal-rys-search-candidates" ).modal('show');
            rysSearchCandidates($(this.job_id).val(), $(this.query).val());
            
            return false;
        });

        function rysSearchCandidates(jobId, query) {
            var query = $.trim(query);

            var dtSearchCandidate = $( '#table-rys-search-candidates' ).DataTable({
                "language": {
                    "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
                },
                dom: 'Bfrtip',
                buttons: [{
                    extend : 'excelHtml5',
                    text : 'Exportar',
                    className: 'btn btn-xs btn-success',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                }],
                "destroy": true,
                "bAutoWidth": false,
                "deferRender": true,
                "iDisplayLength": 20,
                "bProcessing": true,
                "lengthChange": false,
                ajax: {
                    url: "<?php echo site_url('employer/recruitment_processes/get_candidates'); ?>",
                    type: 'GET',
                    data : {
                        job_id: jobId
                    }
                },
                columns: [
                    {data:'document_type_abbr', 'className': 'style_td text-left'},
                    {data:'document_number', 'className': 'style_td text-left'},
                    {data:'first_name', 'className': 'style_td text-left'},
                    {data:'last_name', 'className': 'style_td text-left'},
                    {data:'dob', 'className': 'style_td text-left'},
                    {data:'email', 'className': 'style_td text-left'},
                    {data:'city', 'className': 'style_td text-left'},
                    {data:'mobile', 'className': 'style_td text-left'}, 
                    {data:'stage_name', 'className': 'style_td text-left'},
                    {
                        data: null,
                        render: function(row) {
                            return row.discarded == 1 ? 'Descartado' : 'Activo';
                        }
                    ,'className': 'style_td text-center'
                    },    
                    {
                        data: null,
                        render: function(row) {
                            return `
                                <div style="display: flex;">
                                    <a class="modal-open-seeker-screening" title="Gestionar Screening" style="margin: 5px;" href="#" data-candidate-id="${row.id}" data-rs-document="screnning"><span class="glyphicon glyphicon-cloud-upload"></span></a>
                                    <a class="emailModal" title="Actualizar Correo" href="#" style="margin: 5px;" data-candidate-id="${row.id}" data-candidate-email="${row.email}" ><span class="glyphicon glyphicon-envelope"></span></a>
                                    <a class="phoneModal" title="Actualizar número de celular" href="#" style="margin: 5px;" data-candidate-id="${row.id}" data-candidate-mobile="${row.mobile}"><span class="glyphicon glyphicon-earphone"></span></a>
                                    <a class="calendarModal" href="#" style="margin: 5px;" title="Actualizar fecha de nacimiento" data-candidate-id="${row.id}" data-candidate-birthdate="${row.dob}"><span class="glyphicon glyphicon-calendar"></span></a>
                                    <a class="btn-seeker-update-city" href="#" style="margin: 5px;" title="Actualizar ubicación" data-candidate-id="${row.id}" data-candidate-city="${row.city}"><span class="glyphicon glyphicon-map-marker"></span></a>
                                    <a class="btn-seeker-update" href="#" style="margin: 5px;" title="Actualizar datos" data-candidate-id="${row.id}"><span class="glyphicon glyphicon-pencil"></span></a>
                                </div>`;
                        }
                    ,'className': 'style_td text-center'
                    }   
                ]
            });

            if (query != '') {
                dtSearchCandidate.search(query).draw();
            }
        }

        function updateEmail(id, email) {
            var data = {
                id: id,
                email: email,
            };
   
            var url = app.siteUrl('employer/recruitment_processes/update_email');
            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    toastr["success"](response.message);

                    $( '.emailModal', `.wrapper-candidate[data-candidate-id='${id}']`).data('candidate-email', $.trim(email));
                    $( '#emailModal' ).modal('hide');
                } else {
                    toastr["error"](response.message);
                }
            }, 'json')
            .fail(function(){
                alert("¡Ha ocurrido un error!");
            });
        }

        function updateBirthdate(id, date) {
            var data = {
                id: id,
                date: date,
            };
   
            var url = app.siteUrl('employer/recruitment_processes/update_birthdate');
            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    toastr["success"]("¡La fecha de nacimiento ha sido actualizada!");
                    $( '.calendarModal', `.wrapper-candidate[data-candidate-id='${id}']`).data('candidate-birthdate', $.trim(date));
                    $( '#calendarModal' ).modal('hide');
                } else {
                    toastr["error"]("¡Ha ocurrido un error!");
                }
            }, 'json')
            .fail(function(){
                toastr["error"]("¡Ha ocurrido un error!");
            });
        }

        function updatePhone(form) {
            const id = $(form.id).val();
            const mobile = ($(form.mobile_phone).data('iti-instance')).getNumber(intlTelInput.utils.numberFormat.E164);
            const data = {
                'id': id,
                'full_mobile_phone_number': mobile
            };

            const url = $(form).prop('action');
           
            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    toastr["success"](response.message);
                    $( '.phoneModal', `.wrapper-candidate[data-candidate-id='${id}']`).data('candidate-mobile', $.trim(mobile));
                    $( '#phoneModal' ).modal('hide');
                } else {
                    toastr["error"](response.message);
                }
            }, 'json')
            .fail(function(){
                alert("¡Ha ocurrido un error!");
            });
        }

        $(document).on("click", ".emailModal", function(e){
            e.preventDefault();

            $( "#emailModal" ).modal('show');
            $( "#email-candidate" ).val($(this).data('candidate-email'));
            $( "#id-candidate" ).val($(this).data('candidate-id'));
        });

        $(document).on("click", ".calendarModal", function(e){
            e.preventDefault();

            $( "#calendarModal" ).modal('show');
            $( "#birthdate-candidate" ).val($(this).data('candidate-birthdate'));
            $( "#id-candidate" ).val($(this).data('candidate-id')); 
        });

        $(document).on("click", ".phoneModal", function(e){
            var mobile = $.trim($(this).data('candidate-mobile'));
            ($('#candidate_mobile_phone').data('iti-instance')).setNumber(mobile);
            $( 'input[name="id"]', "#phoneModal" ).val($(this).data('candidate-id'));

            $( "#phoneModal" ).modal('show');
        });

        $(document).on("click", "#btn_update_email", function(e){  
          var id = $( "#id-candidate" ).val();
          var email = $( "#email-candidate" ).val();

          updateEmail(id, email);
        });

        $(document).on("click", "#btn_update_birthdate", function(e){
            var id = $( "#id-candidate" ).val();
            var date = $( "#birthdate-candidate" ).val();
  
            updateBirthdate(id, date);      
        });

        $(document).on("submit", "#form-update-seeker-phone", function(e){
            e.preventDefault();

            updatePhone(this);

            return false;
        }); 

        //Actualizar ubicacion
        $(document).on("click", ".btn-seeker-update-city", function(e){
            $( '#modal-seeker-update-city' ).modal('show');
            $( 'input[name="id"]', "#modal-seeker-update-city" ).val($(this).data('candidate-id'));
            $( 'select[name="city"]', "#modal-seeker-update-city" ).val($(this).data('candidate-city'));
            $( 'select[name="city"]', "#modal-seeker-update-city" ).select2({ width: '100%' });
        });

        //Guardar ubicacion
        $(document).on("submit", "#modal-seeker-update-city form", function(e){
            e.preventDefault();
            const form = $(this);
            const data = form.serialize();
            const url = form.prop('action');
            const id = $(this.id).val();
            const city = $(this.city).val();

            $.post(url, data, function(response) {
                const status = response.success;
                if (status) {
                    toastr["success"](response.message);
                    $( '.btn-seeker-update-city', `.wrapper-candidate[data-candidate-id='${id}']`).data('candidate-city', $.trim(city));
                    $( '#modal-seeker-update-city' ).modal('hide');
                } else {
                    toastr["error"](response.message);
                }
            }, 'json')
            .fail(function(){
                toastr["error"]("¡Ha ocurrido un error!");
            });

            return false;
        });

        $(document).on("click", ".btn-seeker-update", function(e){
            e.preventDefault();
            $( '#modal-update-data-seeker' ).modal('show');
            const candidateId = $(this).data('candidate-id');
            const url = "<?php echo site_url('employer/recruitment_processes/update_seeker_data'); ?>/" + candidateId;

            $( '#modal-update-data-seeker .modal-content' ).html(`
                <div class="modal-header">
                    <h5 class="modal-title">Espere un momento...</h5>
                </div>
            `);

            $.get(url, {}, function(response) {
                $( '#modal-update-data-seeker .modal-content' ).html(response);
            })
            .fail(function(){
                toastr["error"]("¡Ha ocurrido un error!");
            });
        });
    });
</script>