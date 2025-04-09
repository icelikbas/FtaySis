<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-user-circle mr-2"></i>Kullanıcı Profili</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Hesap Bilgileri</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Adı Soyadı:</th>
                        <td><?php echo $data['user']->name; ?></td>
                    </tr>
                    <tr>
                        <th>E-posta:</th>
                        <td><?php echo $data['user']->email; ?></td>
                    </tr>
                    <tr>
                        <th>Kullanıcı Rolü:</th>
                        <td>
                            <?php if($data['user']->role == 'admin'): ?>
                                <span class="badge badge-primary">Yönetici</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Kullanıcı</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Kayıt Tarihi:</th>
                        <td><?php echo date('d.m.Y H:i', strtotime($data['user']->created_at)); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Şifre Değiştir</h5>
                <form action="<?php echo URLROOT; ?>/users/changePassword" method="post">
                    <div class="form-group">
                        <label for="current_password">Mevcut Şifre:</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Yeni Şifre:</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Yeni Şifre (Tekrar):</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Şifreyi Değiştir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 