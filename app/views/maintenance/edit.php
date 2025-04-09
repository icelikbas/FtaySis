<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><i class="fas fa-tools mr-2"></i><?php echo $data['title']; ?></h2>
            <div class="float-right">
                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $data['id']; ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Detay Sayfasına Dön
                </a>
                <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-primary ml-2">
                    <i class="fas fa-list mr-1"></i> Tüm Bakımlar
                </a>
            </div>
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <!-- Durum Bilgisi -->
    <div class="row mb-4">
        <div class="col-md-12">
            <?php if($data['status'] == 'Planlandı'): ?>
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-info">
                                    <i class="fas fa-calendar mr-1"></i> Bu bakım planlandı ve henüz başlamadı
                                </div>
                                <div class="mt-2">
                                    Bakım işlemini başlatmak için durumu "Devam Ediyor" olarak güncelleyebilirsiniz.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($data['status'] == 'Devam Ediyor'): ?>
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-warning">
                                    <i class="fas fa-spinner mr-1"></i> Bu bakım şu anda devam ediyor
                                </div>
                                <div class="mt-2">
                                    Bakım işlemi tamamlandığında durumu "Tamamlandı" olarak güncelleyebilirsiniz.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cog fa-spin fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($data['status'] == 'Tamamlandı'): ?>
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-success">
                                    <i class="fas fa-check-circle mr-1"></i> Bu bakım tamamlandı
                                </div>
                                <div class="mt-2">
                                    Tamamlanmış bakım kaydını güncellemek için aşağıdaki alanları düzenleyebilirsiniz.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($data['status'] == 'İptal'): ?>
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-danger">
                                    <i class="fas fa-times-circle mr-1"></i> Bu bakım iptal edildi
                                </div>
                                <div class="mt-2">
                                    İptal edilen bakımı aktif hale getirmek için durumunu güncelleyebilirsiniz.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ban fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Bakım Kaydını Düzenle</h6>
                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['vehicle_id']; ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-car mr-1"></i> Araca Git
                    </a>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/maintenance/edit/<?php echo $data['id']; ?>" method="post">
                        <!-- Temel Bilgiler -->
                        <div class="card border-left-primary mb-4">
                            <div class="card-body">
                                <h5 class="text-primary font-weight-bold mb-3">Temel Bilgiler</h5>
                                <div class="row">
                                    <!-- Araç Seçimi -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vehicle_id">Araç: <sup class="text-danger">*</sup></label>
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
                                            <label for="maintenance_type">Bakım Türü: <sup class="text-danger">*</sup></label>
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
                                    <label for="description">Bakım Açıklaması: <sup class="text-danger">*</sup></label>
                                    <textarea name="description" id="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="3"><?php echo $data['description']; ?></textarea>
                                    <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarih ve Durum Bilgileri -->
                        <div class="card border-left-info mb-4">
                            <div class="card-body">
                                <h5 class="text-primary font-weight-bold mb-3">Tarih ve Durum</h5>
                                <div class="row">
                                    <!-- Başlangıç Tarihi -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Başlangıç Tarihi: <sup class="text-danger">*</sup></label>
                                            <input type="date" name="start_date" id="start_date" class="form-control <?php echo (!empty($data['start_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['start_date']; ?>">
                                            <span class="invalid-feedback"><?php echo $data['start_date_err']; ?></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Bitiş Tarihi (Opsiyonel) -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="end_date">Bitiş Tarihi: <small class="text-muted">(Opsiyonel)</small></label>
                                            <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $data['end_date']; ?>">
                                        </div>
                                    </div>
                                    
                                    <!-- Durum -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">Bakım Durumu: <sup class="text-danger">*</sup></label>
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
                                    
                                    <!-- Kilometre -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="km_reading">Kilometre: <sup class="text-danger">*</sup></label>
                                            <input type="number" name="km_reading" id="km_reading" class="form-control <?php echo (!empty($data['km_reading_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['km_reading']; ?>">
                                            <span class="invalid-feedback"><?php echo $data['km_reading_err']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Maliyet ve Servis Bilgileri -->
                        <div class="card border-left-success mb-4">
                            <div class="card-body">
                                <h5 class="text-primary font-weight-bold mb-3">Maliyet ve Servis Bilgileri</h5>
                                
                                <div class="row">
                                    <!-- Maliyet -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cost">Maliyet (TL): <sup class="text-danger">*</sup></label>
                                            <div class="input-group">
                                                <input type="text" name="cost" id="cost" class="form-control <?php echo (!empty($data['cost_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['cost']; ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">₺</span>
                                                </div>
                                            </div>
                                            <span class="invalid-feedback"><?php echo $data['cost_err']; ?></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Servis Sağlayıcı -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_provider">Servis Sağlayıcı: <small class="text-muted">(Opsiyonel)</small></label>
                                            <input type="text" name="service_provider" id="service_provider" class="form-control" value="<?php echo $data['service_provider']; ?>" placeholder="Örn: Yetkili Servis / Özel Servis">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sonraki Bakım Bilgileri -->
                        <div class="card border-left-warning mb-4">
                            <div class="card-body">
                                <h5 class="text-primary font-weight-bold mb-3">Sonraki Bakım Bilgileri</h5>
                                <div class="row">
                                    <!-- Sonraki Bakım Tarihi -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="next_maintenance_date">Sonraki Bakım Tarihi: <small class="text-muted">(Opsiyonel)</small></label>
                                            <input type="date" name="next_maintenance_date" id="next_maintenance_date" class="form-control" value="<?php echo $data['next_maintenance_date']; ?>">
                                            <small class="form-text text-muted">Bir sonraki bakım için planlanan tarihi belirtin.</small>
                                        </div>
                                    </div>
                                    
                                    <!-- Sonraki Bakım KM -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="next_maintenance_km">Sonraki Bakım KM: <small class="text-muted">(Opsiyonel)</small></label>
                                            <input type="number" name="next_maintenance_km" id="next_maintenance_km" class="form-control" value="<?php echo $data['next_maintenance_km']; ?>" min="0">
                                            <small class="form-text text-muted">Bir sonraki bakım için kilometre sınırını belirtin.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notlar -->
                        <div class="card border-left-secondary mb-4">
                            <div class="card-body">
                                <h5 class="text-primary font-weight-bold mb-3">Ek Notlar</h5>
                                <div class="form-group mb-0">
                                    <label for="notes">Notlar:</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Bakımla ilgili ek notlar..."><?php echo $data['notes']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Butonları -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $data['id']; ?>" class="btn btn-secondary btn-block btn-lg">
                                    <i class="fas fa-times mr-1"></i> İptal
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-success btn-block btn-lg">
                                    <i class="fas fa-save mr-1"></i> Kaydet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Durum değiştiyse, bitiş tarihini otomatik olarak ayarla
        const statusSelect = document.getElementById('status');
        const endDateInput = document.getElementById('end_date');
        
        statusSelect.addEventListener('change', function() {
            if(this.value === 'Tamamlandı' && endDateInput.value === '') {
                // Bugünün tarihini YYYY-MM-DD formatında al
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                const todayFormatted = `${yyyy}-${mm}-${dd}`;
                
                endDateInput.value = todayFormatted;
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 