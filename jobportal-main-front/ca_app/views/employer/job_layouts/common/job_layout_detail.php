<?php 
    if (!isset($export_pdf)) {
        $export_pdf = false;
    }
?>
<style type="text/css">
  
    .tbl-style-1  { 
        font-size: 13px;
        border-spacing: 0;
        border-collapse: collapse;
    }

    .tbl-style-1 th {
        font-weight: normal;
        text-align: left;
    }

    .tbl-style-1 td {
        color: #333;
    }

    .tbl-style-1 th, 
    .tbl-style-1 td {
        padding: 7px;
        border:0px solid #bbbbbb;
    }

    .tbl-style-1 tr:nth-child(even) {
        background-color: #fff;
    }

    .tbl-style-1 tr:nth-child(odd) {
        background-color: #f5f5f5;
    }

    .section-title {
        font-size: 15px;
        text-transform: uppercase;
        diplay: block;
        border-bottom: 1px solid #ccc;
        padding: 8px 5px;
        margin-top: 25px;
    }
</style>
<?php if ($export_pdf == true): ?>
    <table width="100%" border="1" class="tbl-style-1" style="border:1px solid #333;">
        <tr>
            <td style="border:1px solid #333;background-color: #fff;" width="200">
                <img src="<?php echo base_url('public/images/overall_blue.png'); ?>">
            </td>
            <td align="center" style="border:1px solid #333;background-color: #fff;">
                DESCRIPCIÓN DEL PUESTO
            </td>
            <td width="100" style="border:1px solid #333;background-color: #fff;">
                <?php echo $job_layout->version; ?>
            </td>
        </tr>
    </table>
<?php endif; ?>

<h3 class="section-title">DESCRIPCIÓN DEL PUESTO</h3>
<table width="100%" class="tbl-style-1">
    <tr>
        <td width="250">ID</td>
        <td><?php e($job_layout->id); ?></td>
    </tr>
    <tr>
        <td width="250">Código</td>
        <td><?php e($job_layout->code ? $job_layout->code : '-'); ?></td>
    </tr>
    <tr>
        <td width="250">Código integración</td>
        <td><?php e($job_layout->code_integration ? $job_layout->code_integration : '-'); ?></td>
    </tr>
    <?php if ($country->iso_3166_1_alpha2 == 'PE'): ?>
        <tr>
            <td width="250">Código SUNAT</td>
            <td><?php e($sunat_code ? $sunat_code->code . ' - ' . $sunat_code->description : '-'); ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td width="250">Nombre del cargo</td>
        <td><?php e($job_layout->job_title); ?></td>
    </tr>
    <tr>
        <td width="250">Grupo ocupacional</td>
        <td><?php e(!empty($jl_occupational_group) ? $jl_occupational_group->charge_name  : '-'); ?></td>
    </tr>
    <tr>
        <td width="250">Criterio de riesgo</td>
        <td><?php e(!empty($job_layout->risk_criteria) ? $job_layout->risk_criteria : '-'); ?></td>
    </tr>
</table>

<h3 class="section-title">Educación Deseable</h3>

<table width="100%" class="tbl-style-1">
    <tr>
        <td width="250">Grado de estudio</td>
        <td>
            <?php
                $study_grade_req = $this->Qualification->get_record_by_id($job_layout->study_grade_req);
                e($study_grade_req['text']);
            ?>
        </td>
    </tr>
    <tr>
        <td width="250">Más detalle</td>
        <td><?php e($job_layout->education_req_detail); ?></td>
    </tr>
</table>

<h3 class="section-title">Educación Mínima</h3>

<table width="100%" class="tbl-style-1">
    <tr>
        <td width="250">Grado de estudio</td>
        <td>
            <?php
                $study_grade_min = $this->Qualification->get_record_by_id($job_layout->study_grade_min);
                e($study_grade_min['text']);
            ?>
        </td>
    </tr>
    <tr>
        <td width="250">Más detalle</td>
        <td><?php e($job_layout->education_min_detail); ?></td>
    </tr>
</table>

<h3 class="section-title">Formación</h3>

<table width="100%" class="tbl-style-1">
    <tr>
        <td><?php e($job_layout->education); ?></td>
    </tr>
</table>

<h3 class="section-title">Experiencia</h3>

<table width="100%" class="tbl-style-1">
    <tr>
        <td width="250">Año(s) de experiencia</td>
        <td>
            <?php
                e(($this->Work_experience->find(['code' => $job_layout->experience]))->name);
            ?>
        </td>
    </tr>

    <tr>
        <td width="250">Más detalle</td>
        <td><?php e($job_layout->experience_detail); ?></td>
    </tr>
</table>

<h3 class="section-title">Habilidades</h3>

<table width="100%" class="tbl-style-1">
    <?php foreach ($jl_skills as $row_skill): ?>
        <tr>
            <td><?php e($row_skill->skill_name); ?></td>
        </tr>
    <?php endforeach; ?>
    <?php foreach ($go_skills as $row_skill): ?>
        <tr>
            <td><?php e($row_skill->skill_name); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h3 class="section-title">Responsabilidades</h3>

<table width="100%" class="tbl-style-1">
    <?php foreach ($jl_responsibilities as $index => $responsibility): ?>
        <?php $responsibility = $responsibility->responsibility; ?>
        <tr>
            <td width="5%"><span class="counter"><?php e($index + 1); ?></span></td>
            <td width="80%"><?php e($responsibility); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if ($export_pdf == false || ($export_pdf == true && $export_filters['salary_structure'] == 1)): ?>
    <?php if ($this->session->userdata('current_profile_id') != 4): ?>

        <h3 class="section-title">Estructura Salarial</h3>

        <table id="tbl-struct-salary" class="tbl-style-1" width="100%">
            <tr>
                <th width="250"></th>
                <th>Mínimo <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?></th>
                <th>Máximo <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?></th>
            </tr>
            <tr>
                <td>Básico</td>
                <td>
                    <?php echo $job_layout->basic_minimum; ?>
                </td>
                <td>
                    <?php echo $job_layout->basic_maximum; ?>
                </td>
            </tr>
            <tr>
                <td width="250">Estereotipo</td>
                <td colspan="2"><?php e($job_layout->stereotype); ?></td>
            </tr>
            <tr>
                <td width="250">Factor diferenciador</td>
                <td colspan="2"><?php e($job_layout->factor_differentiating); ?></td>
            </tr>

            <?php if ($job_layout->factor_differentiating == 'Otro'): ?>
                <tr>
                    <td width="250">Factor diferenciador Otro</td>
                    <td colspan="2"><?php e($job_layout->factor_differentiating_other); ?></td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>
<?php endif; ?>

<?php if ($export_pdf == false || ($export_pdf == true && $export_filters['disability'] == 1)): ?>

    <?php if (isset($jl_disability_options)): ?>
        <?php foreach ($jl_disability_options as $row_theme): ?>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="section-title">
                        <?php echo 'PERMITIR ' . $row_theme->name; ?>
                    </h3>
                    <table width="100%" class="tbl-style-1">
                        <?php foreach ($row_theme->subthemes as $row_subtheme): ?>
                                <tr>
                                    <td style="font-size:14px;" width="35%"><?php echo $row_subtheme->name; ?></td>
                                    <td style="font-size:14px;">
                                        <?php if ($row_subtheme->jl_disability_allow !== null): ?>
                                            <?php echo $row_subtheme->jl_disability_allow == 1 ? 'SI' : 'NO'; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($jl_results_disability)): ?>
        <?php foreach ($jl_results_disability as $row_theme): ?>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="section-title">
                        <?php echo $row_theme->name; ?>
                    </h3>
                    <?php foreach ($row_theme->subthemes as $row_subtheme): ?>
                    <div class="col-md-6">
                        <br>
                        <table width="100%">
                            <tr>
                                <td style="font-size:14px;"><b><?php echo $row_subtheme->name; ?></b></td>
                            </tr>
                        </table>
                        <br>
                        <?php foreach ($row_subtheme->sections as $row_section): ?>
                        <div style="padding: 5px 3px;">
                            <table class="tbl-style-1" width="100%">
                                <tr>
                                    <th style="font-size:13px;">
                                        <b><?php e($row_section->name); ?></b>
                                    </th>
                                    <th style="font-size:13px;">
                                        <b>Grado</b>
                                    </th>
                                </tr>
                                <?php foreach ($row_section->items as $key => $row_item): ?>
                                    <tr>
                                        <td width="60%"><?php e($row_item->name); ?></td>
                                        <td>
                                            <?php 
                                                e($row_item->grade); 
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <br>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($jl_disability_values)): ?>
        <h3 class="section-title">Valorización del puesto con discapacidad</h3>
        <table width="100%" class="tbl-style-1">
            <tr>
                <th><b>Tipo de discapacidad</b></th>
                <th><b>Valorización</b></th>
                <th><b>Nivel de riesgo</b></th>
                <th><b>Puesto adaptable con discapacidad</b></th>
            </tr>

            <?php 
                $disability_risk = 1;
                $disability_value_sum = array_sum(array_column($jl_disability_values, 'value')); 

                $value_color = [
                    1 => 'green',
                    2 => 'yellow',
                    3 => 'red'
                ];
                $value_risk_level = [
                    1 => 'BAJO',
                    2 => 'TOLERABLE',
                    3 => 'ALTO'
                ]; 
                $value_adaptable_position = [
                    1 => 'ADAPTABLE',
                    2 => 'ADAPTABLE PARCIALMENTE',
                    3 => 'NO ADAPTABLE'
                ];
                
                $value_measures = [
                    1 => 'LAS "CONDICIONES DE TRABAJO" DEL PUESTO SON ADECUADAS PARA INCLUIR PERSONAL CON DISCAPACIDAD',
                    2 => 'REQUIERE REVISIÓN POR LA GERENCIA/JEFATURA DEL PUESTO PARA DESPLEGAR RECURSOS ADICIONALES ANTES DE ASIGNAR PERSONAL CON DISCAPACIDAD',
                    3 => 'LAS "CONDICIONES DE TRABAJO" DEL PUESTO NO ES APTO PARA PERSONAS CON DISCAPACIDAD'
                ];
            ?>
            
            <?php foreach ($jl_disability_values as $value): ?>

                <?php 
                    $value_status = 0;

                    if ($value['value'] >= 15 && $value['value'] <= 17) {
                        $value_status = 1;
                        $disability_risk = 0;
                    }
                    
                    if ($value['value'] >= 18 && $value['value'] <= 20) {
                        $value_status = 2;
                        $disability_risk = 0;
                    }

                    if ($value['value'] >= 21 && $value['value'] <= 45) {
                        $value_status = 3;
                    }
                    
                    $disability_value_info = isset($value_measures[$value_status]) ? $value_measures[$value_status] : ''; 
                ?>
                <tr>
                    <td align="center">
                        <?php echo $value['theme'] . ' <br/>' . '(' . $value['subtheme'] . ')'; ?>
                    </td>
                    <td align="center" style="color: #000; <?php echo isset($value_color[$value_status]) ? ' background: ' . $value_color[$value_status] . ';' : ''; ?>">
                        <?php echo $value['value'] > 0 ? '<a class="disabilily-value-info" href="#" style="color:#000;text-decoration:underline;text-decoration-style: dotted;" data-tippy-content="' . htmlentities($disability_value_info). '">' . $value['value'] . '</a>' : '-'; ?>
                    </td>
                    <td align="center">
                        <?php echo isset($value_risk_level[$value_status]) ? $value_risk_level[$value_status] : '-'; ?>
                    </td align="center">
                    <td align="center">
                        <?php echo isset($value_adaptable_position[$value_status]) ? $value_adaptable_position[$value_status] : '-'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
   
        <h3 class="section-title">Discapacidades aptas para el puesto</h3>

        <?php if ($disability_risk == 1): ?>
            <table width="100%" class="tbl-style-1">
                <tr>
                    <td>
                        Las condiciones del trabajo del puesto no es apto para personas con discapacidad
                    </td>
                </tr>
            </table>
        <?php endif; ?>

        <?php if ($disability_risk == 0): ?>
            <div style="text-align: right;">
                <?php if ($this->session->userdata('current_profile_id') == 4 && 
                            $job_layout->active == 0 && 
                            $disability_value_sum > 0): ?>
                    <a href="#" data-toggle="modal" data-target="#modal-add-disability-eligibles">
                        <?php echo count($jl_disability_eligibles) > 0 ? 'Editar' : 'Registrar'; ?>
                    </a>
                <?php endif; ?>
            </div>
            <table class="tbl-style-1" width="100%">
                <thead>
                    <tr>
                        <th>
                            <b>Discapacidades</b>
                        </th>
                        <th>
                            <b>Recursos</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jl_disability_eligibles as $row): ?>
                        <tr>
                            <td><?php echo $row->disability; ?></td>
                            <td><?php echo $row->resources; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<?php if ($export_pdf == false || ($export_pdf == true && $export_filters['valorization'] == 1)): ?>
    <h3 class="section-title">Valorización del puesto</h3>
    <div class="row">
        <div class="col-md-12">        
            <?php foreach ($jl_factor_valuations as $row_factor): ?>
            
            <div class="col-md-12" style="padding: 5px;">
                <h5 style="padding: 5px;background:#f9f9f9;"><b><?php echo $row_factor['name']; ?></b></h5>

                <table class="tbl-style-1" width="100%" style="margin: 10px;">
                    <tr>
                        <?php if ($row_factor['automatic']): ?>
                            <td width="40%" colspan="2">Factor</td>
                        <?php else: ?>
                            <td width="40%">Factor</td>
                            <th width="40%"></th>
                        <?php endif; ?>  
                        <th width="10%">Grado</th>
                        <th width="10%">Puntaje</th>
                    </tr>
                    <tr>
                        <?php if ($row_factor['automatic'] == 0): ?>
                            <td width="40%">
                                <?php echo $row_factor['value_factor_level'] ? $row_factor['value_factor_level'] : '-'; ?> 
                            </td>
                            <td width="40%"><?php echo $row_factor['value_factor_name'] ? $row_factor['value_factor_name'] : '-'; ?></td>
                        <?php else: ?>
                            <td width="40%" colspan="2"><?php echo $row_factor['value_factor_name'] ? $row_factor['value_factor_name'] : '-'; ?></td>
                        <?php endif; ?>
                        
                        <td width="10%"><?php echo $row_factor['value_factor_grade'] ? $row_factor['value_factor_grade'] : '-'; ?></td>
                        <td width="10%"><?php echo $row_factor['value_factor_score'] ? $row_factor['value_factor_score'] : '-'; ?></td>
                    </tr>
                </table>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <h3 class="section-title">Total de Valorización</h3>
    <table width="100%" class="tbl-style-1">
        <tr>
            <td width="100">
                Puntaje total
            </td>
            <td>
                <?php e($jl_factor_total_score); ?>
            </td>
        </tr>
    </table>
<?php endif; ?>

<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script type="text/javascript">
    tippy('.disabilily-value-info', {trigger: 'click'});
</script>