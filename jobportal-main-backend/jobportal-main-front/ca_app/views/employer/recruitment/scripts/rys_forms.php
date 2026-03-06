<script type="text/javascript">
    $(function(){

        $(document).on("click", ".modal-open-sworn-declaration", function() {

            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var jobId = $( "#job_id" ).val();
            var formId = $(this).data('form-id');
            var formName = $(this).data('form-name');

            var path = 'general/rys/form_seekers/show_answer/' + formId + '/' + jobId + '/' + candidateId;
            var url = app.siteUrl(path);

            $( "#modal-sworn-declaration" ).load(url, function(response) {
                $(this).html(response).modal('show');
                $( "#modal-sworn-declaration .modal-title" ).html(formName);
            });
        });

        $(document).on("click", ".btn-assign-form", function(e) {
            
            e.preventDefault();

            if (!window.confirm("¿Está seguro de enviar / notificar este formulario a los postulantes de esta etapa?")) {
                return;
            }

            var data = {
                job_id: $( "#job_id" ).val(),
                form_id: $(this).data('form-id'),
                send_type: $(this).data('send-type'),
                stage: $( "#current_stage" ).val()
            };
            
            var liParent = $(this).closest('li');
            var td = $(this).closest('tr').find('td:eq(1)');

            var url = app.siteUrl('employer/rys_form_seekers/assign_form');
            var countTotalSeeker = $(this).data('total-seeker');

            $.post(url, data, function(response) {
                toastr["success"]("¡Formularios y encuestas enviados!");

                if (data.send_type == 2) {
                    liParent.remove();
                }

                td.html(countTotalSeeker + '/' + countTotalSeeker);
            })
            .fail(function(e) {
                alert("¡Ha ocurrido un error!");
            });
        });

        $(document).on("click", ".btn-unassign-form", function(e) {
            
            e.preventDefault();

            if (!window.confirm("¿Está seguro de quitar las asignaciones de este fomulario?")) {
                return;
            }

            var data = {
                job_id: $( "#job_id" ).val(),
                form_id: $(this).data('form-id'),
                stage: $( "#current_stage" ).val()
            };

            var countTotalSeeker = $(this).data('total-seeker');

            var td = $(this).closest('tr').find('td:eq(1)');

            var url = app.siteUrl('employer/rys_form_seekers/unassign_form');
            
            $.post(url, data, function(response) {
                
                toastr["success"]("¡Asignación eliminada!");
                
                td.html(response.assign_rows + '/' + countTotalSeeker);

            }, 'json')
            .fail(function(e) {
                alert("¡Ha ocurrido un error!");
            });
        });
    })
</script>