<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<?php $this->load->view('common/before_head_close'); ?>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link rel="stylesheet" href="<?php echo base_url('public/autocomplete/demo.css'); ?>">
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
        <div class="wrap_enable">
          <?php $this->load->view('jobseeker/common/jobseeker_menu');?>
        </div>
      </div>
    </div>
  
    <div class="col-md-9"> 
      <?php echo $this->session->flashdata('msg'); ?>
      <!--Personal info-->
      <div class="formwraper">
        <div class="titlehead">Agregar Habilidades</div>
        <div class="formint">
          <div class="normal-details">   
            RECOMENDACIÓN: Por favor ingresa mínimo 3 habilidades para que tu currículum pueda ser encontrado más rápido.
            <br />
            <br />
            <strong>Ejemplos: </strong>
            <br>
            El desarrollador de PHP puede poner las siguientes habilidades: desarrollador PHP, codificador PHP, programador PHP, desarrollador de sitios web, Word Press, Java Script, JS, Ajax, etc. 
          </div>
          <div class="jobdescription" style="border-top:0px;">
            <div class="row">
              <div class="col-md-12">
              <div id="emsg"></div>
                <div class="subtitlebar">Mis Habilidades <span class="min3skills" ></span></div>
                <div class="skillBox">
                  <ul class="skillDetail" id="myskills">
                    <?php 
                      if($result):
                        foreach($result as $skill_row):
                          if(trim($skill_row->skill_name)!=''): ?>
                                <li><?php echo trim($skill_row->skill_name);?> <a href="#" data-skill="<?php echo trim($skill_row->ID); ?>" class="delete"><i class="fa fa-times-circle"></i></a></li>
                              <?php 
                          endif;
                        endforeach;
                      endif;
                    ?>
                  </ul>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="clear"></div>
          </div>
          <div class="input-group">
            <label class="input-group-addon">Agregar habilidad <span></span></label>
            <form id="form_add_jobseeker_skill">
              <div class="row">
                <div class="col-md-7">
                  <input type="text" name="skill" id="skill" value="" autocomplete="off" class="form-control" autofocus />
                  <input type="hidden" name="s_val" id="s_val" value="<?php echo (set_value('s_val'))?set_value('s_val'):''; ?>" class="form-control" />
                </div>
                <div class="col-md-3">
                  <input type="submit" name="js_skill_submit" id="js_skill_submit" value="Agregar" class="btn btn-primary" />
                </div>
              </div>
            </form>
          </div>
          <div class="clear">&nbsp;</div>
          <div class="clear">&nbsp;</div>
          <div class="clear">&nbsp;</div>
        </div>
      </div>
    </div>
    <!--/Job Detail-->
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function() {

  $( "#form_add_jobseeker_skill" ).submit(function(e){	
	  e.preventDefault();
	  
	  if(!is_empty($("#skill"), 'skill', 'habilidad')) {
	  	add_jobseeker_skill();
	  }
  });
  
  $("#skill").bind('keyup blur', function(){
	 $( this ).closest('div').removeClass( "has-error" ); 
	 $(".skill_err").remove();
 });
 
  const availableSkills = <?php echo $available_skills;?>;
  $( "#skill" ).autocomplete({
    source: availableSkills,
    select: function( event, ui ) {
      $( "#skill" ).val(ui.item.value);
      $( "#form_add_jobseeker_skill" ).trigger('submit');
    }
  });

  //Add Skill
  function add_jobseeker_skill(){

    $('#js_skill_submit').attr("disabled", true);
    $('#js_skill_submit').val('Agregando...');

    $.ajax({
      type: "POST",
      url: "<?php echo site_url('jobseeker/add_skills/add'); ?>",
      data: { skill: $("#skill").val()},
      dataType: "json",
    })
    .done(function( res ) {

      $('#js_skill_submit').attr("disabled", false);
      $('#js_skill_submit').val('Agregar');

      if (res.status == true) {
        const skill = "'" + $("#skill").val() + "'";
      
        $('#emsg').html('<span class="label label-success">Muy bien! La habilidad se agregó correctamente.</span>');
        $("#myskills").append('<li>'+$("#skill").val()+'<a href="#" class="delete" data-skill="' + res.data.id + '"><i class="fa fa-times-circle"></i></a></li>');
        $("#skill").val('');
        $("#skill").focus();

        return;
      }

      $('#emsg').html(`<span class="label label-danger">${res.message}</span>`);
    });
  }

  $( document ).on('click', '#myskills li a.delete', function(){

    const skillId = $(this).data('skill');
    const button = $(this);

    $.ajax({
      type: "POST",
      url:"<?php echo site_url('jobseeker/add_skills/remove'); ?>",
      data: { skill: skillId}
    })
    .done(function( msg ) {

      if ($.isNumeric(msg)) {
        $('#emsg').html('');
        button.closest('li').remove();
      } else {
        $('#emsg').html('<span class="label label-danger">'+msg+'</span>');
      }
    });
  });
});
</script>
</body>
</html>
