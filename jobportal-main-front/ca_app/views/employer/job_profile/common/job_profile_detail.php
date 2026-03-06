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
                <?php echo $job_profile->version; ?>
            </td>
        </tr>
    </table>
<?php endif; ?>
<h3 class="section-title">Datos del cliente</h3>
<table width="100%" class="tbl-style-1">
    <tr>
        <td width="250">Consultora</td>
        <td>
            <?php e($job_profile->consultant_name); ?>
        </td>
    </tr>
    <tr>
        <td width="250">Unidad de negocio</td>
        <td>
            <?php e($job_profile->business_unit_name); ?>
        </td>
    </tr>
    <tr>
        <td width="250">Empresa cliente</td>
        <td>
            <?php e($job_profile->client_company_name); ?>
        </td>
    </tr>
    <tr>
        <td width="250">Centro de costo</td>
        <td>
            <?php e($job_profile->cost_center); ?>
        </td>
    </tr>
</table>

<h3 class="section-title">DESCRIPCIÓN DEL PUESTO</h3>
<table width="100%" class="tbl-style-1">
    <tr>
        <td width="250">ID perfil</td>
        <td><?php e($job_profile->ID); ?></td>
    </tr>
    <tr>
        <td width="250">Código del perfil</td>
        <td><?php e($job_profile->code ? $job_profile->code : '-'); ?></td>
    </tr>
    <tr>
        <td width="250">Código SUNAT</td>
        <td><?php e($job_profile->sunat_code ? $job_profile->sunat_code : '-'); ?></td>
    </tr>
    <tr>
        <td width="250">Nombre del cargo</td>
        <td><?php e($job_profile->job_title); ?></td>
    </tr>
    <tr>
        <td width="250">Grupo ocupacional</td>
        <td><?php e(!empty($occupational_group) ? $occupational_group->charge_name  : '-'); ?></td>
    </tr>
    <tr>
        <td width="250">Criterio de riesgo</td>
        <td><?php e(!empty($job_profile->risk_criteria) ? $job_profile->risk_criteria : '-'); ?></td>
    </tr>
</table>

<h3 class="section-title">Educación Deseable</h3>

<table width="100%" class="tbl-style-1">
    <tr>
        <td width="250">Grado de estudio</td>
        <td>
            <?php
                $study_grade_req = $this->Qualification->get_record_by_id($job_profile->study_grade_req);
                e($study_grade_req ? $study_grade_req['text'] : '-');
            ?>
        </td>
    </tr>
    <tr>
        <td width="250">Más detalle</td>
        <td><?php e($job_profile->education_req_detail); ?></td>
    </tr>
</table>

<h3 class="section-title">Educación Mínima</h3>

<table width="100%" class="tbl-style-1">
    <tr>
        <td width="250">Grado de estudio</td>
        <td>
            <?php
                $study_grade_min = $this->Qualification->get_record_by_id($job_profile->study_grade_min);
                e($study_grade_min ? $study_grade_min['text'] : '-');
            ?>
        </td>
    </tr>
    <tr>
        <td width="250">Más detalle</td>
        <td><?php e($job_profile->education_min_detail); ?></td>
    </tr>
</table>

<h3 class="section-title">Formación</h3>

<table width="100%" class="tbl-style-1">
    <tr>
        <td><?php e($job_profile->education); ?></td>
    </tr>
</table>

<h3 class="section-title">Experiencia</h3>

<table width="100%" class="tbl-style-1">
    <tr>
        <td width="250">Año(s) de experiencia</td>
        <td>
            <?php
                e(($this->Work_experience->find(['code' => $job_profile->experience]))->name);
            ?>
        </td>
    </tr>

    <tr>
        <td width="250">Más detalle</td>
        <td><?php e($job_profile->experience_detail); ?></td>
    </tr>
</table>

<h3 class="section-title">Habilidades</h3>

<table width="100%" class="tbl-style-1">
    <?php foreach ($job_profile_skills as $row_skill): ?>
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
    <?php foreach ($job_profile_responsibilities as $index => $responsibility): ?>
        <?php $responsibility = $responsibility->responsibility; ?>
        <tr>
            <td width="5%"><span class="counter"><?php e($index + 1); ?></span></td>
            <td width="80%"><?php e($responsibility); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if ($export_pdf == false || ($export_pdf == true && $export_filters['resource'] == 1)): ?>
    <h3 class="section-title">
        Recursos
        <?php if ($this->session->userdata('current_profile_id') == 4): ?>
            <a id="edit-profile-resources" 
            class="pull-right" 
            href="#" 
            style="text-transform:none;" 
            data-toggle="modal" 
            data-target="#modal-edit-profile-resources">
                Editar
            </a>
        <?php endif; ?>
    </h3>
    <br>
    <div class="alert <?php echo $job_profile->occupational_exams_approved ? 'alert-success' : 'alert-warning'; ?>">
        <span style="text-transform: uppercase;font-size:14px;">Validación de exámenes ocupacionales: <b><?php echo $job_profile->occupational_exams_approved ? 'APROBADO' : 'SIN APROBAR'; ?></b></span>
        <?php if ($job_profile->active == 0 && $this->session->userdata('current_profile_id') == 4): ?>
            <button id="occupational-exams-approved" 
                    class="btn btn-primary btn-xs pull-right"
                    data-id="<?php echo $job_profile->ID; ?>" 
                    data-approved="<?php echo !$job_profile->occupational_exams_approved; ?>">
                <?php echo $job_profile->occupational_exams_approved ? 'Quitar aprobación' : 'Aprobar'; ?>
            </button>
        <?php endif; ?>
    </div>
    <br>
    <?php 
        $rys_stages = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];

        $resources_types = [
            'type_screening' => 'Tipo Screening',
            'type_emo' => 'Tipo EMO',
            'exam_type_covid' => 'Examen COVID 19',
            'exams_complementary' => 'Exámenes complementarios',
            'home_verification' => 'Verificación domiciliaria',
            'credit_verification' => 'Verificación crediticia',
            'labor_verification' => 'Verificación laboral',
            'degrees_titles_verification' => 'Verificación de grados y titulos',
            'degrees_titles_person_verification' => 'Verificación de grados y titulos presenciales'
        ];
    ?>

    <table width="100%" class="tbl-style-1">
        <tr>
            <th><b>Nombre</b></th>
            <th><b>Tipo / Valor</b></th>
            <th><b>Tipo egreso</b></th>
            <th><b>Realizar en</b></th>
            <th><b>Encargado</b></th>
        </tr>

        <?php foreach ($resources_types as $resource_key => $resource_name): ?>

            <?php 
                $resource_row = $this->Job_profile->get_resource_by_name($resource_key, $job_profile->ID);    

                $resource_type = $resource_row->resource_value;

                if ($resource_key == 'exam_type_covid') {

                    $covid_array = get_options_exam_type_covid();
                    $covid19_values = explode(',', $resource_type);
                    
                    $covid19_list = [];
                    foreach ($covid19_values as $val) {
                        $val = trim($val);
                        
                        if (isset($covid_array[$val])) {
                            $covid19_list[] = $covid_array[$val];
                        }
                    }

                    $resource_type = implode(', ', $covid19_list);
                }
            ?>

            <tr>
                <td><?php e($resource_name); ?></td>
                <td>
                    <?php 
                        if ($resource_type == '1') {
                            e('SI');
                        }
                        
                        if ($resource_type == '0') {
                            e('NO');
                        }

                        if ($resource_type != '0' && $resource_type != '1') {
                            e($resource_type);
                        }
                    ?>
                </td>
                <td><?php e($resource_row->type_expense); ?></td>
                <td><?php e(isset($rys_stages[$resource_row->perform_on_stage]) ? $rys_stages[$resource_row->perform_on_stage] : '-'); ?></td>
                <td><?php e(!empty($resource_row->staff_in_charge) ? $resource_row->staff_in_charge : '-'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php if ($export_pdf == false || ($export_pdf == true && $export_filters['disability'] == 1)): ?>
    <?php foreach ($results_disability as $row_theme): ?>
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
            $disability_value_sum = array_sum(array_column($disability_values, 'value')); 

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
        
        <?php foreach ($disability_values as $value): ?>

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
                        $job_profile->active == 0 && 
                        $disability_value_sum > 0): ?>
                <a href="#" data-toggle="modal" data-target="#modal-add-disability-eligibles">
                    <?php echo count($disability_eligibles) > 0 ? 'Editar' : 'Registrar'; ?>
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
                <?php foreach ($disability_eligibles as $row): ?>
                    <tr>
                        <td><?php echo $row->disability; ?></td>
                        <td><?php echo $row->resources; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>

<?php if ($export_pdf == false || ($export_pdf == true && $export_filters['valorization'] == 1)): ?>
    <h3 class="section-title">Valorización del puesto</h3>
    <div class="row">
        <div class="col-md-12">        
            <?php foreach ($jp_factor_valuations as $row_factor): ?>
            
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
                                <?php echo $row_factor['jp_factor_level'] ? $row_factor['jp_factor_level'] : '-'; ?> 
                            </td>
                            <td width="40%"><?php echo $row_factor['jp_factor_name'] ? $row_factor['jp_factor_name'] : '-'; ?></td>
                        <?php else: ?>
                            <td width="40%" colspan="2"><?php echo $row_factor['jp_factor_name'] ? $row_factor['jp_factor_name'] : '-'; ?></td>
                        <?php endif; ?>
                        
                        <td width="10%"><?php echo $row_factor['jp_factor_grade'] ? $row_factor['jp_factor_grade'] : '-'; ?></td>
                        <td width="10%"><?php echo $row_factor['jp_factor_score'] ? $row_factor['jp_factor_score'] : '-'; ?></td>
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
                <?php e($jp_factor_total_score); ?>
            </td>
        </tr>
    </table>
<?php endif; ?>

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
                    <?php echo $job_profile->basic_minimum; ?>
                </td>
                <td>
                    <?php echo $job_profile->basic_maximum; ?>
                </td>
            </tr>

            <?php foreach ($jp_benefits as $row): ?>
                <tr>
                    <td><?php echo $row->benefit_name; ?></td>
                    <td>
                        <?php echo $row->minimum; ?>   
                    </td>
                    <td>
                        <?php echo $row->maximum; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <td width="250">Estereotipo</td>
                <td colspan="2"><?php e($job_profile->stereotype); ?></td>
            </tr>
            <tr>
                <td width="250">Factor diferenciador</td>
                <td colspan="2"><?php e($job_profile->factor_differentiating); ?></td>
            </tr>

            <?php if ($job_profile->factor_differentiating == 'Otro'): ?>
                <tr>
                    <td width="250">Factor diferenciador Otro</td>
                    <td colspan="2"><?php e($job_profile->factor_differentiating_other); ?></td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>
<?php endif; ?>

<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script type="text/javascript">
    tippy('.disabilily-value-info', {trigger: 'click'});
</script>