<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-id-card"></i> <?php echo $data['driver']->name . ' ' . $data['driver']->surname; ?> - Ehliyet Ekle</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/licenses/index/<?php echo $data['driver']->id; ?>" class="btn btn-light float-right">
            <i class="fa fa-backward"></i> Ehliyet Listesine Dön
        </a>
    </div>
</div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light">
            <h2 class="mb-4">Yeni Ehliyet Sınıfı Ekle</h2>
            <form action="<?php echo URLROOT; ?>/licenses/add/<?php echo $data['driver']->id; ?>" method="post">
                <div class="form-group mb-3">
                    <label for="license_type_id">Ehliyet Sınıfı <sup class="text-danger">*</sup></label>
                    <select name="license_type_id" id="license_type_id" class="form-control <?php echo (!empty($data['license_type_id_err'])) ? 'is-invalid' : ''; ?>">
                        <option value="">Ehliyet Sınıfı Seçin</option>
                        <?php foreach($data['licenseTypes'] as $licenseType) : ?>
                            <option value="<?php echo $licenseType->id; ?>" 
                                <?php echo ($data['license_type_id'] == $licenseType->id) ? 'selected' : ''; ?>>
                                <?php echo $licenseType->code . ' - ' . $licenseType->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data['license_type_id_err']; ?></span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="issue_date">Verilme Tarihi</label>
                            <input type="date" name="issue_date" id="issue_date" class="form-control" value="<?php echo $data['issue_date']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="expiry_date">Geçerlilik Tarihi</label>
                            <input type="date" name="expiry_date" id="expiry_date" class="form-control" value="<?php echo $data['expiry_date']; ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="notes">Notlar</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"><?php echo $data['notes']; ?></textarea>
                </div>

                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                    </div>
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/licenses/index/<?php echo $data['driver']->id; ?>" class="btn btn-light btn-block">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ehliyet sınıfı seçildiğinde açıklamasını göster
        const licenseSelect = document.getElementById('license_type_id');
        const licenseDescriptions = {
            <?php foreach($data['licenseTypes'] as $licenseType) : ?>
            "<?php echo $licenseType->id; ?>": "<?php echo addslashes($licenseType->description); ?>",
            <?php endforeach; ?>
        };
        
        licenseSelect.addEventListener('change', function() {
            const selectedLicense = this.value;
            const descriptionElement = document.getElementById('licenseDescription');
            
            if(descriptionElement) {
                if(selectedLicense && licenseDescriptions[selectedLicense]) {
                    descriptionElement.textContent = licenseDescriptions[selectedLicense];
                    descriptionElement.parentElement.style.display = 'block';
                } else {
                    descriptionElement.textContent = '';
                    descriptionElement.parentElement.style.display = 'none';
                }
            }
        });
        
        // Tarih kontrolü - yeni ve daha güvenli kodlar
        const issueDate = document.getElementById('issue_date');
        const expiryDate = document.getElementById('expiry_date');
        
        // Tarihleri kontrol eden yardımcı fonksiyon
        function validateDates() {
            if (!issueDate.value || !expiryDate.value) return true;
            
            // Tarih değerlerini Date nesnelerine dönüştürür
            // Format: YYYY-MM-DD (HTML5 date input standardı)
            const issueVal = issueDate.value;
            const expiryVal = expiryDate.value;
            
            // Doğrudan string olarak karşılaştır (YYYY-MM-DD formatı kronolojik olarak sıralanabilir)
            if (expiryVal < issueVal) {
                return false;
            }
            return true;
        }
        
        // Bitiş tarihi değiştiğinde kontrol et
        expiryDate.addEventListener('change', function() {
            if (!validateDates()) {
                alert('Geçerlilik tarihi, verilme tarihinden sonra olmalıdır.');
                this.value = '';
            }
        });
        
        // Verilme tarihi değiştiğinde kontrol et
        issueDate.addEventListener('change', function() {
            if (!validateDates()) {
                alert('Geçerlilik tarihi, verilme tarihinden sonra olmalıdır.');
                expiryDate.value = '';
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 