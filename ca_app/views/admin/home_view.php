<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title; ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #0f2540 0%, #1b3a5c 50%, #0d3d6b 100%);
    }

    .tb-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 48px rgba(0,0,0,.25);
      padding: 44px 40px;
      width: 100%;
      max-width: 400px;
      margin: 20px;
    }

    .tb-header {
      text-align: center;
      margin-bottom: 32px;
    }

    .tb-header img {
      height: 34px;
      margin-bottom: 16px;
    }

    .tb-header h2 {
      font-size: 20px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 4px;
    }

    .tb-header p {
      font-size: 13px;
      color: #64748b;
    }

    .tb-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: #fef3c7;
      border: 1px solid #fde68a;
      border-radius: 20px;
      padding: 3px 12px;
      font-size: 11.5px;
      font-weight: 600;
      color: #92400e;
      margin-top: 8px;
    }

    .tb-field {
      margin-bottom: 16px;
    }

    .tb-field label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #334155;
      margin-bottom: 6px;
    }

    .tb-field input {
      width: 100%;
      height: 44px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      padding: 0 14px;
      font-size: 14px;
      font-family: inherit;
      color: #0f172a;
      background: #fff;
      transition: border-color .18s, box-shadow .18s;
      outline: none;
    }

    .tb-field input:focus {
      border-color: #0f2540;
      box-shadow: 0 0 0 3px rgba(15,37,64,.1);
    }

    .tb-field input::placeholder { color: #94a3b8; }

    .tb-error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13px;
      color: #dc2626;
      margin-bottom: 16px;
    }

    .tb-btn {
      width: 100%;
      height: 46px;
      background: #0f2540;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: background .18s;
      margin-top: 4px;
    }

    .tb-btn:hover { background: #1b3a5c; }

    .tb-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 11.5px;
      color: #94a3b8;
    }

    .tb-footer a {
      color: #64748b;
      text-decoration: none;
    }

    .tb-footer a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="tb-card">
    <div class="tb-header">
      <img src="<?php echo base_url('public/images/overall_blue.png'); ?>" alt="Overall" />
      <h2>Panel Administrativo</h2>
      <p>Acceso exclusivo para administradores</p>
      <div class="tb-badge">🔐 Admin</div>
    </div>

    <?php if (!empty($msg)): ?>
      <div class="tb-error"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php echo form_open('', ['name' => 'login_form', 'id' => 'login-form']); ?>

      <div class="tb-field">
        <label for="username">Usuario</label>
        <input type="text" name="username" id="username" placeholder="usuario@overall.pe" autocomplete="username" />
        <?php echo form_error('username', '<div style="color:#dc2626;font-size:12px;margin-top:4px;">', '</div>'); ?>
      </div>

      <div class="tb-field">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="••••••••" autocomplete="current-password" />
        <?php echo form_error('password', '<div style="color:#dc2626;font-size:12px;margin-top:4px;">', '</div>'); ?>
      </div>

      <button type="submit" class="tb-btn">Iniciar sesión</button>

    <?php echo form_close(); ?>

    <div class="tb-footer">
      © <?php echo date('Y'); ?> Corporativo Overall &nbsp;·&nbsp;
      <a href="<?php echo site_url(); ?>">Volver al portal</a>
    </div>
  </div>
</body>
</html>
