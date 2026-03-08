<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
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
    <div class="container dashboard-container">
        <div class="welcome-card">
            <h2>Panel de Administración</h2>
            <p>Gestión completa del sistema Portal de Empleo</p>
        </div>
        
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($total_users) ? $total_users : 0; ?></div>
                    <div class="stat-label">Usuarios Totales</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($total_employers) ? $total_employers : 0; ?></div>
                    <div class="stat-label">Empresas Registradas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($total_jobs) ? $total_jobs : 0; ?></div>
                    <div class="stat-label">Ofertas de Empleo</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($total_applications) ? $total_applications : 0; ?></div>
                    <div class="stat-label">Aplicaciones</div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Herramientas de Administración</h5>
                    </div>
                    <div class="card-body">
                        <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-primary mr-2">Gestionar Usuarios</a>
                        <a href="<?php echo site_url('admin/companies'); ?>" class="btn btn-info mr-2">Gestionar Empresas</a>
                        <a href="<?php echo site_url('admin/jobs'); ?>" class="btn btn-warning mr-2">Gestionar Ofertas</a>
                        <a href="<?php echo site_url('admin/logs'); ?>" class="btn btn-secondary mr-2">Ver Logs</a>
                        <a href="<?php echo site_url('logout'); ?>" class="btn btn-danger float-right">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
