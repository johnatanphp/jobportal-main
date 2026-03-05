<style>
    table tr td, table tr th {
        padding: 4px;
    }
</style>

<?php echo $body; ?>
<br />

<?php 
    $areas = $this->Mof->get_belonging_areas_by_mof_id($mof->ID);

    $array_areas = [];

    foreach ($areas as $row) {
        $array_areas[] = $row->area_name;
    }

    $mof_areas = join(", ", $array_areas);
?>
<ul>
    <li><b>Nombre:</b> <?php echo $mof->job_title; ?></li>
    <li><b>Área perteneciente:</b> <?php echo $mof_areas; ?></li>
    <?php if (isset($edit_fields)): ?>
        <li><b>Campos editados:</b> <?php echo $edit_fields; ?></li>
    <?php endif; ?>
    <li><b>Creado por:</b> <?php echo $created_by; ?></li>
    <li><b>Código:</b> <?php echo $mof->code ? $mof->code : '-'; ?></li>
    <?php if (isset($edited_by)): ?>
        <li><b>Modificado por:</b> <?php echo $edited_by; ?></li>
    <?php endif; ?>
    <?php if (isset($disability_grade_edit)): ?>
        <li><b>Grados en discapacidad ha sido modificada</b></li>
    <?php endif; ?>
</ul>
<br />

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
        $disability_risk = 0; 
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
                $disability_risk = 1;
                
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
<?php endif; ?>
<?php if (isset($disability_eligibles)): ?>
    <br />
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
<?php endif; ?>

<?php if (isset($factor_valuations)): ?>

    <h4 class="sub-title-h3">
        Valorización del puesto
    </h4>
    
    <?php foreach ($factor_valuations as $row_factor): ?>
    
    <div class="col-md-12"style="padding: 5px;">
        <h5 style="padding: 5px;background:#f9f9f9;"><b><?php echo $row_factor['name']; ?></b></h5>

        <table class="table" width="100%" style="margin: 10px;">
        <tr>
            <?php if ($row_factor['automatic']): ?>
            <th width="20%" colspan="2" align="left">Factor</th>
            <?php else: ?>
            <td width="20%" align="left">Factor</td>
            <th width="40%" align="left"></th>
            <?php endif; ?>  
            <th width="10%" align="left">Grado</th>
            <th width="10%" align="left">Puntaje</th>
        </tr>
        <tr>
            <?php if ($row_factor['automatic']): ?>
            <td width="20%" colspan="2"><?php echo $row_factor['mof_factor_name'] ? $row_factor['mof_factor_name'] : '-'; ?></td>
            <?php else: ?>
            <td width="20%">
                <?php echo $row_factor['mof_factor_level'] ? $row_factor['mof_factor_level'] : '-'; ?> 
            </td>
            <td width="40%"><?php echo $row_factor['mof_factor_name'] ? $row_factor['mof_factor_name'] : '-'; ?></td>
            <?php endif; ?>
            
            <td width="10%"><?php echo $row_factor['mof_factor_grade'] ? $row_factor['mof_factor_grade'] : '-'; ?></td>
            <td width="10%"><?php echo $row_factor['mof_factor_score'] ? $row_factor['mof_factor_score'] : '-'; ?></td>
        </tr>
        </table>
    </div>
    <?php endforeach; ?>

<?php endif; ?>

<?php if (isset($factor_total_score)): ?>
    <div class="row">
        <div class="col-md-12">
            <h4 class="sub-title-h3">
                Total de Valorización
            </h4>
        <div>Puntaje total: <?php echo $factor_total_score; ?></div>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($structure_salary) && $structure_salary == true): ?>
    <?php 
        $company = $this->Company->find($mof->company_id);
        $country = $this->Country->find($company->country_id);
    ?>
    <br />

    <h4 class="sub-title-h3">
        Estructura Salarial
    </h4>

    <table width="100%">
        <tr>
            <td width="250"><label>Básico <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?></label></td>
            <td><?php echo $mof->basic_minimum; ?></td>
            <td><?php echo $mof->basic_maximum; ?></td>
        </tr>

        <?php if (isset($mof_benefits)): ?>
            <?php foreach ($mof_benefits as $row): ?>
                <tr>
                    <td><label><?php echo $row->benefit_name; ?> <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?></label></td>
                    <td>
                        <?php echo $row->minimum; ?>   
                    </td>
                    <td>
                        <?php echo $row->maximum; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        <tr>
            <td width="250"><label>Estereotipo</label></td>
            <td colspan="2"><?php echo $mof->stereotype; ?></td>
        </tr>
        <tr>
            <td width="250"><label>Factor diferenciador</label></td>
            <td colspan="2"><?php echo $mof->factor_differentiating; ?></td>
        </tr>

        <?php if ($mof->factor_differentiating == 'Otro'): ?>
            <tr>
                <td width="250"><label>Factor diferenciador Otro</label></td>
                <td colspan="2"><?php echo $mof->factor_differentiating_other; ?></td>
            </tr>
        <?php endif; ?>

        <?php 
            $occupational_category = $this->Occupational_category->find($mof->occupational_category_id);
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
    <a href='<?php echo site_url('admin/mofs/show/' . $mof->ID); ?>'> Ver MOF</a>
<?php else: ?>
    <a href='<?php echo site_url('employer/mofs/mofs/show/' . $mof->ID); ?>'> Ver MOF</a>
<?php endif; ?>
