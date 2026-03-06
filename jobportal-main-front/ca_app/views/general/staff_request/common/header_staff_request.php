<?php 
  $employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
 
  //Si el empleador en session es administrador
  $employer_is_admin = $employer ? $employer->is_admin == 'yes' : false;

  //Si la solicitud esta activa
  $request_sts_active = $request->sts_process != 'suspended' && 
                        $request->sts_process != 'canceled' && 
                        $request->sts_process != 'rejected';      

  //Si el usuario tiene la unidad de negocio asignada de la solicitud
  $employer_manage_permission = is_staff_request_user_manage($this->session->userdata('user_id'), $request->ID);

  //Si el usuario en session tiene la solicitud asignada
  $assignment_permission = count(array_filter($request_assigned_employers, function($assigned_employer) {
    return $this->session->userdata('user_id') == $assigned_employer->employer_ID;
  })) > 0;

  $stage_items = get_RS_stages();

  $gantt_menu_list = [];
  foreach ($request_gantt as $gantt_item) {
    $gantt_menu_list[$gantt_item->type_id] = $gantt_item;
  }
?>
<div class="__wrapper-request-head sys-banner">
  
  <h1><?php echo $request->job_title; ?></h1>
  
  <div class="row">
    <div class="col-sm-8" style="padding-bottom: 10px;">
      <div class="sys-content-list">
        <ul class="sys-list">
         <li>
            <label>
              Código solicitud:
              <?php echo $request->ID; ?>&nbsp;&nbsp;
            </label>
          </li>
          <?php if ($request->type_requirement != null): ?>
            <li>
              <label>
                Tipo de requerimiento:
                <?php echo $request->type_requirement; ?>
              </label>
            </li>
          <?php endif; ?>
          <li>
            <label>
              Solicitud creada el:
              <?php echo _date_locale_format(strtotime($request->creation_date), 'dd MMM y'); ?>&nbsp;&nbsp;
            </label>
          </li>
          <li>
              <label>
                Modelo:
                <?php echo $request->request_model_id; ?>
              </label>
            </li>
          <?php if (count($profile_survey_logs) > 0): ?>
            <li>
              <label>
                Levantamiento de perfil: 
                <a href="#" class="show-profile-survey">Ver historial</a>
              </label>
            </li>
          <?php endif; ?>

          <li>
            <label>
              Solicitante: 
              <?php echo $request->recruiter_first_name; ?>&nbsp;&nbsp;
            </label>
          </li>
          <?php if (!is_null($request->employer_ID) || count($request_assigned_employers) > 0): ?>
            <li>
              <label>
                Empleadores asignados:  <a href="#"
                                        class="sr-show-assigned-employers"
                                        data-request-id="<?php echo $request->ID; ?>">Ver</a>
              </label>
                <?php if ($this->session->userdata('current_profile_id') == 1 && 
                          $request_sts_active && in_array($request->request_model_id, [1, 2, 3])): ?>
                  <div class="dropdown dropdown-options-job pull-right">
                    <button class="btn btn-sm dropdown-toggle" style="padding: 1px;background: border:none;background: transparent; font-weight: bold;" type="button" data-toggle="dropdown">
                      <i class="glyphicon glyphicon-option-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                          <a href="<?php echo site_url('employer/staff_requests/modal_reassign_employer/' . $request->ID); ?>" class="btn-assign-employer">
                            Reasignar solicitud
                          </a>
                        </li>
                        <?php if ($request->sts_process == 'assigned'): ?>
                          <li>
                            <a id="btn-del-assign-employer" href="#" data-request-id="<?php echo $request->ID; ?>">
                              Eliminar asignación
                            </a>
                          </li>
                        <?php endif; ?>
                    </ul>
                  </div>
              <?php endif; ?>
            </li>
          <?php endif; ?>

          <?php foreach ($request_gantt as $gantt_row): ?>
            <li>
              <label>
                Gantt de <?php e(mb_strtolower($gantt_row->gantt_type_name)); ?> creado
                <?php if ($gantt_row->creation_date != null): ?>
                  <?php echo ' el: ' . _date_locale_format(strtotime($gantt_row->creation_date), 'dd MMM y'); ?>&nbsp;&nbsp;
                <?php endif; ?>
              </label>
              <?php if ($this->session->userdata('current_profile_id') == 1): ?>
                <div class="dropdown dropdown-options-job pull-right">
                  <button class="btn btn-sm dropdown-toggle" style="padding: 1px; border:none; text-decoration: underline;background: transparent; font-weight: bold;" type="button" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-option-vertical"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right">
                      <?php if ($assignment_permission &&
                        in_array($request->request_model_id, [1, 2, 3])): ?>
                        <li>
                          <a href="<?php echo site_url('employer/staff_request/gantt_activities/' . $gantt_row->request_ID . '/' . $gantt_row->type_id); ?>">
                            Editar
                          </a>
                        </li>
                      <?php endif; ?>
                      <li>
                        <a href="<?php echo site_url('employer/staff_request/gantt_activities/export/' . $gantt_row->ID); ?>">
                          Descargar
                        </a>
                      </li>
                  </ul>
                </div>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
          
          <?php if ($request->sts_process == 'published'): ?>
            <li>
              <label>
                <?php echo 'Empleo creado el ' . _date_locale_format(strtotime($request_posted_job->dated), 'dd MMM y'); ?>
                &nbsp;
                <a href="<?php echo site_url('jobs/' . $request_posted_job->job_slug);?>">Ver empleo</a>
              </label>
            </li>

            <?php if (in_array($request->request_model_id, [1, 2, 3])): ?>
              <li>
                <label>
                    Proceso R&S:
                    <?php 
                      if ($rs_process) {
                        $rs_text = "#" . $rs_process->id . ' - ' . rs_stage_status_process($rs_process->sts_stage) . ' - ' . rs_process_status_text($rs_process->sts);             
                      } else {
                        $rs_text = "No iniciado";
                      }
                      
                      if ($this->session->userdata('current_profile_id') == 1 && $request->job_ID != null) {
                        $rs_text = "<a href='" . site_url('employer/recruitment_processes/' . $request->job_ID) . "'>" . $rs_text. "</a>";
                      }
                    ?>                
                    <?php echo $rs_text; ?>
                </label>
              </li>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($request->sts_process == 'rejected'): ?>
            <li>
              <label>
                <?php echo 'Motivo del rechazo: ' . $request->reason_rejection; ?>
              </label>
            </li>
          <?php endif;  ?>

          <?php if ($request->sts_process == 'canceled'): ?>
            <li>
              <label>
                <?php echo 'Motivo de la cancelación: ' . $request->reason_cancellation; ?>
              </label>
            </li>
          <?php endif;  ?>
        </ul>
      </div>
    </div>
    <div class="col-sm-4">
      <div id="wrapper-request-col-2">
        <ul> 
        <!-- Show options only for employers -->   
          <?php if ($this->session->userdata('current_profile_id') == 1 && 
                    $request_sts_active && 
                    $assignment_permission && 
                    count($request_gantt) < count($gantt_types) && in_array($request->request_model_id, [1, 2, 3])):        
          ?>
            <li style="padding: 3px 0;">
              <a class="btn btn-xs btn-primary-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Crear Gantt <i class="glyphicon glyphicon-chevron-down"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-right" style="top: 40px; min-width: 0;">
              
                <?php foreach ($gantt_types as $gantt_type_row): ?>
                  <?php if (!isset($gantt_menu_list[$gantt_type_row->id])): ?>
                    <li>
                      <a href="<?php echo site_url('employer/staff_request/gantt_activities/' . $request->ID . '/' . $gantt_type_row->id); ?>">
                        <?php e($gantt_type_row->name); ?>
                      </a>
                    </li>
                  <?php endif; ?>
                <?php endforeach;?>
              </ul>
            </li>
          <?php endif; ?>
            
          <?php if ($this->session->userdata('current_profile_id') == 1 && 
                    $request->sts_process == 'unassigned' && 
                    ($employer_is_admin || $employer_manage_permission) && 
                    in_array($request->request_model_id, [1, 2, 3])): ?>
            <li style="padding: 3px 0;">
              <a href="<?php echo site_url('employer/staff_requests/modal_assign_employer/' . $request->ID); ?>" class="btn btn-xs btn-assign-employer btn-primary-dark">
                    Asignar solicitud
              </a>
            </li>
          <?php endif; ?>

          <!-- End Show options employer assigned to the request -->
          <?php if ($this->session->userdata('current_profile_id') == 1 && 
                    in_array($request->request_model_id, [1, 2]) &&
                    $request->sts_process == 'assigned' && 
                    $assignment_permission): ?>
            <li style="padding: 3px 0;">
              <a class="btn-stepv2-request" href="<?php echo site_url('employer/post_new_job?r=' . $request->ID); ?>" >
                  Crear empleo
              </a>
            </li>           
          <?php endif; ?>
          <!-- End Show options only for employers -->

          <!-- End Show options employer assigned to the request -->
          <?php if ($this->session->userdata('current_profile_id') == 1 && 
            in_array($request->request_model_id, [3]) && 
            $request->sts_process == 'assigned' && 
            $assignment_permission && false): ?>
            <li style="padding: 3px 0;">
              <button class="btn-create-rys-process btn-stepv2-request">
                Crear Proceso RyS
              </button>
            </li>           
          <?php endif; ?>
          <!-- End Show options only for employers -->
          
          <?php if ($this->session->userdata('current_profile_id') == 2 && 
                    in_array($request->request_model_id, [1, 2, 3]) &&
                    $exist_short_list_for_posted_job && false): ?>
            <li style="padding: 3px 0;">
               <a class="btn btn-xs btn-primary-dark" href="<?php echo site_url('employer/recruitment_short_list/candidates/show/' . $request_posted_job->ID); ?>">Ver Terna o Short List</a>
            </li>
          <?php endif; ?>      
        </ul>
      </div>
    </div>
  </div>
  <div style="position:absolute;right:10px;top:23px;">
    <table style="border:none;">
      <tr>
        <td>
          <label class="btn-status">
            Estado: <?php echo status_process_request_text($request->sts_process); ?>
          </label> 
        </td>
        <td>
          <div class="dropdown dropdown-options-job">
            <button class="btn btn-sm dropdown-toggle" style="border:none;text-decoration: underline;background: transparent; font-weight: bold;" type="button" data-toggle="dropdown">
              <i class="glyphicon glyphicon-option-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
              <?php if ($this->session->userdata('current_profile_id') == 1  && 
                        in_array($request->request_model_id, [1, 2, 3]) &&
                        (($employer_is_admin || $employer_manage_permission))): ?>
                <li>
                  <a id="btn-observe-request" 
                     href="#">
                    Observar solicitud
                  </a>
                </li>
              <?php endif; ?>

              <?php if ($this->session->userdata('current_profile_id') == 1  && 
                        (($employer_is_admin || $employer_manage_permission)) &&
                        in_array($request->request_model_id, [1, 2, 3]) &&
                        $request->sts_process == 'unassigned'): ?>
                <li>
                  <a href="<?php echo site_url('employer/staff_requests/reject/' . $request->ID); ?>">
                    Rechazar solicitud
                  </a>
                </li>
              <?php endif; ?>
              
              <?php if ($this->session->userdata('current_profile_id') == 1 && 
                        $request_sts_active && 
                        in_array($request->request_model_id, [1, 2]) && 
                        (@$rs_process->sts_stage != 7)): ?> 
                <li>
                  <a id="btn-request-edit" 
                      href="#">
                    Levantamiento de perfil
                  </a>
                </li>
              <?php endif; ?>
              
              <?php if ($this->session->userdata('current_profile_id') == 1 && 
                        $assignment_permission &&
                        in_array($request->request_model_id, [1, 2, 3]) && 
                        $request_sts_active): ?> 
                <li>
                  <a href="<?php echo site_url('employer/staff_requests/cancel/' . $request->ID); ?>">
                    Cancelar solicitud
                  </a>
                </li>
              <?php endif; ?>
              <li>
                <a href="<?php echo site_url('general/staff_requests/profile_export_pdf/' . $request->ID); ?>" target="_blank">
                  Exportar perfil laboral
                </a>
              </li>
            </ul>
          </div>
        </td>

      </tr>
    </table>
  </div>
</div>
