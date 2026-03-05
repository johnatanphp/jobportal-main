<style type="text/css">
  .content-candidate-error { 
    padding: 8px 0px;
  }

  .content-candidate-error i {
    color: #ffa200;
  }

  #load-verify-candidate {
    padding: 8px 0px;
    font-size: 13px;
    font-style: italic;
    color: #555;
  }


  #form-search-seeker-names table tr td,
  #form-search-seeker-doc table tr td,
  #form-search-seeker-email table tr td   {
    padding: 6px;
  }
</style>
<div id="modal-add-candidate" class="modal" role="dialog">
  <div class="modal-dialog" style="width:96%;max-width: 750px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          Agregar candidato
        </h4>
      </div>
      <br>
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#search-seekers">Buscar</a></li>
        <?php if (user_belong_to_company_internal()): ?>
          <li><a data-toggle="tab" href="#importer-seekers">Importar</a></li>
        <?php endif; ?>
        <li><a data-toggle="tab" href="#register-seekers">Registro</a></li>
      </ul>

      <div class="tab-content">
        <div id="search-seekers" class="tab-pane fade in active" style="padding:2px;">
          
          <div id="rs-premium-candidates" style="display:none;padding: 10px;"></div>
          
          <div id="rs-search-candidates">
            <div class="alert alert-info" style="display: none;">
              Para asignar o incluir un candidato a la etapa ingrese email o correo electrónico en el  
                campo de búsqueda y seleccione.
      
                <?php if (user_belong_to_company_internal()): ?>
                  <br />
                    <div style="display: none;">
                      También puede incluir candidatos por la opción de
                      <a id="show-premium-candidates" href="#" style="text-decoration: underline; color: #fff;">
                        Candidatos Premium
                      </a>
                    </div>
                <?php endif; ?>
            </div>
    
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">

                  <div>
                    <div class="row">
                      <div class="col-md-5">
                      </div>
                      <div class="col-md-7" style="text-align: right;">

                        <div class="panel-group accordion-notifications" id="accordion-notifications-add-candidate">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                              <h4 class="panel-title">
                                  <a data-toggle="collapse" data-parent="#accordion-notifications-add-candidate" href="#collapse-add-candidate-1" style="justify-content: end;">
                                      <label style="margin: 0 10px 0 0;color: #666;font-size: 13px;">
                                          <span class="glyphicon glyphicon-bell" style="color: #666;margin-right: 5px;"></span>
                                          ¿Notificar al postulante?
                                      </label>
                                      <span class="glyphicon glyphicon-chevron-right"></span>
                                  </a>
                              </h4>
                              </div>
                              <div id="collapse-add-candidate-1" class="panel-collapse collapse">
                                  <div class="panel-body" style="background: #ffffff;">

                                    <div class="row">
                                      <div class="col-sm-6">
                                        <div style="display: flex; justify-content: end;align-items: center;">
                                          <label style="font-weight: normal;font-size: 13px;">Por Correo</label>
                                          <div class="checkbox-wrapper-2" style="margin-left: 12px;">
                                              <input class="tgl tgl-light" id="notify-candidate-add-process-by-mail" type="checkbox" name="notify_candidate_by_mail" value="1" />
                                              <label class="tgl-btn" for="notify-candidate-add-process-by-mail" style="width: 30px;height: 15px;">
                                          </div>
                                        </div>
                                      </div>

                                      <div class="col-sm-6">
                                        <div style="display: flex; justify-content: end;align-items: center;">
                                          <label style="font-size: 13px;font-weight: normal;">Por WhatsApp</label>
                                          <div class="checkbox-wrapper-2" style="margin-left: 12px;">
                                              <input class="tgl tgl-light" id="notify-candidate-add-process-by-whatsapp" type="checkbox" name="notify_candidate_by_whatsapp" value="1" />
                                              <label class="tgl-btn" for="notify-candidate-add-process-by-whatsapp" style="width: 30px;height: 15px;">
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                  </div>
                              </div>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div style="text-align:center;margin-top: 5px;">
                      <table align="center">
                        <tr>
                          <td>
                            <ul class="nav nav-pills">
                              <li class="active"><a data-toggle="pill" href="#home" data-tab="email">Por email</a></li>
                              <li><a data-toggle="pill" href="#menu1" data-tab="document_number">Por Doc. Identidad</a></li>
                              <li><a data-toggle="pill" href="#menu2" data-tab="names">Por Nombre y Apellido</a></li>
                            </ul>
                          </td>
                        </tr>
                      </table>
                    </div>
                    <br>

                    <div class="tab-content">
                      <div id="home" class="tab-pane fade in active">
                        <?php echo form_open('', ['id' => 'form-search-seeker-email']); ?>
                          <table style="width:65%" align="center">
                            <tr>
                              <td>
                                <input type="text" name="email" class="form-control" placeholder="Ingrese email" required>
                              </td>
                              <td>
                                <button type="submit" id="btn-seeker-search" class="btn btn-sm btn-default">Buscar</button>
                              </td>
                            </tr>
                          </table>
                        <?php echo form_close(); ?>
                      </div>

                      <div id="menu1" class="tab-pane fade">
                        <?php echo form_open('', ['id' => 'form-search-seeker-doc']); ?>
                          <table style="width:55%" align="center">
                            <tr>
                              <td width="120">
                                <select name="document_type" class="form-control" required>
                                  <option value="">Tipo de Doc.</option>
                            
                                  <?php foreach ($document_types as $doc_type): ?>
                                    <option value="<?php echo $doc_type->id; ?>">
                                        <?php e($doc_type->name); ?>
                                    </option> 
                                  <?php endforeach; ?>

                                </select>
                              </td>
                              <td>
                                <input type="text" class="form-control" name="document_number" placeholder="Ingrese el número de documento" required>
                              </td>
                              <td>
                                <button type="submit" class="btn btn-sm btn-default">Buscar</button>
                              </td>
                            </tr>
                          </table>
                        <?php echo form_close(); ?>
                      </div>

                      <div id="menu2" class="tab-pane fade">
                        <?php echo form_open('', ['id' => 'form-search-seeker-names']); ?>
                          <table style="width:90%">
                            <tr>
                              <td>
                                <input type="text" name="first_name" class="form-control" placeholder="Nombres">
                              </td>
                              <td>
                                <input type="text" name="paternal_last_name" class="form-control" placeholder="Apellido paterno">
                              </td>
                              <td>
                                <input type="text" name="maternal_last_name" class="form-control" placeholder="Apellido materno">
                              </td>
                              <td>
                                <button type="submit" id="btn-seeker-search" class="btn btn-sm btn-default">Buscar</button>
                              </td>
                            </tr>
                          </table>
                        <?php echo form_close(); ?>
                      </div>
                    </div>
                  </div>

                  <hr>
                  <table id="tbl-seeker-search-add-results" class="table" style="width:100%">
                    <thead>
                      <tr>
                        <th>
                          Doc. Identidad
                        </th>
                        <th>
                          Email
                        </th>
                        <th>
                          Nombres
                        </th>
                        <th>
                          Apellidos
                        </th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                            
                </div>
              </div>
            </div>
            
          </div>

        </div>
        
        <div id="register-seekers" class="tab-pane fade">
          <?php $this->load->view('employer/recruitment/common/jobseeker_form_create'); ?>
        </div>

        <div id="importer-seekers" class="tab-pane fade">
          <?php $this->load->view('employer/recruitment/common/importer_seekers'); ?>
        </div>

      </div>
    </div>
  </div>
</div>

<div id="modal-import-template" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Plantillas para importar candidatos</h4>
        </div>
        <div class="modal-body text-center">
            <a class="btn btn-sm btn-success btn-block"
               href="<?php echo site_url('public/documents/templates/candidate_other_site/template_computrabajo_v3.xlsx'); ?>">
                PLANTILLA PARA COMPUTRABAJO
            </a>
            <br />
            <a class="btn btn-sm btn-success btn-block" 
              href="<?php echo site_url('public/documents/templates/candidate_other_site/importar_candidatos_otros_v3.xlsx'); ?>">
                PLANTILLA PARA OTROS
            </a>
        </div>
    </div>  
  </div>
</div>

<!-- Modal -->
<div id="modal-confirm-create-seeker" 
     class="modal" 
     role="dialog" 
     data-backdrop="static" 
     data-keyboard="false">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Creación postulante</h4>
        </div>
        <div class="modal-body">
          <p>
            El postulante seleccionado no está registrado en el portal de empleo, pero si tenemos sus datos registrados en nuestro sistema de nómina, por favor confirmar la dirección de correo con la que se creará el usuario en el portal para el postulante.
          </p>
          <br>
          <label>Email</label>
          <br>
          <input type="text" name="email" value="" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm" data-dismiss="modal">Cancelar</button>
          <button id="create-seeker-from-data" type="button" class="btn btn-sm btn-primary">Agregar</button>
        </div>
      </div>
    </div>
  </div>

<?php $this->load->view('employer/recruitment/scripts/add_seeker_stage_js'); ?>
<?php $this->load->view('employer/recruitment/scripts/jobseeker_register_js'); ?>
