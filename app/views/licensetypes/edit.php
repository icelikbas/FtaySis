<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-id-badge"></i> Ehliyet Sınıfı Düzenle</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/licensetypes" class="btn btn-light float-right">
            <i class="fa fa-backward"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card card-body bg-light mt-4">
    <form action="<?php echo URLROOT; ?>/licensetypes/edit/<?php echo $data['id']; ?>" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code">Ehliyet Kodu: <sup class="text-danger">*</sup></label>
                    <input type="text" name="code" class="form-control <?php echo (!empty($data['code_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['code']; ?>" maxlength="5">
                    <span class="invalid-feedback"><?php echo $data['code_err']; ?></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Ehliyet Adı: <sup class="text-danger">*</sup></label>
                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Açıklama:</label>
            <textarea name="description" class="form-control" rows="3"><?php echo $data['description']; ?></textarea>
        </div>
        
        <div class="row">
            <div class="col">
                <input type="submit" value="Güncelle" class="btn btn-success btn-block">
            </div>
            <div class="col">
                <a href="<?php echo URLROOT; ?>/licensetypes" class="btn btn-light btn-block">İptal</a>
            </div>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 