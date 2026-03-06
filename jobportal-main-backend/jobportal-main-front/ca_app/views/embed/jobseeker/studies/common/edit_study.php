<div class="titlebar">
    <div class="row">
        <div class="col-xs-6">
            <a href="#" class="back">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            <b>Editar educación</b>
        </div>
        <div class="col-xs-6">    
            <?php if (@$education_min): ?>
                <div style="padding-left: 10px;font-size:14px;font-style:italic;display:block;text-align:right;"> Educación mínima:
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
    <form name="frm_edit_education" id="frm_edit_education" role="form" method="post" action="<?php echo site_url('embed/jobseeker/studies/manage/edit');?>">
        <?php echo input_embed_token(); ?>

        <div class="modal-body">
            <div class="box-body">
            <div id="emsg_profle"></div>
                <div class="form-group">
                <label>Nivel académico</label>
                <select name="ed_degree_title" id="ed_degree_title" class="form-control">
                    <option value="">Seleccione</option>
                    <?php foreach($degrees_studies as $row_degree): ?>
                        <option value="<?php echo $row_degree->text;?>" <?php echo $row_degree->text == $seeker_academic->degree_title ? 'selected' : ''; ?>>
                            <?php echo $row_degree->text;?>
                        </option>
                    <?php endforeach;?>
                </select>
                </div>
                
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" class="form-control"  id="ed_major_subject" name="ed_major_subject" value="<?php echo $seeker_academic->major; ?>" placeholder="Título">
                </div>

                <div class="form-group">
                    <label>País</label>
                    <select name="ed_edu_country" id="ed_edu_country" class="form-control">
                        <option value=""></option>
                        <?php foreach ($countries as $key => $country): ?>
                        <option value="<?php echo $country->country_name; ?>" <?php echo $country->country_name == $seeker_academic->country ? 'selected' : ''; ?>>
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
                                <option value="<?php echo $row->id; ?>" <?php echo $row->id == $seeker_academic->institution_educational_type_id ? 'selected' : ''; ?>>
                                <?php e($row->name); ?>
                                </option>
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
                            <option value="<?php echo $row->id; ?>" <?php echo $row->id == $seeker_academic->institution_educational_class_id ? 'selected' : ''; ?>>
                            <?php e($row->name); ?>
                            </option>
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
                            <option value="<?php echo $row->id; ?>" <?php echo $row->id == $seeker_academic->institution_type_id ? 'selected' : ''; ?>>
                            <?php e($row->name); ?>
                            </option>
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
                        
                        <?php foreach ($institutions as $row): ?>
                            <option value="<?php echo $row->code; ?>" <?php echo $row->code == $seeker_academic->institution ? 'selected' : ''; ?>>
                            <?php e($row->name); ?>
                            </option>
                        <?php endforeach; ?>
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
                        <?php foreach ($careers as $row): ?>
                            <option value="<?php echo $row->code; ?>" <?php echo $row->code == $seeker_academic->career ? 'selected' : ''; ?>>
                            <?php e($row->name); ?>
                            </option>
                        <?php endforeach; ?>
                        </select>  
                    </div>
                    </div>
                </div>
                
                <div class="form-group">
                <label>Fecha inicio</label>
                    <div class="row">
                    <div class="col-md-4">
                        <select class="form-control" name="year_start_date" id="ed_start_year" required>
                        <option value="" selected="selected">Año</option>
                        <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                        <option value="<?php echo $cyear;?>" <?php echo substr($seeker_academic->start_date, 0, 4) == $cyear ? 'selected' : ''; ?>>
                            <?php echo $cyear;?>
                        </option>
                        <?php endfor;?>
                        </select>      
                    </div>                  
                    <div class="col-md-4">
                        <select class="form-control" name="month_start_date" id="ed_start_month" required>
                        <option value="">Mes</option>
                        <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                            $month = sprintf("%02s", $mnth);
                            $selected = "";
                        ?>
                        <option value="<?php echo $month;?>" <?php echo substr($seeker_academic->start_date, 5, 2) == $month ? 'selected' : ''; ?>>
                            <?php echo ucwords(_date_locale_format('MMMM', strtotime($dummy_date)));?>
                        </option>
                        <?php endfor; ?>
                        </select>  
                    </div>
                    </div>
                </div> 

                <div class="form-group">
                <label>Fecha fin</label>
                    <div class="row">
                    <div class="col-md-4">
                        <select class="form-control" name="year_end_date" id="ed_completion_year" required>
                        <option value="" selected="selected">Año</option>
                        <?php for ($cyear=date("Y"); $cyear>=1950; $cyear--):?>
                        <option value="<?php echo $cyear;?>" <?php echo $seeker_academic->end_date && substr($seeker_academic->end_date, 0, 4) == $cyear ? 'selected' : ''; ?>>
                            <?php echo $cyear;?>
                        </option>
                        <?php endfor;?>
                        </select>      
                    </div>                  
                    <div class="col-md-4">
                        <select class="form-control" name="month_end_date" id="ed_completion_month" required>
                        <option value="">Mes</option>
                        
                        <?php for ($mnth = 1; $mnth <= 12; $mnth++):
                            $month = sprintf("%02s", $mnth);
                            $selected = "";
                            $dummy_date = '2014-'.$month.'-'.'01';
                        ?>
                        
                        <option value="<?php echo $month;?>" <?php echo $seeker_academic->end_date && substr($seeker_academic->end_date, 5, 2) == $month ? 'selected' : ''; ?>>
                            <?php echo ucwords(_date_locale_format('MMMM', strtotime($dummy_date)));?>
                        </option>
                        <?php endfor; ?>
                        </select>
                    </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nro. colegiatura (Opcional)</label>
                    <input type="text" name="tuition_number" class="form-control" placeholder="Nro. colegiatura" autocomplete="off" value="<?php echo $seeker_academic->tuition_number; ?>">
                </div>

                <div class="form-group">
                    <label style="font-weight: normal;"><input id="ed_studying" type="checkbox" name="ed_studying" value="true" <?php echo !$seeker_academic->end_date ? 'checked' : ''; ?>> Actualmente estoy estudiando</label>
                </div>

            </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="ed_edu_id" name="ed_edu_id" value="<?php echo $seeker_academic->ID; ?>" />
                <button type="submit" name="js_edit_edu_submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(function(){

        $( "#ed_studying" ).change(function(){

            var isSelected = $(this).is(':checked');

            $( "#ed_completion_year" ).attr({'disabled': isSelected, 'required': true});
            $( "#ed_completion_month" ).attr({'disabled': isSelected, 'required': true});

            if (isSelected) {
                $( "#ed_completion_month" ).closest('div').removeClass( "has-error" ); 
                $( "#ed_completion_month" ).removeAttr('required');
                $( '.month_end_date_err').remove();
                $( "#ed_completion_year" ).closest('div').removeClass( "has-error" ); 
                $( '.year_end_date_err').remove();
                $( "#ed_completion_year" ).removeAttr('required');
            }
        });

        function getCareers(select, params) {
            const url = "<?php echo site_url('general/jobseeker/seeker_academics/get_careers'); ?>";
            $(select).html('<option value="">Cargando...</option>').prop('disabled', true);

            $.get(url, params, function(res) {

                const results =  res.data ? res.data : [];
                $.each(results, function(i, row) {
                    $(select).append(`<option value="${row.code}" >${row.name}</option>`);
                });      
            }, 'json')
            .fail(function() {
                console.error('¡Ha ocurrido un error al tratar de listar las carreras!');
            }).always(function() {
                $(select).find("option:eq(0)").text("Seleccione");
                $(select).prop('disabled', false);
            }); 
        }

        function getInstitutions(select, params) {
            const url = "<?php echo site_url('general/jobseeker/seeker_academics/get_institutions'); ?>";
            $(select).html('<option value="">Cargando...</option>').prop('disabled', true);

            $.get(url, params, function(res) {

                const results =  res.data ? res.data : [];
                $.each(results, function(i, row) {
                    $(select).append(`<option value="${row.code}" >${row.name}</option>`);
                });      
            }, 'json')
            .fail(function() {
                console.error('¡Ha ocurrido un error al tratar de listar las instituciones!');
            }).always(function() {
                $(select).find("option:eq(0)").text("Seleccione");
                $(select).prop('disabled', false);
            }); 
        }

        $( '#frm_edit_education' ).submit(function(e){
            e.preventDefault();

            const data = $(this).serialize();
            const form = $(this);
            form.find('button[type="submit"]').prop('disabled', true);

            $.ajax({
                type: "POST",
                url: $(this).prop('action'),
                data: data,
                dataType: "json",
            })
            .done(function(res) {
                if (res.success) {
                    $('#edit_education_modal').modal('hide');
                    location.reload();
                } else {
                    toastr["error"](res.message);
                    form.find('button[type="submit"]').prop('disabled', false);
                }
            });
            return false;
        });

        $(document).on('change', '#frm_edit_education select[name="institution_educ_type"]', function(e){
            const select = $( '#frm_edit_education select[name="institution"]' );

            getInstitutions(select, {
                'institution_educ_type': $( '#frm_edit_education select[name="institution_educ_type"]').val(),
                'institution_type': $( '#frm_edit_education select[name="institution_type"]').val(),
            });
        });

        $(document).on('change', '#frm_edit_education select[name="institution_type"]', function(e){
            const select = $( '#frm_edit_education select[name="institution"]' );

            getInstitutions(select, {
                'institution_educ_type': $( '#frm_edit_education select[name="institution_educ_type"]').val(),
                'institution_type': $( '#frm_edit_education select[name="institution_type"]').val(),
            });
        });

        $(document).on('change', '#frm_edit_education select[name="institution"]', function(e){
            const select = $( '#frm_edit_education select[name="career"]' );

            getCareers(select, {
                'institution': $( '#frm_edit_education select[name="institution"]').val(),
            });
        });

        $( "#ed_studying" ).change();

        validate_range_dates('#ed_start_year', '#ed_start_month', '#ed_completion_year', '#ed_completion_month', '#ed_studying');
        $( '#ed_start_year' ).change();
    });
</script>

<!-- Ends edit Jobseeker Education --> 




