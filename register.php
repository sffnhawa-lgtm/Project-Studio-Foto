<?php
    $register_errors = [
        'kosong' => 'Username dan password wajib diisi.',
        'format' => 'Username 3-30 karakter: huruf, angka, atau underscore.',
        'pendek' => 'Password minimal 6 karakter.',
        'beda'   => 'Konfirmasi password tidak sama.',
        'ada'    => 'Username sudah dipakai.',
    ];
    $register_error = $register_errors[$_GET['error'] ?? ''] ?? null;
?>
<div id="admin-register-view" class="login-card">
    <div class="login-header">
        <div class="login-icon-box">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <h3 class="login-title">Daftar Akun Admin</h3>
    </div>

    <?php if ($register_error): ?>
        <div class="form-alert form-alert-error"><?= e($register_error) ?></div>
    <?php endif; ?>

    <form method="POST" action="services/process_register.php" class="login-form">
        <div>
            <label class="form-label">Username</label>
            <input type="text" name="username" required minlength="3" maxlength="30" class="form-input">
        </div>
        <div>
            <label class="form-label">Password</label>
            <input type="password" name="password" required minlength="6" class="form-input">
        </div>
        <div>
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirm" required minlength="6" class="form-input">
        </div>

        <button type="submit" class="submit-btn">
            <i class="fa-solid fa-user-plus"></i> Daftar
        </button>
    </form>

    <p class="form-footer">Sudah punya akun? <a href="?page=admin">Masuk</a></p>
</div>
