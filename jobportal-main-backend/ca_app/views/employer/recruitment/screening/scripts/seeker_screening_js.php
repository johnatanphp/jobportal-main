<script type="text/javascript">
    $(function(){
        $(document).on("click", ".modal-open-seeker-screening", function(){

            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");

            if(candidateId == undefined) {
                candidateId =  $(this).data("candidate-id");
            }

            var jobId = $( "#job_id" ).val();
            var rsDocument = $(this).data('rs-document');
            var path = 'employer/recruitment/screening/modal_documents/' + jobId + '/' + candidateId + '/' + rsDocument;
            var url = app.siteUrl(path);
            $( "#modal-" ).load(url, function(response){
                $(this).html(response).modal('show');
            });

            $( '#modal-seeker-screening' ).data('seeker-id', candidateId);
            $( '#modal-seeker-screening' ).modal('show');
            

            loadScreeningResults();
        });

        $( '#btn-confirm-create-screening' ).click(function(){
            $( '#modal-confirm-create-screening' ).modal('show');

            var url = "<?php echo site_url('employer/recruitment/screening/create_form'); ?>";
            var data = {
                'job_id': $( "#job_id" ).val(),
                'seeker_id': $( '#modal-seeker-screening' ).data('seeker-id'),
            }
            
            var contentForm = $( '.screening-create-form', '#modal-confirm-create-screening' );

            var url_image_loading = "<?php echo img_loading_url(); ?>";
            contentForm.html(`<img src="${url_image_loading}" style="width:24px; height:24px;"/>`);
            contentForm.css({"text-align": "center"});

            $.post(url, data, function(response){
               contentForm.html(response);
               contentForm.css({"text-align": "left"});
            });            
        });

        $( document ).on('submit', '#form-create-screening', function(e){

            e.preventDefault();

            var url = $(this).prop('action');
            var data = $(this).serialize();

            btn = $(this).find('.btn-submit');
            $('button', '#modal-confirm-create-screening .modal-footer').prop('disabled', true);
            btn.text('Creando...');
            
            $.post(url, data, function(response){

                if (response.status) {
                    $('#modal-confirm-create-screening').modal('hide');
                    loadScreeningResults();
                    toastr["success"](response.message);
                }

                if (!response.status) {
                    toastr["error"](response.message);
                }

            }, 'json').always(function(){
                $('button', '#modal-confirm-create-screening .modal-footer').prop('disabled', false);
                btn.text('Crear');
            });

            return false;
        });

        $( '#btn-confirm-upload-screening' ).click(function(){
            $( '#modal-confirm-upload-screening' ).modal('show');
        });

        function loadScreeningResults()
        {
            var url = "<?php echo site_url('employer/recruitment/screening/get_files'); ?>";
            var data = {
                'job_id': $( "#job_id" ).val(),
                'seeker_id': $( '#modal-seeker-screening' ).data('seeker-id'),
            }
            
            var contentResult = $( '.screening-results', '#modal-seeker-screening' );

            var url_image_loading = "<?php echo img_loading_url(); ?>";
            contentResult.html(`<img src="${url_image_loading}" style="width:24px; height:24px;"/>`);
            contentResult.css({"text-align": "center"});

            $.post(url, data, function(response){
                contentResult.html(response);
            });
        }

        function showSelectorFiles() {
            $( "#input-attach-file" ).remove();
            var mime_types = ".jpg,.png,.docx,.pdf";
            var inputFile = $(`<input id="input-attach-file" type="file" name="file" style="display:none;" accept="${mime_types}">`);
            $( ".content-attach-files" ).append(inputFile);
            
            $(inputFile).fileupload({
                dataType: 'json',
                url: "<?php echo site_url('employer/recruitment/screening/upload'); ?>",
                formData: {
                    'job_id': $( "#job_id" ).val(),
                    'seeker_id': $( '#modal-seeker-screening' ).data('seeker-id'),
                },
                autoUpload: true,
                add: function (e, data) {
                    data.context = $( '.content-attach-view' );      
                    data.submit();
                },
                progress: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    data.context.html(`Cargando(${progress}%)`);
                }, 
                done: function (e, data) {
                    var item = data.context;
                    data.context.html("");

                    if (!data.result.status) {
                        toastr["error"](data.result.message);
                        item.remove();
                        return;
                    }

                    loadScreeningResults();
                    $( '#modal-confirm-upload-screening' ).modal('hide');
                }
            });
            
            inputFile[0].click();
        }

        function uploadRemove(fileId) { 

            var url = "<?php echo site_url('employer/recruitment/screening/upload_remove'); ?>";
            var data = {
                file_id: fileId
            };

            $.post(url, data, function(response){
                var success = response.status;
                if (success) {
                    loadScreeningResults();
                    return;
                }

                toastr["error"](response.message);
            
            }, 'json').fail(function(){
                toastr["error"]("Error al remover el archivo");
            });
        }

        $( "#btn-attach-files" ).click(function(){
            showSelectorFiles();
        });

        $(document).on('click', '.screening-upload-remove', function(){
            var fileId = $(this).data('file-id');
            uploadRemove(fileId);
        });

        $( '#btn-upload-screennig' ).click(function(){
            showSelectorFiles();
        });
    });
</script>