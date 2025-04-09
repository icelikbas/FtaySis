<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-user-edit me-2"></i> Şoför Bilgilerini Düzenle
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Listeye Dön
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('driver_message'); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 bg-light">
            <h6 class="m-0 font-weight-bold text-primary">
                <?php echo isset($data['name']) ? $data['name'] . ' ' . $data['surname'] : 'Şoför Bilgileri'; ?>
            </h6>
        </div>
        <div class="card-body">
            <form action="<?php echo URLROOT; ?>/drivers/edit/<?php echo $data['id']; ?>" method="post" class="needs-validation <?php echo (isset($data['form_validated']) && $data['form_validated']) ? 'was-validated' : ''; ?>" novalidate>
                
                <!-- Kişisel Bilgiler -->
                <div class="card mb-4">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-primary"><i class="fas fa-user me-2"></i>Kişisel Bilgiler</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label"><strong>Ad:</strong></label>
                                <input type="text" name="name" id="name" class="form-control <?php echo (isset($data['form_validated']) && $data['form_validated'] && !empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['name']); ?>" data-required="required">
                                <div class="invalid-feedback">
                                    <?php echo (!empty($data['name_err'])) ? $data['name_err'] : 'Ad alanı gereklidir'; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="surname" class="form-label"><strong>Soyad:</strong></label>
                                <input type="text" name="surname" id="surname" class="form-control <?php echo (isset($data['form_validated']) && $data['form_validated'] && !empty($data['surname_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo html_entity_decode(htmlspecialchars($data['surname'])); ?>" data-required="required">
                                <div class="invalid-feedback">
                                    <?php echo (!empty($data['surname_err'])) ? $data['surname_err'] : 'Soyad alanı gereklidir'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="identity_number" class="form-label"><strong>TC Kimlik No:</strong></label>
                                <input type="text" name="identity_number" id="identity_number" class="form-control <?php echo (isset($data['form_validated']) && $data['form_validated'] && !empty($data['identity_number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['identity_number']); ?>" maxlength="11" data-required="required">
                                <div class="invalid-feedback">
                                    <?php echo (!empty($data['identity_number_err'])) ? $data['identity_number_err'] : 'TC Kimlik No alanı gereklidir'; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label"><strong>Doğum Tarihi:</strong></label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control" value="<?php echo isset($data['birth_date']) && $data['birth_date'] != '0000-00-00' ? htmlspecialchars($data['birth_date']) : ''; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label"><strong>Telefon:</strong></label>
                                <input type="tel" name="phone" id="phone" class="form-control <?php echo (isset($data['form_validated']) && $data['form_validated'] && !empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['phone']); ?>" data-required="required">
                                <div class="invalid-feedback">
                                    <?php echo (!empty($data['phone_err'])) ? $data['phone_err'] : 'Geçerli bir telefon numarası giriniz'; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label"><strong>E-posta:</strong></label>
                                <input type="email" name="email" id="email" class="form-control <?php echo (isset($data['form_validated']) && $data['form_validated'] && !empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
                                <div class="invalid-feedback">
                                    <?php echo (!empty($data['email_err'])) ? $data['email_err'] : 'Geçerli bir e-posta adresi giriniz'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="company_id" class="form-label"><strong>Şirket:</strong></label>
                                <select name="company_id" id="company_id" class="form-control <?php echo (isset($data['form_validated']) && $data['form_validated'] && !empty($data['company_id_err'])) ? 'is-invalid' : ''; ?>" data-required="required">
                                    <option value="">Seçiniz</option>
                                    <?php if(isset($data['companies']) && !empty($data['companies'])) : ?>
                                        <?php foreach($data['companies'] as $company) : ?>
                                            <option value="<?php echo $company->id; ?>" <?php echo (isset($data['company_id']) && $data['company_id'] == $company->id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars(isset($company->name) ? $company->name : $company->company_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?php echo (!empty($data['company_id_err'])) ? $data['company_id_err'] : 'Şirket seçimi zorunludur'; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label"><strong>Durum:</strong></label>
                                <select name="status" id="status" class="form-control <?php echo (isset($data['form_validated']) && $data['form_validated'] && !empty($data['status_err'])) ? 'is-invalid' : ''; ?>" data-required="required">
                                    <option value="Aktif" <?php echo isset($data['status']) && $data['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="Pasif" <?php echo isset($data['status']) && $data['status'] == 'Pasif' ? 'selected' : ''; ?>>Pasif</option>
                                    <option value="İzinli" <?php echo isset($data['status']) && $data['status'] == 'İzinli' ? 'selected' : ''; ?>>İzinli</option>
                                </select>
                                <div class="invalid-feedback">
                                    <?php echo (!empty($data['status_err'])) ? $data['status_err'] : 'Durum alanı gereklidir'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="address" class="form-label"><strong>Adres:</strong></label>
                                <textarea name="address" id="address" class="form-control" rows="2"><?php echo htmlspecialchars($data['address']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ehliyet Bilgileri -->
                <div class="card mb-4">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-primary"><i class="fas fa-id-card me-2"></i>Ehliyet Bilgileri</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="license_number" class="form-label"><strong>Ehliyet No:</strong></label>
                                <input type="text" name="license_number" id="license_number" class="form-control" value="<?php echo htmlspecialchars($data['license_number']); ?>" placeholder="xxxxxxxxxx">
                                <div class="form-text text-muted small">
                                    Ehliyetin üzerinde yazan belge numarasını girin. 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="license_issue_date" class="form-label"><strong>Ehliyet Verilme Tarihi:</strong></label>
                                <input type="date" name="license_issue_date" id="license_issue_date" class="form-control" value="<?php echo htmlspecialchars($data['license_issue_date'] ?? ''); ?>">
                                <div class="form-text text-muted small">
                                    Ehliyetin düzenlendiği/verildiği tarihi seçin.
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="primary_license_type" class="form-label"><strong>Birincil Ehliyet Sınıfı:</strong></label>
                                <select name="primary_license_type" id="primary_license_type" class="form-control <?php echo (isset($data['form_validated']) && $data['form_validated'] && !empty($data['primary_license_type_err'])) ? 'is-invalid' : ''; ?>" data-required="required">
                                    <option value="">Seçiniz</option>
                                    <option value="B" <?php echo ($data['primary_license_type'] == 'B') ? 'selected' : ''; ?>>B (Otomobil, Kamyonet)</option>
                                    <option value="C" <?php echo ($data['primary_license_type'] == 'C') ? 'selected' : ''; ?>>C (Kamyon)</option>
                                    <option value="D" <?php echo ($data['primary_license_type'] == 'D') ? 'selected' : ''; ?>>D (Minibüs, Otobüs)</option>
                                    <option value="E" <?php echo ($data['primary_license_type'] == 'E') ? 'selected' : ''; ?>>E (B, C, D + Römork)</option>
                                </select>
                                <div class="invalid-feedback">
                                    <?php echo (!empty($data['primary_license_type_err'])) ? $data['primary_license_type_err'] : 'Ehliyet sınıfı seçiniz'; ?>
                                </div>
                                <div class="form-text text-muted small">
                                    Sürücünün taşıt kullanırken öncelikli olarak kullandığı ehliyet sınıfını seçin.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="license_classes" class="form-label"><strong>Ek Ehliyet Sınıfları:</strong></label>
                                <input type="text" name="license_classes" id="license_classes" class="form-control <?php echo (isset($data['form_validated']) && $data['form_validated'] && !empty($data['license_classes_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['license_classes'] ?? ''); ?>" placeholder="Örn: A, B, F" data-required="required">
                                <div class="invalid-feedback">
                                    <?php echo (!empty($data['license_classes_err'])) ? $data['license_classes_err'] : 'Ehliyet sınıfı seçimi zorunludur'; ?>
                                </div>
                                <div class="form-text text-muted small">
                                    Sürücünün sahip olduğu tüm ehliyet sınıflarını virgülle ayırarak girin. Örn: "A, B, F"<br>
                                    A: Motorsiklet, B: Otomobil, C: Kamyon, D: Minibüs/Otobüs, E: B,C,D+Römork, F: Traktör, G: İş Makinesi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notlar -->
                <div class="card mb-4">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-primary"><i class="fas fa-sticky-note me-2"></i>Notlar</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="notes" class="form-label"><strong>Ek Notlar:</strong></label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Sürücü hakkında ek notlar..."><?php echo isset($data['notes']) ? html_entity_decode(htmlspecialchars($data['notes'])) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12 text-center mt-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i> Kaydet
                        </button>
                        <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-secondary btn-lg px-5 ms-2">
                            <i class="fas fa-times me-2"></i> İptal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Validasyonları -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validasyon mesajlarını temizle (ilk yüklemede)
        if (document.querySelector('.needs-validation:not(.was-validated)')) {
            // Tüm hata mesajlarını gizle
            document.querySelectorAll('.invalid-feedback').forEach(function(element) {
                element.style.display = 'none';
            });
            
            // is-invalid sınıflarını temizle
            document.querySelectorAll('.is-invalid').forEach(function(element) {
                element.classList.remove('is-invalid');
            });
            
            // Browser'ın kendi validasyon mesajlarını göstermemesi için required özelliklerini kaldır
            document.querySelectorAll('[data-required]').forEach(function(element) {
                element.removeAttribute('required');
            });
        }

        // Form submit edildiğinde validasyon işlemi
        const formElement = document.querySelector('.needs-validation');
        if (formElement) {
            formElement.addEventListener('submit', function(event) {
                // Tüm zorunlu alanları işaretle
                document.querySelectorAll('[data-required]').forEach(function(element) {
                    element.setAttribute('required', 'required');
                });
                
                // Form geçerli değilse, sayfayı yeniden yüklemeden önce was-validated sınıfı ekle
                if (!this.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    this.classList.add('was-validated');
                    
                    // Formu görüş alanının üst kısmına scroll et
                    window.scrollTo({
                        top: this.offsetTop - 20,
                        behavior: 'smooth'
                    });
                }
            });
        }
        
        // TC Kimlik No için input kontrolü
        const tcInput = document.getElementById('identity_number');
        if (tcInput) {
            tcInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
            });
        }
        
        // Telefon numarası için input kontrolü
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
            });
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 