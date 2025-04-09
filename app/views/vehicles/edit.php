<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="mb-4 mt-3 px-3 py-2 bg-light rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mt-2 mb-0 display-6 fw-bold text-primary">
                    <i class="fas fa-edit text-primary me-2"></i>Araç Düzenle
                </h1>
                <p class="lead mb-2 text-muted"><?php echo $data['plate_number']; ?> plakalı aracın bilgilerini düzenliyorsunuz</p>
            </div>
            <div class="text-end">
                <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Araç Listesine Dön
                </a>
            </div>
        </div>
    </div>
    
    <form action="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['id']; ?>" method="post">
        <!-- Temel Bilgiler -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Temel Araç Bilgileri</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 mb-3">
                        <label for="plate_number" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-id-card me-1"></i> Plaka <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="plate_number" class="form-control form-control-lg border border-primary <?php echo (!empty($data['plate_number_err'])) ? 'is-invalid' : ''; ?>" id="plate_number" value="<?php echo $data['plate_number']; ?>">
                        <div class="invalid-feedback"><?php echo $data['plate_number_err']; ?></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="brand" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-tag me-1"></i> Marka <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="brand" class="form-control form-control-lg border border-primary <?php echo (!empty($data['brand_err'])) ? 'is-invalid' : ''; ?>" id="brand" value="<?php echo $data['brand']; ?>">
                        <div class="invalid-feedback"><?php echo $data['brand_err']; ?></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="model" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-car me-1"></i> Model <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="model" class="form-control form-control-lg border border-primary <?php echo (!empty($data['model_err'])) ? 'is-invalid' : ''; ?>" id="model" value="<?php echo $data['model']; ?>">
                        <div class="invalid-feedback"><?php echo $data['model_err']; ?></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="year" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-calendar me-1"></i> Yıl <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="year" class="form-control form-control-lg border border-primary <?php echo (!empty($data['year_err'])) ? 'is-invalid' : ''; ?>" id="year" value="<?php echo $data['year']; ?>">
                        <div class="invalid-feedback"><?php echo $data['year_err']; ?></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vehicle_type" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-truck me-1"></i> Araç Tipi <span class="text-danger">*</span>
                        </label>
                        <select name="vehicle_type" class="form-select form-select-lg border border-primary <?php echo (!empty($data['vehicle_type_err'])) ? 'is-invalid' : ''; ?>" id="vehicle_type">
                            <option value="" <?php echo ($data['vehicle_type'] == '') ? 'selected' : ''; ?>>-- Araç Tipi Seçin --</option>
                            <?php foreach($data['vehicleTypesList'] as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo ($data['vehicle_type'] == $type) ? 'selected' : ''; ?>><?php echo $type; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?php echo $data['vehicle_type_err']; ?></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-toggle-on me-1"></i> Durum <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select form-select-lg border border-primary <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>" id="status">
                            <option value="Aktif" <?php echo ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Pasif" <?php echo ($data['status'] == 'Pasif') ? 'selected' : ''; ?>>Pasif</option>
                            <option value="Bakımda" <?php echo ($data['status'] == 'Bakımda') ? 'selected' : ''; ?>>Bakımda</option>
                            <option value="Arızalı" <?php echo ($data['status'] == 'Arızalı') ? 'selected' : ''; ?>>Arızalı</option>
                        </select>
                        <div class="invalid-feedback"><?php echo $data['status_err']; ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Kurumsal Bilgiler -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-building me-1"></i>
                <strong>Kurumsal Bilgiler</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 mb-3">
                        <label for="company_id" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-building me-1"></i> Şirket
                        </label>
                        <select name="company_id" class="form-select form-select-lg border <?php echo (!empty($data['company_id_err'])) ? 'is-invalid' : ''; ?>" id="company_id">
                            <option value="">-- Şirket Seçin --</option>
                            <?php if(isset($data['companies'])): ?>
                                <?php foreach($data['companies'] as $company): ?>
                                    <option value="<?php echo $company->id; ?>" <?php echo ($data['company_id'] == $company->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($company->company_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback"><?php echo $data['company_id_err'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="order_number" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-hashtag me-1"></i> Sipariş Numarası
                        </label>
                        <input type="text" name="order_number" class="form-control form-control-lg" id="order_number" value="<?php echo $data['order_number'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="equipment_number" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-tools me-1"></i> Ekipman Numarası
                        </label>
                        <input type="text" name="equipment_number" class="form-control form-control-lg" id="equipment_number" value="<?php echo $data['equipment_number'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fixed_asset_number" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-barcode me-1"></i> Sabit Kıymet No
                        </label>
                        <input type="text" name="fixed_asset_number" class="form-control form-control-lg" id="fixed_asset_number" value="<?php echo $data['fixed_asset_number'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cost_center" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-money-bill-alt me-1"></i> Maliyet Merkezi
                        </label>
                        <input type="text" name="cost_center" class="form-control form-control-lg" id="cost_center" value="<?php echo $data['cost_center'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="production_site" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-industry me-1"></i> Üretim Sahası
                        </label>
                        <input type="text" name="production_site" class="form-control form-control-lg" id="production_site" value="<?php echo $data['production_site'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="work_site" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-hard-hat me-1"></i> Çalışma Sahası
                        </label>
                        <input type="text" name="work_site" class="form-control form-control-lg" id="work_site" value="<?php echo $data['work_site'] ?? ''; ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sigorta ve Muayene Bilgileri -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-calendar-check me-1"></i>
                <strong>Sigorta ve Muayene Bilgileri</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 mb-3">
                        <label for="inspection_date" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-car-crash me-1"></i> Muayene Tarihi
                        </label>
                        <input type="date" name="inspection_date" class="form-control form-control-lg" id="inspection_date" value="<?php echo $data['inspection_date'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="traffic_insurance_agency" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-file-contract me-1"></i> Trafik Sigortası Acentesi
                        </label>
                        <input type="text" name="traffic_insurance_agency" class="form-control form-control-lg" id="traffic_insurance_agency" value="<?php echo $data['traffic_insurance_agency'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="traffic_insurance_date" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-calendar-alt me-1"></i> Trafik Sigortası Tarihi
                        </label>
                        <input type="date" name="traffic_insurance_date" class="form-control form-control-lg" id="traffic_insurance_date" value="<?php echo $data['traffic_insurance_date'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="casco_insurance_agency" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-shield-alt me-1"></i> Kasko Sigortası Acentesi
                        </label>
                        <input type="text" name="casco_insurance_agency" class="form-control form-control-lg" id="casco_insurance_agency" value="<?php echo $data['casco_insurance_agency'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="casco_insurance_date" class="form-label fw-bold text-primary mb-1">
                            <i class="fas fa-calendar-alt me-1"></i> Kasko Sigortası Tarihi
                        </label>
                        <input type="date" name="casco_insurance_date" class="form-control form-control-lg" id="casco_insurance_date" value="<?php echo $data['casco_insurance_date'] ?? ''; ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- İşlem Butonları -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <button type="submit" class="btn btn-success btn-lg px-5 me-2">
                    <i class="fas fa-save me-2"></i>Değişiklikleri Kaydet
                </button>
                <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-secondary btn-lg px-5">
                    <i class="fas fa-times me-2"></i>İptal
                </a>
            </div>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 