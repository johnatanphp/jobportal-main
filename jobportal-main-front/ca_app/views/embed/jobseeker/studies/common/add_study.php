

<div id="studies-add" class="innerbox2" style="margin:0;display:none;" >
    <div class="titlebar">
        <div class="row">
            <div class="col-xs-6">
                <a href="#" class="back">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
                <b>Agregar educación</b>
            </div>
            <div class="col-xs-6">
                <?php if ($education_min): ?>
                    <div style="padding-left: 10px; font-size:14px;font-style:italic;text-align:right;display:block;"> Educación mínima:
                        <a href="#"
                            class="btn-modal-education-detail" 
                            style="padding:0;"
                            data-edu-detail="<?php echo $education_min_detail; ?>">
                            <?php echo $education_min->text; ?>
                            &nbsp;
                            <i class="fa fa-info-circle"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div>
        <!-- Starts Add Jobseeker Education-->
        <form id="frm_add_education" role="form" method="post" action="<?php echo site_url('embed/jobseeker/studies/manage/add'); ?>">
                <input type="hidden" name="seeker_id" value="<?php echo $seeker->ID; ?>">
                <?php echo input_embed_token(); ?>
                <div style="padding: 10px 15px;">
                    <div class="box-body">
                    <div id="emsg_profle"></div>
                    <div class="form-group">
                    <label>Nivel académico</label>
                    <select name="degree_title" id="degree_title" class="form-control" required>
                        <option value="" selected="selected">Seleccione</option>
                        <?php foreach($degrees_studies as $row_degree): ?>
                            <option value="<?php echo $row_degree->text;?>"><?php echo $row_degree->text;?></option>
                        <?php endforeach; ?>
                    </select></div>
                    
                    <div class="form-group">
                        <label>Título</label>
                        <input type="text" class="form-control"  id="major_subject" name="major_subject" value="" placeholder="Título" required>
                    </div>

                    <div class="form-group">
                    <label>País</label>
                        <select name="edu_country" id="edu_country" class="form-control" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($countries as $key => $country): ?>
                            <option value="<?php echo $country->country_name; ?>">
                            <?php echo $country->country_name; ?>
                            </option>
                        <?php endforeach; ?>
                        </select>              	
                    </div>

                    <div class="form-group">
                        <label>Regimen</label>
                        <div class="row">                  
                        <div class="col-md-12">
                            <select class="form-control" name="institution_educ_type" required>
                            <option value="">Seleccione</option>
                            <?php foreach ($institution_educ_types as $row): ?>
                                <option value="<?php echo $row->id; ?>" ><?php e($row->name); ?></option>
                            <?php endforeach; ?>
                            </select>  
                        </div>
                        </div>
                    </div> 

                    <div class="form-group">
                    <label>Clase</label>
                        <div class="row">                  
                        <div class="col-md-12">
                            <select class="form-control" name="institution_educ_class" required>
                            <option value="">Seleccione</option>
                            <?php foreach ($institution_educ_class as $row): ?>
                                <option value="<?php echo $row->id; ?>" ><?php e($row->name); ?></option>
                            <?php endforeach; ?>
                            </select>  
                        </div>
                        </div>
                    </div> 
                    
                    <div class="form-group">
                    <label>Tipo institución</label>
                        <div class="row">                  
                        <div class="col-md-12">
                            <select class="form-control" name="institution_type" required>
                            <option value="">Seleccione</option>
                            <?php foreach ($institution_types as $row): ?>
                                <option value="<?php echo $row->id; ?>" ><?php e($row->name); ?></option>
                            <?php endforeach; ?>
                            </select>  
                        </div>
                        </div>
                    </div> 

                    <div class="form-group">
                    <label>Institución educativa</label>
                        <div class="row">                  
                        <div class="col-md-12">
                            <select class="form-control" name="institution" required>
                            <option value="">Seleccione</option>
                            </select>  
                        </div>
                        </div>
                    </div> 

                    <div class="form-group">
                    <label>Carrera</label>
                        <div class="row">                  
                        <div class="col-md-12">
                            <select class="form-control" name="career" required>
                            <option value="">Seleccione</option>
                            </select>  
                        </div>
                        </div>
                    </div> 
                    
                    <div class="form-group">
                    
                    <label>Fecha inicio</label>
                        <div class="row">
                        <div class="col-md-4">
                            <select class="form-control" name="year_start_date" id="year_start_date" required>
                            <option value="" selected="selected">Año</option>
                            <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                            <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                            <?php endfor;?>
                            </select>      
                        </div>                  
                        <div class="col-md-4">
                            <select class="form-control" name="month_start_date" id="month_start_date" required>
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
                            <select class="form-control" name="year_end_date" id="year_end_date" disabled="true" required>
                            <option value="" selected="selected">Año</option>
                            <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                            <option value="<?php echo $cyear;?>"><?php echo $cyear;?></option>
                            <?php endfor;?>
                            </select>      
                        </div>                  
                        <div class="col-md-4">
                            <select class="form-control" name="month_end_date" id="month_end_date" disabled="true" required>
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
                        <label>Nro. colegiatura (Opcional)</label>
                        <input type="text" name="tuition_number" class="form-control" placeholder="Nro. colegiatura" autocomplete="off">
                    </div>  

                    <div class="form-group">
                        <label style="font-weight: normal;"><input id="studying" type="checkbox" name="studying" value="true"> Actualmente estoy estudiando</label>
                    </div> 
                </div>
           
                <div class="modal-footer">
                    <button type="submit" name="js_education_submitter" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Ends add Jobseeker Education --> 