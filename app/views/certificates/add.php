<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus mr-2"></i>Yeni Sertifika Ekle</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo URLROOT; ?>/certificates/index/<?php echo $data['driver_id']; ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-user mr-1"></i>
        Sürücü: <?php echo $data['driver']->name . ' ' . $data['driver']->surname; ?>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/certificates/add/<?php echo $data['driver_id']; ?>" method="post">
            <div class="form-group">
                <label for="certificate_type_id">Sertifika Türü: <sup>*</sup></label>
                <select name="certificate_type_id" class="form-control form-control-lg <?php echo (!empty($data['certificate_type_id_err'])) ? 'is-invalid' : ''; ?>">
                    <option value="">Sertifika Türü Seçin</option>
                    <?php foreach($data['certificateTypes'] as $type) : ?>
                        <option value="<?php echo $type->id; ?>" <?php echo ($data['certificate_type_id'] == $type->id) ? 'selected' : ''; ?>>
                            <?php echo $type->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="invalid-feedback"><?php echo $data['certificate_type_id_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="certificate_number">Sertifika Numarası: <sup>*</sup></label>
                <input type="text" name="certificate_number" class="form-control form-control-lg <?php echo (!empty($data['certificate_number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['certificate_number']; ?>">
                <span class="invalid-feedback"><?php echo $data['certificate_number_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="issue_date">Veriliş Tarihi: <sup>*</sup></label>
                <input type="date" name="issue_date" class="form-control form-control-lg" value="<?php echo $data['issue_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="expiry_date">Bitiş Tarihi: <sup>*</sup></label>
                <input type="date" name="expiry_date" class="form-control form-control-lg" value="<?php echo $data['expiry_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="issuing_authority">Veren Kurum: <sup>*</sup></label>
                <input type="text" name="issuing_authority" class="form-control form-control-lg" value="<?php echo $data['issuing_authority']; ?>" required>
            </div>

            <div class="form-group">
                <label for="notes">Notlar:</label>
                <textarea name="notes" class="form-control form-control-lg"><?php echo $data['notes']; ?></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Kaydet
                </button>
                <a href="<?php echo URLROOT; ?>/certificates/index/<?php echo $data['driver_id']; ?>" class="btn btn-secondary">
                    <i class="fas fa-times mr-1"></i> İptal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>