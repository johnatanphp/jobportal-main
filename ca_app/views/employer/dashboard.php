<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Empresa - <?php echo SITE_NAME; ?></title>
    <?php $this->load->view('common/meta_tags'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f5fb; }
        .dashboard-container { margin-top: 3rem; }
        .welcome-card { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 1rem; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #f5576c; }
        .stat-label { color: #666; font-size: 0.9rem; }
    </style>
</head>
<body>
    <?php $this->load->view('common/topheader'); ?>
    <div class="container dashboard-container">
        <div class="welcome-card">
            <h2>Bienvenido, <?php echo $this->session->userdata('full_name'); ?>!</h2>
            <p>Gestiona tus ofertas de empleo y candidatos</p>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($jobs_posted) ? $jobs_posted : 0; ?></div>
                    <div class="stat-label">Ofertas Publicadas</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($applications_received) ? $applications_received : 0; ?></div>
                    <div class="stat-label">Aplicaciones Recibidas</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($candidates_interviewed) ? $candidates_interviewed : 0; ?></div>
                    <div class="stat-label">Candidatos Entrevistados</div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <a href="<?php echo site_url('employer/post-job'); ?>" class="btn btn-success mr-2">Publicar Oferta</a>
                        <a href="<?php echo site_url('employer/my-jobs'); ?>" class="btn btn-primary mr-2">Mis Ofertas</a>
                        <a href="<?php echo site_url('employer/applications'); ?>" class="btn btn-info mr-2">Aplicaciones</a>
                        <a href="<?php echo site_url('logout'); ?>" class="btn btn-danger float-right">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('common/footer'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
