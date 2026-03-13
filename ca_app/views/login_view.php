<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title><?php echo $title; ?></title>
  <link rel="icon" href="<?php echo base_url('public/images/favicon.ico'); ?>">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      min-height: 100vh;
      display: flex;
    }

    /* ── Left branded panel ── */
    .tb-panel-left {
      display: none;
      width: 45%;
      background: linear-gradient(160deg, #1b3a5c 0%, #0f2540 55%, #1a4a7a 100%);
      position: relative;
      overflow: hidden;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 48px 40px;
    }

    @media (min-width: 900px) {
      .tb-panel-left { display: flex; }
    }

    .tb-panel-left::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse 60% 50% at 30% 20%, rgba(255,255,255,.06) 0%, transparent 70%),
        radial-gradient(ellipse 40% 40% at 80% 80%, rgba(26,74,122,.5) 0%, transparent 70%);
    }

    .tb-brand-icon {
      width: 88px;
      height: 88px;
      background: rgba(255,255,255,.12);
      border-radius: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 28px;
      position: relative;
      z-index: 1;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,.15);
    }

    .tb-brand-icon img {
      height: 52px;
      filter: brightness(10);
    }

    .tb-panel-left h1 {
      color: #fff;
      font-size: 28px;
      font-weight: 700;
      text-align: center;
      line-height: 1.3;
      position: relative;
      z-index: 1;
      margin-bottom: 14px;
    }

    .tb-panel-left p {
      color: rgba(255,255,255,.65);
      font-size: 15px;
      text-align: center;
      line-height: 1.65;
      max-width: 320px;
      position: relative;
      z-index: 1;
    }

    .tb-panel-left .tb-features {
      margin-top: 40px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      width: 100%;
      max-width: 320px;
      position: relative;
      z-index: 1;
    }

    .tb-feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255,255,255,.07);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 10px;
      padding: 12px 16px;
    }

    .tb-feature-item .icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      background: rgba(255,255,255,.12);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 18px;
    }

    .tb-feature-item span {
      color: rgba(255,255,255,.85);
      font-size: 13.5px;
      font-weight: 500;
    }

    /* ── Right form panel ── */
    .tb-panel-right {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f8fafc;
      padding: 32px 24px;
      min-height: 100vh;
    }

    .tb-form-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 8px 32px rgba(0,0,0,.08);
      padding: 40px 36px;
      width: 100%;
      max-width: 420px;
    }

    .tb-form-header {
      text-align: center;
      margin-bottom: 32px;
    }

    .tb-form-header .logo-wrap {
      margin-bottom: 16px;
    }

    .tb-form-header .logo-wrap img {
      height: 34px;
    }

    .tb-form-header h2 {
      font-size: 22px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 6px;
    }

    .tb-form-header p {
      font-size: 14px;
      color: #64748b;
    }

    /* Form fields */
    .tb-field {
      margin-bottom: 18px;
    }

    .tb-field label {
      display: block;
      font-size: 13.5px;
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
      font-size: 14.5px;
      font-family: inherit;
      color: #0f172a;
      background: #fff;
      transition: border-color .18s, box-shadow .18s;
      outline: none;
    }

    .tb-field input:focus {
      border-color: #1b3a5c;
      box-shadow: 0 0 0 3px rgba(27,58,92,.1);
    }

    .tb-field input::placeholder {
      color: #94a3b8;
    }

    .tb-field.has-error input {
      border-color: #ef4444;
    }

    /* Links row */
    .tb-links-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 4px 0 20px;
    }

    .tb-links-row a {
      font-size: 13px;
      color: #1b3a5c;
      text-decoration: none;
      font-weight: 500;
    }

    .tb-links-row a:hover { text-decoration: underline; }

    /* Error */
    .tb-error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13.5px;
      color: #dc2626;
      margin-bottom: 16px;
      display: none;
    }

    .tb-error.visible { display: block; }

    /* Submit button */
    .tb-btn-primary {
      width: 100%;
      height: 46px;
      background: #1b3a5c;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: background .18s, transform .1s;
      letter-spacing: .01em;
    }

    .tb-btn-primary:hover { background: #0f2540; }
    .tb-btn-primary:active { transform: scale(.99); }
    .tb-btn-primary:disabled { background: #94a3b8; cursor: not-allowed; }

    /* Divider */
    .tb-divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 24px 0;
    }

    .tb-divider::before,
    .tb-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #e2e8f0;
    }

    .tb-divider span {
      font-size: 12.5px;
      color: #94a3b8;
      white-space: nowrap;
    }

    /* Employer link section */
    .tb-alt-login {
      text-align: center;
      font-size: 13.5px;
      color: #64748b;
    }

    .tb-alt-login a {
      color: #1b3a5c;
      font-weight: 600;
      text-decoration: none;
    }

    .tb-alt-login a:hover { text-decoration: underline; }

    /* Footer */
    .tb-footer {
      text-align: center;
      margin-top: 28px;
      font-size: 12px;
      color: #94a3b8;
    }

    /* Flash message */
    .tb-flash {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13.5px;
      color: #1d4ed8;
      margin-bottom: 16px;
    }

    /* Mobile logo on right panel when left panel hidden */
    .tb-mobile-brand {
      display: none;
    }

    @media (max-width: 899px) {
      .tb-mobile-brand { display: block; }
      .tb-form-card {
        box-shadow: none;
        background: transparent;
        padding: 16px 8px;
      }
      .tb-panel-right {
        background: #fff;
      }
    }
  </style>
</head>
<body>

  <!-- Left branded panel -->
  <div class="tb-panel-left">
    <div class="tb-brand-icon">
      <img src="<?php echo base_url('public/images/overall_blue.png'); ?>" alt="Overall" />
    </div>
    <h1>Portal de Empleo Overall</h1>
    <p>Conectamos el talento con las mejores oportunidades laborales del mercado.</p>
    <div class="tb-features">
      <div class="tb-feature-item">
        <div class="icon">🔍</div>
        <span>Busca empleos en tu área</span>
      </div>
      <div class="tb-feature-item">
        <div class="icon">📄</div>
        <span>Gestiona tu perfil y CV</span>
      </div>
      <div class="tb-feature-item">
        <div class="icon">🚀</div>
        <span>Aplica a miles de vacantes</span>
      </div>
    </div>
  </div>

  <!-- Right form panel -->
  <div class="tb-panel-right">
    <div class="tb-form-card">

      <div class="tb-form-header">
        <div class="logo-wrap">
          <a href="<?php echo site_url(); ?>">
            <img src="<?php echo base_url('public/images/overall_blue.png'); ?>" alt="Overall Portal Empleo" />
          </a>
        </div>
        <h2>Iniciar sesión</h2>
        <p>Ingresa tus credenciales para acceder</p>
      </div>

      <?php
        $flash = $this->session->flashdata('msg');
        if ($flash): ?>
        <div class="tb-flash"><?php echo $flash; ?></div>
      <?php endif; ?>

      <?php echo form_open('login', ['id' => 'login-form']); ?>

        <div class="tb-error" id="tb-error-box"></div>

        <div class="tb-field <?php echo form_error('email') ? 'has-error' : ''; ?>">
          <label for="email">Correo electrónico</label>
          <input type="email" name="email" id="email"
                 value="<?php echo set_value('email'); ?>"
                 placeholder="tu@correo.com"
                 autocomplete="email" />
        </div>

        <div class="tb-field <?php echo form_error('pass') ? 'has-error' : ''; ?>">
          <label for="pass">Contraseña</label>
          <input type="password" name="pass" id="pass"
                 placeholder="••••••••"
                 autocomplete="current-password" />
        </div>

        <div class="tb-links-row">
          <a href="<?php echo site_url('jobseeker-signup'); ?>">Regístrate</a>
          <a href="<?php echo site_url('forgot'); ?>">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" id="login-submit" class="tb-btn-primary">Iniciar sesión</button>

      <?php echo form_close(); ?>

      <div class="tb-divider"><span>¿Eres empresa?</span></div>

      <div class="tb-alt-login">
        <a href="<?php echo site_url('employer-login'); ?>">Ingresar como Empresa →</a>
      </div>

      <div class="tb-footer">© <?php echo date('Y'); ?> Corporativo Overall</div>
    </div>
  </div>

  <script>
    (function () {
      var form   = document.getElementById('login-form');
      var btn    = document.getElementById('login-submit');
      var errBox = document.getElementById('tb-error-box');

      form.addEventListener('submit', function (e) {
        e.preventDefault();
        btn.disabled = true;
        btn.textContent = 'Espere...';
        errBox.classList.remove('visible');
        errBox.textContent = '';

        var url  = form.getAttribute('action');
        var data = new FormData(form);
        var params = new URLSearchParams(data).toString();

        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
          if (xhr.readyState !== 4) return;
          try {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
              window.location = res.redirect;
              return;
            }
            errBox.textContent = res.message || 'Error al iniciar sesión';
            errBox.classList.add('visible');
          } catch (ex) {
            errBox.textContent = '¡No se pudo realizar la solicitud!';
            errBox.classList.add('visible');
          }
          btn.disabled = false;
          btn.textContent = 'Iniciar sesión';
        };
        xhr.send(params);
      });
    })();
  </script>

</body>
</html>
