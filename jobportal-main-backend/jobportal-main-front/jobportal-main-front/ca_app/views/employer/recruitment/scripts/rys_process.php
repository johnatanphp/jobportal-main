<script type="text/javascript">
    $(function(){
        function suspendProcess() {
            var data = {
                job_id: $( "#job_id" ).val(),
                note: $( "#suspend-process-note" ).val()
            };
            var btnSuspendProcess = $( "#btn-suspend-process" );

            btnSuspendProcess.prop('disabled', true);

            var url = app.siteUrl('employer/recruitment_processes/suspend_process');
            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    toastr["success"]("¡El proceso ha sido suspendido!");
                    btnSuspendProcess.hide();
                    window.location.reload();
                } else {
                    toastr["error"](response.message_error);
                }
            }, 'json')
            .fail(function() {
                toastr["error"]("¡Ha ocurrido un error!");
            }).always(function(){
                btnSuspendProcess.prop('disabled', false);
            });
        }

        function resumeProcess() {
            var data = {
                job_id: $( "#job_id" ).val(),
            };
            var btnResumeProcess = $( ".btn-resume-process" );
            btnResumeProcess.prop('disabled', true);

            var url = app.siteUrl('employer/recruitment_processes/resume_process');

            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    toastr["success"]("¡El proceso ha sido reanudado!");
                    btnResumeProcess.hide();
                    window.location.reload();
                } else {
                    toastr["error"](response.message_error);
                }
            }, 'json')
            .fail(function() {
                toastr["error"]("¡Ha ocurrido un error!");
            }).always(function(){
                btnResumeProcess.prop('disabled', false);
            });
        }

        function finishProcess() {
            
            var data = {
                job_id: $( "#job_id" ).val(),
                note: $( "#finish-process-note" ).val()
            };

            var btnFinishProcess = $( "#btn-finish-process" );
            btnFinishProcess.prop('disabled', true);

            var url = app.siteUrl('employer/recruitment_processes/finish_process');
            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    btnFinishProcess.hide();
                    toastr["success"]("¡El proceso ha sido terminado!");
                    window.location.reload();
                } else {
                    toastr["error"](response.message_error);
                }
            }, 'json')
            .fail(function() {
                toastr["error"]("¡Ha ocurrido un error!");
            }).always(function(){
                btnFinishProcess.prop('disabled', false);
            });
        }

        $(document).on("click", "#modal-open-suspend-process", function(e){
            e.preventDefault();
            $( "#modal-suspend-process" ).modal('show');
        });

        $( "#btn-suspend-process" ).click(function(){
            suspendProcess();
        });

        $( ".btn-resume-process" ).click(function(){
            if (window.confirm("¿Está seguro de reanudar el proceso?")) {
                resumeProcess();
            }
        });

        $(document).on("click", "#modal-open-finish-process", function(e){
            e.preventDefault();
            $( "#modal-finish-process" ).modal('show');
        });

        $(document).on("click", "#btn-finish-process", function(){
            finishProcess();
        });

        $(document).on("click", "#show-rs-search-candidates", function(){
            $( "#rs-premium-candidates" ).hide();
            $( "#rs-search-candidates" ).show();
        });
        
        $(document).on("click", ".seeker-view-profile", function(e) {
            e.preventDefault();

            url = $(this).data('url-detail');
            name = $(this).data('name');
            url_pic = $(this).data('url-pic');
            image_loading_url = "<?php echo img_loading_url(); ?>";

            $( '#modal-view-profile .modal-content' ).html(`
                <div class="modal-body">
                    <div style="padding:20px 7px;border:1px solid #cccccc;">
                        <div class="row">
                            <div class="col-xs-2">
                                <a href="#">
                                    <img width="100" src="${url_pic}">
                                </a>
                            </div>
                            <div class="col-xs-10">
                                <h4 style="font-weight: bold;padding: 5px 0;text-decoration:underline;display:inline;">${name}</h4>
                                <img src="${image_loading_url}" style="width:24px; height:24px;display:inline;margin: 0 5px;">
                            </div>               
                        </div>                    
                    </div>
                </div> 
            `);
            $( '#modal-view-profile' ).modal('show');

            $( '#modal-view-profile .modal-content' ).load(url, function(response) {
                $(this).html(response);
            });
            
            return false;
        });

        $( "#select2-rrhh-users").select2({
            placeholder: 'Seleccione'
        });
    });
</script>