<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />

<style type="text/css">
  .ui-autocomplete { 
    z-index:99999999; 
  }
  
  .ui-autocomplete-input {
    width: 100%;
  }

</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div>
    <!--/Header-->
    <div class="containe">
        <div class="row" style="padding:0;margin:0;">  
            <div class="col-md-12">
                <div><?php echo $this->session->flashdata('msg');?></div>

                <div>
                    <?php $this->load->view('embed/jobseeker/other_studies/common/add_other_study'); ?>
                    <?php $this->load->view('embed/jobseeker/other_studies/common/edit_other_study'); ?>
                </div>  

                <!--Other studies-->
                <div id="other-studies-list" class="innerbox2">
                    <div class="titlebar">
                        <div class="row">
                            <div class="col-xs-9"><b>Otros estudios</b></div>
                            <div class="col-xs-3 text-right">
                                <a href="javascript:;" id="add-other-study" class="editlink">
                                    Añadir
                                </a> 
                            </div>
                        </div>
                    </div>
                
                    <div class="experiance">
                    <?php 
                    if($result_other_studies):
                        foreach($result_other_studies as $row_other_studies):
                    ?>
                    <div class="row expbox" id="otst_<?php echo $row_other_studies->ID;?>">
                        <div class="col-md-12">
                        <?php
                            $start_date = ucwords(_date_locale_format(strtotime($row_other_studies->start_date), "MMM y"));
                            $end_date = $row_other_studies->end_date != null && $row_other_studies->end_date != '0000-00-00' ? ucwords(_date_locale_format(strtotime($row_other_studies->end_date), "MMM y")) : "Presente";
                        ?>
                        <div class="title-other-studies">
                            <h4><?php echo $row_other_studies->institute;?></h4>
                            <span> (<?php echo $start_date . " - " . $end_date?>)</span>
                        </div>
                        <ul class="useradon">
                            <li><?php echo $row_other_studies->name;?> - <?php echo $row_other_studies->type;?></li>
                        </ul>
                        <div class="action"><a href="javascript:;" onClick="load_edit_other_studies(<?php echo $row_other_studies->ID;?>);" title="Editar" class="edit-ico"><i class="fa fa-pencil">&nbsp;</i></a> <a href="javascript:;" onClick="del_other_studies(<?php echo $row_other_studies->ID;?>);" title="Eliminar" class="delete-ico"><i class="fa fa-times">&nbsp;</i></a></div>
                        </div>
                    </div>
                    <?php endforeach; endif;?>
                    <div class="clear"></div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>

<!--Footer-->

<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/validate_jobseeker.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">

    function add_other_studies() {
        var data = $( "#frm_other_studies" ).serialize();
        var url = $( "#frm_other_studies" ).prop('action');

        $( '#add_other_study_submit' ).prop('disabled', true);

        $.ajax({
            type: "POST",
            url: url,
            data: data			  
        })
        .done(function( msg ) {

            if (msg == 'done') {
                $( "#other_studies_modal" ).modal('hide');
                $('#emsg_add_exp').html('');
                location.reload();
            } else {
                $('#emsg_add_exp').html('<span class="label label-warning">' + msg + '</span>');
                $( '#add_other_study_submit' ).prop('disabled', false);
            }
        });
    }

    function edit_other_studies() {
        var data = $( "#frm_edit_other_studies" ).serialize();
        var url = $( "#frm_edit_other_studies" ).prop('action');

        $( '#edit_other_study_submit' ).prop('disabled', true);

        $.ajax({
            type: "POST",
            url: url,
            data: data
        })
        .done(function( msg ) {
            if (msg == 'done'){
                $('#edit_other_studies_modal').modal('hide');
                $('#emsg_edit_exp').html('');
                location.reload();
            } else{
                $( '#emsg_edit_exp' ).html('<span class="label label-warning">' + msg + '</span>');
                $( '#edit_other_study_submit' ).prop('disabled', false);
            }
        });
    }

    function del_other_studies(id) {
        var ed_id = id;
        confirmed = confirm("¿Seguro que quieres eliminar tu otro estudio?");
        
        if (confirmed) {
            
            $('#osts_' + id).fadeOut();
            
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('embed/jobseeker/other_studies/manage_other_studies/delete'); ?>",
                data: { 
                    id: id,
                    "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>"
                }
            })
            .done(function( msg ) {
                if(msg == 'done'){
                    $('#otst_'+ id).fadeOut();
                }
            });
        }
    }

    function load_edit_other_studies(id) {
        $( "#ed_otst_studying" ).off('change.checkdate');
        $( "#other-studies-list" ).hide();
        $( "#other-studies-edit" ).show();
        
        $.ajax({
                type: "POST",
                url: "<?php echo site_url('embed/jobseeker/other_studies/manage_other_studies/get_other_study'); ?>",
                data: { 
                    id: id,
                    "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>"
                }
            })
        .done(function( data ) {
            obj = jQuery.parseJSON(data);

            var start_date = obj.start_date;
            var end_date = obj.end_date;
            var pieces_start_date = start_date.split("-");
            var completion_year = "";
            var completion_month = "";
        
            $( "#ed_otst_id" ).val(obj.ID);
            $( "#ed_otst_study_name" ).val(obj.name);
            
            select_value('ed_otst_country',obj.country);

            $( "#ed_otst_institute" ).val(obj.institute);
            $( "#ed_otst_type_study" ).val(obj.type);

            select_value('ed_otst_start_month', pieces_start_date[1]);
            select_value('ed_otst_start_year', pieces_start_date[0]);
        
            $( "#ed_otst_studying" ).prop('checked', true);

            if (end_date != null && end_date != '0000-00-00') {
                var pieces_end_date = end_date.split("-");
                completion_year = pieces_end_date[0];
                completion_month = pieces_end_date[1];
                $( "#ed_otst_studying" ).prop('checked', false);
            }

            select_value('ed_otst_completion_year', completion_year);
            select_value('ed_otst_completion_month', completion_month);
            
            $( "#ed_otst_studying" ).change();
            validate_range_dates('#ed_otst_start_year', '#ed_otst_start_month', '#ed_otst_completion_year', '#ed_otst_completion_month', '#ed_otst_studying');
        
        });
    }

    $(function(){

        $( "#otst_studying" ).change(function(){

            var isSelected = $(this).is(':checked');

            $( "#otst_completion_year" ).attr({'disabled': isSelected});
            $( "#otst_completion_month" ).attr({'disabled': isSelected});

            if (isSelected) {
                $( "#otst_completion_month" ).closest('div').removeClass( "has-error" ); 
                $( '.completion_month_err').remove();
                $( "#otst_completion_year" ).closest('div').removeClass( "has-error" ); 
                $( '.completion_year_err').remove();
            }
        });

        $( "#ed_otst_studying" ).change(function(){

            var isSelected = $(this).is(':checked');

            $( "#ed_otst_completion_year" ).attr({'disabled': isSelected});
            $( "#ed_otst_completion_month" ).attr({'disabled': isSelected});

            if (isSelected) {
                $( "#ed_otst_completion_month" ).closest('div').removeClass( "has-error" ); 
                $( '.completion_month_err').remove();
                $( "#ed_otst_completion_year" ).closest('div').removeClass( "has-error" ); 
                $( '.completion_year_err').remove();
            }
        });

            $( "#add-other-study" ).click(function(){
            $( "#other-studies-list" ).hide();
            $( "#other-studies-add" ).show();
        });

        $( ".back" ).click(function(){
            $( "#other-studies-list" ).show();
            $( "#other-studies-add" ).hide();
            $( "#other-studies-edit" ).hide();
        });
    });
</script>
</body>
</html>