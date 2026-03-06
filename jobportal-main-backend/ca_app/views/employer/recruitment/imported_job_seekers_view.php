<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                                <b>Listado de postulantes</b>
                            </div>
                        </div>
                    </div>
                    
                    <!--Job Description-->
                    <div class="wrapper-table"> 
                        <input type="hidden" id="job-id" value="<?php echo $job->ID; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="input-group-addon">
                                    <b>Empleo:</b> <?php echo $job->job_title; ?>      
                                </label>
                            </div>
                            <div class="col-md-6">
                                
                                <a class="btn btn-xs btn-primary pull-right" 
                                   href="<?php echo site_url('employer/entry_job_seekers/import/' . $job->ID); ?>"
                                   style="margin: 5px;">
                                    Importar postulantes
                                </a>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" style="padding:20px 10px;">
                                    <table id="import-seeker-entry" width="100%" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    DNI
                                                </th>
                                                <th>
                                                    Nombre y apellido
                                                </th>
                                                <th>
                                                    Email
                                                </th>
                                                <th>
                                                    Fuente
                                                </th>
                                                <th style="text-align: center;">Notificado</th>
                                            </tr>
                                        </thead>
                                        <?php foreach ($result_job_seekers as $row): ?> 
                                            <tr class="">
                                                <td>
                                                    <?php e($row->document_number); ?>
                                                </td>
                                                <td width="35%">
                                                    <div><?php e(trim($row->first_name . ' ' . $row->last_name)); ?></div>
                                                </td>
                                                <td>
                                                    <?php e($row->email); ?>
                                                </td>
                                                <td>
                                                    <?php e($row->recruitment_channel ? $row->recruitment_channel : '-'); ?>
                                                </td>
                                                <td align="center">
                                                    <?php e($row->notified ? 'SI' : 'NO'); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                        
                                <div class="clear"></div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Job Detail-->
            <!--Pagination-->
        </div>
    </div>
    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <?php $this->load->view('common/before_body_close'); ?>
    <script src="<?php echo base_url('public/js/validate_employer.js');?>" type="text/javascript"></script>
    <script type="text/javascript">

        $( '#import-seeker-entry ').DataTable({
            "language": {
                "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
            },
            lengthMenu: [
            [25],
            [25]
        ]
        });

        $( ".js-notify-entry-jobseeker" ).click(function(){

            if (!window.confirm("¿Está seguro de notificar de nuevo al postulante?")) {
                return false;
            }

            var btnNotify = $(this);
            btnNotify.prop('disabled', true);
            
            var email = $(this).data('jobseeker-email');
            var url = "<?php echo site_url('employer/entry_job_seekers/notify_entry_jobseeker_email'); ?>";
            var data = {
                email: email
            };

            $.post(url, data, function(response) {
                if (response.status) {
                    alert("¡Notificación enviada!");
                } else {
                    alert("¡No se pudo enviar la notificación!");
                }
            }, 'json')
            .always(function(){
                btnNotify.prop('disabled', false);
            });
        });
    </script>
    </body>
</html>