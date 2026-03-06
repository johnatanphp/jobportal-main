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

    .formwraper p{font-size:13px;}

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

    #tbl-rys-posted-jobs thead th {
        text-align: center;
    }

    #tbl-rys-posted-jobs tbody td {
        text-align: center;
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
        <?php echo $this->session->flashdata('msg');?>
            <!--Job Application-->
            <div class="formwraper">
                <div class="titlehead">
                    <div class="row">
                        <div class="col-md-12">
                            <b>Programación de exámenes</b>
                        </div>
                    </div>
                </div>
                
                <div class="table-search">
                    <?php echo form_open('employer/recruitment_scheduled_exams/scheduled_exams/search', array('method' => 'get')); ?>   
                        <table width="100%">
                            <tr>
                                <td width="90%">
                                    <input type="text" name="query" class="form-control" value="<?php echo html_escape($filters['query']); ?>" placeholder="Buscar empleo publicado">            
                                </td>
                                <td width="10%">
                                    <button type="submit" class="btn btn-block btn-search">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </button>           
                                </td>
                                <td align="right">
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle" style="text-decoration: underline;border:1px solid #ccc;background: #fff; font-weight: bold;" type="button" data-toggle="dropdown">
                                            <i class="glyphicon glyphicon-option-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="#" data-toggle="modal" data-target="#modal-filter-jobs">
                                                    Filtrar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    <?php echo form_close(); ?>
                </div>
                <div style="padding: 6px 5px;text-align: right;">
                    <h5><?php echo $total_jobs . ($total_jobs > 1 ? ' resultados encontrados' : ' resultado encontrado'); ?></h5>
                </div>  
                <div class="table-responsive">
                    <table id="tbl-rys-posted-jobs" width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Puesto
                                </th>
                                <th align="center">
                                    Etapa R&S
                                </th>
                                <th align="center">
                                    Estado R&S
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result_posted_jobs as $row_jobs): ?>    
                                <tr>
                                    <td style="text-align: left;">
                                        <a href="#"
                                           data-job-id="<?php echo $row_jobs->ID; ?>"
                                           class="open-scheduled-exam"
                                           style="font-size: 14px;">
                                            <?php echo $row_jobs->job_title; ?>     
                                        </a>
                                        <span style="display:block;color: #555;font-style: italic;font-size:13px;">
                                            RyS Cod <?php echo $row_jobs->ID; ?> 
                                            
                                            <?php if (user_belong_to_company_internal()): ?>
                                                <?php if ($row_jobs->request_ID): ?>
                                                    - Sol. Cod <?php echo $row_jobs->request_ID; ?>
                                                <?php endif; ?>   
                                            <?php endif; ?>
                                        
                                        </span>
                                    </td>
                        
                                    <td style="text-transform: uppercase;">
                                        <?php 
                                            if (!$row_jobs->recruiment_sts):
                                                echo 'No iniciado';
                                            else:
                                                echo rs_stage_status_process($row_jobs->recruiment_sts_stage);
                                            endif; 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if (!$row_jobs->recruiment_sts):
                                                echo 'No iniciado';
                                            else:
                                                echo rs_process_status_text($row_jobs->recruiment_sts);
                                            endif;
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($total_jobs == 0): ?>
                    <div align="center" class="text-red" style="padding: 20px 10px;">
                        <h4>Ningún resultado</h4>
                    </div>              
                <?php endif; ?>
            </div>
            <div class="paginationWrap pag-wrap-v2">
                <?php echo ($result_posted_jobs) ? $links : '' ; ?>       
            </div>
        </div>
        <!--/Job Detail-->
        <!--Pagination-->
    </div>

    <!-- Modal -->
    <div id="modal-filter-jobs" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <?php echo form_open('', array('method' => 'get')); ?>
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Mostrar Empleos</h4>
          </div>
          <div class="modal-body">
            <div class="panel-filter">
              <div class="filter-title"><h4>Por R&S estado</h4></div>
                <select name="rs_status" class="form-control">
                    <option value="all" <?php echo $filters['rs_status'] == 'all' ? 'selected="selected"' : ''; ?> >Todos</option>
                    <option value="not_started" <?php echo $filters['rs_status'] == 'not_started' ? 'selected="selected"' : ''; ?>>No iniciados</option>
                    <option value="active" <?php echo $filters['rs_status'] == 'active' ? 'selected="selected"' : ''; ?>>Activos</option>
                    <option value="suspended" <?php echo $filters['rs_status'] == 'suspended' ? 'selected="selected"' : ''; ?>>Suspendidos</option>
                    <option value="canceled" <?php echo $filters['rs_status'] == 'canceled' ? 'selected="selected"' : ''; ?>>Cancelados</option>
                    <option value="finished" <?php echo $filters['rs_status'] == 'finished' ? 'selected="selected"' : ''; ?>>Terminados</option>
                </select>
            </div>
            <div class="panel-filter">
              <div class="filter-title"><h4>Por R&S etapas</h4></div>
                <select name="rs_stage" class="form-control">
                    <option value="all" <?php echo $filters['rs_stage'] == 'all' ? 'selected="selected"' : ''; ?> >Todas</option>
                    <?php foreach ($result_rs_stages as $key_value => $stage): ?>
                        <option value="<?php echo $key_value; ?>"  <?php echo $filters['rs_stage'] === (string)$key_value ? 'selected="selected"' : ''; ?> >
                            <?php echo $stage; ?>       
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
      
            <?php if ($row->is_admin == 'yes'): ?>
            <div class="panel-filter">
              <div class="filter-title"><h4>Por empleador</h4></div>
              <label>
                <input class="filter-radio" type="radio" name="employer_query" value="all" <?php echo $filters['employer_query'] == 'all' ? 'checked="checked"' : ''; ?>>Todos
              </label>
              <label>
                <input class="filter-radio" type="radio" name="employer_query" value="self" <?php echo $filters['employer_query'] == 'self' ? 'checked="checked"' : ''; ?>>Yo
              </label>
              <label>
                <input class="filter-radio" type="radio" name="employer_query" value="select" <?php echo $filters['employer_query'] == 'select' ? 'checked="checked"' : ''; ?>>Seleccionar empleador
              </label>
              <div>
                <select id="select-employer" name="employer_id" class="form-control"  <?php echo $filters['employer_query'] != 'select' ? 'style="display:none;"' : ''; ?>>
                <?php foreach($employers AS $row_employer): ?>
                  <?php $selected = $row_employer->ID == $filter_employer_id ? 'selected="selected"' : ''; ?>
                <option value="<?php echo $row_employer->ID; ?>" <?php echo $selected; ?>><?php echo $row_employer->first_name;?></option>
                <?php endforeach; ?>
                </select>
              </div>
            </div>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" >Mostrar</button>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
</div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>

<script type="text/javascript">
    $(function(){

        $( '.open-scheduled-exam' ).click(function(e){
            e.preventDefault();
            const jobId = $(this).data('job-id');
			const url = "<?php echo site_url('employer/scheduled_exams/schedule/'); ?>" + jobId;

            $( "#modal-schedule-exam" ).remove();
            $( 'body' ).append($( `<div id="modal-schedule-exam" class="modal" role="dialog"></div>` ));

            $.get(url, null, function(response){
                $( "#modal-schedule-exam" ).html(response).modal('show');
            });
        });

        $( '.filter-radio' ).radio();
        
        $( 'input[name="employer_query"]' ).change(function(){

          const value = $(this).val();
          
          $( '#select-employer' ).hide();
          
          if (value == 'select') {
            $( '#select-employer' ).show();
          }
        });
    });
</script>
</body>
</html>