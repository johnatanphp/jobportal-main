<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $title;?></title>
    <?php $this->load->view('admin/common/meta_tags'); ?>
    <?php $this->load->view('admin/common/before_head_close'); ?>
    <script src="<?php echo base_url('public/js/jquery-1.9.1.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/jquery-ui.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/admin/bootstrap.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo base_url('public/js/admin/admin_functions.js');?>" type="text/javascript"></script>
    <script src="<?php echo base_url('public/js/validation.js');?>" type="text/javascript"></script>
    <script type="text/javascript" src="http://www.technicalkeeda.com/js/javascripts/plugin/json2.js"></script>
    <script>
      var totalRec = <?php echo $total_skills; ?>;
      var startRec = 0;
      var endRec = 100;
      var currentPage = 1; // Página actual
      var totalPages = Math.ceil(totalRec / endRec);

      // Función para cargar datos de una página específica
      function loadPage(page) {
        startRec = (page - 1) * endRec;
        var datastr = "start=" + startRec + "&end=" + endRec;
        $('#loader').css('display', 'block');
        $('#finalResult').html("");
        $(window).scrollTop(0);
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('admin/skills/load_ajax'); ?>",
          data: datastr,
          success: function(html2) {
            $('#finalResult').html(html2);
            $('#loader').css('display', 'none');
            currentPage = page;
            updatePaginationControls();
          }
        });
      }

      // Función para actualizar los controles de paginación
      function updatePaginationControls() {
        var paginationHtml = '';

        if (currentPage > 1) {
          paginationHtml += '<button onclick="loadPage(' + (currentPage - 1) + ')" class="btn btn-secondary">Anterior</button>';
        }

        paginationHtml += ' Página ' + currentPage + ' de ' + totalPages + ' ';

        if (currentPage < totalPages) {
          paginationHtml += '<button onclick="loadPage(' + (currentPage + 1) + ')" class="btn btn-secondary">Siguiente</button>';
        }

        $('#paginationControls').html(paginationHtml);
      }

      $(document).ready(function() {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
          var target = $(e.target).attr("href");
          if (target === '#manage') {
            loadPage(1);
          }
        });
      });
    </script>
  </head>
  <body class="skin-blue">
      <?php $this->load->view('admin/common/after_body_open'); ?>
      <?php $this->load->view('admin/common/header'); ?>
      <div class="wrapper row-offcanvas row-offcanvas-left">
          <?php $this->load->view('admin/common/left_side'); ?>
          <!-- Right side column. Contains the navbar and content of the page -->
          <aside class="right-side">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1> Manage Skills</h1>
                  <ol class="breadcrumb">
                      <li><a href="<?php echo base_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                      <li class="active">Skills</li>
                  </ol>
              </section>

              <!-- Main content -->
              <section class="content">
                  <!-- Navegación de Pestañas -->
                  <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#skills">Skills</a></li>
                      <li><a data-toggle="tab" href="#manage">Skills Frequency</a></li>
                  </ul>

                  <!-- Contenido de las Pestañas -->
                  <div class="tab-content">
                      <!-- Pestaña Skills -->
                      <div id="skills" class="tab-pane fade in active">
                          <div class="text-right" style="margin-top: 10px;">
                              <a href="javascript:;" onClick="load_skill_add_form();" class="btn btn-primary btn-md">Add New Skill</a>
                          </div>
                          <div class="clear">&nbsp;</div>
                          <div class="row">
                              <div class="col-xs-12">
                                  <div class="box">
                                      <div class="box-header">
                                          <h3 class="box-title">Skills</h3>
                                          <div class="paginationWrap"> <?php echo ($result) ? $links : ''; ?> </div>
                                      </div>

                                      <?php echo form_open('admin/skills', array('method' => 'get')); ?>
                                      <div style="padding: 10px;">
                                        <table width="100%" style="margin-bottom: 15px;">
                                          <tr>
                                            <td width="90%">
                                              <input type="text" name="query" value="<?php echo $filters['query']; ?>" class="form-control" style="min-width: 100%;" placeholder="Buscar skill">
                                            </td>
                                            <td width="10%">
                                              <button type="submit" class="btn btn-primary btn-sm btn-block">
                                                <i class="glyphicon glyphicon-search"></i>
                                              </button>
                                            </td>    
                                          </tr>
                                        </table>
                                      </div>
                                      <?php echo form_close(); ?>
                                      <!-- /.box-header -->
                                      <div class="box-body table-responsive" style="height:800px; overflow:auto;">
                                          <table class="table table-bordered table-hover" id="example2">
                                              <tbody>
                                                  <?php if ($result) : ?>
                                                      <?php foreach ($result as $row) : ?>
                                                          <tr id="row_<?php echo $row->ID; ?>">
                                                              <td width="200">
                                                                  <?php echo $row->skill_name . ' (' . $row->counter . ')'; ?>
                                                              </td>
                                                              <td width="100">
                                                                  <a href="javascript:;" onClick="load_skill_edit_form(<?php echo $row->ID; ?>);" class="btn btn-primary btn-xs">Edit</a>&nbsp;
                                                                  <a href="javascript:;" onClick="delete_skill(<?php echo $row->ID; ?>);" class="btn btn-danger btn-xs">Delete</a>
                                                              </td>
                                                          </tr>
                                                      <?php endforeach; ?>
                                                  <?php else : ?>
                                                      <tr>
                                                          <td colspan="5" align="center" class="text-red">No Record found!</td>
                                                      </tr>
                                                  <?php endif; ?>
                                              </tbody>
                                          </table>
                                      </div>
                                      <!--Pagination-->
                                      <div class="paginationWrap"> <?php echo ($result) ? $links : ''; ?> </div>
                                      <!-- /.box-body -->
                                  </div>
                                  <!-- /.box -->
                              </div>
                          </div>
                      </div>

                      <!-- Pestaña Skills Frequency -->
                      <div id="manage" class="tab-pane fade">
                        <div class="col-xs-12">
                          <div class="box">
                              <div class="box-header">
                                  <h3 class="box-title">Skills Frequency</h3>
                                  <div class="paginationWrap">
                                      Total Skills: <strong><?php echo $total_skills; ?></strong>
                                  </div>
                              </div>

                              <div class="box-body table-responsive">
                                  <div class="text-right" style="padding-bottom:2px;">
                                      <!-- <input type="button" class="btn btn-primary btn-sm" value="Add New Page" onClick="load_cms_add_form();" /> -->
                                  </div>

                                  <table class="table table-bordered table-hover" id="example2">
                                      <tbody id="finalResult">
                                      </tbody>
                                      <tfoot>
                                      </tfoot>
                                  </table>

                                  <div id="loader" style="display:none; margin-top:10px; font-size:20px; color:#CCC; text-align:center; font-weight:bold;">
                                      <i class="fa fa-refresh fa-spin" style="font-size:40px;"></i> Loading more records...
                                  </div>
                              </div>

                              <div id="paginationControls" class="text-center" style="margin-top: 20px; padding-bottom: 20px;">
                              </div>
                          </div>
                        </div>
                      </div>
                      <!-- /.Skills Frequency -->
                  </div>
              </section>
              <!-- /.content -->
          </aside>
          <!-- /.right-side -->

          <?php $this->load->view('admin/common/footer'); ?>
          <?php if (validation_errors() != false) { ?>
              <script type="text/javascript">
                  $('#edit_skill_form').modal('show');
                  select_option_value('blend_to', '');
              </script>
          <?php } ?>
      </div>
      <!-- Add Model -->
      <div class="modal fade" id="add_skill_form">
        <div class="modal-dialog">
          <form name="frm_skill" id="frm_skill" role="form" method="post" action="<?php echo base_url('admin/skills/add');?>" onSubmit="return validate_add_skill_form(this)">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Skill</h4>
              </div>
              <div class="modal-body"> 
                <!-- /.box-header --> 
                <!-- form start -->
                
                <div class="box-body">
                  <div class="form-group">
                    <input type="text" class="form-control" name="add_skill_name" id="add_skill_name" value="<?php echo set_value('add_skill_name');?>" placeholder="Skill Name">
                    <?php echo form_error('add_skill_name'); ?> </div>
                </div>
                <!-- /.box-body --> 
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" name="add_submit" class="btn btn-primary">Add</button>
              </div>
            </div>
          </form>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- Edit Model-->
      <div class="modal fade" id="edit_skill_form">
        <div class="modal-dialog">
          <form name="frm_skill" id="frm_skill" role="form" method="post" action="<?php echo base_url('admin/skills/update');?>" onSubmit="return validate_edit_skill_form(this)">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit / Blend Skill</h4>
              </div>
              <div class="modal-body"> 
                <!-- /.box-header --> 
                <!-- form start -->
                
                <div class="box-body">
                  <div class="form-group">
                    <input type="text" class="form-control" name="skill_name" id="skill_name" value="<?php echo set_value('skill_name');?>" placeholder="Skill Name" style="display:inline;">&nbsp;<small style="color:#000; font-style:italic;">This will get deleted</small>
                    <?php echo form_error('skill_name'); ?> </div>
                    <input type="hidden" name="sid" id="sid" />
                    Blend into <select name="blend_to" id="blend_to">
                      <option value=""> - Select - </option>
                      <?php foreach($result as $row):?>
                          <option value="<?php echo $row->skill_name;?>"><?php echo $row->skill_name;?></option>
                        <?php endforeach;?>
                        
                    </select>&nbsp;<small style="color:#000; font-style:italic;">This will survive</small>
                    <br />
                    <small style="color:#561810; font-style:italic;"><strong>Note:</strong> Keep it blank if you just want to edit the skill name.</small>
                    <br /><br />
                    <div class="checkbox" style="padding-left: 20px;">
                      <label>
                        <input type="checkbox" name="skill_favorite" id="skill_favorite">  Skill Favorite
                      </label>
                    </div>
                    <div class="form-group <?php echo (form_error('occupational_group'))?'has-error':'';?>">
                      <label>Grupo ocupacional <span>*</span></label>
                      <select name="occupational_group" class="form-control factor-automatic" data-factor-type-id="3" data-factor-ref-val="skills" id="occupational_group" style="width: 90%;" required="true">
                        <option value="">Seleccione</option>
                        <?php foreach ($job_charges as $row): ?>
                          <?php 
                            $selected = ($row->ID == $job_layout->job_charge_id) ? 'selected="selected"' : ''; 
                            $og_skills = $this->db->get_where('tbl_job_charge_skills', [
                              'job_charge_id' => $row->ID
                            ])->result();
                    
                            $array_skills = [];
                    
                            foreach ($og_skills as $skill) {
                                $array_skills[] = mb_strtoupper($skill->skill_name);
                            }
                            sort($array_skills);
                          ?>

                          <option value="<?php echo $row->ID; ?>" 
                            data-factor-grade="<?php echo $row->valorization_grade; ?>"
                            data-factor-score="<?php echo $row->valorization_score; ?>"
                            data-factor-desc="<?php echo join(',', $array_skills); ?>"
                            <?php echo $selected; ?>>
                            <?php echo $row->charge_name; ?>    
                          </option>
                        <?php endforeach; ?>                  
                      </select>
                      <?php echo form_error('occupational_group'); ?>
                    </div>
                </div>
                <!-- /.box-body --> 
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" name="submitter" class="btn btn-primary">Update</button>
              </div>
            </div>
          </form>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- /.right-side -->
  </body>
</html>