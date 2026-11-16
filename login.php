<head>
    <link rel="stylesheet" href="style/login.css">
    <title>Document</title>
</head>

<div id="admin-login-view" class="login-card">
    <div class="login-header">
        <div class="login-icon-box">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <h3 class="login-title">Login Admin Studio</h3>
    </div>

    <div class="credentials-hint">
        <p class="hint-title"><i class="fa-solid fa-key text-rose"></i> Demo Account:</p>
        <p>Username: <span class="text-rose">admin</span></p>
        <p>Password: <span class="text-rose">admin1234</span></p>
    </div>

    <form method="POST" action="services/process_login.php" class="login-form">
        <div>
            <label class="form-label">Username Admin</label>
            <input type="text" id="admin-user-input" name="username" required value="admin" class="form-input">
        </div>
        <div>
            <label class="form-label">Password</label>
            <input type="password" id="admin-pass-input" name="password" required value="admin1234" class="form-input">
        </div>

        <label class="remember-label">
            <input type="checkbox" id="admin-remember-cookie" name="remember" checked class="remember-checkbox">
            <span class="remember-text">Ingat Saya</span>
        </label>

        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-right-to-bracket"></i> Masuk
        </button>
    </form>
</div>