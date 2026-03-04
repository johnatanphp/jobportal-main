<!DOCTYPE html>
<html lang="es_pe">

<head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
    <link href="<?php echo base_url('public/css/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
    <?php $this->load->view('common/before_head_close'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.2/css/fixedColumns.dataTables.min.css">
    <style>
        .card .menu-tab {
            background: #F8F9FA;
            color: #555;
            padding: 1em 0.5em;
            border: 1px solid #ccc;
        }
        
        .card .btn-primary {
            background: #005da4;
            color: #ffffff;
        }
        
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
    
        table.dataTable thead th,
        table.dataTable tbody td {
            white-space: nowrap;
            font-size: 12px;
        }
    
        table.dataTable tr td:first-child,
        table.dataTable tr th:first-child {
            text-align: center;
        }
        
        .grip {
            width: 5px;
            cursor: col-resize;
            background-color: transparent;
            margin-left: -2px;
            height: 100%;
            z-index: 100;
        }
        
        .grip:hover {
            background-color: #0056b3;
        }
        
        /* Evitar que las celdas fijas tapen el contenido */
        .DTFC_LeftBodyLiner {
            overflow-y: hidden !important;
            background-color: #fff;
            overflow-x: hidden;
        }
        
        table.dataTable {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
    </style>
</head>

<body>
    <?php $this->load->view('common/after_body_open'); ?>
    <div class="siteWraper">
        <!--Header-->
        <?php $this->load->view('common/header'); ?>
        <!--/Header-->
        <div class="container detailinfo">
            <div class="row">
                <div class="col-md-3">
                    <div class="dashiconwrp">
                        <?php $this->load->view('employer/common/menu/sidebar'); ?>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="formwraper">
                        <div class="titlehead">
                            <a href="#" class="_link-back" style="color: #ffffff;">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                            Gestionar Filtros a: <?php echo @$app_user_info->first_name; ?> <?php echo @$app_user_info->last_name; ?>
                        </div>

                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header card-header-primary">
                                            <br>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <!-- Tabs -->
                                                <div class="col-md-3">
                                                    <a href="#" class="btn btn-block menu-tab btn-primary" data-content="#container-business-unit">
                                                        UNIDAD DE NEGOCIO
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="#" class="btn btn-block menu-tab" data-content="#container-consultants">
                                                        CONSULTORAS
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="#" class="btn btn-block menu-tab" data-content="#container-clients">
                                                        CLIENTES
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="#" class="btn btn-block menu-tab" data-content="#container-cost-centers">
                                                        CENTRO DE COSTO
                                                    </a>
                                                </div>
                                            </div>
                                            <br />

                                            <!-- BUSINESS UNIT TAB -->
                                            <div id="container-business-unit">
                                                <form id="form-business-unit">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-sm btn-round btn-primary" data-toggle="modal" data-target="#modal-business-unit">
                                                            Agregar
                                                        </button>
                                                        <br />
                                                        <div id="input-business-unit" style="margin-bottom:10px;"></div>
                                                        <table id="tbl-business-unit" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="text-align: center;" width="120">
                                                                        <button type="button" class="btn btn-sm btn-round btn-danger remove-all-business-unit">Quitar Todos</button>
                                                                    </th>
                                                                    <th width="80">Codigo</th>
                                                                    <th>Nombre</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" style="text-align:center;">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- CONSULTANTS TAB -->
                                            <div id="container-consultants" style="display:none;">
                                                <form id="form-consultants">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-sm btn-round btn-primary" data-toggle="modal" data-target="#modal-consultants">
                                                            Agregar
                                                        </button>
                                                        <br />
                                                        <div id="input-consultants" style="margin-bottom:10px;"></div>
                                                        <table id="tbl-consultants" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="text-align: center;" width="120">
                                                                        <button type="button" class="btn btn-sm btn-round btn-danger remove-all-consultants">Quitar Todos</button>
                                                                    </th>
                                                                    <th width="80">Codigo</th>
                                                                    <th>Nombre</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" style="text-align:center;">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- CLIENTS TAB -->
                                            <div id="container-clients" style="display:none;">
                                                <form id="form-clients">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-sm btn-round btn-primary" data-toggle="modal" data-target="#modal-clients">
                                                            Agregar
                                                        </button>
                                                        <br />
                                                        <div id="input-clients"></div>
                                                        <div class="table-responsive">
                                                            <table id="tbl-clients" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="text-align:center" width="120">
                                                                            <button type="button" class="btn btn-sm btn-round btn-danger remove-all-clients">Quitar Todos</button>
                                                                        </th>
                                                                        <th>Consultora</th>
                                                                        <th>Cliente codigo</th>
                                                                        <th>Cliente nombre</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" style="text-align:center;">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- COST CENTERS TAB -->
                                            <div id="container-cost-centers" style="display:none;">
                                                <form id="form-cost-centers">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-sm btn-round btn-primary" data-toggle="modal" data-target="#modal-cost-center">
                                                            Agregar
                                                        </button>
                                                        <br />
                                                        <div id="input-cost-centers"></div>
                                                        <div class="table-responsive">
                                                            <table id="tbl-cost-centers" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="text-align:center">
                                                                            <button type="button" class="btn btn-sm btn-round btn-danger remove-all-cost-centers">Quitar Todos</button>
                                                                        </th>
                                                                        <th>Centro de costo</th>
                                                                        <th>Consultora</th>
                                                                        <th>Cliente</th>
                                                                        <th>Unidad de Negocio</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" style="text-align:center;">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- MODALS -->

                                <!-- BUSINESS UNIT MODAL -->
                                <div class="modal fade" id="modal-business-unit">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Seleccione Unidades de Negocio</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <table id="table-business-units" class="table table-bordered w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" id="check-all-bu">
                                                            </th>
                                                            <th>Código</th>
                                                            <th>Nombre</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" id="btn-add-business-units">
                                                    Agregar seleccionados
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- CONSULTANTS MODAL -->
                                <div class="modal fade" id="modal-consultants" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Seleccionar Consultoras</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">

                                                <table id="table-consultants" class="table table-bordered table-sm w-100">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th style="width:30">
                                                                <input type="checkbox" id="check-all-consultants">
                                                            </th>
                                                            <th>Código</th>
                                                            <th>Nombre</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" id="btn-add-consultants">
                                                    Agregar seleccionados
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <!-- CLIENTS MODAL -->
                                <div class="modal fade" id="modal-clients" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Seleccionar Clientes</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">

                                                <table id="table-clients-modal"  class="table table-bordered w-100" style="width: 100%;">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th style="width:40px">
                                                                <input type="checkbox" id="check-all-clients">
                                                            </th>
                                                            <th>Consultora</th>
                                                            <th>Cliente codigo</th>
                                                            <th>Cliente nombre</th>
                                                        </tr>
                                                    </thead>
                                                </table>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" id="btn-add-clients">
                                                    Agregar seleccionados
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <!-- COST CENTER MODAL -->
                                <div class="modal fade" id="modal-cost-center" tabindex="-1">
                                    <div class="modal-dialog modal-lg" style="width: 95%;max-width: 1280px;">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Seleccionar Centros de Costo</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table id="table-cost-centers-modal"  class="table table-bordered w-100" style="width: 100%;">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th style="width:40px">
                                                                    <input type="checkbox" id="check-all-cost-centers">
                                                                </th>
                                                                <th>Consultora</th>
                                                                <th>Cliente</th>
                                                                <th>Unidad Negocio</th>
                                                                <th>Centro de costo</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" id="btn-add-cost-centers">
                                                    Agregar seleccionados
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('common/bottom_ads'); ?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<script src="<?php echo base_url('public/js/bad_words.js'); ?>"></script>
<?php $this->load->view('common/before_body_close'); ?>

<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.2.11/js/dataTables.checkboxes.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>

<script type="text/javascript">
    $(function() {
        messageSuccess = "<?php echo trim((string)$this->session->flashdata('success')); ?>";
        if (messageSuccess != '') {
            toastr["success"](messageSuccess);
        }

        messageDanger = "<?php echo trim((string)$this->session->flashdata('error')); ?>";
        if (messageDanger != '') {
            toastr["error"](messageDanger);
        }
    });
</script>
<script>
    $(document).ready(function() {
        const userId = <?= (int)$app_user_info->ID ?>;
        loadBusinessUnit(userId);
           
        function createIdValues() {
          const args = Array.from(arguments);
          return args.join('__');
        }
        
        // === TAB SWITCH ===
        $('.menu-tab').on('click', function(e) {
            e.preventDefault();
            const content = $(this).data('content');
            $('#container-business-unit, #container-consultants, #container-clients, #container-cost-centers').hide();
            $(content).show();
            $('.menu-tab').removeClass('btn-primary');
            $(this).addClass('btn-primary');

            if (content === '#container-business-unit') loadBusinessUnit(userId);
            if (content === '#container-consultants') loadConsultants(userId);
            if (content === '#container-clients') loadClients(userId);
            if (content === '#container-cost-centers') loadCostCenters(userId);
        });

        // === BUSINESS UNIT TABLE ===
        const businessUnitTable = $('#tbl-business-unit').DataTable({
            columns: [
                {
                    data: null,
                    orderable: false,
                    render: function () {
                        return `<button class="btn btn-sm btn-danger remove">✖</button>`;
                    },
                    'className': 'style_td text-center'
                },
                { data: 'code' },
                { data: 'name' }
            ],
            bAutoWidth: false,
            paging: true,
            searching: true,
            ordering: false,
            info: true
        });

        function loadBusinessUnit(userId) {
            $.getJSON("<?= site_url('employer/users/profile_actions/assign_profile_filters/business_unit_allowed') ?>/" + userId, function(data) {
                businessUnitTable.clear();
                $('#input-business-unit').empty();
                data.forEach(c => addBusinessUnit(c.code, c.name, false));
                businessUnitTable.draw(false);
            });
        }

        function addBusinessUnit(code, name) {

            selectedBusinessUnits.add(code);

            businessUnitTable.row.add({
                name: name,
                code: code
            }).draw(false);

            $('#input-business-unit').append(
                `<input type="hidden" name="business_unit_codes[]" value="${code}">`
            );
        }

        $('.remove-all-business-unit').on('click', function () {

            selectedBusinessUnits.clear();
            businessUnitTable.clear().draw();
            $('#input-business-unit').empty();

            if (businessUnitModalTable) {
                businessUnitModalTable.ajax.reload(null, false);
            }
        });

        $('#tbl-business-unit tbody').on('click', '.remove', function () {

            const row = businessUnitTable.row($(this).closest('tr'));
            const data = row.data();
            
            const code = data['code'];
            const name = data['name'];

            selectedBusinessUnits.delete(code);

            row.remove().draw(false);

            $(`#input-business-unit input[value="${code}"]`).remove();

            if (businessUnitModalTable) {
                businessUnitModalTable.ajax.reload(null, false);
            }
        });

        $('#form-business-unit').on('submit', function(e) {
            e.preventDefault();
            $.post("<?= site_url('employer/users/profile_actions/assign_profile_filters/save_business_units/' . $app_user_info->ID) ?>", $(this).serialize(), function(r) {
                
                if (r.status == 'success') {
                  window.location.reload();
                  return;
                } 
                
                toastr.error(r.message);
                
                // toastr.success(r.message);
                // loadBusinessUnit(userId);
                
            }, 'json');
        });

        let businessUnitModalTable;
        let selectedBusinessUnits = new Set();

        function loadBusinessUnitsTable() {

            if ($.fn.DataTable.isDataTable('#table-business-units')) {
                businessUnitModalTable.ajax.reload();
                return;
            }

            businessUnitModalTable = $('#table-business-units').DataTable({
                ajax: {
                    url: "<?= site_url('employer/users/profile_actions/assign_profile_filters/business_units_all/'.$app_user_info->ID) ?>",
                    dataSrc: function (json) {
                        return json.filter(bu => !selectedBusinessUnits.has(bu.code));
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        render: function (data) {
                            return `
                                <input type="checkbox"
                                    class="bu-check"
                                    value="${data.code}"
                                    data-name="${data.name}">
                            `;
                        }
                    },
                    { data: 'code' },
                    { data: 'name' }
                ],
                order: [[1, 'asc']],
                pageLength: 10,
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_",
                    info: "Mostrando _START_ a _END_ de _TOTAL_",
                    paginate: {
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                }
            });
        }

        // LOAD CONSULTANTS SELECT MODAL
        $('#modal-business-unit').on('shown.bs.modal', function () {
            loadBusinessUnitsTable();
        });

        $(document).on('change', '#check-all-bu', function () {
            const checked = $(this).is(':checked');
            $('.bu-check').prop('checked', checked);
        });
                
        $('#btn-add-business-units').on('click', function () {

            const rows = businessUnitModalTable.rows().nodes();
            const selected = [];

            $('input.bu-check:checked', rows).each(function () {
                selected.push({
                    code: $(this).val(),
                    name: $(this).data('name')
                });
            });

            selected.forEach(bu => addBusinessUnit(bu.code, bu.name, true));

            $('#modal-business-unit').modal('hide');
        });

        $(document).on('change', '#check-all-bu', function () {
            const checked = this.checked;

            businessUnitModalTable.rows().every(function () {
                $(this.node()).find('.bu-check').prop('checked', checked);
            });
        });

        function removeBusinessUnit(code) {
            selectedBusinessUnits.delete(code);

            businessUnitTable.rows().every(function () {
                if (this.data()[3] === code) {
                    this.remove();
                }
            });

            businessUnitTable.draw();
        }

        // === CONSULTANTS TABLE ===
        const consultantsTable = $('#tbl-consultants').DataTable({
            bAutoWidth: false,
            paging: true,
            searching: true,
            ordering: false,
            info: true,
            columns: [
                {
                    data: null,
                    orderable: false,
                    render: () => `<button type="button" class="btn btn-danger btn-sm remove">X</button>`,
                    'className': 'style_td text-center' 
                },
                { data: 'code' },
                { data: 'name' }
            ]
        });

        let selectedConsultants = new Set();
        let consultantsModalTable;

        loadConsultants(userId);

        function loadConsultants(userId) {
            $.getJSON("<?= site_url('employer/users/profile_actions/assign_profile_filters/consultants_allowed') ?>/" + userId, function(data) {

                selectedConsultants.clear();
                consultantsTable.clear();
                $('#input-consultants').empty();

                data.forEach(c => addConsultant(c.code, c.name, false));

                consultantsTable.draw(false);
                loadClients(userId);
            });
        }

        function addConsultant(code, name, fromModal = false) {

            if (selectedConsultants.has(code)) return;

            selectedConsultants.add(code);

            consultantsTable.row.add({
                code: code,
                name: name
            }).draw(false);

            $('#input-consultants').append(
                `<input type="hidden" name="consultant_codes[]" value="${code}">`
            );

            if (fromModal && consultantsModalTable) {
                consultantsModalTable.ajax.reload(null, false);
            }
        }

        $('#tbl-consultants tbody').on('click', '.remove', function () {

            const row = consultantsTable.row($(this).closest('tr'));
            const code = row.data().code;

            selectedConsultants.delete(code);
            row.remove().draw(false);
            $(`#input-consultants input[value="${code}"]`).remove();

            if (consultantsModalTable) {
                consultantsModalTable.ajax.reload(null, false);
            }
        });

        $('.remove-all-consultants').on('click', function () {

            selectedConsultants.clear();
            consultantsTable.clear().draw();
            $('#input-consultants').empty();

            if (consultantsModalTable) {
                consultantsModalTable.ajax.reload(null, false);
            }
        });

        $('#form-consultants').on('submit', function(e) {
            e.preventDefault();
            $.post("<?= site_url('employer/users/profile_actions/assign_profile_filters/save_consultants/' . $app_user_info->ID) ?>", $(this).serialize(), function(r) {
                
                if (r.status == 'success') {
                  window.location.reload();
                  return;
                } 
                
                toastr.error(r.message);
              
                // toastr.success(r.message);
                // loadConsultants(userId);
              
            }, 'json');
        });

        // LOAD CONSULTANTS SELECT MODAL
        $('#modal-consultants').on('shown.bs.modal', function () {
            loadConsultantsModalTable();
        });

        function loadConsultantsModalTable() {

            if ($.fn.DataTable.isDataTable('#table-consultants')) {
                consultantsModalTable.ajax.reload();
                return;
            }

            consultantsModalTable = $('#table-consultants').DataTable({
                ajax: {
                    url: "<?= site_url('employer/users/profile_actions/assign_profile_filters/consultants_all/') ?>"+userId,
                    dataSrc: json => json.filter(c => !selectedConsultants.has(c.code))
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        render: data => `
                            <input type="checkbox"
                                class="consultant-check"
                                value="${data.code}"
                                data-name="${data.name}">
                        `
                    },
                    { data: 'code' },
                    { data: 'name' }
                ],
                pageLength: 10
            });
        }

        $('#btn-add-consultants').on('click', function () {

            const rows = consultantsModalTable.rows().nodes();

            $('input.consultant-check:checked', rows).each(function () {
                addConsultant($(this).val(), $(this).data('name'), true);
            });

            $('#modal-consultants').modal('hide');
        });

        $(document).on('change', '#check-all-consultants', function () {
            const checked = this.checked;

            consultantsModalTable.rows().every(function () {
                $(this.node()).find('.consultant-check').prop('checked', checked);
            });
        });

        // === CLIENTS TABLE ===
        const clientsTable = $('#tbl-clients').DataTable({
            bAutoWidth: false,
            paging: true,
            searching: true,
            ordering: false,
            info: true
        });

        let clientsIndex = $('#input-cost-centers input[name^="client_codes"]').length;
        function loadClients(userId) {
            const consultantCodes = $('input[name="consultant_codes[]"]').map(function() {
                return this.value
            }).get();

            if (!consultantCodes.length) {
                clientsTable.clear().draw();
                return;
            }

            $.post("<?= site_url('employer/users/profile_actions/assign_profile_filters/clients_allowed') ?>/" + userId, {
                consultant_codes: consultantCodes
            }, function(clients) {
                //clientsTable.clear();
                //$('#input-clients').empty();
                clients.forEach(c => addClient(c.client_code, c.name, c.consultant_code, c.consultant_name));
                clientsTable.draw(false);
            }, 'json');
        }

        function addClient(clientCode, clientName, consultantCode, consultantName) {

            const uid = crypto.randomUUID();
            const rowId = createIdValues(consultantCode, clientCode);
            
            if (selectedClients.has(rowId)) {
              return;
            }
              
            selectedClients.add(rowId);
                     
            clientsTable.row.add([
                `<button type="button" class="btn btn-danger btn-sm remove" data-uid="${uid}" data-id="${rowId}">X</button>`,
                consultantName,
                clientCode,
                clientName
            ]).draw(false);

            $('#input-clients').append(`
                <input type="hidden" name="client_codes[]" value="${rowId}" data-id="${rowId}">
            `);
        }

        $('#form-clients').on('submit', function(e) {
            e.preventDefault();
            $.post("<?= site_url('employer/users/profile_actions/assign_profile_filters/save_clients/' . $app_user_info->ID) ?>", $(this).serialize(), function(r) {
              
              if (r.status == 'success') {
                window.location.reload();
                return;
              } 
              
              toastr.error(r.message); 
              
            }, 'json');
        });

        let selectedClients = new Set();
        let clientsModalTable;
        
        $(document).on('change', '#check-all-clients', function () {
          const checked = this.checked;

          clientsModalTable.rows().every(function () {
              $(this.node()).find('.client-check').prop('checked', checked);
          });
        });
        
        function loadClientsModalTable(userId) {

            const consultantCodes = $('input[name="consultant_codes[]"]').map(function () {
                return this.value;
            }).get();

            if (!consultantCodes.length) return;

            if ($.fn.DataTable.isDataTable('#table-clients-modal')) {
                clientsModalTable.ajax.reload();
                return;
            }

            clientsModalTable = $('#table-clients-modal').DataTable({
                ajax: {
                    url: "<?= site_url('employer/users/profile_actions/assign_profile_filters/clients_all/') ?>" + userId,
                    type: "POST",
                    data: function(d){
                        d.consultant_codes = consultantCodes;
                    },
                    dataSrc: function (json) {
                        return json.filter(c => !selectedClients.has(createIdValues(c.consultant_code, c.client_code)));
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        render: function (data) {
                            return `<input type="checkbox" class="client-check"
                                    value="${data.client_code}"
                                    data-consultant="${data.consultant_code}"
                                    data-consultant-name="${data.consultant_name}"
                                    data-name="${data.name}">`;
                        }
                    },
                    { data: 'consultant_name' },
                    { data: 'client_code' },
                    { data: 'name' }
                ],
                order: [[2, 'asc']],
                pageLength: 10
            });
        }

        $('#modal-clients').on('show.bs.modal', function () {
            loadClientsModalTable(userId);
        });

        $('#btn-add-clients').on('click', function () {

          const rows = clientsModalTable.rows().nodes();

          $( 'input.client-check:checked', rows).each(function () {
            
              const clientCode = $(this).val();
              const consultantCode = $(this).data('consultant');
              const consultantName = $(this).data('consultant-name');
              const name = $(this).data('name');
  
              addClient(clientCode, name, consultantCode, consultantName);
          });

          $('#modal-clients').modal('hide');
        });

        $('#tbl-clients tbody').on('click', '.remove', function () {
            const rowId = $(this).data('id');

            const row = clientsTable.row($(this).closest('tr'));
            selectedClients.delete(rowId);
            row.remove().draw(false);
            $(`#input-clients input[data-id="${rowId}"]`).remove();
        });

        $('.remove-all-clients').on('click', function() {
            selectedClients.clear();
            clientsTable.clear().draw();
            $('#input-clients').empty();
        });

        // === COST CENTERS TABLE ===
        const costCentersTable = $('#tbl-cost-centers').DataTable({
           bAutoWidth: false,  
          fixedColumns: {
                leftColumns: 2      // Fija la primera columna
            },
            fixedRows: {
                leftRows: 0
            },
            scrollCollapse: true,
            scrollX: true,
            paging: true,
            searching: true,
            ordering: false,
            info: true
        });

        let costCenterIndex = $('#input-cost-centers input[name^="cost_center_codes"]').length;
        function loadCostCenters(userId) {
            // const consultantCodes = $('input[name="consultant_codes[]"]').map(function() {
            //     return this.value
            // }).get();

            const businessUnitCodes = $('input[name="business_unit_codes[]"]').map(function() {
                return this.value
            }).get();
            
            const clientCodes = $('input[name="client_code[]"]')
                .map(function () {
                    return this.value;
                })
                .get();
                      
            // if (!consultantCodes.length && !clientCodes.length) {
            //     costCentersTable.clear().draw();
            //     return;
            // }
            
            $.post("<?php echo site_url('employer/users/profile_actions/assign_profile_filters/cost_centers_allowed') ?>/" + userId, {
                //consultant_codes: consultantCodes,
                client_codes: clientCodes,
                business_unit_codes: businessUnitCodes
            }, function(costCenters) {
                // costCentersTable.clear();
                // $('#input-cost-centers').empty();
                costCenters.forEach(c => addCostCenter(
                    c.consultant_code, 
                    c.consultant_name, 
                    c.client_code, 
                    c.client_name, 
                    c.business_unit_code,
                    c.business_unit_name,
                    c.code
                  )
                );
                costCentersTable.draw(false);
            }, 'json');
        }

        function addCostCenter(
          consultantCode, 
          consultantName, 
          clientCode, 
          clientName, 
          businessUnitCode, 
          businessUnitName, 
          code
        ) {
          
            if (selectedCostCenters.has(code)) return;
  
            const uid = crypto.randomUUID();
            
            costCentersTable.row.add([
                `<button type="button" class="btn btn-danger btn-sm remove" data-uid="${uid}">X</button>`,
                code,
                consultantName,
                clientName,
                businessUnitName
            ]).draw(false);
            
             selectedCostCenters.add(code);
             
            $('#input-cost-centers').append(`
                <input type="hidden" name="cost_center_codes[${uid}][client_code]" value="${clientCode}" data-uid="${uid}">
                <input type="hidden" name="cost_center_codes[${uid}][consultant_code]" value="${consultantCode}" data-uid="${uid}">
                <input type="hidden" name="cost_center_codes[${uid}][business_unit_code]" value="${businessUnitCode}" data-uid="${uid}">
                <input type="hidden" name="cost_center_codes[${uid}][cost_center_code]" value="${code}" data-uid="${uid}">
            `);
        }

        $('#form-cost-centers').on('submit', function(e) {
            e.preventDefault();
            $.post("<?= site_url('employer/users/profile_actions/assign_profile_filters/save_cost_centers/' . $app_user_info->ID) ?>", $(this).serialize(), function(r) {
              if (r.status == 'success') {
                window.location.reload();
                return;
              } 
              
              toastr.error(r.message);
            }, 'json');
        });

        let selectedCostCenters = new Set();
        let costCentersModalTable;

        function loadCostCentersModalTable(userId) {
          
            // const consultantCodes = $('input[name="consultant_codes[]"]').map(function () {
            //     return this.value;
            // }).get();
            // 
            
            const businessUnitCodes = $('input[name="business_unit_codes[]"]').map(function () {
                return this.value;
            }).get();
            
            const clientCodes = $('input[name="client_codes[]"]').map(function () {
                return this.value;
            }).get();
              
            if (!clientCodes.length || !businessUnitCodes.length) return;

            if ($.fn.DataTable.isDataTable('#table-cost-centers-modal')) {
                costCentersModalTable.ajax.reload();
                return;
            }

            costCentersModalTable = $('#table-cost-centers-modal').DataTable({
                ajax: {
                    url: "<?= site_url('employer/users/profile_actions/assign_profile_filters/cost_centers_all/') ?>" + userId,
                    type: "POST",
                    data: function(d){
                        //d.consultant_codes = consultantCodes;
                        d.client_codes = clientCodes;
                        d.business_unit_codes = businessUnitCodes;
                    },
                    dataSrc: function (json) {
                        return json.filter(c => !selectedCostCenters.has(c.code));
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        render: function (data) {
                            return `<input type="checkbox" class="cc-check"
                                    value="${data.code}"
                                    data-consultant="${data.consultant_code}"
                                    data-consultant-name="${data.consultant_name}"
                                    data-client="${data.client_code}"
                                    data-client-name="${data.client_name}"
                                    data-business-unit-code="${data.business_unit_code}"
                                    data-business-unit-name="${data.business_unit_name}">`;
                        }
                    },
                    { data: 'consultant_name' },
                    { data: 'client_name' },
                    { data: 'business_unit_name' },
                    { data: 'code' }
                ],
                order: [[3, 'asc']],
                pageLength: 10
            });
        }

        $('#modal-cost-center').on('show.bs.modal', function () {
            loadCostCentersModalTable(userId);
        });

        $('#check-all-cost-centers').on('change', function () {
            const rows = costCentersModalTable.rows({ search: 'applied' }).nodes();
            $('input.cc-check', rows).prop('checked', this.checked);
        });

        $('#btn-add-cost-centers').on('click', function () {

            const rows = costCentersModalTable.rows().nodes();
    
            $('input.cc-check:checked', rows).each(function () {
              const costCenterCode = $(this).val();
              const consultantCode = $(this).data('consultant');
              const consultantName = $(this).data('consultant-name');
              const clientCode = $(this).data('client');
              const clientName = $(this).data('client-name');
              const businessUnitCode = $(this).data('business-unit-code');
              const businessUnitName = $(this).data('business-unit-name');
  
              addCostCenter(
                consultantCode, 
                consultantName, 
                clientCode, 
                clientName, 
                businessUnitCode, 
                businessUnitName, 
                costCenterCode
              );
            });
          
            $('#modal-cost-center').modal('hide');
        });

        $('#tbl-cost-centers tbody').on('click', '.remove', function () {

            const uid = $(this).data('uid');

            const row = costCentersTable.row($(this).closest('tr'));
            const data = row.data();
            const code = data[1];

            selectedCostCenters.delete(code);

            row.remove().draw(false);

            $(`#input-cost-centers input[data-uid="${uid}"]`).remove();
        });

        $('.remove-all-cost-centers').on('click', function() {
            selectedCostCenters.clear();
            costCentersTable.clear().draw();
            $('#input-cost-centers').empty();
        });
    });
</script>

</body>

</html>