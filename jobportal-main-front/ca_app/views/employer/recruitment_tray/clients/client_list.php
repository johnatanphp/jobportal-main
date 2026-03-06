<!DOCTYPE html>
<html lang="es_PE">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title>
      <?php echo $title; ?>        
    </title>
    <?php $this->load->view('common/before_head_close'); ?>
    <style>

      .formwraper {
        border-radius: 12px;
        border: 1px solid var(--Gray-500, #BCBCBC);
        background: var(--Schemes-On-Primary, #FFF);
        box-shadow: 0px 4px 10px 0px rgba(0, 0, 0, 0.12);
      }

      .table-style-1 th, .table-style-1 td {
        padding: 12px 20px;
      }

      .table-style-1 td {
        border-top: 1px solid #DFDFDF;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        padding: 18px 20px;
      }

      .table-style-1 th {
        background: #F8F9FA;
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 18px;
      }

      .table-style-1 tbody tr:hover {
        box-shadow: 0px 8px 35px 0px rgba(0, 0, 0, 0.16), inset 13px 0px 0px -4px #0D6EFD;
      }

      .paginationWrap {
        text-align: center;
      }

      .pag-wrap-v3 .pagination > .active > a, 
      .pag-wrap-v3 .pagination > .active > span, 
      .pag-wrap-v3 .pagination > .active > a:hover, 
      .pag-wrap-v3 .pagination > .active > span:hover, 
      .pag-wrap-v3 .pagination > .active > a:focus, 
      .pag-wrap-v3 .pagination > .active > span:focus {
        background-color: #007BFF;
        color: #fff;
      }

      .pag-wrap-v3 .pagination>li>a, .pag-wrap-v3 .pagination>li>span {
        position: relative;
        float: left;
        padding: 5px 10px;
        margin-left: -1px;
        line-height: 1.42857143;
        color: #007BFF;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #DEE2E6;
      }

      .page-title {
        font-size: 26px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
        color: #333;
      }

      .section-filter-tray {
        border-radius: 12px;
        border: 1px solid var(--Gray-500, #BCBCBC);
      }

      .section-filter-tray .title {
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 18px;
      }


      #filter-tray.panel-group .panel {
        margin: 0;
        border-radius: 0;
        box-shadow: none;
      }

      #filter-tray.panel-group {
        margin-bottom: 0;
      }

      #filter-tray .panel {
        border: 0;
      }

      #filter-tray .panel-default>.panel-heading {
        color: #333;
        background-color: #ffffff;
        border-color: #DFDFDF;
      }

      #filter-tray .panel-heading {
        padding: 10px 15px;
        border-top: 1px solid #DFDFDF;
        border-bottom: 0;
        border-radius: 0;
      }
     
      #filter-tray .panel-title {
        color: var(--Gray-800, #333);
        font-size: 13px;
        font-style: normal;
        font-weight: 600;
        line-height: 18px;
      }
      
      .filter-list-items {
        list-style: none;
      }

      .filter-list-items li {
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
        padding: 0;
      }

      .filter-list-items li label {
        overflow: hidden;
        padding: 0;
        margin: 0;
        min-width: 0;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 11px;
      }

      .filter-list-items li div {
        padding: 5px;
        background: #ffffff;
      }

      .filter-list-items li div input[type='checkbox'] {
        margin: 0;
      }

      .section-filter-tray-control {
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
        padding: 0;
      }

      .section-filter-tray-control label {
        margin: 0;
        padding: 0;
        cursor: pointer;
      }

      .load {
        position: relative;
      }
      
      .load:before {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        content: " ";
        background-color: rgba(255,255,255,.6);
        z-index: 4;
      }

      .load-image {
        position: relative;
      }

      .load-image:after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        content: " ";
        background: url(<?php echo img_loading_url(); ?>) no-repeat;
        background-position:center center;
        background-size: 24px 24px;
        z-index: 4;
      }
    </style>
  </head>
  <body>
    <?php $this->load->view('common/after_body_open'); ?>
    <div class="siteWraper">
      <!--Header-->
      <?php $this->load->view('common/header'); ?>
      <!--/Header-->
      <div class="container detailinfo">

        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-9">
            <div style="padding: 15px 0 20px 0;text-align: center;">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="97" height="81" viewBox="0 0 97 81" fill="none">
                  <circle cx="41.2637" cy="40.5" r="40.5" fill="#DFDFDF"/>
                  <mask id="mask0_910_9218" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="82" height="81">
                    <circle cx="41.2637" cy="40.5" r="40.5" fill="#DFDFDF"/>
                  </mask>
                  <g mask="url(#mask0_910_9218)">
                    <path d="M-6.73633 18.5L49.2637 18.5C54.7865 18.5 59.2637 22.9772 59.2637 28.5V55C59.2637 60.5229 54.7865 65 49.2637 65H-6.73633V18.5Z" fill="white"/>
                    <circle cx="30.7637" cy="41.6842" r="15.1579" fill="#A9A9A9" fill-opacity="0.16"/>
                    <mask id="mask1_910_9218" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="14" y="24" width="33" height="32">
                      <circle cx="30.7637" cy="40" r="16" fill="#D9D9D9"/>
                    </mask>
                    <g mask="url(#mask1_910_9218)">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M31.0441 43.2696C34.2434 43.2696 36.8368 40.6761 36.8368 37.4769C36.8368 34.2777 34.2434 31.6842 31.0441 31.6842C27.8449 31.6842 25.2515 34.2777 25.2515 37.4769C25.2515 40.6761 27.8449 43.2696 31.0441 43.2696Z" fill="#198754"/>
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M30.9215 70.6842C38.1012 70.6842 43.9215 64.8639 43.9215 57.6842C43.9215 50.5045 38.1012 44.6842 30.9215 44.6842C23.7418 44.6842 17.9215 50.5045 17.9215 57.6842C17.9215 64.8639 23.7418 70.6842 30.9215 70.6842Z" fill="#198754"/>
                    </g>
                    <path d="M-2.23633 34H-3.73633V37H-2.23633V34ZM7.76367 37C8.5921 37 9.26367 36.3284 9.26367 35.5C9.26367 34.6716 8.5921 34 7.76367 34V37ZM-2.23633 37H7.76367V34H-2.23633V37Z" fill="#DFDFDF"/>
                    <path d="M-2.23633 44.5H-3.73633V47.5H-2.23633V44.5ZM7.76367 47.5C8.5921 47.5 9.26367 46.8284 9.26367 46C9.26367 45.1716 8.5921 44.5 7.76367 44.5V47.5ZM-2.23633 47.5H7.76367V44.5H-2.23633V47.5Z" fill="#DFDFDF"/>
                  </g>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M65.6736 44.8208C60.4701 48.9689 52.8891 48.1134 48.7409 42.9099C44.5927 37.7064 45.4483 30.1254 50.6518 25.9772C55.8552 21.8291 63.4363 22.6846 67.5844 27.8881C71.7326 33.0916 70.8771 40.6726 65.6736 44.8208ZM69.429 49.5316C61.6238 55.7539 50.2523 54.4706 44.03 46.6654C37.8078 38.8601 39.0911 27.4886 46.8963 21.2664C54.7015 15.0441 66.0731 16.3274 72.2953 24.1326C78.5175 31.9379 77.2343 43.3094 69.429 49.5316Z" fill="#0D6EFD"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M66.143 45.4096C64.8421 46.4467 64.6282 48.3419 65.6653 49.6428L79.7482 67.3086L84.4591 63.5531L70.3761 45.8873C69.3391 44.5865 67.4438 44.3726 66.143 45.4096Z" fill="#0D6EFD"/>
                  <circle cx="58.2637" cy="35.5" r="12.5" fill="#0D6EFD" fill-opacity="0.42"/>
                </svg>
              </div>
              <h3 class="page-title">Documentos para contratación</h3>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="dashiconwrp">

              <div class="section-filter-tray">
                <div style="background: #F8F9FA;border-top-left-radius: 12px;border-top-right-radius: 12px;padding: 12px;">
                  <span class="title">Filtros</span>
                </div>

                <div class="panel-group" id="filter-tray">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a class="section-filter-tray-control" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                          <label>Consultoras</label> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 2.34575L7.47918 9.20872L7.47918 5.68597L12.6571 0.5L14.5 2.34575ZM0.458345 2.34743L2.30124 0.501672L7.47917 5.68764L7.47917 9.21039L0.458345 2.34743Z" fill="#333333"/>
                          </svg>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                      <div class="panel-body">
                        <ul class="filter-list-items">
                          <?php foreach ($filter_consultants as $row): ?>
                            <li>
                              <label title="<?php echo $row->consultant_name; ?>"><?php echo $row->consultant_name; ?></label>
                              <div><input type="checkbox" name="filter_consultant" value="<?php echo $row->consultant_code; ?>" class="filter-tray-items"></div>
                            </li>
                          <?php endforeach; ?> 
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a class="section-filter-tray-control" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                          <label>Clientes</label> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 2.34575L7.47918 9.20872L7.47918 5.68597L12.6571 0.5L14.5 2.34575ZM0.458345 2.34743L2.30124 0.501672L7.47917 5.68764L7.47917 9.21039L0.458345 2.34743Z" fill="#333333"/>
                          </svg>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse">
                      <div class="panel-body">
                        <ul class="filter-list-items">
                          <?php foreach ($filter_clients as $row): ?>
                            <li>
                              <label title="<?php echo $row->client_name; ?>"><?php echo $row->client_name; ?> </label>
                              <div><input type="checkbox"  name="filter_client" value="<?php echo $row->client_code; ?>" class="filter-tray-items"></div>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a class="section-filter-tray-control" data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                          <label>Unidad de negocio</label> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 2.34575L7.47918 9.20872L7.47918 5.68597L12.6571 0.5L14.5 2.34575ZM0.458345 2.34743L2.30124 0.501672L7.47917 5.68764L7.47917 9.21039L0.458345 2.34743Z" fill="#333333"/>
                          </svg>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse">
                      <div class="panel-body">
                        <ul class="filter-list-items">
                          <?php foreach ($filter_business_units as $row): ?>
                            <li>
                              <label title="<?php echo $row->business_unit_name; ?>"><?php echo $row->business_unit_name; ?></label>
                              <div><input type="checkbox" name="filter_business_unit" value="<?php echo $row->business_unit_code; ?>" class="filter-tray-items"></div>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default" style="border-bottom-left-radius: 12px;border-bottom-right-radius: 12px;">
                    <div class="panel-heading" style="border-bottom-left-radius: 12px;border-bottom-right-radius: 12px;">
                      <h4 class="panel-title">
                        <a class="section-filter-tray-control" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                          <label>Centro de costo</label> 
                          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 2.34575L7.47918 9.20872L7.47918 5.68597L12.6571 0.5L14.5 2.34575ZM0.458345 2.34743L2.30124 0.501672L7.47917 5.68764L7.47917 9.21039L0.458345 2.34743Z" fill="#333333"/>
                          </svg>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse4" class="panel-collapse collapse">
                      <div class="panel-body">
                        <ul class="filter-list-items">
                            <?php foreach ($filter_cost_centers as $row): ?>
                              <li>
                                <label title="<?php echo $row->cost_center_code; ?>"><?php echo $row->cost_center_code; ?></label> 
                                <div><input type="checkbox" name="filter_cost_center" value="<?php echo $row->cost_center_code; ?>" class="filter-tray-items"></div>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                      </div>
                    </div>
                  </div>

                </div>

              </div>

            </div>
          </div>
          <div class="col-md-9">
            <div id="content-search"><?php echo $content_search; ?></div>
          </div>
        </div>
      </div>
    </div>

    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <!-- Profile Popups -->
    <?php $this->load->view('common/before_body_close'); ?>

    <script type="text/javascript">
    $(function(){
      
      const urlSearch = "<?php echo site_url('employer/recruitment_tray/client_list/search'); ?>";

      function buildFilters() {
        
        let consultants = [];
        let clients = [];
        let businessUnit = [];
        let costCenter = [];
        
        $( 'input[name="filter_consultant"]:checked' ).each(function(i, item){
          consultants.push(item.value);
        });

        $( 'input[name="filter_client"]:checked' ).each(function(i, item){
          clients.push(item.value);
        });

        $( 'input[name="filter_business_unit"]:checked' ).each(function(i, item){
          businessUnit.push(item.value);
        });

        $( 'input[name="filter_cost_center"]:checked' ).each(function(i, item){
          costCenter.push(item.value);
        });

        return {
          consultants: consultants,
          clients: clients,
          businessUnit: businessUnit,
          costCenter: costCenter
        };
      }

      function search(url, page, filters) {
        
        const params = {
          'page': page,
          'consultant_codes': filters.consultants,
          'client_codes': filters.clients,
          'business_unit_codes': filters.businessUnit,
          'cost_center_codes': filters.costCenter,
        };

        $( '#content-search' ).addClass('load');
        $( '#content-search .formwraper' ).addClass('load-image');
        $( '.section-filter-tray' ).addClass('load');

        $.get(url, params, function(res) {
          $( '#content-search' ).html(res.data);
        }, 'json')
        .fail(function(e) {})
        .always(function() {
          $( '#content-search' ).removeClass('load');
          $( '#content-search .formwraper' ).removeClass('load-image');
          $( '.section-filter-tray' ).removeClass('load');
        }); 
      }

      $(document).on('mouseover', '.table-style-1 tr', function(e) {
        $(this).find('.link-botton').show();
      });

      $(document).on('mouseout', '.table-style-1 tr', function(e) {
        $(this).find('.link-botton').hide();
      });

      $(document).on('click', '.pag-wrap-v3 [data-ci-pagination-page]', function(e) {
        e.preventDefault();
        const page = $(this).data('ci-pagination-page'); 
        search(urlSearch, page, buildFilters());
        return false;
      });

      $(document).on('change', '.filter-tray-items', function(e) {
        e.preventDefault();
        search(urlSearch, 1, buildFilters());
      });
    });

    </script>
  </body>
</html>