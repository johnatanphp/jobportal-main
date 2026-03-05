<style type="text/css"> 

  #modal-candidate-detail .modal-content {
    padding: 15px;
  }

  #modal-candidate-detail .list-documents-item-options {
    display: none;
  }

  .candidate-section-content {
    border-bottom: 1px solid #ccc;
    margin-bottom: 8px;
  }

  .candidate-section-content-title {
    padding: 8px 15px;
    font-size: 13px;
    text-transform: uppercase;
    background: #eee;
    color: #444;
    display: inline-block;
    border: 1px solid #ccc;
    border-bottom: none;
    font-weight: bold; 
  }
  
  .attach-file-item {
    border: 1px solid #888;
    padding: 15px 5px;
    margin-top: 5px;
    position: relative;
    text-align: center;
    font-size: 16px;
    background: #eee;
  }

  .btn-menu-candidate {
    background: #337ab7;
    border: 1px solid #ccc;
    color: #fff;
    border-radius: 4px;
    padding: 1px 6px;
  }

  .btn-option {
    background: #eee;
    padding: 3px 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .dropdown ul li a {
    cursor: pointer;
  }

  .list-options {
    list-style: none;
    padding-bottom: 3px;
  }

  .list-options__item {
    padding: 4px 0;
    border-bottom: 1px solid #cccccc;
    font-weight: normal;
  }

  .document-ok {
    color: green;
  }

  .dropdown-options-more .dropdown-toggle {
    background: transparent;
    padding: 1px;
    border: 0;
  }

  .dropdown-options .dropdown-toggle {
    background: #fff;
  }

  .userinfoWrp .username {
    display: none;
  }

  .uploadPhoto {
    display: none;
  }

  .userinfoWrp {
    border: 1px solid #ccc;
    border-radius: 0;
    padding: 10px 30px;
  }

  .userinfoWrp .col-md-8 {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
  }

  .userinfoWrp .usercel {
    border-bottom: 1px solid #ccc;
  }
</style>

<div id="modal-candidate-detail" class="modal fade modal-style-1" role="dialog">
  <div class="modal-dialog" style="width: 98%;max-width: 920px;">
    <div class="modal-content"></div>
  </div>
</div>

<script type="module">

window.loadContentRecruitmentDocument = function(processId, candidateId, document) {
  $( ".content-detail" ).hide();
  $( '.content-main-detail').addClass('load load-image');
  const contentId = 'rs-content-recruitment-documents';
  let contentLoad = $( "#" + contentId); 

  if (contentLoad.length == 0) {
    contentLoad = $( "<div id='" + contentId + "' class='content-detail'/>");
    $( ".content-main-detail" ).append(contentLoad);
  }
      
  const url = "<?php echo site_url('employer/recruitment_tray/recruitment_documents/list/'); ?>" + processId + '/' + candidateId + '/' + document;

  contentLoad.load(url, function(response) {
    $(this).html(response);
    $( '.content-main-detail').removeClass('load load-image');
  });
  contentLoad.show();
}

window.loadContentExamRequestResults = function(processId, candidateId, document) {
  $( ".content-detail" ).hide();
  $( '.content-main-detail').addClass('load load-image');
  const contentId = 'rs-content-recruitment-documents';
  let contentLoad = $( "#" + contentId); 

  if (contentLoad.length == 0) {
    contentLoad = $( "<div id='" + contentId + "' class='content-detail'/>");
    $( ".content-main-detail" ).append(contentLoad);
  }
      
  const url = "<?php echo site_url('employer/recruitment_tray/exam_request_documents/list/'); ?>" + processId + '/' + candidateId + '/' + document;

  contentLoad.load(url, function(response) {
    $(this).html(response);
    $( '.content-main-detail').removeClass('load load-image');
  });
  contentLoad.show();
}

$(function(){

  $(document).on('click', ".js-btn-load-content", function(e){
      e.preventDefault();
      $( ".menu-option-candidate-item.selected" ).removeClass('selected');
      $( ".content-detail" ).hide();
  
      var contentId = $(this).data('content-id');
      $(this).addClass('selected');

      if (!contentId) {
          contentId = 'rs-content-' + Date.now();
          $(this).data('content-id', contentId);      
      }
  
      var contentLoad = $( "#" + contentId); 
  
      if (contentLoad.length == 0) {
          $( "#list-rd-seeker-documents" ).remove();
          contentLoad = $( "<div id='" + contentId + "' class='content-detail'/>");
          var url = $(this).data('url');
          contentLoad.html("<div style='text-align:center;'>Cargando...</div>");
          contentLoad.load(url, function(response) {
              $(this).html(response);
          });

          $( ".content-main-detail" ).append(contentLoad);
      }       

      contentLoad.show();
  });

  $(document).on('click', ".js-btn-load-recruitment-documents", function(e){
    e.preventDefault();
    const processId = $(this).data('process-id');
    const candidateId = $(this).data('candidate-id');
    const document = $(this).data('document');
    loadContentRecruitmentDocument(processId, candidateId, document);  
  });

  $(document).on('click', ".btn-load-content-exam-requests", function(e){
    e.preventDefault();
    const processId = $(this).data('process-id');
    const candidateId = $(this).data('candidate-id');
    const document = $(this).data('document');
    loadContentExamRequestResults(processId, candidateId, document);  
  });

  $(document).on('click', 'a[data-target="#modal-candidate-detail"]', function(e) {
    e.preventDefault();
    
    const trayCandidateId = $(this).data('tray-candidate-id');

    $( '#modal-candidate-detail .modal-content' ).html(`
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
    `);
    $( '#modal-candidate-detail' ).modal('show');
    const url = "<?php echo site_url('employer/recruitment_tray/process_candidates/modal_candidate_detail'); ?>";
    const data = {
      tray_id: trayCandidateId
    };
    $.post(url, data, function(res){
      $( '#modal-candidate-detail .modal-content' ).html(res);
    });
    return false;
  });

});
</script>