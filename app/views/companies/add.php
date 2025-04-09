<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>Yeni Şirket Ekle
            </h1>
            <p class="mb-4">Yeni bir şirket eklemek için formu doldurun.</p>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" action="<?php echo URLROOT; ?>/companies/add" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="company_name" class="form-label">Şirket Adı *</label>
                        <input type="text" class="form-control <?php echo (!empty($data['company_name_err'])) ? 'is-invalid' : ''; ?>" 
                               id="company_name" name="company_name" value="<?php echo $data['company_name']; ?>" required>
                        <div class="invalid-feedback">
                            <?php echo $data['company_name_err']; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control <?php echo (!empty($data['logo_err'])) ? 'is-invalid' : ''; ?>" 
                               id="logo" name="logo" accept="image/*">
                        <div class="invalid-feedback">
                            <?php echo isset($data['logo_err']) ? $data['logo_err'] : ''; ?>
                        </div>
                        <small class="text-muted">Maksimum 5MB, JPEG, PNG veya GIF</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">Adres</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo $data['address']; ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Telefon</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $data['phone']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-posta</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $data['email']; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tax_office" class="form-label">Vergi Dairesi</label>
                        <input type="text" class="form-control" id="tax_office" name="tax_office" value="<?php echo $data['tax_office']; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tax_number" class="form-label">Vergi No *</label>
                        <input type="text" class="form-control <?php echo (!empty($data['tax_number_err'])) ? 'is-invalid' : ''; ?>" 
                               id="tax_number" name="tax_number" value="<?php echo $data['tax_number']; ?>" maxlength="10" required>
                        <div class="invalid-feedback">
                            <?php echo $data['tax_number_err']; ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Durum *</label>
                        <select class="form-control <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>" 
                                id="status" name="status" required>
                            <option value="">Seçiniz</option>
                            <option value="Aktif" <?php echo ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Pasif" <?php echo ($data['status'] == 'Pasif') ? 'selected' : ''; ?>>Pasif</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo $data['status_err']; ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Kaydet
                        </button>
                        <a href="<?php echo URLROOT; ?>/companies" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Geri
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Form doğrulama
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

// Telefon formatı
document.getElementById('phone').addEventListener('input', function (e) {
    var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
    e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
});

// Vergi no kontrolü - sadece rakam girişi
document.getElementById('tax_number').addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/\D/g, '').substr(0, 10);
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 