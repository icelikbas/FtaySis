<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><i class="fas fa-tools mr-2"></i>Yeni Bakım Kaydı</h2>
            <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Bakım Bilgileri</h5>
        </div>
        <div class="card-body">
            <?php flash('success'); ?>
            <?php flash('error'); ?>

            <form action="<?php echo URLROOT; ?>/maintenance/add" method="post">
                <div class="row">
                    <!-- Araç Seçimi -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vehicle_id">Araç: <sup>*</sup></label>
                            <select name="vehicle_id" id="vehicle_id" class="form-control <?php echo (!empty($data['vehicle_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Araç Seçin</option>
                                <?php foreach($data['vehicles'] as $vehicle) : ?>
                                    <option value="<?php echo $vehicle->id; ?>" <?php echo ($data['vehicle_id'] == $vehicle->id) ? 'selected' : ''; ?>>
                                        <?php echo $vehicle->plate_number . ' - ' . $vehicle->vehicle_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['vehicle_id_err']; ?></span>
                        </div>
                    </div>
                    
                    <!-- Bakım Türü -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="maintenance_type">Bakım Türü: <sup>*</sup></label>
                            <select name="maintenance_type" id="maintenance_type" class="form-control <?php echo (!empty($data['maintenance_type_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Bakım Türü Seçin</option>
                                <option value="Periyodik Bakım" <?php echo ($data['maintenance_type'] == 'Periyodik Bakım') ? 'selected' : ''; ?>>Periyodik Bakım</option>
                                <option value="Arıza" <?php echo ($data['maintenance_type'] == 'Arıza') ? 'selected' : ''; ?>>Arıza</option>
                                <option value="Lastik Değişimi" <?php echo ($data['maintenance_type'] == 'Lastik Değişimi') ? 'selected' : ''; ?>>Lastik Değişimi</option>
                                <option value="Yağ Değişimi" <?php echo ($data['maintenance_type'] == 'Yağ Değişimi') ? 'selected' : ''; ?>>Yağ Değişimi</option>
                                <option value="Diğer" <?php echo ($data['maintenance_type'] == 'Diğer') ? 'selected' : ''; ?>>Diğer</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['maintenance_type_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Bakım Açıklaması -->
                <div class="form-group">
                    <label for="description">Bakım Açıklaması: <sup>*</sup></label>
                    <textarea name="description" id="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="3"><?php echo $data['description']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
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
                
                <!-- Servis Sağlayıcı -->
                <div class="form-group">
                    <label for="service_provider">Servis Sağlayıcı: <small>(Opsiyonel)</small></label>
                    <input type="text" name="service_provider" id="service_provider" class="form-control" value="<?php echo $data['service_provider']; ?>">
                </div>
                
                <div class="row">
                    <!-- Maliyet -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cost">Maliyet (TL): <sup>*</sup></label>
                            <input type="text" name="cost" id="cost" class="form-control <?php echo (!empty($data['cost_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['cost']; ?>">
                            <span class="invalid-feedback"><?php echo $data['cost_err']; ?></span>
                        </div>
                    </div>
                    
                    <!-- Kilometre -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="km_reading">Kilometre: <sup>*</sup></label>
                            <input type="text" name="km_reading" id="km_reading" class="form-control <?php echo (!empty($data['km_reading_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['km_reading']; ?>">
                            <span class="invalid-feedback"><?php echo $data['km_reading_err']; ?></span>
                        </div>
                    </div>
                    
                    <!-- Durum -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Bakım Durumu: <sup>*</sup></label>
                            <select name="status" id="status" class="form-control <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Durum Seçin</option>
                                <option value="Planlandı" <?php echo ($data['status'] == 'Planlandı') ? 'selected' : ''; ?>>Planlandı</option>
                                <option value="Devam Ediyor" <?php echo ($data['status'] == 'Devam Ediyor') ? 'selected' : ''; ?>>Devam Ediyor</option>
                                <option value="Tamamlandı" <?php echo ($data['status'] == 'Tamamlandı') ? 'selected' : ''; ?>>Tamamlandı</option>
                                <option value="İptal" <?php echo ($data['status'] == 'İptal') ? 'selected' : ''; ?>>İptal</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['status_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Sonraki Bakım Tarihi -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="next_maintenance_date">Sonraki Bakım Tarihi: <small>(Opsiyonel)</small></label>
                            <input type="date" name="next_maintenance_date" id="next_maintenance_date" class="form-control" value="<?php echo $data['next_maintenance_date']; ?>">
                        </div>
                    </div>
                    
                    <!-- Sonraki Bakım KM -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="next_maintenance_km">Sonraki Bakım KM: <small>(Opsiyonel)</small></label>
                            <input type="number" name="next_maintenance_km" id="next_maintenance_km" class="form-control" value="<?php echo $data['next_maintenance_km']; ?>" min="0">
                        </div>
                    </div>
                </div>
                
                <!-- Notlar -->
                <div class="form-group">
                    <label for="notes">Notlar:</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"><?php echo $data['notes']; ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Kaydet" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-secondary btn-block">İptal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 