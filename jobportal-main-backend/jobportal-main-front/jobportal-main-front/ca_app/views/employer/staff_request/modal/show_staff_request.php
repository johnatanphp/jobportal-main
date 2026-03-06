<style type="text/css">
  /** */

 .section-title {
    border-bottom: 1px solid #999;
    margin-bottom: 10px;
    font-size: 19px;
    text-transform: uppercase;
  }

  .tbl-show-items tr th {
    padding: 4px 6px;
    background: #666;
    color: #fff;
  }

  .tbl-show-items tr td {
    padding: 5px 8px;
  }

  .formwraper .row {
    margin-bottom: 15px;
  }
  
  .content-function {
    background: #eee;
    padding: 6px 8px;
    margin-bottom: 8px;
    border-radius: 5px;
  }

  .bg-tr-1 {
    background: #eee;
  }

  .bg-tr-2 {
    background: #fff;
  }

  .btn-section-edit {
    float: right;
    font-size: 16px;
  }

  .section-title a {
    display: none;
  }

  .wrapper-request-head {
    color: #777;
    background: #eee;
    padding: 8px 10px;
    background: -moz-linear-gradient(left, rgba(240,240,240,1) 0%, rgba(224,224,224,1) 47%, rgba(201,201,201,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(left top, right top, color-stop(0%, rgba(240,240,240,1)), color-stop(47%, rgba(224,224,224,1)), color-stop(100%, rgba(201,201,201,1)));/* Chrome, Safari4+ */
    background: -webkit-linear-gradient(left, rgba(240,240,240,1) 0%, rgba(224,224,224,1) 47%, rgba(201,201,201,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(left, rgba(240,240,240,1) 0%, rgba(224,224,224,1) 47%, rgba(201,201,201,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(left, rgba(240,240,240,1) 0%, rgba(224,224,224,1) 47%, rgba(201,201,201,1) 100%); /* IE 10+ */
    background: linear-gradient(to right, rgba(240,240,240,1) 0%, rgba(224,224,224,1) 47%, rgba(201,201,201,1) 100%);/* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f0f0f0', endColorstr='#c9c9c9', GradientType=1 );/* IE6-9 */
  }

  .wrapper-request-head h1 {
    display:block;
    font-size: 20px;
    color:#333;
    padding: 12px 0px;
  }

  #wrapper-request-col-1 {
    font-size: 13px;
    background: #fcfcfc; 
    padding: 3px 15px;
    border-radius: 3px;
    font-style: italic;
    font-weight: normal;
  }

  #wrapper-request-col-1 label {
    display: block;
  }

  #wrapper-request-col-2 {
    text-align: right;
    font-size: 13px;
    padding-top: 10px;
  }

  #wrapper-request-col-2 .btn-status {
    font-size:13px;
    background: #007806;
    padding: 3px;
    border-radius: 4px;
    color: #fff;
    border:1px solid #007806;
    text-align: center;
    opacity: 0.8;
  }

  #wrapper-request-col-2 .btn-step-request {
    font-size:13px;
    background: #107ca7;
    padding: 3px;
    border-radius: 4px;
    color:#fff;
    border:1px solid #107ca7;
    text-align: center;
    opacity: 0.8;
  }

  .btn-status {
    font-size:13px;
    background: #fff;
    padding: 3px 7px;
    color: #0c408f;
    border:1px solid #0c408f;
    text-align: center;
    border-radius: 10px;
  }

  #wrapper-request-col-1 ul {
    list-style-type: none;
  }

  #wrapper-request-col-1 ul li {
   display: block;
   border-bottom: 1px solid #444;
   padding: 3px 2px;
   margin-bottom: 2px;
   color: #444;
  }
  

</style>
<div class="modal-dialog" style="width:100%;max-width: 750px;">
  <!-- Modal content-->
  <div class="modal-content">
    <div  style="padding: 20px 10px;">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
          <div class="formwraper" style="border: none;">
            <?php if ($request->request_type == 'external'): ?>
              <?php $this->load->view('employer/staff_request/common/detail_external_staff_request'); ?>
            <?php endif; ?>

            <?php if ($request->request_type == 'internal'): ?>
              <?php $this->load->view('employer/staff_request/common/detail_internal_staff_request'); ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
