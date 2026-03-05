<?php 
    $this->load->view('employer/screening/template/screening_basic_preliminary');

    $incidences = $GLOBALS['__screening_vars__']['incidences'];
    $psychotechnical_indicator = $GLOBALS['__screening_vars__']['psychotechnical_indicator'];
?>

<?php if ($incidences || $psychotechnical_indicator): ?>
<pagebreak />
<?php 
    $this->load->view('employer/screening/template/screening_basic_annexes');
?>
<?php endif; ?>