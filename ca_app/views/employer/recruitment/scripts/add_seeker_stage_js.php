<script type="text/javascript">
    $(function(){

        function addCandidateToStage(candidateId, email, documenNumber, btn) {

            var data = {
                process_id: $( "#global_process_id" ).val(),
                stage: $( "#current_stage" ).val(),
                job_seeker_id: candidateId ,
                email: email,
                document_number: documenNumber,
                notify_candidate_by_mail: $( 'input[name="notify_candidate_by_mail"]', '#rs-search-candidates' ).is(':checked') ? 1 : 0,
                notify_candidate_by_whatsapp: $( 'input[name="notify_candidate_by_whatsapp"]', '#rs-search-candidates').is(':checked') ? 1 : 0
            };

            var btnAddCandidate = $(btn);
            var url = app.siteUrl('employer/recruitment_candidates/add_candidate');

            btnAddCandidate.prop('disabled', true);
            btnAddCandidate.html('Agregando');

            $.post(url, data, function(response) {
            
            var status = response.status;
            
            if (!status) {
                btnAddCandidate.prop('disabled', false);
                btnAddCandidate.html('Agregar');
                toastr["error"](response.message);
                return;
            }
            
            $(`<span><i class="glyphicon glyphicon-ok"><i/> Agregado</span>`).insertAfter(btnAddCandidate);
            btnAddCandidate.remove();
            
            loadDataCandidates();
                        
            }, 'json').fail(function(){
                toastr["error"]("¡Ha ocurrido un error!");
            });
        }

        function verifyCandidate() {

            var url = app.siteUrl('employer/recruitment_candidates/verify_candidate');
            var data = {
                job_id: $( "#job_id" ).val(),
                seeker_id: $( "#select-jobseeker-id").val(), 
            };

            $( ".content-selection" ).append('<div id="load-verify-candidate">Verificando candidato...</div>');
            $.post(url, data, function(response) {

            if (response.success) {
                var errors = response.errors;

                $.each(errors, function(i, msgError) {
                    $( ".content-selection" ).append('<div class="content-candidate-error"><i class="glyphicon glyphicon-alert"></i>&nbsp;' + msgError + '</div>');
                });

                if (errors.length == 0) {
                    var html = '<div style="text-align:right;padding-top: 10px;">' +
                                    '<button class="btn btn-sm btn-primary" id="btn-selection-add-candidate">Agregar</button>' + 
                                '</div>';                              
                    $( ".content-selection" ).append(html);
                }
            } else {
                alert('Ha ocurrido un error');
            }

            }, 'json').fail(function(){
                alert("Ha ocurrido un error");
            }).always(function(){
                $( "#load-verify-candidate" ).remove();
            });
        }

        function addCandidatePremiumToStage(candidateId, btnTarget) {

            var data = {
            job_id: $( "#job_id" ).val(),
            from_job_id: $(btnTarget).data('from-job-id'),
            stage: $( "#current_stage" ).val(),
            candidate_id: candidateId 
            };

            btnTarget.prop('disabled', true);

            var url = app.siteUrl('employer/recruitment_candidates/add_premium_candidate');

            $.post(url, data, function(response) {
            
            var status = response.success;
                
            if (!status) {
                btnTarget.prop('disabled', false);
                toastr["error"]("¡Error al agregar el candidato!");
                return;
            }   
            
            toastr["success"]("¡Candidato agregado!");
            var stage = stage || $( "#current_stage" ).val();

            loadDataCandidates(
                $( "#job_id" ).val(),
                stage
            );
            
            }, 'json').fail(function(){
                btnTarget.prop('disabled', false);
                toastr["error"]("¡Error al enviar la solicitud!");
            });
        }

        function searchSeeker(data) {
        
            urlData = {};

            $.map(data, function(row, e){
                urlData[row.name] = row.value;
            });

            var dtSearchCandidate = $( '#tbl-seeker-search-add-results' ).DataTable({
                "language": {
                    "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
                },
                "destroy": true,
                "bAutoWidth": false,
                "deferRender": true,
                "iDisplayLength": 25,
                "bProcessing": true,
                "searching": false,
                "bLengthChange": false,
                ajax: {
                    url: "<?php echo site_url('employer/recruitment_candidates/search_seekers'); ?>",
                    type: 'POST',
                    data: urlData
                },
                columns: [
                    {
                        data: null,
                        render: function(row) {
                            return row.document_type + ' ' + row.document_number;
                            
                        }
                    ,'className': 'style_td text-center'
                    },
                    {data:'email', 'className': 'style_td text-left'},
                    {data:'first_name', 'className': 'style_td text-left'},
                    {
                        data: null,
                        render: function(row) {
                            return row.paternal_last_name + ' ' + row.maternal_last_name;
                        }
                    ,'className': 'style_td text-center'
                    },
                    {
                        data: null,
                        render: function(row) {
                            return `
                                <button class="btn btn-xs btn-primary btn-add-seeker-stage"
                                        data-seeker-id="${row.id}"
                                        data-seeker-email="${row.email}"
                                        data-seeker-document-number="${row.document_number}">
                                    Agregar
                                </button> 
                            `;
                        }
                    ,'className': 'style_td text-center'
                    },       
                ],
                "columnDefs": [ {
                    "targets": 4,
                    "orderable": false
                }]
            });
        }

        $( '#create-seeker-from-data' ).click(function(){
            email =  $( '#modal-confirm-create-seeker input[name="email"]' ).val();

            if ($.trim(email) == '') {
                toastr["error"]("¡Debe ingresar el email del postulante!");
                return;
            }

            btnOrigen = $( '#modal-confirm-create-seeker' ).data('btn-origen');
            documentNumber = $(btnOrigen).data('seeker-document-number');
            
            $( '#modal-confirm-create-seeker' ).modal('hide');
            addCandidateToStage(null, email, documentNumber, btnOrigen);
        });
        
        $('.nav-pills > li > a').on("click",function(e){
            e.preventDefault();

            $( '#tbl-seeker-search-add-results').DataTable().destroy();
            $( '#tbl-seeker-search-add-results tbody').html('');
        });

        $(document).on('submit', '#form-search-seeker-email', function(e){
            e.preventDefault();
            searchSeeker($(this).serializeArray());

            return false;
        });

        $(document).on('submit', '#form-search-seeker-doc', function(e){
            e.preventDefault();
            searchSeeker($(this).serializeArray());
            return false;
        });

        $(document).on('submit', '#form-search-seeker-names', function(e){
            e.preventDefault();

            if ($.trim($('input[name="first_name"]', this).val()) == '' && 
                $.trim($('input[name="paternal_last_name"]', this).val()) == '' && 
                $.trim($('input[name="maternal_last_name"]', this).val()) == '') {
                
                toastr["error"]("¡Debe ingresar una búsqueda válida!");
                return;
            }

            searchSeeker($(this).serializeArray());
            return false;
        });

        $(document).on('click', '.btn-add-seeker-stage', function(){
            var seekerId = $(this).data('seeker-id');
            var email =  $(this).data('seeker-email');
            var documentNumber = $(this).data('seeker-document-number');

            if (!seekerId) {
                $( '#modal-confirm-create-seeker' ).data('btn-origen', $(this));
                $( '#modal-confirm-create-seeker input[name="email"]' ).val(email);
                $( '#modal-confirm-create-seeker' ).modal('show');
                return;
            }

            addCandidateToStage(seekerId, email, documentNumber, this);
        });

        $(document).on("click", ".js-rs-add-candidate-premium", function(){
            var candidateId = $(this).data('candidate-id');
            var btnTarget = $(this);
            addCandidatePremiumToStage(candidateId, btnTarget);
        }); 

        $(document).on("click", "#show-premium-candidates", function(){

            var url = app.siteUrl("employer/recruitment_candidates/get_premium_candidates/" + $( "#job_id").val());
            $( "#rs-premium-candidates" ).load(url, {}, function(result){
                $(this).html(result);
                $(this).show();
                $( "#rs-search-candidates" ).hide();
            });
        });
    });
</script>