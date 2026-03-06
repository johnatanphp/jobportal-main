<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>
        <link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('public/css/app/pages/recruitment/selection.css?t=3');?>" rel="stylesheet" type="text/css" />

        <style type="text/css">
            @media (min-width: 800px) {
                #modal-view-profile .modal-dialog {
                    width: 800px;
                }            
		    }

            .companydescription {
                padding: 0;
            }

            .sys-banner {
                border-bottom-left-radius: 5px; 
                border-bottom-right-radius: 5px; 
            }

            .content-main {
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
            }

            .sys-content-list ul li {
                padding: 5px 4px;
            }

        </style>
    </head>
    <body>
        <?php $this->load->view('common/after_body_open'); ?>
        <div class="siteWraper">
            <!--Header-->
            <?php $this->load->view('common/header'); ?>
            <!--/Header--> 
            <!--Detail Info-->
            <div class="container detailinfo">
                <div class="row">
                    <div class="col-md-3">
                        <div class="dashiconwrp">
                            <?php $this->load->view('employer/common/menu/sidebar'); ?>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="formwraper">
                            <div class="titlehead">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a class="_link-back" style="color:#fff;" href="#">
                                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                        </a>
                                        <b>Proceso de reclutamiento y selección</b>
                                    </div>
                                </div>
                            </div>
                        
                            <style>                                   
                                .companydescription {
                                    padding: 5em 10px;
                                }
                            </style>
                            <div class="companydescription">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div style="padding: 10px; font-size: 16px;">
                                            <div style="font-size: 15px;padding: 20px; text-align:center;">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                                    width="55.000000pt" height="55.000000pt" viewBox="0 0 224.000000 225.000000"
                                                    preserveAspectRatio="xMidYMid meet" style="opacity: 0.7;">

                                                    <g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)"
                                                    fill="#000000" stroke="none">
                                                    <path d="M380 2158 c-25 -13 -55 -38 -67 -57 -21 -31 -23 -45 -23 -177 0 -151
                                                    7 -174 50 -174 42 0 50 24 50 153 0 95 4 128 16 145 l15 22 719 0 719 0 15
                                                    -22 c14 -20 16 -107 16 -745 l0 -723 -221 0 c-248 0 -275 -6 -303 -65 -9 -19
                                                    -16 -59 -16 -94 l0 -61 -210 0 -210 0 0 61 c0 35 -7 75 -16 94 -28 59 -55 65
                                                    -303 65 l-221 0 0 518 c0 390 -3 521 -12 530 -7 7 -24 12 -38 12 -14 0 -31 -5
                                                    -38 -12 -9 -9 -12 -140 -12 -530 l0 -518 -35 0 c-43 0 -88 -22 -109 -52 -23
                                                    -33 -23 -403 0 -436 38 -54 -2 -52 994 -52 996 0 956 -2 994 52 23 33 23 403
                                                    0 436 -21 30 -66 52 -109 52 l-35 0 0 744 0 743 -23 34 c-12 19 -42 44 -67 57
                                                    l-44 22 -716 0 -716 0 -44 -22z m440 -1754 c0 -73 18 -115 57 -133 32 -15 494
                                                    -15 526 0 39 18 57 60 57 133 l0 66 295 0 295 0 0 -160 0 -160 -910 0 -910 0
                                                    0 160 0 160 295 0 295 0 0 -66z"/>
                                                    <path d="M1090 1400 l0 -240 50 0 50 0 0 240 0 240 -50 0 -50 0 0 -240z"/>
                                                    <path d="M1090 980 l0 -80 50 0 50 0 0 80 0 80 -50 0 -50 0 0 -80z"/>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div style="text-align: center;">
                                                Este proceso no esta disponible
                                            </div>
                                        </div>      
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                    </div>
                    <!--/Job Detail--> 
                </div>
            </div>
            <?php $this->load->view('common/bottom_ads');?>
            <!--Footer-->
            <?php $this->load->view('common/footer'); ?>
            <?php $this->load->view('common/before_body_close'); ?>        
        </div>
    </body>
</html>

