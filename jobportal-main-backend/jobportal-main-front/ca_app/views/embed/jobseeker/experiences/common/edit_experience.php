

<div id="experiences-edit" class="innerbox2" style="margin:0; display:none;" >
    <div class="titlebar">
        <div class="row">
            <div class="col-xs-9">
                <a href="#" class="back">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
                <b>Editar experiencia</b>
            </div>
        </div>
    </div>

    <div>
        <form name="frm_edit_exp" id="frm_edit_exp" role="form" method="post" action="<?php echo base_url('');?>">
        <?php echo input_embed_token(); ?>
        <div style="padding: 10px 15px;">
            <div>
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
                            $selected = "";//($dob[1]==$month)?'selected="selected"':'';
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
            <button type="button" name="js_edit_exp_submit" id="js_edit_exp_submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
        </form>
    </div>
</div>
