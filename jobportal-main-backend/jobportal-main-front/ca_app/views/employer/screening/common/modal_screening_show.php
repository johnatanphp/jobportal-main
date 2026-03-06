<div id="modal-show-screening" class="modal" role="dialog">
  <div class="modal-dialog modal-fullscreen">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"></div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

<script type="module">
function searchScreening(id) {
  const url = "<?php echo site_url('employer/screening/search/get_screening'); ?>";
  const data = {
    'id': id
  };
  
  const img_loading_url = "<?php echo img_loading_url(); ?>";
  $( '#modal-show-screening .modal-content' ).html(`
    <div class="modal-header">
        <h4>Screening Detalle</h4>
    </div>
    <div clas="modal-body">
      <div style="text-align:center">
        <img src="${img_loading_url}" style="width:24px; height:24px;"/>
      </div>
    </div>
  `);

  $( '#modal-show-screening' ).modal('show');

  $.get(url, data, function(response) {
    $( '#modal-show-screening .modal-content' ).html(response);
    $( '#cont-display-json pre' ).html(JSON.stringify(JSON.parse($( '#cont-display-json pre' ).html()), null, 2));
  });
}

$( ".show-results" ).click(function(){
  searchScreening($(this).data('id'));
});
</script>
