<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title;?></title>
  <?php $this->load->view('common/before_head_close'); ?>
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
    <?php echo $this->session->flashdata('msg');?>
      <!--Job Application-->
      <div class="formwraper">
        <div class="titlehead">
          <div class="row">
            <div class="col-md-12">
              <b>
                Carga de resultados
              </b>
            </div>
          </div>
        </div>
        <br />
        <ul class="nav nav-tabs">
            <li class="<?php echo $filters['loaded'] == 0 ? 'active' : ''; ?>">
                <a href="<?php echo site_url('employer/exam_requests/request_results/search/0'); ?>">
                    Pendientes
                </a>
            </li>
            <li class="<?php echo $filters['loaded'] == 1 ? 'active' : ''; ?>">
                <a href="<?php echo site_url('employer/exam_requests/request_results/search/1'); ?>">
                    Cargados
                </a>
            </li>
        </ul>
        
        <div >
          <div class="table-search">
            <?php echo form_open('', array('method' => 'get')); ?>  
              <table width="100%">
                <tr>
                  <td width="90%">
                    <input type="text" name="query" class="form-control" value="<?php echo $filters['query']; ?>" placeholder="Buscar por candidatos">     
                  </td>
                  <td width="10%">
                    <button type="submit" class="btn btn-block btn-search">
                      <i class="glyphicon glyphicon-search"></i>
                    </button>     
                  </td>
                  <td align="right">
                  </td>
                </tr>
              </table>
            <?php echo form_close(); ?>
          </div>
          <table class="table table-striped">
          <?php foreach ($result_candidates as $row_candidate): ?>
            <tr>
                <td width="100">
                    <img src="<?php echo img_pic_candidate($row_candidate->photo); ?>" 
                         alt="<?php echo $row_candidate->first_name; ?>" style="max-height:50px;" />
                </td>
                <td>
                    <b>
                        <?php echo ellipsize(strip_tags(trim($row_candidate->first_name . ' ' . $row_candidate->last_name)), 25); ?>    
                    </b>    
                    <div class="devinfo">   
                        <span style="font-style: italic;">
                          <?php echo $row_candidate->exam_request_type_name . ' - ' . $row_candidate->medical_center_name . ' - ' . date('d/m/Y', strtotime($row_candidate->exam_date)); ?> 
                        </span>
                    </div>            
                    <div class="devinfo">
                        <?php echo $row_candidate->job_title; ?>
                    </div>
                    <div class="devinfo" style="display: none;">
                        <?php echo $row_candidate->count_doc_without_uploading; ?> Doc. Pendiente(s).
                    </div>
                </td>
                <td align="right">
                    <br />
                    <a class="btn btn-xs btn-primary-dark" 
                       href="<?php echo site_url('employer/exam_requests/request_results/detail/' . $row_candidate->job_ID . '/' . $row_candidate->seeker_ID); ?>">
                      <?php echo $filters['loaded'] ? 'Ver documentos' : 'Subir documentos'; ?>      
                    </a>
                  </div>
                </td>
            </tr>
          <?php endforeach; ?>
        </table>

        <?php if (count($result_candidates) == 0): ?>
            <div align="center" class="text-red" style="padding: 20px;">
                <h4>Sin resultados</h4>
            </div>              
        <?php endif; ?>
        </div>
      </div>
      <div class="paginationWrap pag-wrap-v2"> <?php echo ($result_candidates) ? $links : '';?> </div>
    </div>
    <!--Pagination-->
  </div>
</div>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('employer/common/employers_popup_forms'); ?>
<?php $this->load->view('common/before_body_close'); ?>
</body>
</html>