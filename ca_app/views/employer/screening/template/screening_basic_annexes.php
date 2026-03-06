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

<?php $incidences = $result_data->dataNegativeIncidence ? $result_data->dataNegativeIncidence->ListNegativeIncidences : []; ?>
<?php $incidences = empty($incidences) ? @$result_data->dataJson->ListNegativeIncidences : $incidences; ?>
<?php $incidences = $incidences ? $incidences : []; ?>

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
?>
<?php $psychotechnical_indicator = $result_ref1 ||
                                   $result_ref2 ||
                                   $result_ref6 ||
                                   $result_ref7 ||
                                   count($result_ref3) > 0 ||
                                   count($result_ref4) > 0 ||
                                   count($result_ref5) > 0;
?>
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

<?php $detail_index = 1; ?>
<?php if (count($incidences) > 0): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left">
                DETALLE PRELIMINAR
            </th>
        </tr>
    </table>

    <?php foreach ($incidences as $row): ?>
        <br>
        <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="5">
            <tr>
                <th style="margin:10px;" width="20%" class="bg-th">PARTE</th>
                <td width="30%"><?php e($row->dsituden); ?></td>
                <th width="20%" class="bg-th" >TIPIFICACIÓN</th>
                <td  width="30%"><?php e($row->materia . ' / ' . $row->tipo); ?></td>
            </tr>
            <tr>
                <th width="20%" class="bg-th" >FECHA</th>
                <td width="30%"><?php e($row->fechahecho); ?></td>
                <th width="20%" rowspan="2" class="bg-th">LUGAR DE HECHO</th>
                <td width="30%" rowspan="2">
                    <?php e($row->cia . ' / ' . $row->ddist . ' / ' . $row->tvia . ' / ' . $row->ubica); ?>
                </td>
            </tr>
            <tr>
                <th width="20%" class="bg-th">MODALIDAD</th>
                <td  width="30%"><?php e($row->subtipo); ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($consolidated) > 0 || count($pnp) > 0): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left">
                ANEXO REF. 1 - DET. POLICIAL
            </th>
        </tr>
    </table>

    <?php foreach ($consolidated as $row): ?>
        <br>
        <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
            <tr>
                <th class="bg-th" width="20%">Detenido</th>
                <td><?php e($row->{'Detenido'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Delito</th>
                <td><?php e($row->{'Delito'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">País</th>
                <td><?php e($row->{'País'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Fecha Detención</th>
                <td><?php e($row->{'Fecha Detención'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Documento Identidad</th>
                <td><?php e($row->{'Documento Identidad'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Entidad Informante</th>
                <td><?php e($row->{'Entidad Informante'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Distrito</th>
                <td><?php e($row->{'Distrito'}); ?></td>
            </tr>
        </table>
    <?php endforeach; ?>

    <?php foreach ($pnp as $row): ?>
        <br>
        <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
            <tr>
                <th class="bg-th" width="20%">Entidad Informante 1</th>
                <td><?php e($row->{'Entidad Informante 1'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Entidad Informante 2</th>
                <td><?php e($row->{'Entidad Informante 2'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Detenido</th>
                <td><?php e($row->{'Detenido'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Edad</th>
                <td><?php e($row->{'Edad'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Documento Identidad</th>
                <td><?php e($row->{'Documento Identidad'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Motivo Libertad</th>
                <td><?php e($row->{'Motivo Libertad'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Distrito</th>
                <td><?php e($row->{'Distrito'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Fecha Egreso</th>
                <td><?php e($row->{'Fecha Egreso'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Fecha Ingreso</th>
                <td><?php e($row->{'Fecha Ingreso'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Delito</th>
                <td><?php e($row->{'Delito'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Nacionalidad</th>
                <td><?php e($row->{'Nacionalidad'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Documento</th>
                <td><?php e($row->{'Documento'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Num. Orden</th>
                <td><?php e($row->{'Num. Orden'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Num. Caso</th>
                <td><?php e($row->{'Num. Caso'}); ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($requisitoriado) > 0): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left">
                ANEXO REF. 2 - REQUISITORIADO
            </th>
        </tr>
    </table>

    <?php foreach ($requisitoriado as $row): ?>
        <br>
        <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
            <tr>
                <th class="bg-th" width="20%">Unidad Interventora</th>
                <td><?php e($row->{'Unidad Interventora'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Detenido</th>
                <td><?php e($row->{'Detenido'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Edad</th>
                <td><?php e($row->{'Edad'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Delito</th>
                <td><?php e($row->{'Delito'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Fecha</th>
                <td><?php e($row->{'Fecha'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Hora</th>
                <td><?php e($row->{'Hora'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Autoridad Solicitante</th>
                <td><?php e($row->{'Autoridad Solicitante'}); ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($result_ref3) > 0): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left">
                ANEXO REF. 3 - REFERENCIA PENAL
            </th>
        </tr>
    </table>

    <?php foreach ($result_ref3 as $row): ?>
        <br>
        <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
            <tr>
                <th class="bg-th" width="20%">Delito</th>
                <td><?php e($row->Delito); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Dependencia</th>
                <td><?php e($row->Dependencia); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Jurisdicción</th>
                <td><?php e($row->{'Jurisdicción'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Fecha</th>
                <td><?php e($row->Fecha); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Caso</th>
                <td><?php e($row->Caso); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Parte</th>
                <td><?php e($row->Parte); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Inicio del proceso</th>
                <td><?php e($row->{'Inicio del proceso'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Especialidad</th>
                <td><?php e($row->Especialidad); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Estado</th>
                <td><?php e($row->Estado); ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($result_ref4) > 0): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left">
                ANEXO REF. 4 - REFERENCIA CIVIL
            </th>
        </tr>
    </table>

    <?php foreach ($result_ref4 as $row): ?>
        <br>
        <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
            <tr>
                <th class="bg-th" width="20%">Delito</th>
                <td><?php e($row->Delito); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Dependencia</th>
                <td><?php e($row->Dependencia); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Jurisdicción</th>
                <td><?php e($row->{'Jurisdicción'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Fecha</th>
                <td><?php e($row->Fecha); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Caso</th>
                <td><?php e($row->Caso); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Parte</th>
                <td><?php e($row->Parte); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Inicio del proceso</th>
                <td><?php e($row->{'Inicio del proceso'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Especialidad</th>
                <td><?php e($row->Especialidad); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Estado</th>
                <td><?php e($row->Estado); ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($result_ref5) > 0): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left">
                ANEXO REF. 5 - REFERENCIA FAMILIA
            </th>
        </tr>
    </table>

    <?php foreach ($result_ref5 as $row): ?>
        <br>
        <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
            <tr>
                <th class="bg-th" width="20%">Delito</th>
                <td><?php e($row->Delito); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Dependencia</th>
                <td><?php e($row->Dependencia); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Jurisdicción</th>
                <td><?php e($row->{'Jurisdicción'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Fecha</th>
                <td><?php e($row->Fecha); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Caso</th>
                <td><?php e($row->Caso); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Parte</th>
                <td><?php e($row->Parte); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Inicio del proceso</th>
                <td><?php e($row->{'Inicio del proceso'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Especialidad</th>
                <td><?php e($row->Especialidad); ?></td>
            </tr>
            <tr>
                <th class="bg-th">Estado</th>
                <td><?php e($row->Estado); ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($inpe) > 0): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left">
                ANEXO REF. 6 - INPE
            </th>
        </tr>
    </table>

    <?php foreach ($inpe as $row): ?>
        <br>
        <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
            <tr>
                <th class="bg-th" width="20%">Establecimiento Penitenciario</th>
                <td><?php e($row->{'Establecimiento Penitenciario'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Detenido</th>
                <td><?php e($row->{'Detenido'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Autoridad Sentencia</th>
                <td><?php e($row->{'Autoridad Sentencia'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Tipo Libertad</th>
                <td><?php e($row->{'Tipo Libertad'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Vence</th>
                <td><?php e($row->{'Vence'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Fecha Egreso</th>
                <td><?php e($row->{'Fecha Egreso'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Fecha Ingreso</th>
                <td><?php e($row->{'Fecha Ingreso'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Número Documento</th>
                <td><?php e($row->{'Número Documento'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Num. Oficio</th>
                <td><?php e($row->{'Num. Oficio'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Años</th>
                <td><?php e($row->{'Años'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Nacionalidad</th>
                <td><?php e($row->{'Nacionalidad'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Años</th>
                <td><?php e($row->{'Años'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Situación Jurídica</th>
                <td><?php e($row->{'Situación Jurídica'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Observaciones</th>
                <td><?php e($row->{'Observaciones'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Entidad Informante</th>
                <td><?php e($row->{'Entidad Informante'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Filiación Política</th>
                <td><?php e($row->{'Filiación Política'}); ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($renadesple) > 0): ?>
    <br>
    <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
        <tr>
            <th align="left">
                ANEXO REF. 7 - DET. JUDICIAL
            </th>
        </tr>
    </table>

    <?php foreach ($renadesple as $row): ?>
        <br>
        <table width="100%" border="1" class="tbl-style-1" cellspacing="1" cellpadding="1">
            <tr>
                <th class="bg-th" width="20%">Delito</th>
                <td><?php e($row->Delito); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Fecha Detención</th>
                <td><?php e($row->{'Fecha Detención'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Establecimiento</th>
                <td><?php e($row->Establecimiento); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Dependencia Policial</th>
                <td><?php e($row->{'Dependencia Policial'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Distrito Fiscal</th>
                <td><?php e($row->{'Distrito Fiscal'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Tipo Documento</th>
                <td><?php e($row->{'Tipo Documento'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Número Documento</th>
                <td><?php e($row->{'Número Documento'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Motivo Detención</th>
                <td><?php e($row->{'Motivo Detención'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Fiscalía</th>
                <td><?php e($row->{'Fiscalía'}); ?></td>
            </tr>
            <tr>
                <th class="bg-th" width="20%">Juzgado</th>
                <td><?php e($row->{'Juzgado'}); ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>


