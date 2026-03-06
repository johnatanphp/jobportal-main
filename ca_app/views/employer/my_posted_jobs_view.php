<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css">

  #modal-filter-jobs .modal-content {
    max-width: 370px;
    margin:0 auto;
  }

  .formwraper p{font-size:13px;}
  
  .dropdown-options-job li {
    padding: 3px;
    margin: 0;
    border: 0;
  }

  .searchlist table td {
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

    <?php 
      if ($this->uri->segment(2) == 'manage_employers'){
        $this->load->view('employer/common/manage_employer_menu');
      } else {
        $this->load->view('employer/common/menu/sidebar');
      }
    ?>
  </div>
  </div>
  
    <div class="col-md-9"> 
    <?php echo $this->session->flashdata('msg');?>
      <!--Job Application-->
      <div class="formwraper">
        <div class="titlehead">
          <div class="row">
            <div class="col-md-12">
              <b>Administrar empleos</b>
              <!-- Trigger the modal with a button -->
            </div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="row searchlist">
          <div style="padding: 5px">
            <?php echo form_open('employer/my_posted_jobs/search', array('method' => 'get')); ?> 
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
                    <div class="dropdown dropdown-options-job">
                      <button class="btn btn-sm dropdown-toggle" style="text-decoration: underline;border:1px solid #ccc;background: #fff; font-weight: bold;" type="button" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-option-vertical"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                          <a href="#" data-toggle="modal"  data-target="#modal-filter-jobs">
                            Filtrar
                          </a>
                        </li>
                        <li>
                          <a data-toggle="modal" data-target="#modal-export" href="#" >
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
          <?php if ($total_jobs > 0): ?>
          <div class="col-md-12">
            <div style="padding-top:7px; text-align: right;">
              <span style="color: #555;font-size: 14px;">
                <?php
                   $s = $total_jobs > 1 ? "s": "";
                  echo $total_jobs . ' empleo' . $s . ' encontrado' . $s;
                ?>
              </span>
            </div>
          </div> 
        <?php endif; ?>
          <!--Job Row-->
          <?php 
				 if($result_posted_jobs):
					foreach($result_posted_jobs as $row_jobs):
					?>
          <div class="col-md-12" id="pj_<?php echo $row_jobs->ID;?>">
            <div class="intlist">
              <div class="col-md-12">
                <div class="col-md-8">
                  <a href="<?php echo base_url('jobs/'.$row_jobs->job_slug);?>" class="jobtitle">
                    <?php echo word_limiter(strip_tags($row_jobs->job_title),9);?>
                  </a>
                  <div class="location">
                    <a href="<?php echo base_url('companies/'.$row->company_slug);?>">
                    <?php echo $row->company_name;?>
                    </a> 
                    &nbsp;-&nbsp; 
                    <?php echo $row_jobs->city;?>
                  </div>
                  <div class="info">
                    <span>
                      <i class="glyphicon glyphicon-calendar"></i>
                      <?php echo _date_locale_format(strtotime($row_jobs->dated), 'dd MMM y');?>
                    </span>
                    &nbsp;&nbsp;
                    <span>
                    <i class="glyphicon glyphicon-user"></i>Responsable:
                    <?php echo $row_jobs->employer_name;?>
                    </span>
                  </div>
                </div>
                <div class="clear"> </div>
                <p><?php echo word_limiter(strip_tags($row_jobs->job_description),35);?></p>
              
              </div>
            
              <div class="wrap-right-actions">  
                  <a href="<?php echo base_url('employer/edit_posted_job/'.$row_jobs->ID);?>" title="Editar" class="edit-ico" style="display: block;text-align: center;"><i class="fa fa-pencil">&nbsp;</i></a>
                  <?php if($row_jobs->sts=='pending'):?>
                    <span class="label label-warning">Pendiente</span>
                  <?php else:?>
                  <a href="javascript:;" style="text-decoration:none;" id="sts_<?php echo $row_jobs->ID;?>" onClick="update_posted_job_status_employer(<?php echo $row_jobs->ID;?>);" title="Desactivar / Activar este empleo"><span class="label label-<?php echo ($row_jobs->sts=='active')?'success':'danger';?>"><?php echo $row_jobs->sts == "active" ? "Activo" : "Desactivo";?></span></a> 
                  <?php endif;?>    
              </div>
              <div class="clear"></div>
            </div>
          </div>
          <?php 
					endforeach;
				 else:					
				?>
          <div align="center" class="text-red" style="padding: 25px 5px;">Ningún empleo publicado.</div>
          <?php endif;?>
        </div>
      </div>
      <div class="paginationWrap pag-wrap-v2"> <?php echo ($result_posted_jobs) ? $links : '';?> </div>
    </div>
    <!--/Job Detail-->
    <!--Pagination-->
  </div>
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
          <div class="filter-title"><h4>Por Estado</h4></div>
          <label>
            <input class="filter-radio" type="radio" name="status" value="all" <?php echo $filters['status'] == 'all' ? 'checked="checked"' : ''; ?>>Todos
          </label>
          <label>
            <input class="filter-radio" type="radio" name="status" value="active" <?php echo $filters['status'] == 'active' ? 'checked="checked"' : ''; ?>>Activos
          </label>
          <label>
            <input class="filter-radio" type="radio" name="status" value="pending" <?php echo $filters['status'] == 'pending' ? 'checked="checked"' : ''; ?>>Pendientes
          </label>
          <label>
            <input class="filter-radio" type="radio" name="status" value="blocked" <?php echo $filters['status'] == 'blocked' ? 'checked="checked"' : ''; ?>>Bloqueados
          </label>
          <label>
            <input class="filter-radio" type="radio" name="status" value="inactive" <?php echo $filters['status'] == 'inactive' ? 'checked="checked"' : ''; ?>>Inactivos
          </label>
        </div>
        <div class="panel-filter">
          <div class="filter-title"><h4>Por fecha de finalización</h4></div>
          <label>
            <input class="filter-radio" type="radio" name="expired" value="all" <?php echo $filters['expired'] == 'all' ? 'checked="checked"' : ''; ?>>Todos
          </label>
          <label>
            <input class="filter-radio" type="radio" name="expired" value="no" <?php echo $filters['expired'] == 'no' ? 'checked="checked"' : ''; ?>>Sin finalizar
          </label>
          <label>
            <input class="filter-radio" type="radio" name="expired" value="yes" <?php echo $filters['expired'] == 'yes' ? 'checked="checked"' : ''; ?>>Finalizados
          </label>
        </div>
        <?php if ($row->is_admin == 'yes'): ?>
        <div class="panel-filter">
          <div class="filter-title"><h4>Publicado por</h4></div>
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
              <?php $selected = $row_employer->ID == $filters['employer_id'] ? 'selected="selected"' : ''; ?>
            <option value="<?php echo $row_employer->ID; ?>" <?php echo $selected; ?>><?php echo $row_employer->first_name;?></option>
            <?php endforeach; ?>
            </select>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Mostrar</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="modal-export" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open('employer/my_posted_jobs/export', array('method' => 'get')); ?>
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Exportar empleos</h4>
        </div>
        <div class="modal-body">  
          <?php if ($row->is_admin == 'yes'): ?>
            <div class="panel-filter"> 
              <div class="filter-title"><h4>Publicado por</h4></div>
              <select name="employer_id" class="form-control">
                <option value="">Todos</option>
                <?php foreach($employers AS $row_employer): ?>
                  <?php $selected = $row_employer->ID == $filters['employer_id'] ? 'selected="selected"' : ''; ?>
                  <option value="<?php echo $row_employer->ID; ?>" <?php echo $selected; ?>><?php echo $row_employer->first_name;?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>
          <div class="panel-filter"> 
            <div class="filter-title"><h4>Fecha de finalización</h4></div>
            <select name="expired" class="form-control">
              <option value="0">Sin finalizar</option>
            </select>
          </div>
          <div class="panel-filter">
            <div class="filter-title"><h4>Estado</h4></div>
            <select name="status" class="form-control">
              <option value="1">Activa</option>
              <option value="0">Inactiva</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Exportar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('employer/common/employers_popup_forms'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/validate_employer.js');?>" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $( '.filter-radio' ).radio();

    $( 'input[name="employer_query"]' ).change(function(){

      var value = $(this).val();
      
      $( '#select-employer' ).hide();
      
      if (value == 'select') {
        $( '#select-employer' ).show();
      }
    });
  });
</script>
</body>
</html>