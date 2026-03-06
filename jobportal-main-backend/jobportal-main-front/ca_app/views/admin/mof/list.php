<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>

<style>
  table.dataTable.no-footer {
    border-bottom: none;
  }

  table.dataTable {
    border-collapse: collapse;
  }

  table.dataTable thead th, table.dataTable thead td {
    border-bottom: 2px solid #ddd;
  }
</style>
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
    <h1>Gestionar MOF </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Listado de MOF</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">
  <?php if($this->session->flashdata('added_action')==true): ?>
      <div class="message-container">
      	<div class="callout callout-success">
        <h4>Nuevo MOF creado con éxito</h4>
      </div>
      </div>
      <?php endif;?>
      
      <?php if($this->session->flashdata('update_action')==true): ?>
      <div class="message-container">
      	<div class="callout callout-success">
        <h4>MOF actualizado con éxito</h4>
      </div>
      </div>
      <?php endif;?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Listado de MOF</h3>

            <div class="pull-right" style="padding: 10px;text-decoration:underline;">
                <a href="<?php echo site_url('admin/mofs/create'); ?>">
                  Nuevo
                </a>
                <a href="<?php echo site_url('admin/mofs/alert_emails'); ?>" style="padding: 10px;text-decoration:underline;display:none;">
                  Gestión alertas
                </a>
                <a href="#"
                   id="btn-mofs-filters"
                   style="padding: 10px;text-decoration:underline;">
                  Filtrar
                </a>
                <a href="#" id="btn-export">
                  Exportar
                </a>
              </div>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table id="tbl-mofs" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>

                  </th>
                  <th>
                    Empresa
                  </th>
                  <th width="15%">
                    Código
                  </th>
                  <th width="35%">
                    Nombre del cargo
                  </th>
                  <th>
                    Áreas perteneciente
                  </th>
                  <th>Por solicitud</th>
                  <th>Estado</th>
                  <th>Acción</th>
                </tr>
              </thead>
            </table>
          </div>          
          <!--Pagination-->
  
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
        
        <!-- /.box --> 
      </div>
    </div>
  </section>
  <!-- Modal -->
  <div id="modal-mofs-filter" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Filtrar</h4>
        </div>
        <?php echo form_open('admin/mofs/search', array('method' => 'get', 'id' => 'form-mofs-filter')); ?>
          <div class="modal-body">
            <h5 style="display: block;">Empresa</h5>
            <select name="company_id" class="form-control">
              <?php foreach ($companies as $row): ?>
                <option value="<?php echo $row->ID; ?>" <?php echo $row->ID == $filters['company_id'] ? 'selected' : ''; ?>>
                  <?php echo $row->company_ruc . ' - ' . $row->company_name; ?>
                </option>
              <?php endforeach; ?>
            </select>

            <h5 style="display: block;">Mof</h5>
            
            <select name="requested" class="form-control">
              <option value="" <?php echo $filters['requested'] == '' ? 'selected="selected"' : ''; ?>>
                Todos
              </option>
              <option value="0" <?php echo $filters['requested'] == '0' ? 'selected="selected"' : ''; ?>>
                No solicitados
              </option>
              <option value="1" <?php echo $filters['requested'] == '1' ? 'selected="selected"' : ''; ?>>
                Solicitados
              </option>
            </select>
            <br>

            <h5 style="display: block;">Estado</h5>
            
            <select name="status" class="form-control">
              <option value="" <?php echo $filters['status'] == '' ? 'selected="selected"' : ''; ?>>
                Todos
              </option>
              <option value="1" <?php echo $filters['status'] == '1' ? 'selected="selected"' : ''; ?>>
                Activo
              </option>
              <option value="0" <?php echo $filters['status'] == '0' ? 'selected="selected"' : ''; ?>>
                Inactivo
              </option>
            </select>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>

        <?php echo form_close(); ?>
      </div>
    </div>
  </div>

  <div id="modal-export" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <?php echo form_open('admin/mofs/export_excel_list', ['method' => 'get', 'id' => 'form-mofs-export']); ?>
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Exportar Mof</h4>
          </div>
          <div class="modal-body">
              <input type="hidden" name="company_id" value="<?php echo $filters['company_id']; ?>">
              <input type="hidden" name="ids" value="">
              <input type="hidden" name="names" value="">
              <div>
                <input type="checkbox" name="show_detail_pdf" value="1">
                <label for="">Incluir detalle Pdf</label>
              </div>
              <div>
                <input type="checkbox" name="show_detail_excel" value="1">
                <label for="">Incluir detalle Excel</label>
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

  <!-- /.content --> 
</aside><!-- /.right-side -->

<?php $this->load->view('admin/common/footer'); ?>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.2.11/js/dataTables.checkboxes.min.js"></script>

<script type="text/javascript">
  $(function(){

    function search(params) 
    {
      $( '#tbl-mofs' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "destroy": true,
        "bAutoWidth": false,
        "deferRender": true,
        "iDisplayLength": 20,
        "bProcessing": true,
        "bLengthChange": false,
        ajax: {
          url: "<?php echo site_url('admin/mofs/search'); ?>",
          type: 'GET',
          data: params
        },
        columns: [
            {data:'ID', 'className': 'style_td text-left'},
            {data:'company_name', 'className': 'style_td text-left'},
            {data:'code', 'className': 'style_td text-left'},
            {data:'job_title', 'className': 'style_td text-left'},
            {data:'belonging_areas', 'className': 'style_td text-left'},
            {
              data: null, render:function(data) {
                return data.requested == '1' ? 'SI' : 'NO';
              }, 'className': 'style_td text-left'
          },          
          {
            data: null, render:function(data) {

              btnClass = data.active == '1' ? 'btn-success' : 'btn-danger';
              btnText = data.active == '1' ? 'Activo' : 'Inactivo';

              return `
                <a href="#" class="btn  btn-xs ${btnClass} js-update-sts" data-mof-id="${data.ID}">
                  ${btnText}
                </a>
              `;
            }, 'className': 'style_td text-left'
          },
          {
            data: null, render:function(data) {

              urlEdit = "<?php echo site_url('admin/mofs/edit/'); ?>" + data.ID;
              urlShow = "<?php echo site_url('admin/mofs/show/'); ?>" + data.ID;

              return `
                <a href="${urlEdit}" class="btn btn-primary btn-xs">Editar</a>
                <a href="${urlShow}" class="btn btn-primary btn-xs">Ver</a>
              `;
            }, 'className': 'style_td text-left'
          },
        ],
        columnDefs:[{
          targets:0,
          className: 'select-checkbox',
          checkboxes:{
              'selectRow': true,
              selector: 'td:first-child'
          },
        },
        {
            targets:[0, 1, 2, 3, 4, 5, 6, 7],
            orderable: false,
        }]
      });
    }

    function update_sts(btn) {   
      $(btn).attr({'disabled': true});
      var mof_id = $(btn).data('mof-id');
      var data = {
        mof_id : mof_id
      };

      var url = "<?php echo site_url('admin/mofs/update_sts'); ?>"

      $.post(url, data, function(response){

        if (!response.success) {
          toastr["error"](response.error);
          return;      
        }

        $(btn).removeClass('btn-success');
        $(btn).removeClass('btn-danger');
        
        if (response.sts == 1) {
          $(btn).addClass('btn-success');
          $(btn).text('Activo');
        } else {
          $(btn).addClass('btn-danger');
          $(btn).text('Inactivo');
        }
      }, 'json')
      .fail(function(){
        alert('¡Ha ocurrido un error!');
      }).always(function(){
        $(btn).attr({'disabled': false});
      });
    }

    $( '#btn-export' ).click(function(){
      var rows = $( '#tbl-mofs' ).DataTable().column(0).checkboxes.selected();

      if (rows.length == 0) {
        toastr["error"]('Por favor seleccione al menos 1 mof');
        return;
      }

      $( '#modal-export' ).modal('show');
    });

    $( '#form-mofs-export' ).submit(function(){
      var rows = $( '#tbl-mofs' ).DataTable().column(0).checkboxes.selected();
      var ids = [];
      var names = [];

      $.each(rows, function(index, id){
        let id_name = parseInt(id) - 1;
       
        names.push(removeAccents($( '#tbl-mofs' ).DataTable().row(id_name).data().code) + " " + removeAccents($( '#tbl-mofs' ).DataTable().row(id_name).data().job_title));
        
        ids.push(id);
      }); 
 
      $( 'input[name=ids]', '#modal-export').val(ids.join(','));
      $( 'input[name=names]', '#modal-export').val(names.join(','));
      
      return true;
    });

    $( '#form-mofs-filter' ).submit(function(e){
      e.preventDefault();

      search({
        company_id: $( '#modal-mofs-filter select[name=company_id]').val(),
        requested: $( '#modal-mofs-filter select[name=requested]').val(),
        status: $( '#modal-mofs-filter select[name=status]').val(),
      });

      $( '#modal-mofs-filter' ).modal('hide');
      return false;
    });

    $(document).on('click', '.js-update-sts', function(e){
      e.preventDefault();
      update_sts(this);
    });

    $( "#btn-mofs-filters" ).click(function(){
      $( '#modal-mofs-filter' ).modal('show');
    });

    search({
      company_id: $( '#modal-mofs-filter select[name=company_id]').val()
    });
  });

  const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
  } 

</script>
