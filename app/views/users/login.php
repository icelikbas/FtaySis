<?php require APPROOT . '/views/inc/header_login.php'; ?>

<div class="login-container">
    <div class="login-wrapper">
        <div class="login-form-container">
            <div class="login-logo-container">
                <!-- Logo alanı -->
                <div class="company-logo-placeholder">
                    <i class="fas fa-truck-moving"></i>
                    <div class="company-name">Filo Takip</div>
                    </div>
            </div>
            
            <div class="login-card">
                <div class="login-header">
                    <h3>Hoş Geldiniz</h3>
                    <p>Sisteme giriş yapmak için bilgilerinizi giriniz</p>
                </div>
                
                <?php flash('register_success'); ?>
                
                <form action="<?php echo URLROOT; ?>/users/login" method="post" class="login-form">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                   placeholder="E-posta adresiniz" value="<?php echo $data['email']; ?>">
                        </div>
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                                   placeholder="Şifreniz" value="<?php echo $data['password']; ?>">
                        </div>
                        <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                    </div>
                    
                    <div class="form-group form-check">
                        <div class="remember-me">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Beni hatırla</label>
                        </div>
                        <a href="#" class="forgot-password">Şifremi unuttum</a>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block login-btn">
                            <i class="fas fa-sign-in-alt mr-2"></i>Giriş Yap
                        </button>
                    </div>
                </form>
                
                <div class="login-footer">
                    <div class="footer-info">
                        <p>&copy; <?php echo date('Y'); ?> Duygu İnşaat Filo Takip Sistemi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ana JS Dosyası (Gerekliyse) -->
<script src="<?php echo getPublicUrl('js/main.js'); ?>"></script>
<!-- Bootstrap 5 JS (header_login.php içinde zaten var, burada tekrar yüklemeye gerek yok) -->
<!-- jQuery (header_login.php içinde zaten var) -->
</body>
</html>
