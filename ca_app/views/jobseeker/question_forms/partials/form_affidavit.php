<style type="text/css">
    body {
        font-size: 12px;
        font-family: verdana;
        color: #222;
    }

    .form-group label {
        font-weight: bold;
    }
    .form-group {
        padding: 2px 0px;
    }

    .answer {
        padding: 0 5px;
    }

    .tbl-signers td {
        text-align: center;
        padding: 0;
    }
</style>
<?php echo $form->affidavit_header; ?>
<?php $this->load->view('jobseeker/question_forms/partials/form_question_answer'); ?>
<br />
<br />
<?php echo $form->affidavit_footer; ?>
<br />
<br />
<br />
<br />
<br />
<div>
    <table class="tbl-signers" width="100%">
        <tr height="110">
            <td width="33.333333333%">
                <img src="<?php echo base_url('public/documents/affidavit_signature/seeker.png'); ?>" width="100" height="100">
                ____________________________
                <div>Postulante</div>
                <p><?php echo mb_strtoupper(document_type_text($jobseeker->document_type)); ?>: <?php echo $jobseeker->document_number; ?></p>
            </td>
            <td width="33.333333333%">
                <img src="<?php echo base_url('public/documents/affidavit_signature/javier-quispe-sotelo.png'); ?>" width="140" height="100">
                ____________________________
                <p>Javier L. Quispe Sotelo</p>
                <p>Médico ocupacional</p>
            </td>
            <td width="33.333333333%">
                 <img src="<?php echo base_url('public/documents/affidavit_signature/jose-perez.jpg'); ?>" width="140" height="100">
                ____________________________
                <p>José Perez Garay</p>
                <p>Apoderado</p>
            </td>
        </tr>
    </table>
</div>
