<script>
    $(function(){

        $(document).on("click", ".modal-open-interview", function(e){
            e.preventDefault();
        
            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var jobId = $( "#job_id" ).val();

            var url = app.siteUrl('employer/recruitment_interviews/modal_interview/' + jobId + '/' + candidateId);
            $( "#modal-upload-document" ).load(url, function(response){
                $(this).html(response).modal('show');
            });
        });

        $(document).on("click", ".modal-open-interview-comments", function(e){
            e.preventDefault();
        
            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var jobId = $( "#job_id" ).val();

            var url = app.siteUrl('employer/recruitment_interviews/modal_interview_comments/' + jobId + '/' + candidateId);
            $( "#modal-upload-document" ).load(url, function(response){
                $(this).html(response).modal('show');
            });
        });

        $(document).on("click", ".modal-open-seeker-video", function(){ 

            var seekerId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var jobId = $( "#job_id" ).val();

            var data = "candidate_ids[]=" + seekerId + "&job_id=" + $( "#job_id" ).val() + '&massive=0'

            var path = 'employer/recruitment_interviews/video';
            var url = app.siteUrl(path);
            
            $( "#modal-seeker-video" ).load(url, data, function(response){
                $(this).html(response).modal('show');
            });
        });

        $(document).on("click", "#modal-open-request-massive-video", function(){ 

            var checkLength = $( "input[name='candidate_ids[]']:checked" ).length;

            if (checkLength == 0) {
                toastr['error']("¿Debe seleccionar al menos 1 candidato?");
                return;
            }

            var data = $( "#wrapper-candidates :input" ).serialize() + 
                           "&job_id=" + $( "#job_id" ).val() + '&massive=1'

            var path = 'employer/recruitment_interviews/video/';
            var url = app.siteUrl(path);
            
            $( "#modal-seeker-video" ).load(url, data, function(response){
                $(this).html(response).modal('show');
            });
        });

        $( document ).on('click', '.modal-open-share-video-email', function(){
            
            var url = "<?php echo site_url('employer/recruitment_interviews/share_record_video'); ?>"

            var seekerId = $(this).data("seeker-id");
            var data =  "candidate_ids[]=" + seekerId + "&job_id=" + $( "#job_id" ).val() + '&massive=0'

            var path = 'employer/recruitment_interviews/share_record_video';
            var url = app.siteUrl(path);
            
            $( "#modal-share-video-email" ).load(url, data, function(response){
                $(this).html(response).modal('show');
            });
        });

        $(document).on("click", "#modal-open-share-massive-video-email", function(){
           
            var checkLength = $( "input[name='candidate_ids[]']:checked" ).length;

            if (checkLength == 0) {
                toastr['error']("¿Debe seleccionar al menos 1 candidato?");
                return;
            }

            var data = $( "#wrapper-candidates :input" ).serialize() + 
                           "&job_id=" + $( "#job_id" ).val() + '&massive=1'

            var path = 'employer/recruitment_interviews/share_record_video';
            var url = app.siteUrl(path);
            
            $( "#modal-share-video-email" ).load(url, data, function(response){
                $(this).html(response).modal('show');
            });
        });

        $(document).off("click", "#btn-share-add-email");
        $(document).on("click", "#btn-share-add-email", function(){

            var inputEmail = $.trim($( "#input-email" ).val());

            emailRegex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

            if (inputEmail == '') {
                toastr["error"]("¡Debe ingresar un correo!");
                return;
            }

            if (!emailRegex.test(inputEmail)) {
                toastr["error"]("¡Debe ingresar un correo con formato válido!");
                return;
            }

            $( "#tbl-list-email tbody" ).prepend(`
                <tr>
                    <td>
                        <input name='emails[]' 
                               type='hidden' 
                               value="${inputEmail}">
                        ${inputEmail}
                    </td>
                    <td>
                        <button class="btn btn-xs btn-danger"
                                type="button" 
                                onclick="$(this).closest('tr').remove();">
                            Quitar
                        </button>
                    </td>
                </tr>`
            );

            $( "#input-email" ).val("");
        });

        $(document).off("submit", "#form-share-interview-video");
        $(document).on("submit", "#form-share-interview-video", function(e){

            e.preventDefault();

            if ($( "#tbl-list-email tbody tr" ).length == 0) {
                toastr["error"]("¡Por favor agregue al menos una dirección de correo!");
                return;
            }

            if (!window.confirm("¿Desea compartir la grabación de video?")) {
                return;
            }

            var url = $(this).prop('action');
        
            $( ".form-submit" ).prop('disabled', true);
            $( ".form-submit" ).val("Enviando...");
            $( ".form-cancel" ).prop('disabled', true);

            var data = $(this).serialize();

            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    toastr["success"]("¡Grabación de video compartida!");
                    $( "#modal-share-video-email" ).modal('hide');
                } else {
                    toastr["error"]("¡Ocurrio un error al compartir!");
                }
            }, 'json')
            .fail(function(){
                alert("¡Ha ocurrido un error!");
            }).always(function(){
                $( ".form-submit" ).prop('disabled', false);
                $( ".form-submit" ).val("Compartir");
                $( ".form-cancel" ).prop('disabled', false);
            });

            return false;
        });

        $(document).on('click', '.remove-interview-video', function(e) {

            e.preventDefault();

            if (!window.confirm("¿Está seguro de eliminar el video?")) {
                return;
            }

            var btn = $(this);
            var id = $(this).data('id');
            var url = "<?php echo site_url('employer/recruitment_interviews/remove_video'); ?>";
            var data = {
                'id': id
            };

            $.post(url, data, function(response) {
                if (!response.success) {
                    toastr["error"]("¡Error al eliminar el video!");
                    return;                 
                }

                toastr["success"]("¡Video ha sido eliminado!");

                var seekerId = response.seeker_id;

                var data = "candidate_ids[]=" + seekerId +  
                               "&job_id=" + $( "#job_id" ).val() + '&massive=0'

                var path = 'employer/recruitment_interviews/video/';
                var url = app.siteUrl(path);

                $( "#modal-seeker-video" ).load(url, data, function(response){
                    $(this).html(response).modal('show');
                });

            }, 'json');
        });

        $(document).on("click", "#modal-open-interview-video-indications", function(){

            var path = 'employer/recruitment_interviews/record_video_indications/' + $(this).data('job-id');
            var url = app.siteUrl(path);
            
            $.get(url, {}, function(response){
                $( "#modal-interview-video-indications" ).html(response).modal('show');
            });
        });
    });
</script>