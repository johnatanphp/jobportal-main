<div id="modal-recruitment-document-stage-config" class="modal" role="dialog">
    <div class="modal-dialog" style="max-width: 400px;margin: 0 auto;margin-top: 5%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Documentos del reclutamiento</h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $( '#btn-rys-document-config' ).click(function(){
            var url = "<?php echo site_url('employer/recruitment/process_document_stages/index'); ?>";

            $( '#modal-recruitment-document-stage-config' ).modal('show');
            $( '#modal-recruitment-document-stage-config .modal-body' ).html('Espere un momento...');

            data = {
                'job_id': $(this).data('job-id'),
                'stage_id': $('#current_stage').val()
            }

            $.get(url, data, function(response) {
                $( '#modal-recruitment-document-stage-config .modal-body' ).html(response);
            });
        });

        $(document).on('change', '.rys-document-config-check', function(){
            
            var url = $(this).closest('form').prop('action');
            var data = $(this).closest('form').serialize();
            
            $.post(url, data, function(response){}, 'json');
        });

        $( '#modal-recruitment-document-stage-config' ).on('hidden.bs.modal', function () {
            reloadDataCandidates();
        })
    });
</script>