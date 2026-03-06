<?php if($this->session->userdata('is_user_login')!=TRUE): ?>

  <div class="candidatesection">
  
  <h1>Cruzada hacia una real Reactivación Laboral</h1>
  
  <?php echo form_open('job_search/',array('id' => 'jsearch', 'method' => 'post'));?>
    <div class="col-md-10">
          <input type="text" name="search" id="job_params" class="form-control" placeholder="Puesto, empresa o palabra clave" />                
    </div>
    
    <div class="col-md-4 hide">
      <select class="form-control" name="city" id="jcity">
      	
        <option value="" selected>Todos los lugares</option>
        <?php if($cities_res): foreach($cities_res as $cities):?>
          
        	<option value="<?php echo $cities->city_name;?>"><?php echo $cities->city_name;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>

    <div class="col-md-2">
      <input type="submit" name="job_submit" class="btn btn-block" id="job_submit" value="Buscar"  />
    </div>
<?php echo form_close();?> 
    <div class="clear"></div>
  </div>

<?php else: 

if($this->session->userdata('is_employer')==TRUE): ?>
<div class="col-md-12">
  <div class="employersection">
    <div class="col-md-6 col-md-offset-3">
      <h1>Buscar Currículum</h1>
      <?php echo form_open('resume_search',array('id' => 'rsearch', 'method' => 'get'));?>
      <div class="input-group">
        <input type="text" name="search" class="form-control" id="resume_params" placeholder="Habilidad o palabra clave" />
        <span class="input-group-btn">
        <input type="submit" class="btn" id="resume_submit" value="Buscar" />
        </span> </div>
      <?php echo form_close();?> </div>
    <div class="clear"></div>
  </div>
</div>
<?php else: ?>
<div class="col-md-12">
  <div class="candidatesection">
    <div class="row">
    
    <div class="col-md-8 col-md-offset-2">
      <h1>Buscar empleo</h1>
      <?php echo form_open('job_search',array('name' => 'jsearch', 'id' => 'jsearch', 'method' => 'post'));?>
      <div class="input-group">
        <input type="text" name="search" id="job_params" class="form-control" placeholder="Puesto, empresa o palabra clave" />
        <span class="input-group-btn">
        <input type="submit" id="job_submit" class="btn" value="Buscar"  />
        </span> </div>
      <?php echo form_close();?> </div>
      
     <div class="col-md-12">
       <div class="employersection">
      <h3>Envíanos tu currículum</h3>
      <input type="submit" value="Enviar currículum" title="Enviar currículum" class="postjobbtn" alt="job search engine USA" onClick="document.location='<?php echo base_url('login');?>'" />
      </div>
      <div class="clear"></div>
    </div> 
    </div>
    <div class="clear"></div>
  </div>
</div>
<?php endif;?>
<?php endif;?>
