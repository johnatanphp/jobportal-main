<script type="text/javascript">
    $(function(){

        $(document).on("click", ".modal-open-schedule-evaluation", function(e){
            e.preventDefault();
        
            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var jobId = $( "#job_id" ).val();

            var url = app.siteUrl('employer/recruitment_evaluations/modal_schedule_evaluation/' + jobId + '/' + candidateId);
            $( "#modal-upload-document" ).load(url, function(response){
                $(this).html(response).modal('show');
            });
        });

        $(document).on("click", ".modal-open-rs-documents", function(){

            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var jobId = $( "#job_id" ).val();
            var rsDocument = $(this).data('rs-document');
            var path = 'employer/recruitment_attached_documents/modal_documents/' + jobId + '/' + candidateId + '/' + rsDocument;
            var url = app.siteUrl(path);
            $( "#modal-upload-document" ).load(url, function(response){
                $(this).html(response).modal('show');
            });
        });

        $(document).on("click", ".modal-open-rs-other-documents", function(){

            var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var stage = $( "#current_stage" ).val();

            var jobId = $( "#job_id" ).val();
            var rsDocument = $(this).data('rs-document');
            var path = 'employer/recruitment_attached_documents/modal_other_documents/' + jobId + '/' + candidateId + '/' + stage;
            var url = app.siteUrl(path);
            $( "#modal-upload-document" ).load(url, function(response){
                $(this).html(response).modal('show');
            });
        });
    });
</script>