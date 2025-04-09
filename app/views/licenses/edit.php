<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-id-card"></i> <?php echo $data['driver']->name . ' ' . $data['driver']->surname; ?> - Ehliyet Düzenle</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/licenses/index/<?php echo $data['driver']->id; ?>" class="btn btn-light float-right">
            <i class="fa fa-backward"></i> Ehliyet Sınıfları Listesine Dön
        </a>
    </div>
</div>

<?php flash('error'); ?>

<div class="card">
    <div class="card-header">
        <h4>Ehliyet Sınıfı Bilgilerini Düzenle</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/licenses/edit/<?php echo $data['driver']->id; ?>/<?php echo $data['id']; ?>" method="post" id="edit-license-form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="license_type_id">Ehliyet Sınıfı <sup>*</sup></label>
                        <select name="license_type_id" class="form-control <?php echo (!empty($data['license_type_id_err'])) ? 'is-invalid' : ''; ?>" readonly disabled>
                            <?php foreach($data['licenseTypes'] as $licenseType) : ?>
                                <option value="<?php echo $licenseType->id; ?>" <?php echo ($data['license_type_id'] == $licenseType->id) ? 'selected' : ''; ?>>
                                    <?php echo $licenseType->code . ' - ' . $licenseType->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['license_type_id_err']; ?></span>
                        <input type="hidden" name="license_type_id" value="<?php echo $data['license_type_id']; ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="issue_date">Verilme Tarihi</label>
                        <input type="date" name="issue_date" class="form-control" value="<?php echo $data['issue_date']; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="expiry_date">Geçerlilik Tarihi</label>
                        <input type="date" name="expiry_date" class="form-control" value="<?php echo $data['expiry_date']; ?>">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="notes">Notlar</label>
                <textarea name="notes" class="form-control" rows="3"><?php echo $data['notes']; ?></textarea>
            </div>
            
            <div class="row">
                <div class="col">
                    <input type="submit" value="Güncelle" class="btn btn-success">
                    <a href="<?php echo URLROOT; ?>/licenses/index/<?php echo $data['driver']->id; ?>" class="btn btn-secondary">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 