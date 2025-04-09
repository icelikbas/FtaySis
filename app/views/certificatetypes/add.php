<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus mr-2"></i>Yeni Sertifika Türü Ekle</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo URLROOT; ?>/certificateTypes" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-certificate mr-1"></i>
        Sertifika Türü Bilgileri
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/certificateTypes/add" method="post">
            <div class="form-group">
                <label for="name">Sertifika Türü Adı: <sup>*</sup></label>
                <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="description">Açıklama: <sup>*</sup></label>
                <textarea name="description" class="form-control form-control-lg <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['description']; ?></textarea>
                <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Kaydet
                </button>
                <a href="<?php echo URLROOT; ?>/certificateTypes" class="btn btn-secondary">
                    <i class="fas fa-times mr-1"></i> İptal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 