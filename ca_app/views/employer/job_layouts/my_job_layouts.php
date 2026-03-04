<!DOCTYPE html>
<html lang="en">
  <head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title>
    <?php echo $title;?>		
  </title>
  <?php $this->load->view('common/before_head_close'); ?>
  <style type="text/css"> 
    .formwraper p {
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
      text-align: left;
    }

    .table tbody td {
      text-align: left;
    }

    .label.label-success, 
    .label.label-danger {
      cursor: pointer;
    }

    .label.disabled {
      opacity: 0.5;
      cursor: no-drop;
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
                    <b>Layouts de puestos creados</b>
                  </div>
                </div>
              </div>

              <!-- Description -->
              <div class="table-search">
                <?php echo form_open('employer/job_layouts/my_job_layouts/search', array('method' => 'get')); ?>	
                  <table width="100%">
                    <tr>
                      <td width="90%">
                        <input type="text" name="query" class="form-control" value="<?php echo $filters['query']; ?>" placeholder="Buscar por código y puesto">			
                      </td>
                      <td width="10%">
                        <button type="submit" class="btn btn-block btn-search">
                          <i class="glyphicon glyphicon-search"></i>
                        </button>			
                      </td>
                      <td>
                        <div class="dropdown dropdown-options-job">
                          <button class="btn btn-sm dropdown-toggle" style="text-decoration: underline;border:1px solid #ccc;background: #fff; font-weight: bold;" type="button" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-option-vertical"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                              <a href="#" data-toggle="modal" data-target="#modal-filter-profile">
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

              <div class="table-responsive">
                <table width="100%" class="table table-striped">
                  <thead>
                    <tr>
                      <th>
                        Cod.
                      </th>
                      <th>
                        Cod. integración
                      </th>
                      <th>
                        Puesto
                      </th>
                      <th>
                        Estado
                      </th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($results as $row_job_layout): ?>
                      <tr>
                          <td><?php echo $row_job_layout->code ? $row_job_layout->code : '-'; ?></td>
                          <td><?php echo $row_job_layout->code_integration ? $row_job_layout->code_integration : '-'; ?></td>
                          <td>
                            <a href="<?php echo site_url('employer/job_layouts/job_layouts/show/' . $row_job_layout->id); ?>">
                              <?php echo $row_job_layout->job_title; ?>
                            </a>
                          </td>      
                          <td>
                            <?php 
                              $toggle_disabled = !has_permission_action('job_layouts', 'active_inactive') ? 'disabled' : '';
                            ?>
                          
                            <div class="checkbox-wrapper-3">
                              <input class='btn-toggle-jl-status tgl tgl-ios' 
                                     id='toggle-jl-status-<?php echo $row_job_layout->id; ?>' 
                                     type='checkbox'
                                     data-id="<?php echo $row_job_layout->id; ?>"
                                     <?php echo $toggle_disabled; ?>
                                     <?php echo $row_job_layout->active ? 'checked' : ''; ?>
                                     value="1">
                              <label class='tgl-btn' for='toggle-jl-status-<?php echo $row_job_layout->id; ?>' style="width: 70px; height: 22px;"></label>
                            </div>
                
                          </td>
                          <td>
                            <?php if ($this->session->userdata('current_profile_id') == 2): ?>
                              <a class="btn btn-sm btn-default" href="<?php echo site_url('employer/job_layouts/job_layouts/edit/' . $row_job_layout->id); ?>">
                                Editar
                              </a>
                            <?php endif; ?>

                            <?php if ($this->session->userdata('current_profile_id') == 4): ?>
                              <a class="btn btn-sm btn-default" href="<?php echo site_url('employer/job_layouts/job_layouts/show/' . $row_job_layout->id . '#section-resources'); ?>">
                                Editar
                              </a>
                            <?php endif; ?>
                          </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <?php if (count($results) == 0): ?>
                <div align="center" class="text-red" style="padding: 20px;">
                  <h4>Sin resultados</h4>
                </div>				
              <?php endif; ?>

            </div>
            <?php if (count($results) > 0): ?>
              <div class="table-footer-info">
                <div class="row">
                  <div class="col-sm-6">
                    <label class="pagination-info">
                      Página <?php echo ($pagination['current_page'] + 1); ?> de <?php echo $pagination['total_pages']; ?> 
                      <span style="margin: 0 4px;color:#ccc;">|</span>
                      <?php echo $pagination['total_rows']; ?> registros
                    </label>
                  </div>
                  <div class="col-sm-6">
                    <div class="paginationWrap pag-wrap-v2">
                      <?php echo ($results) ? $pagination['links'] : '';?>
                    </div>
                  </div>
                </div>
              </div>       
            <?php endif; ?>          
          </div>
          <!-- End column col-md-9-->
        </div>
      </div>
    </div>

    <?php $this->load->view('employer/job_layouts/modal/filters_layouts');?>

    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <!-- Profile Popups -->
    <?php $this->load->view('common/before_body_close'); ?>
    <script type="text/javascript">
      $(function(){
        
        $( "#modal-filter-profile select").each(function(){
          $(this).select2();
        });

        $( '.btn-toggle-jl-status' ).change(function(){
          const newStatus = $(this).is(':checked') ? 1 : 0;
          const jobLayoutId = $(this).data('id');
          const btnToggle = $(this);

          btnToggle.closest('.checkbox-wrapper-3').addClass('load load-image');
          
          const data = {
            id: jobLayoutId,
            sts: newStatus 
          };

          const url = "<?php echo site_url('employer/job_layouts/job_layouts/update_sts'); ?>"

          $.post(url, data, function(response){

            btnToggle.closest('.checkbox-wrapper-3').removeClass('load load-image');

            if (!response.status) {
              btnToggle.prop('checked', newStatus ? false : true);
              toastr["error"](response.message);
              return;
            }
          }, 'json')
          .fail(function(){
            toastr["error"]('¡Ha ocurrido un error!');
            btnToggle.closest('.checkbox-wrapper-3').removeClass('load load-image');
            btnToggle.prop('checked', newStatus ? false : true);
          }).always(function(){});

        });
      });
    </script>
  </body>
  