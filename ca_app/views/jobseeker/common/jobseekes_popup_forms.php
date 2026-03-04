<!-- Starts Edit Jobseeker Profile -->
<div class="modal fade" id="edit_profile_modal">
  <div class="modal-dialog">
  
    <form name="frm_edit_profile" id="frm_edit_profile" role="form" method="post" action="<?php echo base_url('');?>" onsubmit="return validate_cv_builder_form(this);">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Actualizar perfil</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
          <div id="emsg_profle"></div>
            <div class="form-group">
              <label>Nombre completo</label>
              <input type="text" class="form-control"  id="full_name" name="full_name" value="<?php echo $row->first_name.' '.$row->last_name;?>" placeholder="Nombre completo">
              <?php echo form_error('full_name'); ?> </div>
              
            <div class="form-group">
              <label>Teléfono móvil</label>
              <input type="text" class="form-control"  id="mobile" name="mobile" value="<?php echo $row->mobile;?>" placeholder="Teléfono móvil">
			  <?php echo form_error('mobile'); ?>
              
               </div>
            <div class="form-group">
              <label>País</label>
              <select name="country" id="country" class="form-control" style="width:50%">
              <?php 
					foreach($result_countries as $row_country):
						$selected = ($row_country->ID==$row->country)?'selected="selected"':'';		
				?>
              <option value="<?php echo $row_country->ID;?>" <?php echo $selected;?>><?php echo $row_country->country_name;?></option>
              <?php endforeach;?>
            </select>
              <?php echo form_error('country'); ?> </div>
            <div class="form-group">
              <label>Ciudad</label>
              <input type="text" class="form-control"  id="city" name="city" value="<?php echo $row->city;?>" placeholder="Ciudad">
              <?php echo form_error('city'); ?> </div>
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" name="jobseeker_profile_submitter" id="jobseeker_profile_submitter" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Ends Edit Jobseeker Profile --> 
<!-- Starts Edit Jobseeker Profile Description-->
<div class="modal fade" id="edit_profile_summary_modal">
  <div class="modal-dialog">
    <form name="frm_seeker_summary" id="frm_seeker_summary" role="form" method="post" action="<?php echo base_url('');?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Actualizar resumen profesional</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
          	<div id="emsg_summary"></div>
            <div class="form-group">
              <label>Resumen</label>
              <textarea id="content" name="content"  class="form-control" rows="9" placeholder=""><?php echo $row_additional ? $row_additional->summary : ''; ?></textarea>
              <?php echo form_error('content'); ?> </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" name="summary_submit" id="summary_submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Ends Edit Jobseeker Profile Description--> 

<!-- Starts add Jobseeker Experience-->
<div class="modal fade" id="add_exp_modal">
  <div class="modal-dialog">
  
    <form name="frm_add_exp" id="frm_add_exp" role="form" method="post" action="<?php echo base_url('');?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Experiencia</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
          <div id="emsg_profle"></div>
            <div class="form-group">
              <label>Empresa</label>
              <input type="text" class="form-control"  id="company_name" name="company_name" value="" placeholder="Empresa">
            </div>
            <div class="form-group">
              <label>Industria</label>
              <input type="text" name="exp_industry" id="exp_industry" class="form-control" value="" placeholder="Industria">
            </div>
            <div class="form-group">
              <label>Puesto</label>
              <input type="text" class="form-control"  id="job_title" name="job_title" value="" placeholder="Puesto">
            </div>   
            <div class="form-group">
              <label>Nivel de puesto</label>
              <select class="form-control"  id="exp_job_level" name="exp_job_level">
                  <option value="">Seleccione</option>
                  <option value="Analista / Asistente">Analista / Asistente</option>
                  <option value="Ejecutivo Comercial">Ejecutivo Comercial</option>
                  <option value="Gerencia">Gerencia</option>
                  <option value="Jefe / Supervisor">Jefe / Supervisor</option>
                  <option value="Practicante">Practicante</option>
                  <option value="Técnicos / Operativos">Técnicos / Operativos</option>
                  <option value="Trainee">Trainee</option>
                  <option value="Vendedor">Vendedor</option>
                  <option value="Otros">Otros</option>
                </select>
              </div>
              <div class="form-group">
              <label>Área</label>
                <select class="form-control"  id="exp_area" name="exp_area">
                  <option value="">Seleccione</option>
                  <?php foreach ($result_industries as $key => $industry): ?>
                    <option value="<?php echo $industry->industry_name; ?>">
                      <?php echo $industry->industry_name; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            <div class="form-group">
              <label>País</label>  
              <select name="exp_country" id="exp_country" class="form-control">
                <option value=""></option>
                <?php foreach ($countries as $key => $country): ?>
                  <option value="<?php echo $country->country_name; ?>">
                    <?php echo $country->country_name; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <div class="form-group">
              <label>Fecha inicio</label>
                <div class="row">
                  <div class="col-md-4">

                    <select class="form-control" name="exp_start_year" id="exp_start_year" >
                      <option value="" selected="selected">Año</option>
                      <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                      <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                      <?php endfor;?>
                    </select>      
                  </div>                  
                  <div class="col-md-4">
                    <select class="form-control" name="exp_start_month" id="exp_start_month" disabled="true">
                      <option value="">Mes</option>
                      <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                        $month = sprintf("%02s", $mnth);
                        $selected = "";
                        $dummy_date = '2014-'.$month.'-'.'01';
                      ?>
                      <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
                      <?php endfor; ?>
                    </select>  
                  </div>
                </div>
            </div> 

            <div class="form-group">
              <label>Fecha fin</label>
                <div class="row">
                  <div class="col-md-4">
                    <select class="form-control" name="exp_completion_year" id="exp_completion_year" disabled="true">
                      <option value="" selected="selected">Año</option>
                      <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                      <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                      <?php endfor;?>
                    </select>      
                  </div>                  
                  <div class="col-md-4">
                    <select class="form-control" name="exp_completion_month" id="exp_completion_month" disabled="true">
                      <option value="">Mes</option>
                      
                      <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                        $month = sprintf("%02s", $mnth);
                        $selected = "";
                        $dummy_date = '2014-'.$month.'-'.'01';
                      ?>
                      
                      <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                </div>
            </div>
            <div class="form-group">
              <label style="font-weight: normal;"><input id="exp_working" type="checkbox" name="exp_working" value="true"> Actualmente estoy trabajando</label>
            </div>
            <div class="form-group">
              <label>Descripción</label>
              <textarea id="exp_decription" name="exp_description" class="form-control"></textarea>
            </div>    
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" name="js_exp_submit" id="js_exp_submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Ends add Jobseeker Experience --> 
<!-- Starts edit Jobseeker Experience-->
<div class="modal fade" id="edit_exp_modal">
  <div class="modal-dialog">
  
    <form name="frm_edit_exp" id="frm_edit_exp" role="form" method="post" action="<?php echo base_url('');?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Experiencia</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
          <div id="emsg_profle"></div>
            <div class="form-group">
              <label>Empresa</label>
              <input type="text" class="form-control"  id="ed_company_name" name="ed_company_name" value="" placeholder="Empresa">
            </div>
            <div class="form-group">
              <label>Industria</label>
              <input type="text" name="ed_exp_industry" id="ed_exp_industry" class="form-control" value="" placeholder="Industria">
            </div>
            <div class="form-group">
              <label>Puesto</label>
              <input type="text" class="form-control"  id="ed_job_title" name="ed_job_title" value="" placeholder="Puesto">
            </div>
            <div class="form-group">
              <label>Nivel de puesto</label>
              <select class="form-control"  id="ed_exp_job_level" name="ed_exp_job_level">
                <option value="">Seleccione</option>
                <option value="Analista / Asistente">Analista / Asistente</option>
                <option value="Ejecutivo Comercial">Ejecutivo Comercial</option>
                <option value="Gerencia">Gerencia</option>
                <option value="Jefe / Supervisor">Jefe / Supervisor</option>
                <option value="Practicante">Practicante</option>
                <option value="Técnicos / Operativos">Técnicos / Operativos</option>
                <option value="Trainee">Trainee</option>
                <option value="Vendedor">Vendedor</option>
                <option value="Otros">Otros</option>
              </select>
            </div>
            <div class="form-group">
              <label>Área</label>
              <select class="form-control"  id="ed_exp_area" name="ed_exp_area">
                <option value="">Seleccione</option>
                <?php foreach ($result_industries as $key => $industry): ?>
                  <option value="<?php echo $industry->industry_name; ?>">
                    <?php echo $industry->industry_name; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>  
            <div class="form-group">
              <label>País</label>
              
              <select name="ed_exp_country" id="ed_exp_country" class="form-control">
                <option value=""></option>
                <?php foreach ($countries as $key => $country): ?>
                  <option value="<?php echo $country->country_name; ?>">
                    <?php echo $country->country_name; ?>
                  </option>
                <?php endforeach; ?>
              </select>  	
            </div>

            <div class="form-group">
              <label>Fecha inicio</label>
                <div class="row">
                  <div class="col-md-4">
                    <select class="form-control" name="ed_exp_start_year" id="ed_exp_start_year" >
                      <option value="" selected="selected">Año</option>
                      <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                      <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                      <?php endfor;?>
                    </select>      
                  </div>                  
                  <div class="col-md-4">
                    <select class="form-control" name="ed_exp_start_month" id="ed_exp_start_month">
                      <option value="">Mes</option>
                      <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                        $month = sprintf("%02s", $mnth);
                        $selected = "";
                        $dummy_date = '2014-'.$month.'-'.'01';
                      ?>
                      <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
                      <?php endfor; ?>
                    </select>  
                  </div>
                </div>
            </div> 

            <div class="form-group">
              <label>Fecha fin</label>
                <div class="row">
                  <div class="col-md-4">
                    <select class="form-control" name="ed_exp_completion_year" id="ed_exp_completion_year" >
                      <option value="" selected="selected">Año</option>
                      <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                      <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                      <?php endfor;?>
                    </select>      
                  </div>                  
                  <div class="col-md-4">
                    <select class="form-control" name="ed_exp_completion_month" id="ed_exp_completion_month">
                      <option value="">Mes</option>
                      
                      <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                        $month = sprintf("%02s", $mnth);
                        $selected = '';
                        $dummy_date = '2014-'.$month.'-'.'01';
                      ?>
                      
                      <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                </div>
            </div>
            <div class="form-group">
              <label style="font-weight: normal;"><input id="ed_exp_working" type="checkbox" name="ed_exp_working" value="true"> Actualmente estoy trabajando</label>
            </div>
            <div class="form-group">
              <label>Descripción</label>
              <textarea id="ed_exp_description" name="ed_exp_description" class="form-control"></textarea>
            </div>     
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="ed_exp_id" id="ed_exp_id" value="" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" name="js_edit_exp_submit" id="js_edit_exp_submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Ends edit Jobseeker Experience -->

<!-- Starts Add Jobseeker Other studies-->
<div class="modal fade" id="other_studies_modal">
  <div class="modal-dialog">
  
    <form name="frm_other_studies" id="frm_other_studies" role="form" method="post" action="<?php echo base_url('');?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Otros estudios</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
          <div id="emsg_profle"></div> 
            <div class="form-group">
              <label>Nombre</label>
              <input type="text" class="form-control"  id="otst_name_study" name="study_name" value="" placeholder="Nombre de estudio">
            </div>
            <div class="form-group">
              <label>Tipo</label>
              <select name="type_study" id="otst_type_study" class="form-control">
                <option value="">Seleccione</option>
                <?php foreach($result_degrees_other_studies as $row_degree): ?>
                <option value="<?php echo $row_degree->text;?>">
                  <?php echo $row_degree->text;?>
                </option>
                <?php endforeach;?>
              </select>
            </div>
            <div class="form-group">
              <label>Institución</label>
                <input type="text" class="form-control institute-search-suggestions"  id="otst_institute" name="institute" value="" placeholder="Institución" autocomplete="off">
            </div>
              
            <div class="form-group">
              <label>País</label>
              <select name="country" id="otst_country" class="form-control">
                <option value=""></option>
                <?php foreach ($countries as $key => $country): ?>
                  <option value="<?php echo $country->country_name; ?>">
                    <?php echo $country->country_name; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <div class="form-group">
              <label>Fecha inicio</label>
                <div class="row">
                  <div class="col-md-4">
                    <select class="form-control" name="start_year" id="otst_start_year" >
                      <option value="" selected="selected">Año</option>
                      <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                      <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                      <?php endfor;?>
                    </select>      
                  </div>                  
                  <div class="col-md-4">
                    <select class="form-control" name="start_month" id="otst_start_month" disabled="true">
                      <option value="">Mes</option>
                      <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                        $month = sprintf("%02s", $mnth);
                        $selected = "";
                        $dummy_date = '2014-'.$month.'-'.'01';
                      ?>
                      <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
                      <?php endfor; ?>
                    </select>  
                  </div>
                </div>
            </div> 

            <div class="form-group">
              <label>Fecha fin</label>
                <div class="row">
                  <div class="col-md-4">
                    <select class="form-control" name="completion_year" id="otst_completion_year" disabled="true">
                      <option value="" selected="selected">Año</option>
                      <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                      <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                      <?php endfor;?>
                    </select>      
                  </div>                  
                  <div class="col-md-4">
                    <select class="form-control" name="completion_month" id="otst_completion_month" disabled="true">
                      <option value="">Mes</option>
                      
                      <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                        $month = sprintf("%02s", $mnth);
                        $selected = '';
                        $dummy_date = '2014-'.$month.'-'.'01';
                      ?>
                      
                      <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                </div>
            </div>
            <div class="form-group">
              <label style="font-weight: normal;"><input id="otst_studying" type="checkbox" name="studying" value="true"> Actualmente estoy estudiando</label>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" id="otst_id" name="otst_id" value="" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" name="add_other_study_submit" id="add_other_study_submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Ends Add Jobseeker Other studies --> 

<!-- Starts edit Jobseeker Other studies-->
<div class="modal fade" id="edit_other_studies_modal">
  <div class="modal-dialog">
  
    <form name="frm_edit_other_studies" id="frm_edit_other_studies" role="form" method="post" action="<?php echo base_url('');?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Otros estudios</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
          <div id="emsg_profle"></div> 
            <div class="form-group">
              <label>Nombre</label>
              <input type="text" class="form-control"  id="ed_otst_study_name" name="study_name" value="" placeholder="Nombre de estudio">
            </div>

            <div class="form-group">
              <label>Tipo</label>
              <select name="type_study" id="ed_otst_type_study" class="form-control">
                <option value="">Seleccione</option>
                <?php foreach($result_degrees_other_studies as $row_degree): ?>
                <option value="<?php echo $row_degree->text;?>"><?php echo $row_degree->text;?></option>
                <?php endforeach;?>
              </select>
            </div>
      
            <div class="form-group">
              <label>Institución</label>
                <input type="text" class="form-control institute-search-suggestions"  id="ed_otst_institute" name="institute" value="" placeholder="Institución" autocomplete="off">
            </div>
              
            <div class="form-group">
              <label>País</label>
              <select name="country" id="ed_otst_country" class="form-control">
                <option value=""></option>
                <?php foreach ($countries as $key => $country): ?>
                  <option value="<?php echo $country->country_name; ?>">
                    <?php echo $country->country_name; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <div class="form-group">
              <label>Fecha inicio</label>
                <div class="row">
                  <div class="col-md-4">
                    <select class="form-control" name="start_year" id="ed_otst_start_year" >
                      <option value="" selected="selected">Año</option>
                      <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                      <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                      <?php endfor;?>
                    </select>      
                  </div>                  
                  <div class="col-md-4">
                    <select class="form-control" name="start_month" id="ed_otst_start_month">
                      <option value="">Mes</option>
                      <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                        $month = sprintf("%02s", $mnth);
                      $selected = '';
          
                        $dummy_date = '2014-'.$month.'-'.'01';

                      ?>
                      <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
                      <?php endfor; ?>
                    </select>  
                  </div>
                </div>
            </div> 

            <div class="form-group">
              <label>Fecha fin</label>
                <div class="row">
                  <div class="col-md-4">
                    <select class="form-control" name="completion_year" id="ed_otst_completion_year" >
                      <option value="" selected="selected">Año</option>
                      <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                      <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                      <?php endfor;?>
                    </select>      
                  </div>                  
                  <div class="col-md-4">
                    <select class="form-control" name="completion_month" id="ed_otst_completion_month">
                      <option value="">Mes</option>
                      
                      <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                        $month = sprintf("%02s", $mnth);
                      
                        $selected = '';

                        $dummy_date = '2014-'.$month.'-'.'01';
                      
                      ?>
                      
                      <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                </div>
            </div>
            <div class="form-group">
              <label style="font-weight: normal;"><input id="ed_otst_studying" type="checkbox" name="studying" value="true"> Actualmente estoy estudiando</label>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" id="ed_otst_id" name="otst_id" value="" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" name="edit_other_study_submit" id="edit_other_study_submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Ends edit Jobseeker Other studies --> 