<!DOCTYPE html>
<html lang="es_PE">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title>
      <?php echo $title; ?>        
    </title>
    <?php $this->load->view('common/before_head_close'); ?>
    <style>

      /*  Estilos 1*/
      .modal-style-1 .modal-content {
        box-shadow: none;
        border-radius: 12px;
      }
      
      .modal-style-1 .modal-title {
        color: var(--Gray-800, #333);
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
      }     
      
      .btn-style-1.btn {
        border-radius: 4px;
        background: #6C757D;
        text-align: center;
        font-style: normal;
        line-height: normal;
        color: #ffffff;
        border-color: #6C757D;
      }

      .btn-style-1.btn-primary {
        border-radius: 4px;
        background: #0D6EFD;
        font-style: normal;
        line-height: normal;
        color: #ffffff;
        border-color: #0D6EFD;
      }

      .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: #0D6EFD;
      }
      
      .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #efefef;
      }

      /* Fin Estilos 1*/

      .page-title {
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
        color: #333;
      }

      .formwraper {
        border-radius: 12px;
        border: 1px solid var(--Gray-500, #BCBCBC);
        background: var(--Schemes-On-Primary, #FFF);
        box-shadow: 0px 4px 10px 0px rgba(0, 0, 0, 0.12);
      }

      .content-empty {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        min-height: 550px;
        text-align: center;
      }

      .add-candidate {
        margin-right: 5px;
      }
      
      .back-page {
        color: #0D6EFD;
        font-size: 14px;
        font-style: normal;
        font-weight: 600;
        line-height: 16px; /* 114.286% */
        display: flex;
        padding: 8px 0px;
        align-items: flex-start;
        gap: 8px;
      }

      .page-controls {
        text-align: right;
        padding: 10px 3px;
        display: flex;
        justify-content: space-between;
      }

      .page-control-options {
        display: flex;
        align-items: flex-start;
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

      .container-progress-bar {
        width: 60px;
      }
      
      .container-progress-bar .progress {
        height: 9px;
        margin: 0;
        flex: 1;
        background: #DEE2E6;
      }

      .container-progress-bar .progress-bar {
        background: #0D6EFD;
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

      .search-candidates input[type="search"] {
        border-radius: 4px;
        border: 1px solid var(--Components-Forms-Input-border, #CED4DA);
        background: #F8F9FA;
      }

      .tray-candidate-detail {
        color: #007BFF;
      }

      #wraper-tbl-candidates-client-list .table-footer {
        font-size: 13px;
        text-align: right;
      }

      #tbl-candidates-client-list .row-selected {
        background: #effaff;
      }

      .link-detail-secundary {
        color: #333333;
        text-decoration: underline;
      }

      .link-tray-candidates-status {
        display: block;
      }

      .link-tray-candidates-status .label {
        display: block;
        padding: 5px 3px;
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
          <div class="col-md-12">
            <div  style="padding: 5px 0 20px 0;text-align: left;">
              <a class="back-page" href="<?php echo site_url('employer/recruitment_tray/client_list'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="15" viewBox="0 0 10 15" fill="none">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M7.65425 14.5L0.791283 7.47918L4.31403 7.47918L9.5 12.6571L7.65425 14.5ZM7.65257 0.458345L9.49833 2.30124L4.31236 7.47917L0.78961 7.47917L7.65257 0.458345Z" fill="#0D6EFD"/>
                </svg>
                Volver al inicio
              </a>
            </div>
            <div style="padding: 10px 0 20px 0;text-align: center;">
              <h3 class="page-title"><?php e($client->name); ?></h3>
            </div>
            <div id="content-search">
              <?php $this->load->view('employer/recruitment_tray/recruitment_candidates/common/tray_candidates_empty', ['init' => 1]); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <!-- Profile Popups -->
    <?php $this->load->view('common/before_body_close'); ?>

    <!-- Modal -->
    <?php $this->load->view('employer/recruitment_tray/common/modal_add_candidates'); ?>
    <?php $this->load->view('employer/recruitment_tray/common/modal_tray_candidates_filters'); ?>
    
    <?php $this->load->view('employer/recruitment_tray/common/modal_hire_candidates'); ?>
    <?php $this->load->view('employer/recruitment_tray/common/modal_candidate_detail'); ?>
    <?php $this->load->view('employer/recruitment_tray/common/modal_tray_candidates_status'); ?>
    <?php $this->load->view('employer/recruitment_tray/common/modal_tray_candidates_sync_logs'); ?>
    <?php $this->load->view('employer/recruitment_tray/common/modal_edit_candidates'); ?>
    <?php $this->load->view('employer/recruitment_tray/screening_batches/common/modal_screening_create'); ?>
    <?php $this->load->view('employer/recruitment_tray/screening_batches/common/modal_screening_list'); ?>
    <?php $this->load->view('employer/recruitment_tray/screening_batches/common/modal_screening_logs'); ?>
    <?php $this->load->view('employer/recruitment_tray/staff_requests/common/modal_detail_staff_request'); ?>
    <?php $this->load->view('employer/recruitment_tray/recruitment_send_candidates/common/modal_sent_candidates'); ?>
    <?php $this->load->view('employer/recruitment_tray/recruitment_send_candidates/common/modal_select_staff_requests'); ?>

    <script type="text/javascript">

      window.tableTrayCandidates = {
        table: "#tbl-candidates-client-list",
        data: {},
        selected: function(value) {
          this.data[value] = value;
        },
        unSelected: function(value) {
          if (this.data.hasOwnProperty(value)) {
            delete this.data[value];
          }
        },
        unSelectedAll: function(value) {
          this.data = [];
          this.refresh();
        },
        selectedAllCurrentPage: function() {
          const objectData = this;
          $( '.checkbox-tray-id' ).each(function(i, e){
            objectData.selected($(e).val());
          });
          objectData.refresh();
        },
        unSelectedAllCurrentPage: function() {
          const objectData = this;
          $( '.checkbox-tray-id' ).each(function(i, e){
            objectData.unSelected($(e).val());
          });
          objectData.refresh();
        },
        getSelected: function(column) {
          const objectData = this;
          return  Object.keys(this.data).map(function(key){
            return objectData.data[key];
          });
        },
        refresh : function() {
          const objectData = this;
          const $checkboxes = $(this.table).find('tbody tr input[type="checkbox"]');
          const checkboxesValues = $checkboxes.map(function(i, e){

            e.checked = false;
            $(e).closest('tr').removeClass('row-selected');

            if (objectData.data.hasOwnProperty(e.value)) {
              $(e).closest('tr').addClass('row-selected');
              e.checked = true;
            }

            return e.value;
          });

          $( '.checkbox-tray-all' ).prop('checked', false);
          $( '.checkbox-tray-all' ).prop('indeterminate', false);

          const $checkboxesSelected = $(this.table).find('tbody tr input[type="checkbox"]:checked');

          if ($checkboxes.length > 0 && $checkboxes.length == $checkboxesSelected.length) {
            $( '.checkbox-tray-all' ).prop('checked', true);
          }

          if ($checkboxesSelected.length > 0 && $checkboxes.length != $checkboxesSelected.length) {
            $( '.checkbox-tray-all' ).prop('indeterminate', true);
          }

          const table = $(this.table);
          if (table.length == 0) {
            return;
          }

          const tableData = table.data('data');
          
          const rowsSelectedLenght = this.getSelected(0).length;

          let footerTextInfo = `Mostrando ${tableData.total_rows_curent_page} de ${tableData.total_rows} registros`;

          if (rowsSelectedLenght > 0) {
            footerTextInfo+= `&nbsp; &nbsp; ${rowsSelectedLenght} ${rowsSelectedLenght > 1 ? 'filas' : 'fila' } seleccionada`;
          }

          $( '#wraper-tbl-candidates-client-list .table-footer' ).html(footerTextInfo);
        }
      };
      
      function searchCandidates(page, filters) {
        const url = "<?php echo site_url('employer/recruitment_tray/process_candidates_list/search/' . $client->code); ?>";
        const searchFilters = filters || {};
        const params = {
          'page': page,
          'search': searchFilters['search'] || '',
          'status_ids' : $( 'select[name="status_ids"]', '#modal-tray-candidates-filters' ).val(),
          'origin_type' : $( 'select[name="origin_type"]', '#modal-tray-candidates-filters' ).val()
        };

        $( '#content-search' ).addClass('load');
        $( '#content-search .formwraper' ).addClass('load-image');
       
        $.get(url, params, function(res) {
          $( '#content-search' ).html(res.data);
          $( '#tbl-candidates-client-list' ).data('page', page);
          $( '#tbl-candidates-client-list' ).data('filters', filters);
          $( '#tbl-candidates-client-list' ).data('data', res);
          tableTrayCandidates.refresh();
        }, 'json')
        .fail(function(e) {})
        .always(function() {
          $( '#content-search' ).removeClass('load');
          $( '#content-search .formwraper' ).removeClass('load-image');
        }); 
      }

      function reloadSearchCandidates() {
        const tableSearch =  $( '#tbl-candidates-client-list' );
        searchCandidates(tableSearch.data('page'), tableSearch.data('filters'))
      }
    </script>

    <script type="text/javascript">

    $(function(){
      $(document).on('click', '.create-resend-link', function(e) {
        e.preventDefault();
        
        const btnLink = $(this);
        const dropdown = $(btnLink.closest('.dropdown'));
        const btnParent = dropdown.find('.dropdown-toggle');
        
        dropdown.removeClass('open');
        btnParent.prop('disabled', true);
        btnParent.html('Enviando...');

        const data = {
          id: btnLink.data('id')
        };  

        const url = "<?php echo site_url('employer/recruitment_tray/process_candidates/resend_link'); ?>";
        $.post(url, data, function(response){

          if (!response.status) {
            toastr["warning"](response.message);
            return;
          }

          toastr["success"](response.message);
          
        }, 'json')
        .fail(function(){
          toastr["error"]('Error al reenviar el link')
        }).always(function(){
          btnParent.prop('disabled', false);
          btnParent.html('Reenviar');
        });
        return false;
      });

      $(document).on('click', '.copy-link', function(e) {
        e.preventDefault();
        
        if (!navigator.clipboard) {
          console.warn('La API del Portapapeles no está disponible');
          return;
        }

        const btnLink = $(this);
        const dropdown = $(btnLink.closest('.dropdown'));
        const btnParent = dropdown.find('.dropdown-toggle');
        
        dropdown.removeClass('open');
        btnParent.prop('disabled', true);
        btnParent.html('Generando...');

        const data = {
          id: btnLink.data('id')
        };  

        const url = "<?php echo site_url('employer/recruitment_tray/process_candidates/copy_link'); ?>";
        $.post(url, data, function(response){

          if (!response.status) {
            toastr["warning"](response.message);
            return;
          }

          navigator.clipboard.writeText(response.data.link_url)
          .then(() => {
            toastr["success"]('Link copiado al portapapeles');
          })
          .catch(err => {
            toastr["error"]('Error al intentar copiar el link');
          });
          
        }, 'json')
        .fail(function(){
          toastr["error"]('Error al generar el link')
        }).always(function(){
          btnParent.prop('disabled', false);
          btnParent.html('Reenviar');
        });
        return false;
      });

      $(document).on('change', '.checkbox-tray-id', function(e) {
        e.preventDefault();
        $checkbox = $(this);

        if ($checkbox.is(':checked')) {
          tableTrayCandidates.selected($checkbox.val());
        } else {
          tableTrayCandidates.unSelected($checkbox.val());
        }

        tableTrayCandidates.refresh();
    
        return false;
      });

      $(document).on('change', '.checkbox-tray-all', function(e) {
        e.preventDefault();
        
        $checkbox = $(this);

        if ($checkbox.is(':checked')) {
          tableTrayCandidates.selectedAllCurrentPage();
        } else {
          tableTrayCandidates.unSelectedAllCurrentPage();
        }

        return false;
      });

      $(document).on('submit', '.tray-candidates-search', function(e) {
        e.preventDefault();

        searchCandidates(1, {
          'search': this.search.value
        });
        return false;
      });

      $(document).on('submit', '.tray-candidates-filters', function(e) {
        e.preventDefault();
      
        searchCandidates(1, {
          'search': '',
          'status_ids': $(this.status_ids).val(),
          'origin_type' : $(this.origin_type).val()
        });
        tableTrayCandidates.unSelectedAll();
        $( '#modal-tray-candidates-filters' ).modal('hide');

        return false;
      });

      $(document).on('click', '.pag-wrap-v3 [data-ci-pagination-page]', function(e) {
        e.preventDefault();
        const page = $(this).data('ci-pagination-page'); 
        searchCandidates(page);
        return false;
      });
    });
    </script>

    <script>
      searchCandidates(0);
    </script>
 
  </body>
</html>