<script>
    $(function () {

        function loadCandidatesBlock(job_id, stage)
        {
            var url = "<?php echo site_url('employer/recruitment_candidates/search_candidates_block'); ?>";
            var data = {
                job_id: job_id,
                stage: stage
            };

            var url_image_loading = "<?php echo img_loading_url(); ?>";
            $( ".content-main-load" ).html(`<img src="${url_image_loading}" style="width:24px; height:24px;"/>`);
            $( ".content-main-load" ).css({"text-align": "center"});
            
            $.post(url, data, function(response) {
                $( "#current_stage" ).val(stage);
                $( ".content-main-load" ).html(response.data);
                $( ".content-main-load" ).css({"text-align": "left"});
            }, 'json')
            .fail(function(){
                toastr["error"]("¡Ha ocurrido un error! \n Cod: " + e.status + " - " + e.statusText + "!")
            });
        }

        $( document ).on('click', '.btn-show-blocks-view', function(){
            loadCandidatesBlock($( "#job_id" ).val(), $( "#current_stage" ).val());
        });

        $( document ).on('click', '.btn-show-candidates-view', function(){                
            loadDataCandidates( {
                'job_id':  $( "#job_id").val(),
                'stage': $( "#current_stage" ).val()
            });
        });

        $( document ).on('click', '.block-candidates', function(){                
            loadDataCandidates( {
                'job_id':  $( "#job_id").val(),
                'stage': $( "#current_stage" ).val(),
                'creation_date': $(this).data('creation-date'),
                'update_date': $(this).data('update-date'),
                'label': $(this).data('block')
            });
        });

        $( document ) .on( 'click', '#change-block-candidates', function() {
            
            if ($( "#wrapper-candidates :checked" ).length == 0) {
                toastr["error"]('Debe seleccionar al menos 1 candidato');
                return;
            }
                
            modal = $( '#modal-blocks-candidates');

            if (!modal.length) {
                modal = $( '<div id="modal-blocks-candidates" class="modal" role="dialog">').appendTo($('body'));
            }

            modal.html(`
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Espere un momento...</h4>
                        </div>
                    </div>
                </div>`
            ).modal('show');

            var url = "<?php echo site_url('employer/recruitment/jobseeker_blocks/list'); ?>";
            var data = {
                'job_id' : $( '#job_id').val(),
                'stage_id' : $( '#current_stage' ).val()
            };
            $.post(url, data, function(res){
                modal.html(res);
            });
        });

        $( document ).on('submit', '.form-move-seeker-blocks', function(e){
            e.preventDefault();

            var url = $(this).prop('action');
            var data = $( "#wrapper-candidates :checked" ).serialize() + "&" + $(this).serialize();

            $.post(url, data, function(res){
                if (!res.success) {
                    toastr["error"](res.message);
                    return;
                }

                toastr["success"](res.message);
                $( '#modal-blocks-candidates').modal('hide');
                loadDataCandidates();
            
            }, 'json');
            return false;
        })
    });
</script>
