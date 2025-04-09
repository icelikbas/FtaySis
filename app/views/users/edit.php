<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header">
                <h3 class="text-center font-weight-light my-2"><i class="fas fa-user-edit mr-2"></i>Kullanıcı Düzenle</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/users/edit/<?php echo $data['id']; ?>" method="post">
                    <div class="form-group">
                        <label for="name" class="small mb-1">İsim Soyisim</label>
                        <input type="text" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" name="name" value="<?php echo $data['name']; ?>">
                        <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email" class="small mb-1">E-posta</label>
                        <input type="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" name="email" value="<?php echo $data['email']; ?>">
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password" class="small mb-1">Şifre</label>
                        <input type="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" name="password" value="<?php echo $data['password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                        <small class="form-text text-muted">Şifreyi değiştirmek istemiyorsanız boş bırakın.</small>
                    </div>
                    <div class="form-group">
                        <label for="role" class="small mb-1">Kullanıcı Rolü</label>
                        <select class="form-control" name="role">
                            <option value="user" <?php echo ($data['role'] == 'user') ? 'selected' : ''; ?>>Kullanıcı</option>
                            <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>>Yönetici</option>
                        </select>
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a href="<?php echo URLROOT; ?>/users" class="btn btn-secondary">İptal</a>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 