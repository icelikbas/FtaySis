<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4><i class="fas fa-id-card mr-2"></i>Yeni Şoför Ekle</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/drivers/add" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Ad <sup>*</sup></label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                        <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="surname">Soyad <sup>*</sup></label>
                        <input type="text" name="surname" class="form-control <?php echo (!empty($data['surname_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['surname']; ?>">
                        <span class="invalid-feedback"><?php echo $data['surname_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="identity_number" class="form-label"><strong>TC Kimlik No:</strong></label>
                        <input type="text" name="identity_number" id="identity_number" class="form-control <?php echo (!empty($data['identity_number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['identity_number']); ?>" placeholder="11 haneli kimlik no giriniz" maxlength="11" required>
                        <div class="invalid-feedback">
                            <?php echo $data['identity_number_err']; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="birth_date" class="form-label"><strong>Doğum Tarihi:</strong></label>
                        <input type="date" name="birth_date" id="birth_date" class="form-control" value="<?php echo isset($data['birth_date']) && $data['birth_date'] != '0000-00-00' ? htmlspecialchars($data['birth_date']) : ''; ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Telefon <sup>*</sup></label>
                        <input type="text" name="phone" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['phone']; ?>">
                        <span class="invalid-feedback"><?php echo $data['phone_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="license_number">Ehliyet No <sup>*</sup></label>
                        <input type="text" name="license_number" class="form-control <?php echo (!empty($data['license_number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['license_number']; ?>">
                        <span class="invalid-feedback"><?php echo $data['license_number_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="primary_license_type">Birincil Ehliyet Sınıfı <sup>*</sup></label>
                        <select name="primary_license_type" class="form-control <?php echo (!empty($data['primary_license_type_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="" <?php echo ($data['primary_license_type'] == '') ? 'selected' : ''; ?>>-- Ehliyet Sınıfı Seçin --</option>
                            <?php foreach($data['available_license_types'] as $licenseType) : ?>
                                <option value="<?php echo $licenseType->code; ?>" <?php echo ($data['primary_license_type'] == $licenseType->code) ? 'selected' : ''; ?>>
                                    <?php echo $licenseType->code . ' - ' . $licenseType->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['primary_license_type_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="license_issue_date">Ehliyet Verilme Tarihi</label>
                        <input type="date" name="license_issue_date" class="form-control" value="<?php echo $data['license_issue_date'] ?? ''; ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="license_expiry_date">Ehliyet Geçerlilik Tarihi</label>
                        <input type="date" name="license_expiry_date" class="form-control" value="<?php echo $data['license_expiry_date'] ?? ''; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="license_classes" class="form-label"><strong>Ehliyet Sınıfları:</strong></label>
                        <input type="text" name="license_classes" id="license_classes" class="form-control" value="<?php echo htmlspecialchars($data['license_classes'] ?? ''); ?>" placeholder="Örn: A, B, F">
                        <div class="form-text text-muted small">
                            Sürücünün sahip olduğu tüm ehliyet sınıflarını virgülle ayırarak girin. Örn: "A, B, F"<br>
                            A: Motorsiklet, B: Otomobil, C: Kamyon, D: Minibüs/Otobüs, E: B,C,D+Römork, F: Traktör, G: İş Makinesi
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">E-posta</label>
                        <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Durum <sup>*</sup></label>
                        <select name="status" class="form-control <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="Aktif" <?php echo ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Pasif" <?php echo ($data['status'] == 'Pasif') ? 'selected' : ''; ?>>Pasif</option>
                            <option value="İzinli" <?php echo ($data['status'] == 'İzinli') ? 'selected' : ''; ?>>İzinli</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['status_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="company_id">Şirket</label>
                        <select name="company_id" class="form-control <?php echo (!empty($data['company_id_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="">-- Şirket Seçin --</option>
                            <?php if(isset($data['companies'])): ?>
                                <?php foreach($data['companies'] as $company): ?>
                                    <option value="<?php echo $company->id; ?>" <?php echo ($data['company_id'] == $company->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($company->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['company_id_err'] ?? ''; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="address">Adres</label>
                <textarea name="address" class="form-control" rows="3"><?php echo $data['address']; ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <label for="notes" class="form-label"><strong>Ek Notlar:</strong></label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Sürücü hakkında ek notlar..."><?php echo isset($data['notes']) ? html_entity_decode(htmlspecialchars($data['notes'])) : ''; ?></textarea>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col">
                    <input type="submit" value="Şoför Ekle" class="btn btn-primary btn-lg">
                    <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-secondary">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // TC Kimlik No için input kontrolü
        const tcInput = document.getElementById('identity_number');
        if (tcInput) {
            tcInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
            });
        }
        
        // Telefon numarası için input kontrolü
        const phoneInput = document.querySelector('input[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
            });
        }
    });
</script> 