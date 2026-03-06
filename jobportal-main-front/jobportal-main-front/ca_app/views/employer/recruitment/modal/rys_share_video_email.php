<style type="text/css">
    .list-options {
        list-style: none;
        padding-bottom: 3px;
    }

    #tbl-add-email td {
        padding: 5px;
    }

</style>
<div class="modal-dialog" style="width: 55%;">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">
                <?php if ($massive): ?>
                    Compartir Grabación de los  postulantes
                <?php else: ?>
                    Compartir Grabación del postulante
                <?php endif; ?>
            </h4>
        </div>
        <div class="modal-body">
            <div class="formwraper">
                <div style="padding: 10px;">

                    <?php if ($count_share_links == 0): ?>
                        <div  class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    ¡No se encontró videos para compartir!
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($count_share_links > 0): ?>
                        <div  class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    ¡Se enviaran <?php echo $count_share_links; ?> video(s) al listado de correos ingresados!
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">        
                        <div class="col-md-6">
                            <table id="tbl-add-email" width="100%">
                                <tr>
                                    <td>
                                        <input id="input-email" 
                                               type="text" 
                                               class="form-control"
                                               placeholder="Agregar correo">
                                    </td>
                                    <td>
                                        <button id="btn-share-add-email"
                                                type="button" 
                                                class="btn btn-xs btn-primary">
                                            Agregar
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            ¡Para compartir el video ingresa los correos y haga clic en Compartir, esto enviará un link a los destinatarios!
                        </div>
                    </div>
                    <?php echo form_open('employer/recruitment_interviews/share_record_video', ['id' => 'form-share-interview-video']); ?>

                        <?php foreach ($seeker_ids as $seeker_id): ?>
                            <input type="hidden" name="seeker_ids[]" value="<?php echo $seeker_id; ?>">
                        <?php endforeach; ?>
                        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                        <div class="row">
                            <div class="col-md-6">

                                <br />
                                <table id="tbl-list-email" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Email</th>
                                            <th width="50"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" 
                                        class="btn btn-primary pull-right form-cancel" 
                                        data-dismiss="modal" style="margin-left: 5px;">
                                    Cancelar    
                                </button>
                                <?php if ($count_share_links > 0): ?>
                                    <input type="submit" 
                                           value="Compartir" 
                                           class="btn btn-primary pull-right form-submit">
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
