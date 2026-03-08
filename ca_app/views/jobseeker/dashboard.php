<!DOCTYPE html>
<html>
<head>
    <title>Mi Dashboard - <?php echo SITE_NAME; ?></title>
    <?php $this->load->view('common/meta_tags'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f5fb; }
        .dashboard-container { margin-top: 3rem; }
        .welcome-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 1rem; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #667eea; }
        .stat-label { color: #666; font-size: 0.9rem; }
    </style>
</head>
<body>
    <?php $this->load->view('common/topheader'); ?>
    <div class="container dashboard-container">
        <div class="welcome-card">
            <h2>Bienvenido, <?php echo $this->session->userdata('first_name'); ?>!</h2>
            <p>Gestiona tu perfil y aplica a ofertas de empleo</p>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($applications_count) ? $applications_count : 0; ?></div>
                    <div class="stat-label">Aplicaciones Enviadas</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($saved_jobs) ? $saved_jobs : 0; ?></div>
                    <div class="stat-label">Empleos Guardados</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($interviews) ? $interviews : 0; ?></div>
                    <div class="stat-label">Entrevistas</div>
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
                        <a href="<?php echo site_url('jobseeker/profile'); ?>" class="btn btn-primary mr-2">Ver Perfil</a>
                        <a href="<?php echo site_url('jobseeker/applications'); ?>" class="btn btn-info mr-2">Mis Aplicaciones</a>
                        <a href="<?php echo site_url('search'); ?>" class="btn btn-success">Buscar Empleos</a>
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
