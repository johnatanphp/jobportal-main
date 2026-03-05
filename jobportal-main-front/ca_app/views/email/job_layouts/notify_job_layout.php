
<style>
    .container-btn-show a {
        padding: 10px 15px;
		text-align: center;
		color:  #fff;
		background: #005da4;
		border-radius: 5px;
		font-weight: bold;
		text-decoration: none;
		font-size: 15px;
    }

    .table-style-1 {
        border-spacing: 1px;
        border-collapse: collapse;
        width: 100%;
    }
    
    .table-style-1 tr td {
        border: 1px solid #333;
        padding: 4px 2px;
        font-size: 12px;
    }

    .table-style-1 tr th {
        border: 1px solid #333;
        padding: 5px;
        font-size: 13px;
        background: #eee;
        text-align: left;
    }
</style>
<?php echo $body; ?>
<br />
<br />
<table class="table-style-1">
    <tr>
        <tr>
            <th colspan="2">DATOS DEL LAYOUT DE PUESTO</th>
        </tr>
    </tr>
    <tr>
        <td width="150" style="background: #eee;font-weight: bold;">Nombre</td>
        <td><?php e($job_layout->job_title); ?></td>
    </tr>
    <tr>
        <td style="background: #eee;font-weight: bold;">Creado por</td>
        <td><?php e($created_by); ?></td>
    </tr>
    <tr>
        <td style="background: #eee;font-weight: bold;">Código</td>
        <td><?php e($job_layout->code ? $job_layout->code : '-'); ?></td>
    </tr>
    <?php if (isset($edited_by)): ?>
        <tr>
            <td style="background: #eee;font-weight: bold;">Modificado por</td>
            <td><?php echo $edited_by; ?></td>
        </tr>
    <?php endif; ?>
    <?php if (isset($edit_fields)): ?>
        <tr>
            <td style="background: #eee;font-weight: bold;">Recursos han sido modificados</td>
            <td><?php echo $edit_fields; ?></td>
        </tr>
    <?php endif; ?>
    <?php if (isset($disability_grade_edit)): ?>
        <tr>
            <td colspan="2" style="background: #eee;font-weight: bold;">
                Grados en discapacidad ha sido modificada
            </td>
        </tr>
    <?php endif; ?>
</table>
<br />
<?php if (isset($disability_options)): ?>
    <?php foreach ($disability_options as $row_theme): ?>
        <table class="table-style-1">
            <tr>
                <th colspan="2"><?php echo 'PERMITIR ' . $row_theme->name; ?></th>
            </tr>
            <?php foreach ($row_theme->subthemes as $row_subtheme): ?>
                <tr>
                    <td width="150"><?php echo $row_subtheme->name; ?></td>
                    <td width="150">
                        <?php if ($row_subtheme->jl_disability_allow !== null): ?>
                            <?php echo $row_subtheme->jl_disability_allow == 1 ? 'SI' : 'NO'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>  
            <?php endforeach; ?>
        </table>
        <br>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($disability_values)): ?>
<h4>Valorización</h4>
<br />
<table class="table table-striped" width="100%">
    <tr>
    <th>Tipo de discapacidad</th>
    <th>Valorización</th>
    <th>Nivel de riesgo</th>
    <th>Puesto adaptable con discapacidad</th>
    </tr>

    <?php
    $disability_risk = 1;
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
            <?php echo $value['value'] > 0 ? $value['value'] : '-'; ?>
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
<br>
<?php endif; ?>

<?php if (isset($disability_eligibles)): ?>
    <h4>Discapacidades aptas</h4>

    <?php if ($disability_risk == 1): ?>
        Las condiciones del trabajo del puesto no es apto para personas con discapacidad
    <?php endif; ?>

    <?php if ($disability_risk == 0): ?>
        <table class="table" width="90%">
            <thead>
                <tr>
                    <th align="left">
                        Discapacidades
                    </th>
                    <th align="left">Recursos</th>
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
    <br>
<?php endif; ?>

<?php if (isset($factor_valuations)): ?>
    <table class="table-style-1" style="border-bottom: none;">
        <tr><th colspan="5"  style="border-bottom: none;">Valorización del puesto</th></tr>
    </table>
    <?php foreach ($factor_valuations as $row_factor): ?>
        <table class="table-style-1">
            <tr>
                <th colspan="5"><?php echo $row_factor['name']; ?></th>
            </tr>
            <tr>
                <?php if ($row_factor['automatic']): ?>
                    <th width="20%" colspan="2" align="left">Factor</th>
                <?php else: ?>
                    <th width="20%" align="left">Factor</th>
                    <th width="40%" align="left"></th>
                <?php endif; ?>  
                    <th width="10%" align="left">Grado</th>
                    <th width="10%" align="left">Puntaje</th>
            </tr>
            <tr>
                <?php if ($row_factor['automatic']): ?>
                    <td width="20%" colspan="2"><?php echo $row_factor['value_factor_name'] ? $row_factor['value_factor_name'] : '-'; ?></td>
                <?php else: ?>
                    <td width="20%">
                        <?php echo $row_factor['value_factor_level'] ? $row_factor['value_factor_level'] : '-'; ?> 
                    </td>
                    <td width="40%"><?php echo $row_factor['value_factor_name'] ? $row_factor['value_factor_name'] : '-'; ?></td>
                <?php endif; ?>
                <td width="10%"><?php echo $row_factor['value_factor_grade'] ? $row_factor['value_factor_grade'] : '-'; ?></td>
                <td width="10%"><?php echo $row_factor['value_factor_score'] ? $row_factor['value_factor_score'] : '-'; ?></td>
            </tr>
        </table>
        <br>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($factor_total_score)): ?>
    <table class="table-style-1">
        <tr>
            <th>PUNTAJE VALORIZACIÓN DE PUESTO</th>
        </tr>
        <tr>
            <td>Total: <?php echo $factor_total_score; ?></td>
        </tr>
    </table>
    <br>
<?php endif; ?>

<?php if (isset($structure_salary) && $structure_salary == true): ?>

    <table class="table-style-1">
        <tr>
            <th colspan="3">ESTRUCTURA SALARIAL</th>
        </tr>
        <?php 
            $company = $this->Company->find($job_layout->company_id);
            $country = $this->Country->find($company->country_id);
        ?>
        <tr>
            <th width="250">Beneficio</th>
            <th>Máximo</th>
            <th>Mínimo</th>
        </tr>
        <tr>
            <td width="250"><label>Básico <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?></label></td>
            <td><?php echo $job_layout->basic_minimum; ?></td>
            <td><?php echo $job_layout->basic_maximum; ?></td>
        </tr>
        <tr>
            <td width="250"><label>Estereotipo</label></td>
            <td colspan="2"><?php echo $job_layout->stereotype; ?></td>
        </tr>
        <tr>
            <td width="250"><label>Factor diferenciador</label></td>
            <td colspan="2"><?php echo $job_layout->factor_differentiating; ?></td>
        </tr>

        <?php if ($job_layout->factor_differentiating == 'Otro'): ?>
            <tr>
                <td width="250"><label>Factor diferenciador Otro</label></td>
                <td colspan="2"><?php echo $job_layout->factor_differentiating_other; ?></td>
            </tr>
        <?php endif; ?>

        <?php 
            $occupational_category = $this->Occupational_category->find($job_layout->occupational_category_id);
        ?>
        <tr>
            <td width="250"><label>Categoría ocupacional</label></td>
            <td colspan="2"><?php echo $occupational_category ? $occupational_category->name : '-'; ?></td>
        </tr>
        <tr>
            <td width="250"><label>Nivel Categoría ocupacional</label></td>
            <td colspan="2"><?php echo $occupational_category ? $occupational_category->level : '-'; ?></td>
        </tr>
    </table>
    <br>
    <div class="container-btn-show" style="text-align:center;">
        <a href='<?php echo site_url('admin/job_layouts/show/' . $job_layout->id); ?>'>Ver</a> 
    </div>
<?php else: ?>
    <br>
    <div class="container-btn-show" style="text-align:center;">
        <a href='<?php echo site_url('employer/job_layouts/job_layouts/show/' . $job_layout->id); ?>'>Ver</a>
    </div>
<?php endif; ?>
