<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>HALCON — Iniciar Sesión</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Arial,sans-serif;background:#f4f6f8;display:flex;align-items:center;justify-content:center;min-height:100vh}
        .login-box{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:36px;width:100%;max-width:380px;box-shadow:0 4px 24px rgba(0,0,0,.07)}
        .brand{text-align:center;margin-bottom:28px}
        .brand h1{font-size:32px;font-weight:900;letter-spacing:3px;color:#1e293b}
        .brand p{color:#64748b;font-size:13px;margin-top:4px}
        .form-group{margin-bottom:16px}
        label{display:block;font-size:12px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px}
        input{width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:6px;font-size:14px}
        input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 2px #bfdbfe}
        .is-invalid{border-color:#dc2626!important}
        .invalid-feedback{color:#dc2626;font-size:12px;margin-top:4px}
        .btn{width:100%;padding:10px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:15px;font-weight:700;cursor:pointer;margin-top:8px}
        .btn:hover{background:#1d4ed8}
        .back{text-align:center;margin-top:16px;font-size:13px;color:#64748b}
        .back a{color:#2563eb}
        .hint{background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:10px 14px;font-size:12px;color:#64748b;margin-top:16px}
        .hint strong{color:#f59e0b}
    </style>
</head>
<body>
<div class="login-box">
    <div class="brand">
        <h1>HALCON</h1>
        <p>Panel Administrativo</p>
    </div>

    <form method="POST" action="<?php echo e(route('login.post')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="username" value="<?php echo e(old('username')); ?>"
                   placeholder="Ej: admin" class="<?php echo e($errors->has('username') ? 'is-invalid' : ''); ?>" required autofocus/>
            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" placeholder="••••••••" required/>
        </div>
        <button type="submit" class="btn">INICIAR SESIÓN</button>
    </form>

    <div class="hint">
        <strong>Demo:</strong><br>
        admin / admin123 &nbsp;|&nbsp; cmendoza / ventas123<br>
        lramirez / alma123 &nbsp;|&nbsp; matorres / ruta123
    </div>

    <div class="back">
        <a href="<?php echo e(route('home')); ?>">← Regresar al portal de clientes</a>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\WebDesing\halcon_final\resources\views/auth/login.blade.php ENDPATH**/ ?>