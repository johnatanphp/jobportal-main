<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>
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
    <h1> Employers Management 
      <!--<small>advanced tables</small>--> 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <!--<li><a href="#">Examples</a></li>-->
      <li class="active">Manage Employers</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">All Employers</h3>
            <!--Pagination-->
            <div class="paginationWrap"> <?php echo ($result)?$links:'';?></div>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <form method="get" action="<?php echo site_url('admin/employers/search_by_company/' . $company_id);?>">
              <div class="row" style="background-color:#3C8DBC; padding:10px; margin:0;">
                <div class="col-md-3 margin-bottom-special">
                  <input class="form-control" 
                         name="first_name" 
                         id="first_name" 
                         type="text" 
                         placeholder="Search By Name" 
                         value="<?php echo html_escape($search_data["first_name"]); ?>"
                         style="min-width:100%;">
                </div>
                <div class="col-md-3 margin-bottom-special">
                  <input class="form-control"
                         name="email" 
                         id="email" 
                         type="text" 
                         placeholder="Search By Email" 
                         value="<?php echo html_escape($search_data["email"]) ;?>"
                         style="min-width:100%;">
                </div>
                              
                <div class="col-md-3 margin-bottom-special">
                  <input class="btn" name="submit" value="Search" type="submit">
                  &nbsp;&nbsp;
                  <input class="btn" 
                         name="button" 
                         value="View All" 
                         type="button" 
                         onClick="document.location='<?php echo site_url('admin/employers/search_by_company/' . $company_id); ?>';">
                </div>
              </div>
            </form>

            <div class="clearfix text-right" style="padding:10px;"> 
              Total Records: <strong><?php echo $total_rows; ?></strong>
            </div>
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Date Joined</th>
                  <th>Name</th>
                  <th>Email Address</th>
                  <th>Posted Jobs</th>
                  <th>Admin</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 

					foreach($result as $row):
						$json_row = array();
						$total_posted_jobs = $this->Posted_job->count_records('tbl_post_jobs','employer_ID', $row->ID);
			
					?>
                <tr id="row_<?php echo $row->ID;?>">
                  <td valign="middle"><?php echo date_formats($row->dated, 'd/m/Y');?><br />
                  <?php echo ($row->ip_address)?'<a href="http://domaintools.com/'.$row->ip_address.'" target="_blank">'.$row->ip_address.'</a>':'';?>
                  </td>
                  <td valign="middle"><a href="<?php echo base_url('admin/employers/details/'.$row->ID);?>"><?php echo $row->first_name.' '.$row->last_name;?></a></td>
                  <td valign="middle"><?php echo $row->email;?></td>
                  <td valign="middle"><a class="btn btn-primary btn-xs" href="<?php echo base_url('admin/posted_jobs/jobs_by_company/'.$row->ID);?>" target="_blank">View (<?php echo $total_posted_jobs;?>)</a></td>
                  
                  <td valign="middle">
                    <?php
                      if ($row->is_admin == 'yes')
                        $top_class_label = 'success';
                      else
                        $top_class_label = 'warning';
                    ?>

                    <a href="javascript:;" id="te_<?php echo $row->ID;?>"> <span class="label label-<?php echo $top_class_label;?>">
                      <?php echo $row->is_admin == 'yes' ? 'yes' : 'no'; ?></span>
                    </a>
                  </td>
                  
                  <td valign="middle">
                    <?php
                      if ($row->sts == 'active')
                        $class_label = 'success';
                      elseif ($row->sts == 'blocked')
                        $class_label = 'danger';
                      else
                        $class_label = 'warning';
                    ?>

                    <a onClick="update_status(<?php echo $row->user_id; ?>);" 
                       href="javascript:;" 
                       id="sts_<?php echo $row->user_id;?>">
                       <span class="label label-<?php echo $class_label;?>"><?php echo camelize($row->sts);?></span>
                     </a>
                  </td>
         
                  <td valign="middle">
                    <a href="<?php echo base_url('admin/employers/update/'.$row->ID);?>" class="btn btn-primary btn-xs">Edit</a>
                    <a target="_blank" href="<?php echo base_url('admin/employers/login/'.$row->user_id);?>" class="btn btn-primary btn-xs" style="margin:1px;">Login</a>
                  </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($result)): ?>
                  <tr>
                    <td colspan="10" align="center" class="text-red">No Record found!</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          
          <!--Pagination-->
          <div class="paginationWrap"> <?php echo ($result)?$links:'';?> </div>
          
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
        
        <!-- /.box --> 
      </div>
    </div>
  </section>
  <!-- /.content --> 
</aside>
<!-- /.right-side -->
<?php $this->load->view('admin/common/footer'); ?>
