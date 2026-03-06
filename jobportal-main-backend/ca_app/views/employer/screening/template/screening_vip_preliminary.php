<style>
    #modal-show-screening div, 
    #modal-show-screening table tr td, 
    #modal-show-screening table tr th{
        font-size: 11px;
    }

    .tbl-style-1  { 
        border-spacing: 0;
        border-collapse: collapse;
    }

    .tbl-style-1 th {
        font-weight: normal;
        background: #064185;
        color: #ffffff;
        text-align: left;
        
    }

    .tbl-style-1 td {
        color: #000000;
    }

    .tbl-style-1 th, 
    .tbl-style-1 td {
        padding: 7px;
        border:1px solid #bbbbbb;
    }
</style>

<div style="padding: 8px 5px;">
    <?php echo date('d/m/Y H:i', strtotime($screening->created_at)); ?>
</div>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th colspan="3" align="left">
            DATOS GENERALES
        </th>
    </tr>
    <tr>
        <td width="200">
            N° de Documento:
        </td>
        <td>
            <?php echo $result_data->documentNumber . ' - ' . $result_data->checkDigit; ?>
        </td>
        <td rowspan="5" width="220" align="center">
            <?php if ($result_data->photo): ?>
                <img src="data:image/jpg;base64,<?php echo $result_data->photo; ?>" 
                    alt="" 
                    style="width: 100px;height:140px;">
            <?php else: ?>
                <img src="<?php echo base_url('public/images/no_pic.jpg'); ?>" 
                    alt="" 
                    style="width: 128px;height:180px;">
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>
            Ape. Paterno:
        </td>
        <td>
            <?php e($result_data->lastname); ?>
        </td>
    </tr>
    <tr>
        <td>
            Ape. Materno:
        </td>
        <td>
            <?php e($result_data->secondSurname); ?>
        </td>
    </tr>
    <tr>
        <td>
            Nombres:
        </td>
        <td>
            <?php e($result_data->firstname); ?>
        </td>
    </tr>
    <tr>
        <td>
            Fecha de Nacimiento:
        </td>
        <td>
            <?php e($result_data->dateBirth); ?>
        </td>
    </tr>
</table>
<br>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th colspan="2" align="left">
            DATOS DE DOMICILIO
        </th>
    </tr>
    <tr>
        <td width="200">
            Nombre del Padre:
        </td>
        <td>
            <?php e($result_data->fatherName); ?>
        </td>
    </tr>
    <tr>
        <td>
            Nombre de la Madre:
        </td>
        <td>
            <?php e($result_data->motherName); ?>
        </td>
    </tr>
    <tr>
        <td>
            Distrito de Domicilio:
        </td>
        <td>
            <?php e($result_data->districtHome); ?>
        </td>
    </tr>
    <tr>
        <td>
            Dpto. Prov y Dist de Domicilio:
        </td>
        <td>
            <?php e($result_data->departmentHome . ' ' . $result_data->provinceHome . ' ' . $result_data->districtHome); ?>
        </td>
    </tr>
    <tr>
        <td>
            Dirección de Domicilio:
        </td>
        <td>
            <?php e($result_data->address); ?>
        </td>
    </tr>
    <tr>
        <td>
            Dpto. Prov y Dist de Nacimiento:
        </td>
        <td>
            <?php e($result_data->departmentBirth . ' ' . $result_data->provinceBirth . ' ' . $result_data->districtBirth); ?>
        </td>
    </tr>
</table>
<br>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th align="left" colspan="2">
            NOTAS ADICIONALES
        </th>
    </tr>
    <tr>
        <td width="180">
            Género
        </td>
        <td>
            <?php e($result_data->gender); ?>
        </td>        
    </tr>
    <tr>
        <td>
            Estado Civil: 
        </td>
        <td>
            <?php e($result_data->civilStatus); ?>
        </td>        
    </tr>
    <tr>
        <td>
            Fecha de Inscripción: 
        </td>
        <td>
            <?php e($result_data->inscriptionDate); ?>
        </td>        
    </tr>
    <tr>
        <td>
            Fecha de Expedición: 
        </td>
        <td>
            <?php e($result_data->deliveryDate); ?>
        </td>        
    </tr>
    <tr>
        <td>
            Fecha de Caducidad:  
        </td>
        <td>
            <?php e($result_data->caducityDate); ?>
        </td>        
    </tr>
    <tr>
        <td>
            Grado de Instrucción 
        </td>
        <td>
            <?php e($result_data->instructionDegree); ?>
        </td>        
    </tr>
    <tr>
        <td>
            Restricción
        </td>
        <td>
            <?php e($result_data->restriction); ?>
        </td>        
    </tr>
</table>
<br>
<?php if (count($result_data->homonymns) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th colspan="6" align="left">
                HOMONIMOS (<?php echo count($result_data->homonymns); ?>)
            </th>
        </tr>
        <tr>
            <th>
                N 
            </th>
            <th>
                DNI 
            </th>
            <th>
                Apellido paterno
            </th>
            <th>
                Apellido materno
            </th>
            <th>
                Nombres
            </th>
            <th>
                Fecha nacimiento
            </th>
        </tr>

        <?php foreach($result_data->homonymns as $index => $row): ?>
            <tr>
                <td>
                    <?php e($index + 1); ?>
                </td>
                <td>
                    <?php e($row->numeroDocumento); ?>
                </td>
                <td>
                    <?php e($row->apellidoPaterno); ?>
                </td>
                <td>
                    <?php e($row->apellidoMaterno); ?>
                </td>
                <td>
                    <?php e($row->nombres); ?>
                </td>
                <td>
                    <?php e($row->fechaNacimiento); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
<?php endif; ?>
<?php $data_json = $result_data->dataJson; ?>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th align="left" colspan="4">
            NOTAS PRINCIPALES
        </th>
    </tr>
    <tr>
        <td width="140">RUC</td>
        <td width="180"><?php e($data_json->Ruc); ?></td>
        <td width="100">Razón social</td>
        <td><?php e($data_json->RazSoc); ?></td>
    </tr>
    <tr>
        <td>Tipo Contribuyente</td>
        <td><?php e($data_json->TipCont); ?></td>
        <td>Condición</td>
        <td><?php e($data_json->CondCont); ?></td>
    </tr>
    <tr>
        <td>Estado de Contribuyente</td>
        <td><?php e($data_json->EstDomic); ?></td>
        <td>CIUU</td> 
        <td><?php e($data_json->CIIU); ?></td>
    </tr>
    <tr>
        <td>Inicio de Actividades </td>
        <td><?php e($data_json->IniAct); ?></td>
        <td></td>
        <td></td>
    </tr>
</table>
<br>
<?php $directions = $result_data->dataJson->ListDirections; ?>
<?php if (count($directions) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="2">
                DIRECCIONES REGISTRADAS
            </th>
        </tr>
        <tr>
            <th width="60" style="text-align:center;">N</th>
            <th>Dirección</th>
        </tr>
        
        <?php foreach ($directions as $dir_n => $dir): ?>
            <tr>
                <td style="text-align:center;"><?php e($dir_n + 1); ?></td>
                <td><?php e($dir->direc); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
<?php endif; ?>

<?php $list_rep_legal = $result_data->dataJson->ListRepLegal; ?>
<?php if (count($list_rep_legal) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="6">
                ES REPRESENTANTE LEGAL DE
            </th>
        </tr>
        <tr>
            <th width="60" style="text-align:center;">N</th>
            <th>RUC</th>
            <th>Razón social</th>
            <th>Fecha</th>
            <th>Cargo</th>
            <th>Estado</th>
        </tr>
        
        <?php foreach ($list_rep_legal as $index => $row): ?>
            <tr>
                <td style="text-align:center;"><?php e($index + 1); ?></td>
                <td><?php e($row->numDoc); ?></td>
                <td><?php e($row->razSoc); ?></td>
                <td><?php e($row->fecOcuCar); ?></td>
                <td><?php e($row->cargo); ?></td>
                <td><?php e($row->estado); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<br>
<?php $previous_works = $result_data->previousWorks; ?>
<?php if (count($previous_works) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="4">
                TRABAJOS ANTERIORES
            </th>
        </tr>
        <tr>
            <th width="60" style="text-align:center;">N</th>
            <th>RUC empresa</th>
            <th>Nombre empresa</th>
            <th>Periodo</th>
        </tr>
        
        <?php foreach ($previous_works as $index => $row): ?>
            <tr>
                <td style="text-align:center;"><?php e($index + 1); ?></td>
                <td><?php e($row->ruc); ?></td>
                <td><?php e($row->eNombre); ?></td>
                <td><?php e($row->periodo); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
<?php endif; ?>

<?php if (count($previous_works) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="8">
                REGISTRO SALARIAL
            </th>
        </tr>
        <tr>
            <th width="60" style="text-align:center;">N</th>
            <th>DNI</th>
            <th>Apellidos y nombre</th>
            <th>RUC empresa</th>
            <th>Nombre empresa</th>
            <th>Nombre comercial</th>
            <th>Codigo RS</th>
            <th>Periodo</th>
        </tr>
        
        <?php foreach ($previous_works as $index => $row): ?>
            <tr>
                <td style="text-align:center;"><?php e($index + 1); ?></td>
                <td><?php e($row->numeroDocumento); ?></td>
                <td><?php e($row->apellidoPaterno . ' ' . $row->apellidoMaterno . ' ' . $row->nombre1 . ' ' . $row->nombre2); ?></td>
                <td><?php e($row->ruc); ?></td>
                <td><?php e($row->eNombre); ?></td>
                <td><?php e($row->eComercial); ?></td>
                <td><?php e($row->codigoRS); ?></td>
                <td><?php e($row->periodo); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
<?php endif; ?>

<br>
<?php $incidences = isset($result_data->dataNegativeIncidence) ? $result_data->dataNegativeIncidence->ListNegativeIncidences : []; ?>
<?php $incidences = empty($incidences) ? $result_data->dataJson->ListNegativeIncidences : $incidences; ?>
<?php $incidences = $incidences ? $incidences : []; ?>

<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th align="left">
            INDICADOR PRELIMINAR
        </th>
    </tr>
    <tr>
        <td style="color: <?php echo count($incidences) > 0 ? '#f2974a;' : '#333333'; ?>">
            <?php echo count($incidences) > 0 ? 'Con Anexo' : 'No Registra'; ?>
        </td>
    </tr>
</table>

<?php
    $prosecution = [];
    foreach ($result_data->data as $row) {

        if (!@$row->Fiscalia) {
            continue;
        }
        $prosecution[] = $row->Fiscalia;
    }

    $consolidated = [];
    foreach ($result_data->data as $row) {

        if (!@$row->Consolidado) {
            continue;
        }
        $consolidated[] = $row->Consolidado;
    }

    $pnp = [];
    foreach ($result_data->data as $row) {

        if (!@$row->Pnp) {
            continue;
        }
        $pnp[] = $row->Pnp;
    }

    $requisitoriado = [];
    foreach ($result_data->data as $row) {

        if (!@$row->Requisitoriado) {
            continue;
        }

        $requisitoriado[] = $row->Requisitoriado;
    }

    $inpe = [];
    foreach ($result_data->data as $row) {
        if (!@$row->Inpe) {
            continue;
        }
        $inpe[] = $row->Inpe;
    }

    $renadesple = [];
    foreach ($result_data->data as $row) {
        if (!@$row->Renadesple) {
            continue;
        }
        $renadesple[] = $row->Renadesple;
    }
    
    $result_ref1 = count($consolidated) > 0 || count($pnp) > 0;
    $result_ref2 = count($requisitoriado) > 0;
    $result_ref6 = count($inpe) > 0;
    $result_ref7 = count($renadesple) > 0;

    $result_ref3 = array_filter($prosecution, function($row){
        $part_negative_list = ['DEMANDADO', 'DENUNCIADO', 'IMPUTADO', 'INFRACTOR', 'INVESTIGADO'];
        //return $row->Especialidad == 'PENAL' && in_array($row->Parte, $part_negative_list);
        return $row->Especialidad == 'PENAL';
    });

    $result_ref4 = array_filter($prosecution, function($row){
        $part_negative_list = ['DEMANDADO', 'DENUNCIADO', 'IMPUTADO', 'INFRACTOR', 'INVESTIGADO'];
        //return $row->Especialidad == 'CIVIL' && in_array($row->Parte, $part_negative_list);
        return $row->Especialidad == 'CIVIL';
    });

    $result_ref5 = array_filter($prosecution, function($row){
        $part_negative_list = ['DEMANDADO', 'DENUNCIADO', 'IMPUTADO', 'INFRACTOR', 'INVESTIGADO'];
        //return $row->Especialidad == 'FAMILIA' && in_array($row->Parte, $part_negative_list);
        return $row->Especialidad == 'FAMILIA';
    });

    $result_ref3_negative = array_filter($prosecution, function($row){
        $part_negative_list = ['DEMANDADO', 'DENUNCIADO', 'IMPUTADO', 'INFRACTOR', 'INVESTIGADO'];
        return $row->Especialidad == 'PENAL' && in_array($row->Parte, $part_negative_list);
    });

    $result_ref4_negative = array_filter($prosecution, function($row){
        $part_negative_list = ['DEMANDADO', 'DENUNCIADO', 'IMPUTADO', 'INFRACTOR', 'INVESTIGADO'];
        return $row->Especialidad == 'CIVIL' && in_array($row->Parte, $part_negative_list);
    });

    $result_ref5_negative = array_filter($prosecution, function($row){
        $part_negative_list = ['DEMANDADO', 'DENUNCIADO', 'IMPUTADO', 'INFRACTOR', 'INVESTIGADO'];
        return $row->Especialidad == 'FAMILIA' && in_array($row->Parte, $part_negative_list);
    });
?>
<?php $psychotechnical_indicator = $result_ref1 ||
                                   $result_ref2 ||
                                   $result_ref6 ||
                                   $result_ref7 ||
                                   count($result_ref3) > 0 ||
                                   count($result_ref4) > 0 ||
                                   count($result_ref5) > 0;

    $psychotechnical_indicator_negative = $result_ref1 ||
                                            $result_ref2 ||
                                            $result_ref6 ||
                                            $result_ref7 ||
                                            count($result_ref3_negative) > 0 ||
                                            count($result_ref4_negative) > 0 ||
                                            count($result_ref5_negative) > 0;
?>
<br>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th align="left">
            INDICADOR PSICOTÉCNICO
        </th>
    </tr>
    <tr>
        <td style="color: <?php echo $psychotechnical_indicator_negative ? 'red;' : '#333333'; ?>">
            <?php echo $psychotechnical_indicator ? 'Con Anexo' : 'No Registra'; ?>
        </td>
    </tr>
</table>

<?php if ($psychotechnical_indicator): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th colspan="3" align="left">
                OBSERVACIONES NEGATIVAS
            </th>
        </tr>
        <tr>
            <th>
                N
            </th>
            <th>
                Referencia 
            </th>
            <th>
                Resultado 
            </th>
        </tr>
        <tr>
            <td>
                1
            </td>
            <td>
                Ref. 1 - DET. POLICIA
            </td>
            <td>
                <?php echo $result_ref1 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
        <tr>
            <td>
                2
            </td>
            <td>
                Ref. 2 - REQUISITORIADO
            </td>
            <td>
                <?php echo $result_ref2 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
        <tr>
            <td>
                3
            </td>
            <td>
                Ref. 3 - REFERENCIA PENAL
            </td>
            <td>
                <?php echo count($result_ref3) > 0 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
        <tr>
            <td>
                4
            </td>
            <td>
                Ref. 4 - REFERENCIA CIVIL
            </td>
            <td>
                <?php echo count($result_ref4) > 0 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
        <tr>
            <td>
                5
            </td>
            <td>
                Ref. 5 - REFERENCIA FAMILIA
            </td>
            <td>
                <?php echo count($result_ref5) > 0 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
        <tr>
            <td>
                6
            </td>
            <td>
                Ref. 6 - INPE
            </td>
            <td>
                <?php echo $result_ref6 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
        <tr>
            <td>
                7
            </td>
            <td>
                Ref. 7 - DET. JUDICIAL
            </td>
            <td>
                <?php echo $result_ref7 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
    </table>
<?php endif; ?>



<br>
<?php 
    $list_ofac = [];
    foreach ($result_data->data as $row) {
        if (!@$row->ListOfac) {
            continue;
        }
        $list_ofac[] = $row->ListOfac;
    }

    $list_ue = [];
    foreach ($result_data->data as $row) {
        if (!@$row->ListUe) {
            continue;
        }
        $list_ue[] = $row->ListUe;
    }

    $list_onu = [];
    foreach ($result_data->data as $row) {
        if (!@$row->ListOnu) {
            continue;
        }
        $list_onu[] = $row->ListOnu;
    }

    $terrorism = [];
    foreach ($result_data->data as $row) {
        if (!@$row->Terrorismo) {
            continue;
        }
        $terrorism[] = $row->Terrorismo;
    }

    $corruption = [];
    foreach ($result_data->data as $row) {
        if (!@$row->Corrupcion) {
            continue;
        }
        $corruption[] = $row->Corrupcion;
    }

    $other_crimes = [];
    foreach ($result_data->data as $row) {
        if (!@$row->OtrosDelitos) {
            continue;
        }
        $other_crimes[] = $row->OtrosDelitos;
    }
?>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th align="left" colspan="2">
            SISTEMA DE PREVENCIÓN DE LAVADO DE ACTIVOS Y FINANCIAMIENTO AL TERRORISMO
        </th>
    </tr>
    <tr>
        <td>
            OFAC - OFICINA DE CONTROL DE ACTIVOS EXTRANJEROS
        </td>
        <td>
            <?php echo count($list_ofac) > 0 ? 'Registra' : 'No registra'; ?>
        </td>
    </tr>
    <tr>
        <td>
            REGISTRO EN LISTA DE LA UNIÓN EUROPEA
        </td>
        <td>
            <?php echo count($list_ue) > 0 ? 'Registra' : 'No registra'; ?>
        </td>
    </tr>
    <tr>
        <td>
            REGISTRO EN ONU
        </td>
        <td>
            <?php echo count($list_onu) > 0 ? 'Registra' : 'No registra'; ?>
        </td>
    </tr>
    <tr>
        <td>
            REGISTRO DE DEUDORES EN REPARACIÓN DEL ESTADO POR TERRORISMO
        </td>
        <td>
            <?php echo count($terrorism) > 0 ? 'Registra' : 'No registra'; ?>
        </td>
    </tr>
    <tr>
        <td>
            REGISTRO DE DEUDORES EN REPARACIÓN DEL ESTADO POR CORRUPCIÓN
        </td>
        <td>
            <?php echo count($corruption) > 0 ? 'Registra' : 'No registra'; ?>
        </td>
    </tr>
    <tr>
        <td>
            REGISTRO DE DEUDORES EN REPARACIÓN DEL ESTADO POR DELITOS COMUNES
        </td>
        <td>
            <?php echo count($other_crimes) > 0 ? 'Registra' : 'No registra'; ?>
        </td>
    </tr>
</table>
<br>

<?php if (count($terrorism) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="5">
                DETALLE REGISTRO DE DEUDORES EN REPARACIÓN DEL ESTADO POR TERRORISMO
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>Fuentes</th>
            <th>Fecha</th>
            <th>DNI</th>
            <th>Delitos</th>
        </tr>
        <?php foreach($terrorism as $terrorism_index => $row): ?>
            <tr>
                <td align="center"><?php e($terrorism_index + 1); ?></td>
                <td><?php e($row->{"Fuentes"}); ?></td>
                <td><?php e($row->{"Fecha ejecutora"}); ?></td>
                <td><?php e($row->{"DNI"}); ?></td>
                <td><?php e($row->{"Delitos"}); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>

<?php if (count($corruption) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="6">
                DETALLE REGISTRO DE DEUDORES EN REPARACIÓN DEL ESTADO POR CORRUPCIÓN
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>Entidad agraviada</th>
            <th>Fuente</th>
            <th>Fecha</th>
            <th>DNI</th>
            <th>Delitos</th>
        </tr>
        <?php foreach($corruption as $corruption_index => $row): ?>
            <tr>
                <td align="center"><?php e($corruption_index + 1); ?></td>
                <td><?php e($row->{"Entidad agraviada"}); ?></td>
                <td><?php e($row->{"Fuente"}); ?></td>
                <td><?php e($row->{"Fecha ejecutora"}); ?></td>
                <td><?php e($row->{"DNI"}); ?></td>
                <td><?php e($row->{"Delitos"}); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>

<?php if (count($other_crimes) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="5">
                DETALLE REGISTRO DE DEUDORES EN REPARACIÓN DEL ESTADO POR DELITOS COMUNES
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>Fuente</th>
            <th>Fecha</th>
            <th>DNI</th>
            <th>Delitos</th>
        </tr>
        <?php foreach($other_crimes as $other_crimes_index => $row): ?>
            <tr>
                <td align="center"><?php e($other_crimes_index + 1); ?></td>
                <td><?php e($row->{"Fuente"}); ?></td>
                <td><?php e($row->{"Fecha ejecutora"}); ?></td>
                <td><?php e($row->{"DNI"}); ?></td>
                <td><?php e($row->{"Delitos"}); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>

<?php 
    $peps = [];
    foreach ($result_data->data as $row) {
        if (!@$row->Peps) {
            continue;
        }
        $peps[] = $row->Peps;
    }

    $resadde = [];
    foreach ($result_data->data as $row) {
        if (!@$row->Resadde) {
            continue;
        }
        $resadde[] = $row->Resadde;
    }

    $redam = [];
    foreach ($result_data->data as $row) {
        if (!@$row->Redam) {
            continue;
        }
        $redam[] = $row->Redam;
    }

    $dataMTC = [];

    if (@$result_data->dataJson->DataMTC) {
        $dataMTC[] = $result_data->dataJson->DataMTC;
    }

    $list_papeletas_MTC = @$result_data->dataJson->ListPapeletasMTC ? $result_data->dataJson->ListPapeletasMTC : [];
    $list_insurance = $result_data->dataJson->ListSeguros ? $result_data->dataJson->ListSeguros : [];
    $list_data_sunedu = $result_data->dataJson->ListDataSunedu ? $result_data->dataJson->ListDataSunedu : [];
?>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th align="left" colspan="2">
            OTRAS REFERENCIAS DE CONDUCTA SOCIAL PUBLICA
        </th>
    </tr>
    <tr>
        <td>
            REGISTRO DE HISTORIAL POLITICO (INFOGOB)
        </td>
        <td>
            <?php echo count($peps) > 0 ? 'Registra' : 'No registra'; ?>
        </td>
    </tr>
    <tr>
        <td>
            REGISTRO NACIONAL DE SANCIONES CONTRA SERVIDORES CIVILES (TRANSPARENCIA)
        </td>
        <td>
            <?php echo count($resadde) > 0 ? 'Registra' : 'No registra'; ?>
        </td>
    </tr>
    <tr>
        <td>
            DEUDORES ALIMENTARIOS EN MOROSIDAD (REDAM)
        </td>
        <td>
            <?php echo count($redam) > 0 ? 'Registra' : 'No registra'; ?>
        </td>
    </tr>
    <?php if (@$result_data->dataJson->pageMaintenanceMtc != 'true'): ?>
        <tr>
            <td>
                SISTEMA DE CONDUCIR POR PUNTOS (MTC)
            </td>
            <td>
                <?php echo count($dataMTC) > 0 || count($list_papeletas_MTC) > 0 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
    <?php endif; ?>

    <?php if (@$result_data->dataJson->pageMaintenanceEssalud != 'true'): ?>
        <tr>
            <td>
                VERIFICACIÓN DE SEGUROS
            </td>
            <td>
                <?php echo count($list_insurance) > 0 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
    <?php endif; ?>

    <?php if (@$result_data->dataJson->pageMaintenanceSunedu != 'true'): ?>
        <tr>
            <td>
                VERIFICACIÓN DE GRADO ACADÉMICO UNIVERSITARIO (SUNEDU)
            </td>
            <td>
                <?php echo count($list_data_sunedu) > 0 ? 'Registra' : 'No registra'; ?>
            </td>
        </tr>
    <?php endif; ?>
</table> 
<br>
<?php if (count($peps) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="5">
                DETALLE REGISTRO DE HISTORIAL POLITICO (INFOGOB)	
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>Cargo</th>
            <th>Cargo electoral</th>
            <th>Organización politica</th>
            <th>Año</th>
        </tr>
        <?php foreach($peps as $peps_index => $row): ?>
            <tr>
                <td align="center"><?php e($peps_index + 1); ?></td>
                <td><?php e($row->{"Cargo"}); ?></td>
                <td><?php e($row->{"Cargo Electoral"}); ?></td>
                <td><?php e($row->{"Organización Política"}); ?></td>
                <td><?php e($row->{"Año"}); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>

<?php if (count($resadde) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="4">
                DETALLE REGISTRO NACIONAL DE SANCIONES CONTRA SERVIDORES CIVILES (TRANSPARENCIA)	
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>DNI</th>
            <th>Nombre completo</th>
            <th>Fin inhabilitación</th>
        </tr>
        <?php foreach($resadde as $resadde_index => $row): ?>
            <tr>
                <td align="center"><?php e($resadde_index + 1); ?></td>
                <td><?php e($row->{"DNI"}); ?></td>
                <td><?php e($row->{"Nombres Completos"}); ?></td>
                <td><?php e($row->{"Fin Inhabilitación"}); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>

<?php if (count($resadde) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="4">
                DETALLE REGISTRO NACIONAL DE SANCIONES CONTRA SERVIDORES CIVILES (TRANSPARENCIA)	
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>DNI</th>
            <th>Nombre completo</th>
            <th>Fin inhabilitación</th>
        </tr>
        <?php foreach($resadde as $resadde_index => $row): ?>
            <tr>
                <td align="center"><?php e($resadde_index + 1); ?></td>
                <td><?php e($row->{"DNI"}); ?></td>
                <td><?php e($row->{"Nombres Completos"}); ?></td>
                <td><?php e($row->{"Fin Inhabilitación"}); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>

<?php if (count($redam) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="4">
                DETALLE DEUDORES ALIMENTARIOS EN MOROSIDAD (REDAM)	
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>Nombre completo</th>
            <th>Tipo documento</th>
            <th>Número documento</th>
        </tr>
        <?php foreach($redam as $redam_index => $row): ?>
            <tr>
                <td align="center"><?php e($redam_index + 1); ?></td>
                <td><?php e($row->{"Nombres Completos"}); ?></td>
                <td><?php e($row->{"Tipo Documento"}); ?></td>
                <td><?php e($row->{"Número Documento"}); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>

<?php if (count($dataMTC) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="4">
                DETALLE SISTEMA DE CONDUCIR POR PUNTOS (MTC)
            </th>
        </tr>
        <tr>
            <th>Vigencia</th>
            <th>N° licencia</th>
            <th>Clase</th>
            <th>Estado</th>
        </tr>
        <tr>
            <td><?php e($dataMTC[0]->VigenteHasta); ?></td>
            <td><?php e($dataMTC[0]->NumeroLicencia); ?></td>
            <td><?php e($dataMTC[0]->ClaseCategoria); ?></td>
            <td><?php e($dataMTC[0]->Estado); ?></td>
        </tr>
    </table> 
    <br>
<?php endif; ?>

<?php if (count($list_papeletas_MTC) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="6">
                DETALLE SISTEMA DE CONDUCIR POR PUNTOS (MTC) - PAPELETAS
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>Fecha</th>
            <th>Pto proceso</th>
            <th>N° papeleta</th>
            <th>Falta</th>
            <th>Entidad</th>
        </tr>
        <?php foreach($list_papeletas_MTC as $list_papeletas_MTC_index => $row): ?>
            <tr>
                <td align="center"><?php e($list_papeletas_MTC_index + 1); ?></td>
                <td><?php e($row->Fecha); ?></td>
                <td><?php e($row->PuntoProceso); ?></td>
                <td><?php e($row->NumeroPapeleta); ?></td>
                <td><?php e($row->Falta); ?></td>
                <td><?php e($row->Entidad); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>
<?php if (count($list_insurance) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="5">
                DETALLE VERIFICACIÓN DE SEGUROS
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>Nombre</th>
            <th>Fecha inicio</th>
            <th>Fecha fin</th>
            <th>Estado</th>
        </tr>
        <?php foreach($list_insurance as $list_insurance_index => $row): ?>
            <tr>
                <td align="center"><?php e($list_insurance_index + 1); ?></td>
                <td><?php e($row->nombreSeguro); ?></td>
                <td><?php e($row->fechaInicio); ?></td>
                <td><?php e($row->fechaFinal); ?></td>
                <td><?php e($row->estado); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>

<?php if (count($list_data_sunedu) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left" colspan="5">
                DETALLE VERIFICACIÓN DE GRADO ACADÉMICO UNIVERSITARIO (SUNEDU)
            </th>
        </tr>
        <tr>
            <th width="40">N°</th>
            <th>Universidad</th>
            <th>Fecha</th>
            <th>Grado</th>
            <th>País</th>
        </tr>
        <?php foreach($list_data_sunedu as $list_data_sunedu_index => $row): ?>
            <tr>
                <td align="center"><?php e($list_data_sunedu_index + 1); ?></td>
                <td><?php e($row->Universidad); ?></td>
                <td><?php e($row->FechaResolucion); ?></td>
                <td><?php e($row->Grado); ?></td>
                <td><?php e($row->Pais); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> 
    <br>
<?php endif; ?>

<?php $debts = $result_data->dataJson->DeudasSBSMicro; ?>

<?php 
    $debts_colors = [
        'NOR' => '#3cb371',
        'CPP' => '#ffd700',
        'DEF' => '#ffa500',
        'DUD' => '#dc143c',
        'PER' => '#2f4f4f',
    ];
?>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th align="left" colspan="5">
            VERIFICACIÓN CREDITICIA
        </th>
    </tr>

    <?php if (count($debts) == 0): ?>
        <tr>
            <td colspan="5">No se encontró información</td>
        </tr>
    <?php endif; ?>

    <?php if (count($debts) > 0): ?>
        <tr>
            <th align="left" colspan="5">
                DEUDAS SBS/MICROFINANZAS
            </th>
        </tr>
        <tr>
            <th align="center">N°</th>
            <th>Institución SBS</th>
            <th>Calificación</th>
            <th>Saldo</th>
            <th>Días vencidos</th>
        </tr>

        <?php foreach($debts as $debts_index => $row): ?>
            <tr>
                <td align="center">
                    <?php e($debts_index + 1); ?>
                </td>
                <td>
                    <?php e($row->inst); ?>
                </td>
                <td align="center" 
                    style="color:#ffffff;background: <?php echo isset($debts_colors[$row->calif]) ? $debts_colors[$row->calif] : '#ffffff'; ?>">
                    <?php e($row->calif); ?>
                </td>
                <td>
                    <?php e($row->saldo); ?>
                </td>
                <td>
                    <?php e($row->diasVen); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<br>
<?php if (count($debts) > 0): ?>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <td align="center"  
                style="color:#ffffff;background: <?php echo $debts_colors['NOR']; ?>">NOR</td>
            <td align="center">NORMAL</td>
            <td align="center"
                style="color:#333333;background: <?php echo $debts_colors['CPP']; ?>">CPP</td>
            <td align="center">CON PROBLEMAS <p>POTENCIALES</p></td>
            <td align="center"
                style="color:#ffffff;background: <?php echo $debts_colors['DEF']; ?>">DEF</td>
            <td align="center">DEFICIENTE</td>
            <td align="center"
                style="color:#ffffff;background: <?php echo $debts_colors['DUD']; ?>">DUD</td>
            <td align="center">DUDOSO</td>
            <td align="center"
                style="color:#ffffff;background: <?php echo $debts_colors['PER']; ?>">PER</td>
            <td align="center">PERDIDA</td>
        </tr>
    </table>
    <br>
<?php endif; ?>

<?php $lines_credit = $result_data->dataJson->LineasCredito; ?>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1" style="border-bottom:0;">
    <tr>
        <th align="left" colspan="5">
            LÍNEAS DE CRÉDITO
        </th>
    </tr>
    <?php if (count($lines_credit) > 0): ?>
        <tr>
            <th>N°</th>
            <th>Institución</th>
            <th>Línea aprobada</th>
            <th>Línea no utilizada</th>
            <th>Línea utilizada</th>
        </tr>
        <?php foreach ($lines_credit as $lines_credit_index => $row): ?>
            <tr>
                <td align="center"><?php e($lines_credit_index + 1); ?></td>
                <td><?php e($row->inst); ?></td>
                <td><?php e($row->linApr); ?></td>
                <td><?php e($row->linNoUti); ?></td>
                <td><?php e($row->linUti); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (count($lines_credit) == 0): ?>
        <tr>
            <td colspan="5">No se encontró información</td>
        </tr>
    <?php endif; ?>
</table>
<br>

<?php $expired_detail = $result_data->dataJson->DetalleVencidos; ?>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1" style="border-bottom:0;">
    <tr>
        <th align="left" colspan="5">
            DETALLE DE VENCIDOS
        </th>
    </tr>
    <?php if (count($expired_detail) > 0): ?>
        <tr>
            <th>N°</th>
            <th>Institución</th>
            <th>Deuda</th>
            <th>Días vencidos</th>
            <th>Tipo</th>
        </tr>
        <?php foreach ($expired_detail as $expired_detail_index => $row): ?>
            <tr>
                <td align="center"><?php e($expired_detail_index + 1); ?></td>
                <td><?php e($row->inst); ?></td>
                <td><?php e($row->montDeuda); ?></td>
                <td><?php e($row->diaVenc); ?></td>
                <td><?php e($row->tipDocVen); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (count($expired_detail) == 0): ?>
        <tr>
            <td colspan="5">No se encontró información</td>
        </tr>
    <?php endif; ?>
</table>
<br>
<?php $traffic_light = $result_data->dataJson->Semaforos; ?>
<?php 
    $traffic_light_color_selected = '#88f78e';
    $traffic_light_last_color = '';
    
    $traffic_light_colors = [
        'V' => '#3cb371',
        'R' => '#dc143c',
        'A' => '#ffd700',
        'G' => '#2f4f4f'
    ];
?>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1" style="border-bottom:0;">
    <tr>
        <th align="left">
            SEMAFORO DE LOS ULTIMOS 24 MESES
        </th>
    </tr>
</table>
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <?php foreach ($traffic_light as $row): ?>
            <?php $traffic_light_last_color = $row->color; ?>
            <td style="vertical-align:top;max-width:18px;padding:2px;">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <?php $bg_color = isset($traffic_light_colors[$traffic_light_last_color]) ? $traffic_light_colors[$traffic_light_last_color] : '#ffffff'; ?>
                        <td style="padding:5px;border:1px solid #fff;;vertical-align:top;height:24px;background:<?php echo $bg_color; ?>"></td>
                    </tr>
                    <tr>
                        <td text-rotate="-90" style="border:0;padding:5px; padding-top:16px; writing-mode: vertical-rl;text-orientation: sideways;">
                            <?php e($row->mes); ?>
                        </td>
                    </tr>
                </table>
            </td>
        <?php endforeach; ?>
    </tr>
</table>
<br>
<table width="50%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1" align="center">
    <tr>
        <th colspan="2">
            SEMAFORO ACTUAL
        </th>
    </tr>
    <tr>
        <td width="60" 
            align="center" 
            style="background:<?php echo $traffic_light_last_color == 'G' ? $traffic_light_color_selected : '#ffffff'; ?>">
            <div style="background:<?php echo $traffic_light_colors['G']; ?>">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            </div>
        </td>
        <td style="background:<?php echo $traffic_light_last_color == 'G' ? $traffic_light_color_selected : '#ffffff'; ?>">
            No registra información de deudas
        </td>
    </tr>
    <tr>
        <td  align="center" 
             style="background:<?php echo $traffic_light_last_color == 'V' ? $traffic_light_color_selected : '#ffffff'; ?>">
            <div style="background:<?php echo $traffic_light_colors['V']; ?>">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            </div>
        </td>
        <td style="background:<?php echo $traffic_light_last_color == 'V' ? $traffic_light_color_selected : '#ffffff'; ?>">
            Mínimo riesgo: Sin deudas vencidas
        </td>
    </tr>
    <tr>
        <td align="center" 
            style="background:<?php echo $traffic_light_last_color == 'A' ? $traffic_light_color_selected : '#ffffff'; ?>">
            <div style="background:<?php echo $traffic_light_colors['A']; ?>">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            </div>
        </td>
        <td style="background:<?php echo $traffic_light_last_color == 'A' ? $traffic_light_color_selected : '#ffffff'; ?>">
            Mediano riesgo: Deudas con poco atraso
        </td>
    </tr>
    <tr>
        <td align="center" 
            style="background:<?php echo $traffic_light_last_color == 'R' ? $traffic_light_color_selected : '#ffffff'; ?>">
            <div style="background:<?php echo $traffic_light_colors['R']; ?>">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            </div>
        </td>
        <td style="background:<?php echo $traffic_light_last_color == 'R' ? $traffic_light_color_selected : '#ffffff'; ?>">
            Alto riesgo: Deudas con atraso significativo
        </td>
    </tr>
</table>

<?php 
    $GLOBALS['__screening_vars__'] = [
        'incidences' => $incidences,
        'psychotechnical_indicator' => $psychotechnical_indicator
    ];
?>