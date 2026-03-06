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

  .report-list {
    list-style: none;
    padding: 10px 5px;
    margin: 10px 3em;
  }

  .report-list-item {
    padding: 10px 5px;
    border-bottom: 1px solid #cccccc;
    display: flex;
    align-items: center;
  }

  .report-list-item label {
    flex: 1;
    font-weight: normal;
    margin: 0;
    font-size: 14px;
  }

  .report-list-item a {
    
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
                <a class="_link-back" style="color:#fff;" href="#">
                  <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
                <b>Reporte listado de requerimientos</b>
              </div>
            </div>
          </div>   
          <div class="formint">
            <div>
              <div style="padding: 12px 0;">
                <?php echo form_open('employer/operational_reports/staff_requests/requirement_list/export', ['method' => 'get']); ?>
                  <div class="input-group">
                    <label class="input-group-addon">Año <span>*</span></label>
                    <select class="form-control select2" name="year" required>
                      <option value="">Seleccione</option>
                      <?php for ($year = 2022; $year <= date('Y'); $year++): ?>
                        <option value="<?php echo $year; ?>" <?php echo $year == date('Y') ? 'selected' : '';?>>
                          <?php echo $year; ?>
                        </option>
                      <?php endfor; ?>
                    </select>
                  </div>
                       
                  <div class="input-group"> 
                
                    <label class="input-group-addon">Estado <span>*</span></label>
                   
                    <select name="status_rq" class="form-control select2" style="width:100%;">
                      <option value="all" >
                          Todas
                      </option>
                      <option value="unassigned" >
                          Sin asignar
                      </option>
                      <option value="assigned" >
                          Asignadas
                      </option>
                      <option value="published" >
                          Publicada
                      </option>
                      <option value="pending" >
                          Pendiente
                      </option>
                      <option value="rejected" >
                          Rechazada
                      </option>
                      <option value="canceled" >
                          Cancelada
                      </option>
                    </select>
                  </div>
                  <div class="input-group"> 
                
                    <label class="input-group-addon">RyS etapa <span>*</span></label>
                    
                    <select name="status_rs" class="form-control select2" style="width:100%;">
                      <option value="all">
                          Todas
                      </option>
                      <option value="0">
                          FILTRO CURRICULAR
                      </option>
                      <option value="1">
                          FILTRO TELEFÓNICO
                      </option>
                      <option value="2">
                          LONG LIST               
                      </option>
                      <option value="3">
                          ENTREVISTA
                      </option>
                      <option value="4">
                          EVALUACIÓN
                      </option>
                      <option value="5">
                          TERNA O SHORT LIST
                      </option>
                      <option value="6">
                          SELECCIÓN DE PERSONAL
                      </option>
                      <option value="6">
                          PROCESO DE CONTRATACIÓN
                      </option>
                    </select>
                  </div>
                  
                  <div class="input-group"> 
                    <label class="input-group-addon">Tipo <span>*</span></label>
                    <select name="employer" class="form-control">
                        <option value="all">Todos</option>
                       
                        <option value="all">Todas</option>
                        <option value="internal">Interna</option>   
                        <option value="external">Externa</option>
                    </select>
                  </div>
          
                    <div class="input-group"> 
                        <label class="input-group-addon">Solicitante <span>*</span></label>
                        <select name="recruiter" class="form-control select2" style="width:100%;">
                            <option value="all">Todos</option>
                            <?php foreach ($result_recruiters as $recruiter): ?>
                                <option value="<?php echo $recruiter->ID; ?>">
                                    <?php echo $recruiter->first_name . ' ' . $recruiter->last_name; ?>
                                </option>   
                            <?php endforeach ?>
                        </select>
                    </div>
          
                    <div class="input-group"> 
                        <label class="input-group-addon">Asignada a <span>*</span></label>
                        <select name="employer" class="form-control select2" style="width:100%;">
                            <option value="all">Todos</option>
                            <?php foreach ($result_employers as $employer): ?>
                                <option value="<?php echo $employer->ID; ?>">
                                    <?php echo $employer->first_name . ' ' . $employer->last_name; ?>
                                </option>   
                            <?php endforeach ?>
                        </select>
                    </div>
              
                  <div style="text-align: center;">
                    <input type="submit" value="Exportar" class="btn btn-sm btn-primary">
                  </div>
                  <?php echo form_close(); ?>
              </div>
            </div>
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
  $( '#site-menu a[href="<?php echo site_url('employer/operational_reports/reports'); ?>"]' ).addClass('active');
  $( 'select' ).select2();
</script>
</body>
</html>