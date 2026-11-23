<div id="admin-login-view" class="login-card">
    <div class="login-header">
        <div class="login-icon-box">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <h3 class="login-title">Login Admin Studio</h3>
    </div>

    <?php if (isset($_GET['registered'])): ?>
        <div class="form-alert form-alert-success">Akun berhasil dibuat. Silakan login.</div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="form-alert form-alert-error">Username atau password salah.</div>
    <?php endif; ?>


    <form method="POST" action="services/process_login.php" class="login-form">
        <div>
            <label class="form-label">Username Admin</label>
            <input type="text" id="admin-user-input" name="username" required value="admin" class="form-input">
        </div>
        <div>
            <label class="form-label">Password</label>
            <input type="password" id="admin-pass-input" name="password" required value="admin1234" class="form-input">
        </div>

        <label class="check-label">
            <input type="checkbox" id="admin-remember-cookie" name="remember" checked class="check-input">
            <span class="check-text">Ingat Saya</span>
        </label>

        <button type="submit" class="submit-btn">
            <i class="fa-solid fa-right-to-bracket"></i> Masuk
        </button>
    </form>

    <p class="form-footer">Belum punya akun? <a href="?page=register">Daftar</a></p>
</div>