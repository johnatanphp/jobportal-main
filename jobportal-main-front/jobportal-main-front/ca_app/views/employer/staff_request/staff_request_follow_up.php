<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css"> 
    .formwraper p{
        font-size:13px;
    }

    .text-info {
        color:#555;
        font-style: italic;
        display: block;
        font-size: 12px;
        margin-top: 2px;
    }

    .table thead th {
        text-align: center;
    }

    .table tbody td {
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
            <div class="formwraper">
                <div class="titlehead">
                    <div class="row">
                        <div class="col-md-12">
                            <b>Mis solicitudes en seguimiento</b>
                        </div>
                    </div>
                </div>
                
                <div class="table-search">
                    <?php echo form_open('employer/staff_requests_follow_up/search', array('method' => 'get')); ?>  
                        <table width="100%">
                            <tr>
                                <td width="90%">
                                    <input type="text" name="query" class="form-control" value="<?php echo html_escape($filters['query']); ?>" placeholder="Buscar por código y nombre de la solicitud">         
                                </td>
                                <td width="10%">
                                    <button type="submit" class="btn btn-block btn-search">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </button>           
                                </td>
                                <td align="right">
                                    <div class="dropdown dropdown-options-job">
                                        <button class="btn btn-sm dropdown-toggle" style="text-decoration: underline;border:1px solid #ccc;background: #fff; font-weight: bold;" type="button" data-toggle="dropdown">
                                            <i class="glyphicon glyphicon-option-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="#" data-toggle="modal" data-target="#modal-filter-request">
                                                    Filtrar
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo site_url('employer/staff_requests_follow_up/export_excel?' . $_SERVER['QUERY_STRING']); ?>">
                                                    Exportar a excel
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    <?php echo form_close(); ?>
                </div>
                <div class="table-responsive">
                    <table width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Código
                                </th>
                                <th>
                                    Solicitud
                                </th>
                                <th>
                                    Solicitante
                                </th>
                                <th>Asignada</th>
                                <th>Tipo</th>
                                <th>
                                    Estado
                                </th>
                                <th>
                                    R&S
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result_requests as $index => $row_request): ?>
                                <tr>
                                    <td>
                                        <?php echo $row_request->ID; ?>
                                    </td>
                                    <td  style="text-align: left;">
                                        <a href="<?php echo base_url('employer/staff_requests/show/' . $row_request->ID); ?>"><?php echo $row_request->job_title; ?></a>
                                        <span class="text-info">
                                            Creada el: <?php echo _date_locale_format(strtotime($row_request->creation_date), 'dd MMM y'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo $row_request->recruiter_first_name; ?>
                                    </td>
                                    <td>
                                        <?php if ($row_request->employer_ID): ?>
                                            <a 
                                                href="#" 
                                                class="sr-show-assigned-employers"
                                                data-request-id="<?php echo $row_request->ID; ?>">Ver asignación</a>
                                        <?php else: ?>
                                            No asignada
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php echo request_type_text($row_request->request_type); ?>
                                        <span class="text-info">
                                            Modelo: <?php echo $row_request->request_model_id; ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;"> 
                                        <?php echo status_process_request_text($row_request->sts_process); ?>
                                    </td>
                                    <td>
                                        <?php 
                                            echo rs_stage_status_process($row_request->rs_process_status);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>            
                <?php if (count($result_requests) == 0): ?>
                    <div align="center" class="text-red" style="padding: 20px;">
                        <h4>Ninguna solicitud encontrada</h4>
                    </div>              
                <?php endif; ?>
    
            </div>
            <!--Pagination-->
            <div class="paginationWrap pag-wrap-v2"> <?php echo ($result_requests) ? $links : '';?> </div>
        </div>
    </div>
</div>
</div>
<!-- Modal -->
<div id="modal-filter-request" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open('employer/staff_requests_follow_up/search', array('method' => 'get')); ?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mostrar Solicitudes de personal</h4>
      </div>
      <div class="modal-body">
        <div class="panel-filter"> 
          <div class="filter-title">
            <h4>Por Estado RQ</h4>
          </div>
          <select name="status_rq" class="form-control">
            <option value="all" <?php echo $filters['status_rq'] == 'all' ? 'selected="selected"' : ''; ?>>
                Todas
            </option>
            <option value="unassigned" <?php echo $filters['status_rq'] == 'unassigned' ? 'selected="selected"' : ''; ?>>
                Sin asignar
            </option>
            <option value="assigned" <?php echo $filters['status_rq'] == 'assigned' ? 'selected="selected"' : ''; ?>>
                Asignadas
            </option>
            <option value="published" <?php echo $filters['status_rq'] == 'published' ? 'selected="selected"' : ''; ?>>
                Publicada
            </option>
            <option value="pending" <?php echo $filters['status_rq'] == 'pending' ? 'selected="selected"' : ''; ?>>
                Pendiente
            </option>
            <option value="rejected" <?php echo $filters['status_rq'] == 'rejected' ? 'selected="selected"' : ''; ?>>
                Rechazada
            </option>
            <option value="canceled" <?php echo $filters['status_rq'] == 'canceled' ? 'selected="selected"' : ''; ?>>
                Cancelada
            </option>
          </select>
        </div>
        <div class="panel-filter"> 
          <div class="filter-title">
            <h4>Por Estado R&S</h4>
          </div>
          <select name="status_rs" class="form-control">
            <option value="all" <?php echo $filters['status_rs'] == 'all' ? 'selected="selected"' : ''; ?>>
                Todas
            </option>
            <option value="0" <?php echo $filters['status_rs'] == '0' ? 'selected="selected"' : ''; ?>>
                FILTRO CURRICULAR
            </option>
            <option value="1" <?php echo $filters['status_rs'] == '1' ? 'selected="selected"' : ''; ?>>
                FILTRO TELEFÓNICO
            </option>
            <option value="2" <?php echo $filters['status_rs'] == '2' ? 'selected="selected"' : ''; ?>>
                LONG LIST               
            </option>
            <option value="3" <?php echo $filters['status_rs'] == '3' ? 'selected="selected"' : ''; ?>>
                ENTREVISTA
            </option>
            <option value="4" <?php echo $filters['status_rs'] == '4' ? 'selected="selected"' : ''; ?>>
                EVALUACIÓN
            </option>
            <option value="5" <?php echo $filters['status_rs'] == '5' ? 'selected="selected"' : ''; ?>>
                TERNA O SHORT LIST
            </option>
            <option value="6" <?php echo $filters['status_rs'] == '6' ? 'selected="selected"' : ''; ?>>
                SELECCIÓN DE PERSONAL
            </option>
            <option value="6" <?php echo $filters['status_rs'] == '7' ? 'selected="selected"' : ''; ?>>
                PROCESO DE CONTRATACIÓN
            </option>
          </select>
        </div>
        <div class="panel-filter">
          <div class="filter-title"><h4>Por Tipo</h4></div>
          <label>
            <input class="filter-radio" type="radio" name="type" value="all" <?php echo $filters['type'] == 'all' ? 'checked="checked"' : ''; ?>>Todas
          </label>
          <label>
            <input class="filter-radio" type="radio" name="type" value="internal" <?php echo $filters['type'] == 'internal' ? 'checked="checked"' : ''; ?>>Internas
          </label>
          <label>
            <input class="filter-radio" type="radio" name="type" value="external" <?php echo $filters['type'] == 'external' ? 'checked="checked"' : ''; ?>>Externas
          </label>
        </div>

        <div class="panel-filter">
            <div class="filter-title">
                <h4>Por solicitante</h4>
            </div>
            <select name="recruiter" class="form-control">
                <option value="all">Todos</option>
                <?php foreach ($result_recruiters as $recruiter): ?>
                    <option value="<?php echo $recruiter->ID; ?>" <?php echo $recruiter->ID == $filters['recruiter'] ? 'selected="selected"' : ''; ?>>
                        <?php echo $recruiter->first_name . ' ' . $recruiter->last_name; ?>
                    </option>   
                <?php endforeach ?>
            </select>
        </div>

        <div class="panel-filter">
            <div class="filter-title">
                <h4>Asignada a</h4>
            </div>
            <select name="employer" class="form-control">
                <option value="all">Todos</option>
                <?php foreach ($result_employers as $employer): ?>
                    <option value="<?php echo $employer->ID; ?>" <?php echo $employer->ID == $filters['employer'] ? 'selected="selected"' : ''; ?>>
                        <?php echo $employer->first_name . ' ' . $employer->last_name; ?>
                    </option>   
                <?php endforeach ?>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" >Filtrar</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<?php $this->load->view('general/staff_request/common/modal_show_assigned_employers.php'); ?>

<script type="text/javascript">
    $(document).ready(function(){
        $( ".filter-radio" ).radio();
    });
</script>
</body>
</html>