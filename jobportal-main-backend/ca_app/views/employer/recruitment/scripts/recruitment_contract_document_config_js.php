<script type="text/javascript">
    $(function(){
        $( '#btn-recruitment-contract-document-config' ).click(function(){
       
            var url = "<?php echo site_url('employer/recruitment/process_contract_documents/index/'); ?>" + $(this).data('job-id');

            $( '#modal-recruitment-contract-document-config' ).modal('show');
            $( '#modal-recruitment-contract-document-config .modal-body' ).html('Espere un momento...');

            $.get(url, {}, function(response) {
                $( '#modal-recruitment-contract-document-config .modal-body' ).html(response);
            });
        });

        $(document).on('change', '.recruitment-contract-document-config-check', function(){
            
            var url = "<?php echo site_url('employer/recruitment/process_contract_documents/save'); ?>";

            var document_ids = [];

            $( '.recruitment-contract-document-config-check:checked' ).each(function(i, e){
                document_ids.push($(e).val());
            });

            var data = {
                'job_id': $(this).data('job-id'),
                'document_ids': document_ids,
            };

            $.post(url, data, function(response){}, 'json');
        });
    });
</script>