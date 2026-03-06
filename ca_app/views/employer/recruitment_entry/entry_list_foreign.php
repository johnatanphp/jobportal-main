<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title>
            <?php echo $title; ?>        
        </title>
        <?php $this->load->view('common/before_head_close'); ?>
        <style type="text/css">
            .dropdown-menu li {
                margin: 0;
                padding: 1px;
                border: none;
            }

            .aboutloc {
                font-style: italic;
                color: #888888;
            }

            #content-log {
                padding-top: 10px;
                font-style: italic;
            }

            #content-log span {
                display: block;
                margin: 8px 0;
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
                    <div class="col-md-3">
                        <div class="dashiconwrp">
                            <?php $this->load->view('employer/common/menu/sidebar'); ?>
                        </div>
                    </div>

                    <div class="col-md-9"> 
                        <?php echo $this->session->flashdata('msg'); ?>
                        <!--Job Application-->
                        <div class="formwraper">
                            <div class="titlehead">
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>
                                            Listado de ingreso extranjeros
                                        </b>
                                    </div>
                                </div>
                            </div>

                            <div class="table-search">
                                <?php echo form_open('employer/recruitment_entry/entry_list_foreign/search', array('method' => 'get')); ?>  
                                    <table width="100%">
                                        <tr>
                                            <td width="5"></td>
                                            <td width="90%">
                                                <input type="text" name="query" class="form-control" value="<?php echo $filters['query']; ?>" placeholder="Buscar por candidatos">     
                                            </td>
                                            <td width="10%">
                                                <button type="submit" class="btn btn-block btn-search">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>     
                                            </td>
                                            <td align="right">
                                                <div class="dropdown dropdown-options-job">
                                                    <button class="btn btn-sm dropdown-toggle" style="text-decoration: underline;border:1px solid #ccc;background: #fff; font-weight: bold;" type="button" data-toggle="dropdown">
                                                    <i class="glyphicon glyphicon-option-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <a href="#" data-toggle="modal" data-target="#modal-filter">
                                                                Filtrar
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                <?php echo form_close(); ?>
                           
                            </div>
                            <table class="table table-striped">
                                <?php foreach ($result_candidates as $row_candidate): ?>
                                    <tr>
                                        <td width="5">
                                       
                                        </td>
                                        <td width="85">
                                            <?php                                              
                                                $age = date_difference_in_years($row_candidate->dob, date('Y-m-d'));
                                                $age = $age > 0 ? $age : ''; 
                                            ?>

                                            <img src="<?php echo img_pic_candidate($row_candidate->photo); ?>" 
                                                 alt="<?php echo $row_candidate->first_name; ?>" 
                                                 style="max-height:80px;" />                    
                                        </td>
                                        <td>
                                            <div> 
                                                <b>
                                                    <a href="<?php echo site_url('employer/recruitment_entry/recruitment_candidates/show_process/' . $row_candidate->job_ID . '/' . $row_candidate->seeker_ID); ?>">
                                                        <?php echo ellipsize(
                                                            strip_tags(trim($row_candidate->first_name . ' ' . $row_candidate->last_name))
                                                            , 35
                                                            ); 
                                                        ?>
                                                    </a>
                                                </b>    
                                            </div>
                                            <div class="aboutloc">
                                                <?php 
                                                    $info_array = [
                                                        gender_text($row_candidate->gender), 
                                                        $age . ' ' . get_singular_plural($age, 'Año', 'Años'), 
                                                        ucwords($row_candidate->city)
                                                    ];
                                                    $candidate_info_array = array_map(function($item) {
                                                        if (!empty($item)) {
                                                            return $item;
                                                        }
                                                    }, $info_array);

                                                    echo join(' - ', $candidate_info_array);
                                                ?>
                                            </div>
                                            <div class="devinfo">
                                                <?php echo $row_candidate->job_title; ?>    
                                                <span style="font-style: italic;">
                                                    <?php if ($row_candidate->request_ID): ?>
                                                        - Solicitud: #<?php echo $row_candidate->request_ID; ?>
                                                    <?php endif; ?> 
                                                    - Cod RyS #<?php echo $row_candidate->job_ID; ?> 
                                                </span>
                                            </div>

                                            <div class="devinfo">
                                                <?php 
                                                    $label_contracted = $row_candidate->contracted ? 'label-success' : 'label-warning';
                                                ?>
                                                <span class="label <?php echo $label_contracted; ?>">
                                                    <?php 
                                                        echo $row_candidate->contracted ? 'Contratado' : 'Sin contratar';
                                                    ?>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            
                            <?php if (count($result_candidates) == 0): ?>
                                <div align="center" class="text-red" style="padding: 20px;">
                                    <h4>Sin resultados</h4>
                                </div>              
                            <?php endif; ?>
                        </div>
                        
                        <div class="paginationWrap pag-wrap-v2">
                            <?php echo ($result_candidates) ? $links : ''; ?>        
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal filter-->
        <div id="modal-filter" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <?php echo form_open('employer/recruitment_entry/entry_list_foreign/search', array('method' => 'get')); ?>
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Mostrar Candidatos</h4>
                        </div>

                        <div class="modal-body">
                            <div class="panel-filter"> 
                                <div class="filter-title">
                                    <h4>Por área</h4>
                                </div>
                                <select name="area" class="form-control">
                                    <option value="">Todas</option>
                                    <?php foreach ($internal_areas as $row_area): ?>
                                        <option value="<?php echo $row_area->ID; ?>" <?php echo $row_area->ID == $filters['area'] ? 'selected="selected"' : ''; ?>>
                                        <?php echo $row_area->area_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="panel-filter"> 
                                <div class="filter-title">
                                    <h4>Estado</h4>
                                </div>
                                <select name="contracted" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="0" 
                                            <?php echo $filters['contracted'] == '0' ? 'selected="selected"': ''; ?>>
                                        Sin contratar
                                    </option>
                                    <option value="1" <?php echo $filters['contracted'] == '1' ? 'selected="selected"': ''; ?>>
                                        Contratado
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" >Filtrar</button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <!-- Profile Popups -->
        <?php $this->load->view('common/before_body_close'); ?>
    </body>
</html>