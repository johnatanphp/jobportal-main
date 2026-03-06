<div class="page-controls">
  <div class="page-control-search" style="width: 100%;max-width: 300px;">
    <?php echo form_open('', ['class' => 'tray-candidates-search']); ?>
      <table width="100%">
        <tbody>
          <tr>
            <td>
            <input type="search" name="search" class="form-control" placeholder="Buscar por número de DNI..." value="<?php e($search); ?>">
            </td>
            <td>
              <button class="btn btn-sm" type="submit">
                <i class="glyphicon glyphicon-search"></i>
              </button>
            </td>
            <td>
              <button class="btn btn-sm" 
                      type="button" data-toggle="modal" 
                      data-target="#modal-tray-candidates-filters">
                <i class="glyphicon glyphicon-filter"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    <?php echo form_close(); ?>
  </div>
  <div class="page-control-options">
    <?php if (has_permission_action('recruitment_tray', 'add_candidates')): ?>
        <button class="btn btn-sm btn-primary btn-style-1 add-candidate" 
                data-target="#modal-add-candidates">
            Agregar
        </button>
    <?php endif; ?>

    <div class="dropdown dropdown-options-job">
      <button class="btn btn-sm btn-default dropdown-toggle"
              data-toggle="dropdown">
        Screening por lote
      </button>
      <ul class="dropdown-menu dropdown-menu-right">
        <li>
          <a href="#"
             id="btn-screening-batch-create">
            Crear
          </a>
        </li>
        <li>
          <a href="#" 
             data-target="#modal-screening-batches-list">
            Ver lotes
          </a>
        </li>
      </ul>
    </div>
    
    <div class="dropdown dropdown-options-job">
      <button class="btn btn-sm dropdown-toggle" 
              style="text-decoration: underline;border:1px solid #ccc;background: #fff; font-weight: bold;" 
              type="button" 
              data-toggle="dropdown">
      <i class="glyphicon glyphicon-option-vertical"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-right">
        <li>
          <a href="#" 
              class="sent-candidates" 
              data-target="#modal-sent-candidates">
            Enviar a contratación
          </a>
        </li>
        <?php if (has_permission_action('recruitment_tray', 'hire_candidates')): ?>
          <li>
            <a href="#" 
                id="btn-hire-candidates">
              Contratar
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>

<div id="wraper-tbl-candidates-client-list">
  <div class="formwraper">
    <table id="tbl-candidates-client-list" class="table-style-1" style="width: 100%;">
      <thead>
        <tr>
          <th style="border-top-left-radius: 12px;" width="10"></th>
          <th>
            Nombre y Apellido
          </th>
          <th>
            Origen
          </th>
          <th style="text-align: center;">Link</th>
          <th>Progreso</th>
          <th></th>
          <th style="border-top-right-radius: 12px;">
            <input type="checkbox" class="checkbox-tray-all">
          </th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($results as $row): ?>
        <tr>
          <td>
            <div class="dropdown dropdown-options-job">
              <button class="" 
                      style="border:0;padding: 0;background: transparent; font-weight: bold;" 
                      type="button" 
                      data-toggle="dropdown">
              <i class="glyphicon glyphicon-option-vertical"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-left">
                <li>
                  <a href="#" 
                      class="edit-candidates" 
                      data-target="#modal-edit-candidates"
                      data-candidate-id="<?php echo $row->candidate_id;?>">
                    Editar datos
                  </a>
                </li>
              </ul>
            </div>
          </td>
          <td>
            <a href="#"
              class="tray-candidate-detail link-detail-secundary" 
              data-target="#modal-candidate-detail" data-tray-candidate-id="<?php echo $row->id; ?>">
              <?php e(trim($row->candidate_first_name . ' ' . $row->candidate_last_name)); ?>
            </a>
            <p><?php e(trim($row->candidate_document_type_abbreviation_name  . ' ' . $row->candidate_document_number)); ?></p>
          </td>
          <td>
            <?php if ($row->request_id): ?>
              <a href="#"
                 class="link-detail-secundary staff-requests-detail"
                 data-id="<?php echo $row->request_id; ?>"> 
                <?php echo $row->request_job_title; ?>
              </a>
              <p>
                <?php echo 'Solicitud: '. $row->request_id; ?>
                <?php echo 'Proceso: ' . $row->process_id; ?>
              </p>
             
            <?php endif; ?>

            <?php if (!$row->request_id): ?>
              <span style="font-style: italic;color: #888888;">Sin solicitud</span>
              <p style="color: #888888;">
                <?php echo 'Proceso: ' . $row->process_id; ?>
              </p>
            <?php endif; ?>
          </td>
          <td style="text-align: center;">
            <div class="dropdown dropdown-options-link">
              <button class="btn btn-sm btn-default dropdown-toggle"
                      <?php echo in_array($row->tray_status_id, [3, 4]) ? 'disabled' : ''; ?>
                      data-toggle="dropdown">
                Reenviar
              </button>
              <ul class="dropdown-menu dropdown-menu-left">
                <li>
                  <a href="#" 
                     class="copy-link" 
                     data-id="<?php echo $row->id; ?>">
                    Copiar link
                  </a>
                </li>
                <li>
                  <a href="#" 
                     class="create-resend-link" 
                     data-id="<?php echo $row->id; ?>">
                    Enviar por WhatsApp
                  </a>
                </li>
              </ul>
            </div>
          </td>
          <td>
            <div class="container-progress-bar">
              <label style="text-align: center;display: block;"><?php echo $row->doc_percentage_progress; ?>%</label>
              <div class="progress">
                <div class="progress-bar" 
                      role="progressbar" 
                      aria-valuenow="<?php echo $row->doc_percentage_progress; ?>"
                      aria-valuemin="0" 
                      aria-valuemax="100" 
                      style="width:<?php echo $row->doc_percentage_progress; ?>%">
                  <span class="sr-only"></span>
                </div>
              </div>
            </div>
          </td>
          <td>
            <a href="#"
               class="link-tray-candidates-status"
              data-target="#modal-tray-candidates-status" 
              data-tray-candidate-id="<?php echo $row->id; ?>">
              <?php $status_style = $row->tray_status_bg_alert_color ? 'background: ' .  $row->tray_status_bg_alert_color . ';' : ''; ?>
              <span class="label label-default"  style="<?php echo $status_style; ?>">
                <?php e($row->tray_status_name); ?>
              </span>
            </a>
          </td>
          <td>
            <input type="checkbox" name="tray_id[]" value="<?php echo $row->id; ?>" class="checkbox-tray-id">
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (count($results) == 0): ?>
          <tr>
            <td colspan="10" align="center">Sin resultados</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="table-footer"></div>
  <div class="paginationWrap pag-wrap-v3">
    <?php echo ($results) ? $links : ''; ?>        
  </div>
</div>
