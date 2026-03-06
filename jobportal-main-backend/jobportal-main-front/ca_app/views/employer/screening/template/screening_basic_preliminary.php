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

<?php $incidences = $result_data->dataNegativeIncidence ? $result_data->dataNegativeIncidence->ListNegativeIncidences : []; ?>
<?php $incidences = empty($incidences) ? @$result_data->dataJson->ListNegativeIncidences : $incidences; ?>
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
<?php 
    $psychotechnical_indicator = $result_ref1 ||
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
?>

<?php if ($psychotechnical_indicator): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th colspan="3">
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
<table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
    <tr>
        <th align="left" colspan="2">
            OTRAS REFERENCIAS DE CONDUCTA SOCIAL PÚBLICA
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
</table>

<?php 
    $GLOBALS['__screening_vars__'] = [
        'incidences' => $incidences,
        'psychotechnical_indicator' => $psychotechnical_indicator
    ];
?>
