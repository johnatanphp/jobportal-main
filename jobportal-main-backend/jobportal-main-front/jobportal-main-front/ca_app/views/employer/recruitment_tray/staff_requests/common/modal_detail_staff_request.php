<div id="modal-detail-staff-requests" class="modal modal-style-1 in" role="dialog">
  <div class="modal-dialog" style="width: 98%;max-width: 900px;">
    <div class="modal-content"></div>
  </div>
</div>

<script type="module">
(function(){
  function loadModalStaffRequestDetail(e) {

    const button = e.target;
    
    if (!button.classList.contains('staff-requests-detail')) {
      return;
    }
  
    const id = button.dataset.id;  
    const url = "<?php echo site_url('employer/recruitment_tray/staff_requests/modal_detail'); ?>" + "/" + id
    
    const modal = document.querySelector('#modal-detail-staff-requests');
    const modalContent = modal.querySelector('.modal-content');
    
    $(modal).modal('show');
    modalContent.innerHTML = `
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Espere un momento...</h4>
      </div>
    `;
    
    if (modalContent) {
      modalContent.classList.add('load', 'load-image');
    }
    
    fetch(url)
    .then(response => {
      modalContent.classList.remove('load', 'load-image');
    
      if (!response.ok) {
        throw new Error('Server responded with status: ' + response.status);
      }
      return response.text();
    })
    .then(response => {
      modalContent.innerHTML = response;
    })
    .catch(error => {
      toastr["error"]('Ha ocurrido un error');        
    });
  }
  
  function init() {
    document.addEventListener('click', loadModalStaffRequestDetail);
  }
  
  init();
})();
</script>
