<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Indicaciones de grabación de video</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">     
                            <?php echo form_open(site_url('employer/recruitment_interviews/record_video_indications/' . $job_id), [
                                    'id' => 'form-indications-video'
                                ]); 
                            ?>
                                <label>Indicaciones</label>
                                <textarea id="text-indications" 
                                          class="form-control" 
                                          rows="8" 
                                          name="indications"><?php echo @$rv_indications->video_indications; ?></textarea>
                                <br >
                                <button
                                        class="btn btn-primary pull-right">
                                    Guardar
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $( "#form-indications-video" ).submit(function(e) {
            
            e.preventDefault();

            if ($.trim($( "#text-indications" ).val()) == '') {
                toastr["error"]("¡Por favor agregue una indicación!");
                return;
            }

            var url = $(this).prop('action');
            var data = $(this).serialize();

            var btnRequest = $(this);
            btnRequest.prop('disabled', true);
 
            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    toastr["success"]("¡Indicaciones guardada!");
                    $( "#modal-seeker-video" ).modal('hide');
                } else {
                    toastr["error"]("¡Ocurrio un error al procesar la solicitud!");
                }
            }, 'json')
            .fail(function(){
                alert("¡Ha ocurrido un error!");
            }).always(function(){
                btnRequest.prop('disabled', false);
            });

            return false;
        });
    })
</script>