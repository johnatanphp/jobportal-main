<div id="modal-rys-form-list" class="modal" role="dialog">
    <div class="modal-dialog" style="margin-top: 5%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Formularios y encuestas</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <button id="btn-assign-forms" class="btn btn-xs btn-primary pull-right" style="display: none;">
                            <span class="glyphicon glyphicon-send"></span>
                            Enviar a candidatos
                        </button>
                    </div>
                </div>
                <div>
                    <?php 
                        $count_seeker_stage = count($this->Recruitment_candidate->get_available_candidates_by_stage($job->ID, $current_stage));
                    ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Formulario</th>
                                <th>Asignaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stage_forms as $form): ?>
                                <?php 
                                    $count_seeker_unassigned = $this->Rys_form_seeker->count_sekeer_form_unassigned($job->ID, $form->form_id, $current_stage);
                                ?>
                                <tr>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                                <span class="glyphicon glyphicon-option-vertical"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-left">

                                                <?php if ($rs_process->sts == 'active'): ?>
                                                    <?php if ($count_seeker_unassigned > 0 && $count_seeker_unassigned < $count_seeker_stage): ?>
                                                        <li style="display: none;">
                                                            <a class="btn-assign-form" 
                                                               href="#" 
                                                               data-form-id="<?php echo $form->form_id; ?>" 
                                                               data-send-type="2" 
                                                               data-total-seeker="<?php echo $count_seeker_stage; ?>">
                                                                Enviar a postulantes sin asignar (<?php echo $count_seeker_unassigned; ?>)
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($count_seeker_stage > 0): ?>
                                                        <li>
                                                            <a  class="btn-assign-form" 
                                                                href="#" data-form-id="<?php echo $form->form_id; ?>" 
                                                                data-send-type="1"
                                                                data-total-seeker="<?php echo $count_seeker_stage; ?>">
                                                                Enviar / Reenviar a todos (<?php echo $count_seeker_stage; ?>)
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="btn-unassign-form" 
                                                               href="#" 
                                                               data-form-id="<?php echo $form->form_id; ?>"
                                                               data-total-seeker="<?php echo $count_seeker_stage; ?>">
                                                                Quitar asignaciones
                                                            </a>
                                                        </li>
                                                        <div class="dropdown-divider"></div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                
                                                <li>
                                                    <a href="<?php echo site_url('employer/report_excel_rys_form_answers/download/' . $form->form_id . '/' . $job->ID . '/' . $current_stage); ?>   "
                                                       target= "_blank" 
                                                       title="">
                                                        Descargar respuestas
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $form->form_name; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php echo ($count_seeker_stage - $count_seeker_unassigned)  . '/' . $count_seeker_stage; ?>        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
