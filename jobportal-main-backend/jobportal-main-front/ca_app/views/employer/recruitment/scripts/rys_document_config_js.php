<script type="text/javascript">
    // $(function(){
    //     $( '#btn-rys-document-config' ).click(function(){
    //         var url = "<?php echo site_url('employer/recruitment/process_documents/index/'); ?>" + $(this).data('job-id');

    //         $( '#modal-recruitment-document-config' ).modal('show');
    //         $( '#modal-recruitment-document-config .modal-body' ).html('Espere un momento...');

    //         $.get(url, {}, function(response) {
    //             $( '#modal-recruitment-document-config .modal-body' ).html(response);
    //         });
    //     });

    //     $(document).on('change', '.rys-document-config-check', function(){
            
    //         var url = "<?php echo site_url('employer/recruitment/process_documents/save'); ?>";

    //         var document_ids = [];

    //         $( '.rys-document-config-check:checked' ).each(function(i, e){
    //             document_ids.push($(e).val());
    //         });

    //         var data = {
    //             'job_id': $(this).data('job-id'),
    //             'document_ids': document_ids,
    //         }
    //         $.post(url, data, function(response){}, 'json');
    //     });

    //     $( '#modal-recruitment-document-config' ).on('hidden.bs.modal', function () {
    //         //reloadPage();
    //         reloadDataCandidates();
    //     })
    // });
</script>