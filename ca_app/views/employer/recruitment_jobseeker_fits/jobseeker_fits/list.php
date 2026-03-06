<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<style type="text/css"> 
.formwraper p{font-size:13px;}

#modal-filter-jobs .modal-content {
    max-width: 370px;
    margin:0 auto;
}

#modal-filter-jobs .modal-body {
    padding: 0px 15px;
}

#modal-filter-jobs .modal-title {
    font-size: 23px;
}

.formwraper p{font-size:13px;}

.label-check i,
.label-radio i {
    color: #333;
    vertical-align:text-bottom;
    font-size: 20px;
}

.panel-filter {
    padding: 10px 0px;
}
.panel-filter label {
    display: block;
    font-size: 16px;
    font-weight: normal;
}

.panel-filter .filter-title {
    padding: 6px 0px;
    border-bottom: 2px solid #1ba6df;
    margin-bottom: 6px;
}

.panel-filter .filter-title h4 {
    font-weight: bold;
}

.dropdown-options-job .dropdown-toggle {
    background: transparent;
    padding: 1px;
}

.text-info {
    color:#555;
    font-style: italic;
    display: block;
    font-size: 12px;
    margin-top: 2px;
}

.wrapper-table table td {
    padding: 4px;
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
                    <?php $this->load->view('employer/common/menu/sidebar');?>
                </div>
            </div>
        
            <div class="col-md-9"> 
                <?php echo $this->session->flashdata('msg'); ?>
                <!--Job Application-->
                <div class="formwraper">
                    <div class="titlehead">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="#" style="color:#fff;" class="_link-back">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                                <b>Candidatos Aptos</b>
                            </div>
                        </div>
                    </div>
                    
                    <!--Job Description-->
                    <div class="wrapper-table"> 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="formint">
                                    <div style="text-align:right;">
                                        <a href="<?php echo site_url('employer/recruitment_jobseeker_fits/jobseeker_fits/import'); ?>"
                                            class="btn btn-xs btn-primary pull-right">
                                            Importar candidatos
                                        </a> 
                                    </div>
                                </div>
                                <br>
                                <div class="formint">
                                    <div>
                                        <?php echo form_open('employer/recruitment_jobseeker_fits/jobseeker_fits/search', ['id' => 'form-filters', 'method' => 'get']); ?>
                                            <div class="formwraper" style="border:0;">
                                                <div class="input-group">
                                                    <label class="input-group-addon">Canal de reclutamiento<span></span></label>
                                                    <select name="channel" class="form-control" style="width:90%">
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($channels as $row): ?>
                                                            <option value="<?php e($row->name); ?>" <?php echo $filters['channel'] == $row->name ? 'selected' : ''; ?> ><?php e($row->name); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="input-group">
                                                    <label class="input-group-addon">Cliente <span></span></label>
                                                            
                                                    <select name="client" class="form-control" style="width:90%">
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($clients as $row): ?>
                                                            <option value="<?php e($row->name); ?>" <?php echo $filters['client'] == $row->name ? 'selected' : ''; ?> ><?php e($row->name); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="input-group">
                                                    <label class="input-group-addon">Puesto de trabajo<span></span></label>
                                                    <select name="job_title" class="form-control" style="width:90%">
                                                        <option value="">Todos</option>
                                                        <?php foreach ($jobs as $row): ?>
                                                            <option value="<?php e($row->name); ?>" <?php echo $filters['job_title'] == $row->name ? 'selected' : ''; ?> ><?php e($row->name); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div style="text-align: center;">
                                                    <button type="submit" class="btn btn-sm btn-primary" >Buscar</button>
                                                </div>
                                            </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="paginationWrap pag-wrap-v2">
                    <?php echo ($result_job_seekers) ? $links : ''; ?>      
                </div>
            </div>
            <!--/Job Detail-->
            <!--Pagination-->
        </div>
    </div>
    
    <?php $this->load->view('employer/recruitment_jobseeker_fits/jobseeker_fits/common/modal_search'); ?>
    <?php $this->load->view('employer/recruitment_jobseeker_fits/jobseeker_fits/common/modal_show_status'); ?>
    <?php $this->load->view('employer/recruitment_jobseeker_fits/job_offers/common/modal_show_job_offers'); ?>
    <?php $this->load->view('employer/recruitment_jobseeker_fits/job_offers/common/modal_notify_job_offers'); ?>
    <?php $this->load->view('employer/recruitment_jobseeker_fits/jobseeker_fits/common/modal_edit_mobile'); ?>

    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <?php $this->load->view('common/before_body_close'); ?>
    
    <script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>  
    <script src="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.2.11/js/dataTables.checkboxes.min.js"></script>
    
    <script type="text/javascript">

        $(function(){

            $( '#export' ).click(function(){
                url = "<?php echo site_url('employer/recruitment_jobseeker_fits/jobseeker_fits/export'); ?>";

                channel = $( 'select[name=channel]',  $( '#form-filters' )).val();
                job_title = $( 'select[name=job_title]',  $( '#form-filters' )).val();
                client = $( 'select[name=client]',  $( '#form-filters' )).val();

                params = 'channel=' + channel + '&' + 'job_title=' + job_title + '&client=' + client;
                window.location = url + '?' + params;
            });

            $( '#form-filters' ).submit(function(e){
                e.preventDefault();                
                channel = $( 'select[name=channel]', this).val();
                job_title = $( 'select[name=job_title]', this).val();
                client = $( 'select[name=client]', this).val();

                if (channel == '' || client == '') {
                    toastr["error"]("¡Por favor seleccione todos los filtros!");
                    return false;
                }

                $( '#modal-search' ).modal('show');

                search({
                    'channel': channel,
                    'job_title': job_title,
                    'client': client
                });

                return false;
            });

            function search(data) {

                $( '#import-seeker-entry' ).DataTable({
                    "language": {
                        "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
                    },
                    "destroy": true,
                    "bAutoWidth": false,
                    "deferRender": true,
                    "iDisplayLength": 25,
                    "bProcessing": true,
                    "order":[[0, 'DESC']],
                    "bLengthChange": false,
                    ajax: {
                        url: "<?php echo site_url('employer/recruitment_jobseeker_fits/jobseeker_fits/search'); ?>",
                        type: 'GET',
                        data: data
                    },
                    columns: [
                        {data:'seeker_id', 'className': 'style_td text-left'},
                        {data:'document_number', 'className': 'style_td text-left'},
                        {
                            data: null,
                            render: function(row) {
                                
                                return `<div>${row.first_name}</div><div style="color:#555;font-size:13px;">${row.email}</div>` ;
                            }
                            , 'className': 'style_td text-left'
                        },
                        {
                            data: null,
                            render: function(row) {
                              

                                return `<a href="#" 
                                           class="btn-edit-mobile"
                                            data-seeker-id="${row.seeker_id}" 
                                            data-mobile="${row.mobile}">
                                            ${row.mobile}
                                        </a>`;
                            }
                            , 'className': 'style_td text-right'
                        },
                        
                        {data:'recruitment_channel', 'className': 'style_td text-left'},
                        {data:'job_title', 'className': 'style_td text-left'},
                        {data:'company_account', 'className': 'style_td text-left'},
                        {
                            data: null,
                            render: function(row) {
                                isFit = row.is_fit == '1' ? 'Apto' : 'No apto';

                                return `<a href="#" 
                                           class="btn-show-status"
                                            data-seeker-id="${row.seeker_id}" >
                                            ${isFit}
                                        </a>`;
                            }
                            , 'className': 'style_td text-right'
                        },
                        {
                            data: null,
                            render: function(row) {

                                return `<a href="#" 
                                           class="show-job-offers" 
                                           data-seeker-id="${row.seeker_id}" >
                                            Ver ofertas
                                        </a>`;
                            }
                            , 'className': 'style_td text-right'
                        },
                    ],
                    columnDefs:[{
                        targets:0,
                        className: 'select-checkbox',
                        checkboxes:{
                            'selectRow': true,
                            selector: 'td:first-child'
                        },
                        createdCell:  function (td, cellData, rowData, row, col){
                        
                            $dt = this;
                        
                            this.api().cell(td).checkboxes.enable();
                            
                            if (rowData.is_fit == '0') {
                                this.api().cell(td).checkboxes.disable();
                            }
                        },
                    },
                    {
                        targets:[1, 2, 3, 4, 5, 6, 7, 8],
                        orderable: false,
                    }]
                });
            }

            $(document).on('click', '.btn-show-status', function(){
                $( '#modal-show-status' ).modal('show');

                $( '#modal-show-status .modal-body' ).html('Cargando...');

                var url = "<?php echo site_url('employer/recruitment_jobseeker_fits/jobseeker_fits/show_status'); ?>";
                var data = { 
                    'seeker_id': $(this).data('seeker-id')
                };
                $.post(url, data, function(res){
                    $( '#modal-show-status .modal-body' ).html(res);
                });
            });

            $( '#btn-modal-notify-job-offers' ).click(function(){

                $( '#modal-notify-job-offers .modal-body' ).html('Cargando...');

                var url = "<?php echo site_url('employer/recruitment_jobseeker_fits/job_offers/notify_view'); ?>";
                var seekerIds = [];
                    
                var rows = $( '#import-seeker-entry' ).DataTable().column(0).checkboxes.selected();

                $.each(rows, function(index, seekerId){
                    seekerIds.push(seekerId);
                });   

                if (seekerIds.length == 0) {
                    toastr["error"]('¡Debe selecionar al menos 1 candidato!');
                    return;
                }

                
                if (seekerIds.length > 100) {
                    toastr["error"]('¡No pude seleccionar más de 100 candidatos!');
                    return;
                }

                var data = {
                    'seeker_ids': seekerIds
                };

                $( '#modal-notify-job-offers' ).modal('show');
                $.post(url, data, function(res) {
                    $( '#modal-notify-job-offers .modal-content' ).html(res);
                });
            });

            $(document).on('click', '.show-job-offers', function(){
                $( '#modal-show-job-offers' ).modal('show');

                $( '#modal-show-job-offers .modal-body' ).html('Cargando...');

                var url = "<?php echo site_url('employer/recruitment_jobseeker_fits/job_offers/seeker_offers'); ?>";
                var data = { 
                    'seeker_id': $(this).data('seeker-id')
                };
                $.get(url, data, function(res){
                    $( '#modal-show-job-offers .modal-content' ).html(res);
                });
            });

            $(document).on('click', '.btn-edit-mobile', function(){

                $( '#modal-seeker-edit-mobile' ).modal('show');

                seekerId = $(this).data('seeker-id');
                mobile = String($(this).data('mobile'));  
                
                mobilePart = mobile.split(' ');
                mobileNumber = mobilePart[mobilePart.length - 1];
                mobileCode = $.trim(mobile.replace(mobileNumber, ""));

                $( '#modal-seeker-edit-mobile input[name=seeker_id]' ).val(seekerId);
                $( '#modal-seeker-edit-mobile input[name=mobile]' ).val(mobileNumber);
                $( '#modal-seeker-edit-mobile select[name=mobile_code]' ).val(mobileCode);
                $( '#modal-seeker-edit-mobile').data('ref-tr',  $(this).closest('tr'));
            });

            $( '#form-update-mobile' ).submit(function(e){
                e.preventDefault();

                url = $(this).prop('action');
                data = $(this).serialize();
                refTr = $( '#modal-seeker-edit-mobile').data('ref-tr');

                $.post(url, data,function(res){

                    if (res.status) {

                        //Actualizar mobile en la tabla
                        td =  $( '#import-seeker-entry' ).DataTable().cell({row:refTr.index(), column:3}).node();
                        $('a', td).html(res.mobile).data('mobile', res.mobile);

                        $( '#modal-seeker-edit-mobile' ).modal('hide');
                        toastr["success"](res.message);
                        return;
                    }

                    toastr["error"](res.message);
                    
                }, 'json')
                .fail(function(){
                    toastr["error"]('Error al enviar la solicitud');
                });

                return false;
            });

            $('.modal').on("hidden.bs.modal", function (e) { 
                if ($('.modal:visible').length) { 
                    $('body').addClass('modal-open');
                }
            });
        });
    </script>
    </body>
</html>