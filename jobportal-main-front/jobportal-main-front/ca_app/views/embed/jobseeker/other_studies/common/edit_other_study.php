<div id="other-studies-edit" class="innerbox2" style="margin:0;display:none;">
    <div class="titlebar">
        <div class="row">
            <div class="col-xs-9">
                <a href="#" class="back">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
                <b>Editar otros estudios</b>
            </div>
        </div>
    </div>
    <div>
        <form name="frm_edit_other_studies" id="frm_edit_other_studies" role="form" method="post" action="<?php echo site_url('embed/jobseeker/other_studies/manage_other_studies/edit');?>">
            <?php echo input_embed_token(); ?>
            <div>
                <div style="padding: 10px 15px;">
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
                    <button type="button" name="edit_other_study_submit" id="edit_other_study_submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Ends edit Jobseeker Education --> 




