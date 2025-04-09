<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-user-plus mr-2"></i>Yeni Kullanıcı Ekle</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/users/register" method="post">
            <div class="form-group">
                <label for="name">Ad Soyad <sup>*</sup></label>
                <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
            </div>    
            <div class="form-group">
                <label for="email">E-posta <sup>*</sup></label>
                <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>    
            <div class="form-group">
                <label for="password">Şifre <sup>*</sup></label>
                <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Şifre Tekrar <sup>*</sup></label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="role">Kullanıcı Rolü <sup>*</sup></label>
                <select name="role" class="form-control">
                    <option value="user" <?php echo ($data['role'] == 'user') ? 'selected' : ''; ?>>Kullanıcı</option>
                    <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>>Yönetici</option>
                </select>
            </div>
            <div class="row">
                <div class="col">
                    <input type="submit" value="Kaydet" class="btn btn-primary">
                    <a href="<?php echo URLROOT; ?>/users" class="btn btn-secondary">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 