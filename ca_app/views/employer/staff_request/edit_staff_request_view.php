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

.content-step {
  display: none;
}

.step-title {
  color: #333;
  font-size: 17px;
  text-transform: uppercase;
  padding: 8px 4px;
  border-bottom: 1px solid #999;

}

.step-inputs {
  padding: 20px 0px;
}

.step-counter {
  text-align: right;
  text-transform: uppercase;
}

.btn-controls-step {
  display: none;
}

.content-checkbox label {
  display: block;
}

#table-competences {
  border: 1px solid #bbb;
  background:#efefef;
  width: 100%;
}

#table-competences tr td input {
  font-size: 15px;
}

#table-competences tr td {
  padding: 8px 4px;
  border-bottom: 1px solid #bbb;
  font-size: 15px;
  font-style: italic;
  color: #444;
}

.btn-add-item {
  margin:0;
  padding: 4px 7px;
  border:0;
  border-radius: 5px;
  
  background: #bbb;
  color: #444;
  font-size: 12px;
}

.table-items {
  width: 100%;
  margin-bottom: 30px;
}

.table-items tr th {
  padding: 5px 5px 15px 5px;
}


.table-items tr td {
  padding: 3px;
}

.btn-remove-item {
  margin:0;
  padding: 0;
  border:0;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  background: #e76767;
  color: #fff;
  font-size: 10px;
}

#table-additional-benefits tr td {
  padding: 3px 7px;
}

#table-additional-benefits .tr-bg-1 {
  background: #e0e0e0;
}

#table-additional-benefits .tr-bg-2 {
  background: #fff;
}


#table-range-salary,
#table-range-age {
  width: 80%
}

#table-range-salary .separator,
#table-range-age .separator
{
  padding: 0px 20px;
  text-align: center;
}

#text-guide-function {
  font-size: 17px;
  text-transform: lowercase;
}

#content-info-eg {
  font-size: 15px;
  padding: 10px 6px;
  background: #eee;
  margin-bottom: 10px;
}

.text-verb {
  font-weight: bold;
}

.text-object {
  font-style: italic;
}

.text-result {
  text-decoration: underline;
}

#table-employee-replace tr td {
  padding: 5px 10px;
}

#table-replace-employee tr td {
  padding: 5px 10px;
}

#btn-search-replace-employee {
  background: #e0e0e0 !important;
  border: 1px solid #ccc;
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
        <?php $this->load->view('employer/common/menu/sidebar');?>
      </div>
    </div>
    <div class="col-md-9"> <?php echo $this->session->flashdata('msg');?> 
      <div class="formwraper">
        <div class="titlehead">
          <a href="#" onclick="window.history.back();" style="color:#fff;">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </a>  
          Editar <?php echo $section_name; ?>
        </div>
        <div class="row">
          <?php echo form_open_multipart('');?>
          <div class="col-md-12">
            <div class="formint">
              <div class="">
                <?php echo $section_view; ?>
              </div>
              <div align="center" style="margin-top: 30px;">
                <input type="submit" value="Guardar" class="btn btn-primary" />
              </div>
            </div>
          </div>
          <?php echo form_close();?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<script src="<?php echo base_url('public/js/bad_words.js'); ?>"></script>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>  
<script src="<?php echo base_url('public/js/jquery.timepicker.js'); ?>" type="text/javascript"></script>  
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>

<script id="tpl-add-additional-competence" type="text/template">
    <tr class="row-additional-competence">
      <td>
        <input type="text" name="additional_competences[]" class="form-control">
      </td>
      <td>
        <button type="button" onclick="$(this).closest('tr').remove();" class="btn-remove-item">
          <i class="glyphicon glyphicon-remove"></i>
        </button>
      </td>
    </tr>
</script>

<script id="tpl-add-application" type="text/template">
  <tr>
    <td>
      <input type="text" name="computing[{{index}}][name]" class="form-control">
      <input type="hidden" name="computing[{{index}}][type]" class="form-control" value="application">
    </td>
    <td>
      <select name="computing[{{index}}][level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
      </select>
    </td>
    <td>
      <button type="button" onclick="$(this).closest('tr').remove();" class="btn-remove-item">
        <i class="glyphicon glyphicon-remove"></i>
      </button>
    </td>
  </tr>
</script>

<script id="tpl-add-functions" type="text/template">
    <tr>
      <td>
        <input type="text" name="functions[]" class="form-control">
      </td>
      <td>
        <button type="button" onclick="$(this).closest('tr').remove();" class="btn-remove-item">
          <i class="glyphicon glyphicon-remove"></i>
        </button>
      </td>
    </tr>
</script>

<script id="tpl-add-languages" type="text/template">
  <tr>
    <td>
      <input type="text" name="languages[{{index}}][name]" class="form-control">
    </td>
    <td>
      <select name="languages[{{index}}][reading_level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
      </select>
    </td>
    <td>
      <select name="languages[{{index}}][speaking_level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
      </select>
    </td>
    <td>
      <select name="languages[{{index}}][writing_level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
      </select>
    </td>
    <td>
      <button onclick="$(this).closest('tr').remove();" class="btn-remove-item">
        <i class="glyphicon glyphicon-remove"></i>
      </button>
    </td>
  </tr>
</script>
<script type="text/javascript">
  
  $(document).ready(function(){

    window.indexLanguages = 0;
    window.indexComputing = 0;
    window.indexLanguages = 0;

    function addRowFunctions()
    {
      var template = $( "#tpl-add-functions" ).html();

      var row = Mustache.render(template, {});  
      $( "#table-functions tbody" ).prepend(row); 
    }

    function addRowLanguages() {
      var template = $( "#tpl-add-languages" ).html();
      var counterLanguages = $( "#table-languages" ).data('counterLanguages') - 1;    
      $( "#table-languages" ).data('counterLanguages', counterLanguages);

      var row = Mustache.render(template, {index: counterLanguages});  
      $( "#table-languages tbody" ).prepend(row);
    }

    function addRowApplication() {
      var template = $( "#tpl-add-application" ).html();
      var counterComputing = $( ".tbl-section-computing" ).data('counterComputing') - 1;
      $( ".tbl-section-computing" ).data('counterComputing', counterComputing);

      var row = Mustache.render(template, {index: counterComputing});  
      $( "#table-applications tbody" ).prepend(row);
    }

    function addRowAdditionalCompetence() {
      var template = $( "#tpl-add-additional-competence" ).html();

      var row = Mustache.render(template, {});  
      $( "#table-competences tbody" ).prepend(row); 
    }

    function addEvents()
    {
      $( "#btn-add-application" ).click(function(){
        addRowApplication();
      });

      $( "#btn-add-language" ).click(function(){
        addRowLanguages();
      });

      $( "#btn-add-function" ).click(function(){
        addRowFunctions();
      });

      $( "#btn-add-additional-competence" ).click(function(){
        addRowAdditionalCompetence();
      });

      $(document).on("click", "#remove-replace-employee", function(){
        $( "#row-selector-replace-employee" ).hide();
        $( "#row-search-replace-employee" ).show();
        $( "#selector-replace-employee" ).val("");
      });

      $( "#reason_request" ).change(function() {
        $( "#content-replace-employee" ).hide();
        var reasonRequestVal = $(this).val();

        if (reasonRequestVal == 'replacement' || 
            reasonRequestVal == 'vacations'  || 
            reasonRequestVal == 'license') {
          $( "#content-replace-employee" ).show();
        }
      });

    $( "#btn-search-replace-employee" ).click(function() {
      var query = $( "#search-replace-employee" ).val();
      
      if (query.length < 3) {
        alert('¡Por favor ingrese al menos 3 caracteres!');
        return;
      }

      var url = "<?php echo site_url('employer/staff_request/staff_requests/search_employee_api?q='); ?>" + query;
     
      $( "#search-replace-employee" ).prop('disabled', true);
      $( "#btn-search-replace-employee" ).prop('disabled', true);
      $( "#row-selector-replace-employee" ).hide();
      $( "#prev-step-request" ).prop('disabled', true);
      $( "#next-step-request" ).prop('disabled', true);
      
      $.getJSON(url, function(response) {
    
        var data_employees = response.data_employees;
       
        if (data_employees.length == 0) {
          alert('¡Ningún resultado encontrado!');
          return;
        }

        var selector_options = '<option value="">Seleccione</option>';
        
        $( "#selector-replace-employee" ).empty();
        $( "#selector-replace-employee" ).append(selector_options);

        for (var i = 0; i < data_employees.length; i++) {
          var employee = data_employees[i];
          var selector_value = employee.DNI + ' - ' + 
                               employee.PRIMER_NOMBRE + ' ' + employee.SEGUNDO_NOMBRE + ' ' +
                               employee.APELLIDO_PATERNO + ' ' + employee.APELLIDO_MATERNO; 

          selector_options = '<option value="' + selector_value + '">' + selector_value + '</option>';

          $( "#selector-replace-employee option[value='" + selector_value + "']").remove();
          $( "#selector-replace-employee" ).append(selector_options);
        }

        $( "#selector-replace-employee" ).select2();
        $( "#row-search-replace-employee" ).hide();
        $( "#row-selector-replace-employee" ).show();
    
      }).fail(function(){
        alert("Ha ocurrido un error!");
      }).always(function() {
        $( "#search-replace-employee" ).prop('disabled', false);
        $( "#btn-search-replace-employee" ).prop('disabled', false);
        $( "#prev-step-request" ).prop('disabled', false);
        $( "#next-step-request" ).prop('disabled', false);
      });
    });

      $( "#location" ).select2();
      $( "#selector-replace-employee" ).select2();
    }
    addEvents();
  });
</script>
</body>
</html>