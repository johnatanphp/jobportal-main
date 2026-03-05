<?php

$search_path = '';
$industry_path = '';
$city_path = '';

if ($city != '') {
  $city_path = '/' . make_slug($city) . '/';
}

if ($industry != '') {
  $industry_path = '-industry-' . make_slug($industry);
}

if ($search != '') {
  $search_path = '-search-' . make_slug($search);
}

$url_path_industries = $city_path . 'jobs{{industry}}' . $search_path . '.html';
$url_path_cities = '/{{city}}/jobs' . $industry_path . $search_path . '.html';

?>
<div class="col-md-3"> 
<div class="secondary">
    <?php if ($left_side_industry): ?>
    <!--Widget-->
    <div class="widget">
      <h4 class="widget-title">Áreas</h4>
      <ul class="nav nav-pills nav-stacked">
        <?php foreach($left_side_industry as $row_industry):?>
        <li>
          <?php
            $url_industries = str_replace('{{industry}}', '-industry-' . make_slug($row_industry->industry_name), $url_path_industries);
            $url_industries = base_url($url_industries);
          ?>
          <a href="<?php echo $url_industries;?>"><span class="badge pull-right"><?php echo $row_industry->score;?></span><?php echo character_limiter($row_industry->industry_name, 10);?>
          </a>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <?php endif; ?>
    
    <!--Widget-->
    <?php if ($left_side_city): ?>
    <div class="widget">
      <h4 class="widget-title"> Ciudad </h4>
      <ul class="nav nav-pills nav-stacked">
      <?php foreach($left_side_city as $row_city):?>
        <li>
          <?php 
            $url_cities = str_replace('{{city}}', make_slug($row_city->city), $url_path_cities);
            $url_cities = base_url($url_cities );
          ?>
          <a href="<?php echo $url_cities;?>"><span class="badge pull-right"><?php echo $row_city->score;?></span><?php echo character_limiter($row_city->city, 14);?>
          </a>
        </li>
      <?php endforeach;?>
      </ul>
    </div>
  <?php endif; ?>
    
    <?php if ($left_side_company): ?>
    <!--Widget-->
    <div class="widget">
      <h4 class="widget-title">Empresas</h4>
      <ul class="nav nav-pills nav-stacked">
      <?php foreach($left_side_company as $row_company):?>
        <li> <a href="<?php echo base_url('companies/'.$row_company->company_slug);?>"><span class="badge pull-right"><?php echo $row_company->score;?></span><?php echo character_limiter($row_company->company_name, 14);?></a> </li>
      <?php endforeach;?>
      </ul>
    </div>
    <?php endif; ?>  
</div>
</div>