<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<style>
.ui-button {
  margin-left: -1px;
}
.ui-button-icon-only .ui-button-text {
  padding: 0.35em;
}
.ui-autocomplete-input {
  margin: 0;
  padding: 0.48em 0 0.47em 0.45em;
}
</style>
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
      <div class="formwraper">
        <div class="titlehead">
          <a href="<?php echo site_url('employer/users/profiles/index/' . $user->ID); ?>"
             class="_link-back" 
             style="color:#fff;">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </a>
          Gestionar U. Negocios - Solicitudes - <?php echo $user->first_name; ?>
        </div>
        <div class="row"> 
            <?php echo form_open('employer/users/permission_staff_request_manage/save_business_units', ['id' => 'form-save-business-units']); ?>
          <div class="col-md-12">
            <div class="formint">
              <input type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
              <div id="content-other-info">
                <div class="row">
                  <div class="col-md-12">
                    <button id="add-business-units"
                            type="button" 
                            class="btn btn-xs btn-primary pull-right">
                      Agregar
                    </button>
                  </div>
                </div>
                <div style="padding-top:15px;">
                  <table id="tbl-permission-business-units" class="table table-striped">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Codigo</th>
                        <th>Unidad de Negocio</th>
                      </tr>
                    </thead>
                  </table>
                  <div id="business-units-inputs"></div>
                </div>
              </div>

              <div align="center" style="margin-top: 30px;">
                <input type="submit" name="submit_button" id="submit_button" value="Guardar" class="btn btn-primary" />
              </div>
            </div>
          </div>
          <?php echo form_close();?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('common/bottom_ads'); ?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>

<?php $this->load->view('employer/users/permission_staff_request_manage/common/modal_list_business_units'); ?>

<?php $this->load->view('common/before_body_close'); ?>

<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>  
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>

<script type="text/javascript">
  $(function(){
    messageSuccess = "<?php echo trim((string)$this->session->flashdata('success')); ?>";
    if (messageSuccess != '') {
      toastr["success"](messageSuccess);
    }

    messageError = "<?php echo trim((string)$this->session->flashdata('error')); ?>";
    if (messageError != '') {
      toastr["error"](messageError);
    }
  });
</script> 
<script type="text/javascript">
    $(function() {

      $( '#tbl-business-units').DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
          }
      });

        $( '#tbl-permission-business-units' ).DataTable({
          "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
          },
          "destroy": true,
          "bAutoWidth": false,
          "deferRender": true,
          "iDisplayLength": 10,
          "bProcessing": true,
          "order":[[0, 'DESC']],

          ajax: {
            url: "<?php echo site_url('employer/users/permission_staff_request_manage/get_business_units'); ?>",
            type: 'GET',
            data: {
              'user_id': "<?php e($user->ID); ?>"
            }
          },
          columns: [
          {
            data: null, render: function(fila, type, row) {
              return '<button type="button" class="btn btn-xs btn-danger remove-business-unit"><i class="material-icons" style="font-size:10px;">clear</i></button>';
            }
            ,'className': 'style_td text-center'
            },
            {data:'business_unit_code', 'className': 'style_td text-left'},
            {data:'business_unit_name', 'className': 'style_td text-left'},
          ]
      });

      $( '#form-save-business-units' ).submit(function(){
        table = $( '#tbl-permission-business-units' ).DataTable();

        rows = table.rows().data();
        containers = $( '#business-units-inputs' );
        containers.empty();

        $.each(rows, function(index, data) {
          row_code = data.business_unit_code;
          containers.append("<input type='hidden' name='business_units[]' value='" + row_code + "'>");
        });

        return true;
      });

      $(document).on('click', '.add-business-unit', function(e) {

        data = $( '#tbl-business-units' ).DataTable().row($(this).closest('tr')).data();

        $( '#tbl-permission-business-units' ).DataTable().row.add({
            'null': '',
            business_unit_code: data[1],
            business_unit_name: data[2]
        }).draw(false);

        $( '#tbl-business-units' ).DataTable().row($(this).closest('tr')).remove().draw();
      });

      $(document).on('click', '.remove-business-unit', function() {

        data = $( '#tbl-permission-business-units' ).DataTable().row($(this).closest('tr')).data();

        $( '#tbl-business-units' ).DataTable().row.add([
          '<button type="button" class="btn btn-xs btn-success add-business-unit"><i class="material-icons" style="font-size:10px;">add</i></button>',
          data.business_unit_code,
          data.business_unit_name
        ]).draw(false);

        $( '#tbl-permission-business-units' ).DataTable().row($(this).closest('tr')).remove().draw();
      });

      $( '.business-units-selected-all' ).click(function() {

          $(this).prop('disabled', true);

          table = $(this).closest('table');
          data = table.DataTable().rows().data();

          $.each(data, function(index, data) {

              $( '#tbl-permission-business-units' ).DataTable().row.add({
                  'null': '',
                  business_unit_code: data[1],
                  business_unit_name: data[2]
              }).draw(false);
          });

          $( '#tbl-business-units' ).DataTable().clear().draw();
          $(this).prop('disabled', false);
      });

      $( '#add-business-units' ).click(function(){
        $( '#modal-business-units' ).modal('show');
      });
    });
</script>
</body>
</html>