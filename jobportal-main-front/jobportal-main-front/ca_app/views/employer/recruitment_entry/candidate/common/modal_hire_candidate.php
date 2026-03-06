<!-- Modal -->
<div id="modal-hire-candidate" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <style>
        .info-required {
            text-align: right;
            font-style: italic;
            font-size: 12px;
            color: #666;
            padding: 0 0 10px 0;
        }

        .info-required span {
            color: red;
        }

        .info-required span {
            color: red;
        }

        #form-hire-candidate label span {
            color: red;
        }
    </style>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content"></div>
    </div>

    <script type="text/javascript">
    $(function(){
        $( '#btn-confirm-hire-candidate' ).click(function(e) {
            e.preventDefault();
            
            $( '#modal-hire-candidate .modal-content' ).html(`
                <div class="modal-header">
                <h4 class="modal-title">Espere un momento...</h4>
                </div>
            `);
            $( '#modal-hire-candidate' ).modal('show');
            const processId = "<?php echo $process->id; ?>";
            const seekerId = "<?php echo $candidate->ID; ?>";
            const url = "<?php echo site_url('employer/recruitment_entry/recruitment_candidates/modal_hire_candidate'); ?>" + `/${processId}/${seekerId}`;
            
            $.get(url, {}, function(res){
                $( '#modal-hire-candidate .modal-content' ).html(res);
            });
            return false;
        });
    });
    </script>
</div>
