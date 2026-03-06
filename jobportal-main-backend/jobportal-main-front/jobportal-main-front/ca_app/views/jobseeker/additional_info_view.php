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
  <div class="row"> <?php echo form_open_multipart('jobseeker/additional_info',array('name' => 'additional_form', 'id' => 'additional_form', 'onSubmit' => 'return validate_additional_form(this);'));?>
    
    
   <div class="col-md-3"> <div class="dashiconwrp">
        <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
      </div>
      </div>
    
    <div class="col-md-9">
    <?php echo $this->session->flashdata('msg');?>
      
      
      <!--Personal info-->
      <div class="formwraper">
        <div class="titlehead">Información Adicional</div>
        <div class="formint">
          <div class="input-group <?php echo (form_error('salary_currency') || form_error('salary_min') || form_error('salary_max')) ? 'has-error':'';?>">
            <label class="input-group-addon">Pretensión salarial<span> *</span></label>
              <div class="salary-range-wrapper">
                <table>
                    <tr>
                      <td width="15%" class="currency">
                        <input id="salary_currency" type="text" name="salary_currency" class="form-control" value="<?php echo @$row->salary_currency != null ? @$row->salary_currency : 'S/'; ?>" placeholder="S/">
                      </td>
                      <td width="35%" class="salary-min">
                        <input id="salary_min" type="text" name="salary_min" class="form-control" placeholder="Mínimo" value="<?php echo @$row->salary_min; ?>">
                      </td>
                      <td width="5%" class="separator">
                        a
                      </td>
                      <td width="35%" class="salary-max">
                        <input id="salary_max" type="text" name="salary_max" class="form-control" placeholder="Máximo" value="<?php echo @$row->salary_max; ?>">
                      </td>
                    </tr>
                </table>
              </div>
            <?php echo form_error('salary_currency'); ?>
            <?php echo form_error('salary_min'); ?>
            <?php echo form_error('salary_max'); ?>
            
          </div>
          <div class="input-group <?php echo (form_error('interest'))?'has-error':'';?>">
            <label class="input-group-addon">Intereses<span> *</span></label>
            <textarea name="interest" id="interest" class="form-control" rows="4"><?php echo @$row->interest; ?></textarea>
            <?php echo form_error('interest'); ?>
          </div>
          <div class="input-group <?php echo (form_error('description'))?'has-error':'';?>">
            <label class="input-group-addon">Objetivos <span>*</span></label>
            <textarea name="description" id="description" class="form-control" rows="4"><?php echo @$row->description; ?></textarea>
            <?php echo form_error('description'); ?>
          </div>  
          <div class="input-group <?php echo (form_error('awards'))?'has-error':'';?>">
            <label class="input-group-addon">Logros/Premios <span></span></label>
            <textarea name="awards" id="awards" class="form-control" rows="4"><?php echo @$row->awards; ?></textarea>
            <?php echo form_error('awards'); ?>
          </div>
          <div align="center">
            <input type="submit" name="js_additional_submit" id="js_additional_submit" value="Guardar" class="btn btn-primary" />
          </div>
        </div>
      </div>
    </div>
    <!--/Job Detail--> 
    <?php echo form_close();?>
   
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/validate_jobseeker.js');?>" type="text/javascript"></script>
</body>
</html>
