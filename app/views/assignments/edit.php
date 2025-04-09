<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-clipboard-list"></i> Görevlendirme Düzenle</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/assignments" class="btn btn-secondary float-right">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card card-body bg-light mt-3">
    <h2 class="card-title">Görevlendirme Bilgilerini Düzenleyin</h2>
    <form action="<?php echo URLROOT; ?>/assignments/edit/<?php echo $data['id']; ?>" method="post">
        <div class="row">
            <!-- Araç Seçimi -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="vehicle_id">Araç: <sup>*</sup></label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control <?php echo (!empty($data['vehicle_id_err'])) ? 'is-invalid' : ''; ?>">
                        <option value="">Araç Seçin</option>
                        <?php foreach($data['vehicles'] as $vehicle) : ?>
                            <option value="<?php echo $vehicle->id; ?>" <?php echo ($data['vehicle_id'] == $vehicle->id) ? 'selected' : ''; ?>>
                                <?php echo $vehicle->plate_number . ' - ' . $vehicle->brand . ' ' . $vehicle->model; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data['vehicle_id_err']; ?></span>
                </div>
            </div>
            
            <!-- Sürücü Seçimi -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="driver_id">Sürücü: <sup>*</sup></label>
                    <select name="driver_id" id="driver_id" class="form-control <?php echo (!empty($data['driver_id_err'])) ? 'is-invalid' : ''; ?>">
                        <option value="">Sürücü Seçin</option>
                        <?php foreach($data['drivers'] as $driver) : ?>
                            <option value="<?php echo $driver->id; ?>" <?php echo ($data['driver_id'] == $driver->id) ? 'selected' : ''; ?>>
                                <?php echo $driver->name . ' ' . $driver->surname . ' - ' . $driver->identity_number; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data['driver_id_err']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Başlangıç Tarihi -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date">Başlangıç Tarihi: <sup>*</sup></label>
                    <input type="date" name="start_date" id="start_date" class="form-control <?php echo (!empty($data['start_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['start_date']; ?>">
                    <span class="invalid-feedback"><?php echo $data['start_date_err']; ?></span>
                </div>
            </div>
            
            <!-- Bitiş Tarihi (Opsiyonel) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date">Bitiş Tarihi: <small>(Opsiyonel)</small></label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $data['end_date']; ?>">
                </div>
            </div>
        </div>
        
        <!-- Durum -->
        <div class="form-group">
            <label for="status">Durum: <sup>*</sup></label>
            <select name="status" id="status" class="form-control <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>">
                <option value="Aktif" <?php echo ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                <option value="Tamamlandı" <?php echo ($data['status'] == 'Tamamlandı') ? 'selected' : ''; ?>>Tamamlandı</option>
                <option value="İptal" <?php echo ($data['status'] == 'İptal') ? 'selected' : ''; ?>>İptal</option>
            </select>
            <span class="invalid-feedback"><?php echo $data['status_err']; ?></span>
        </div>
        
        <!-- Notlar -->
        <div class="form-group">
            <label for="notes">Notlar:</label>
            <textarea name="notes" id="notes" class="form-control" rows="3"><?php echo $data['notes']; ?></textarea>
        </div>
        
        <div class="row">
            <div class="col">
                <input type="submit" value="Güncelle" class="btn btn-success btn-block">
            </div>
            <div class="col">
                <a href="<?php echo URLROOT; ?>/assignments" class="btn btn-secondary btn-block">İptal</a>
            </div>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 