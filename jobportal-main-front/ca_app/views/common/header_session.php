  <div class="navbar navbar-default" role="navigation">
        <div class="col-md-6 col-sm-6 col-xs-2">
          <div class="navbar-header navbar-header-mobile">
            <table >
              <tr>
                <td>          
                  <button type="button" class="navbar-toggle navbar-toggle-mobile"> 
                    <span class="sr-only">Toggle navigation</span> 
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span> 
                    <span class="icon-bar"></span>
                  </button>
                </td>
                <td class="navbar-brand-mobile">            
                  <a class="navbar-brand" href="<?php echo site_url(); ?>">
                    <img src="<?php echo base_url('public/images/overall_blue.png');?>" style="height: 30px;" />
                  </a>
                </td>
              </tr>
            </table>
          </div>
        </div>
      
        <div class="col-md-6 col-sm-6 col-xs-10">
          <div class="usertopbtn">
            <div align="right">
              <table>
                <tr>
                  <td>
                    <a href="<?php echo site_url($this->session->userdata('user_dashboard'));?>" class="username">
                      <?php if ($this->session->userdata('is_employer') 
                               && $this->session->userdata('profile') && 
                               user_belong_to_company_internal()): ?>
                        <div style="font-size: 10px;color:#777777;">
                          <?php echo ($this->session->userdata('profile'))->name; ?>
                        </div>
                      <?php endif; ?>

                      <?php echo $this->session->userdata('first_name'); ?>    
                    </a>
                  </td>
                  <td>              
                    <a href="#" class="show-selector-profiles">
                      <i class="glyphicon glyphicon-user"></i>
                    </a>
                </td>
                </tr>
              </table>

            <div class="clear"></div>
          </div>
        </div>
        <div class="clearfix"></div>
  </div>
</div>

<div id="menu-user-session" style="display: none;">
  <?php $this->load->view('common/user_menu'); ?>
</div>