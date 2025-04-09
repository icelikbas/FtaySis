<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2><i class="fas fa-car"></i> Hızlı Araç Ekleme</h2>
            <p>Yeni bir araç eklemek için formu doldurun</p>
            <form action="<?php echo URLROOT; ?>/entries/quickVehicle" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Plaka -->
                        <div class="form-group mb-3">
                            <label for="plate_number">Plaka Numarası: <sup>*</sup></label>
                            <input type="text" name="plate_number" class="form-control form-control-lg <?php echo (!empty($data['plate_number_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['plate_number']; ?>" placeholder="34ABC123" maxlength="15">
                            <span class="invalid-feedback"><?php echo $data['plate_number_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Yıl -->
                        <div class="form-group mb-3">
                            <label for="year">Üretim Yılı: <sup>*</sup></label>
                            <input type="number" name="year" class="form-control form-control-lg <?php echo (!empty($data['year_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['year']; ?>" min="1900" max="<?php echo date('Y') + 1; ?>">
                            <span class="invalid-feedback"><?php echo $data['year_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Marka -->
                        <div class="form-group mb-3">
                            <label for="brand">Marka: <sup>*</sup></label>
                            <input type="text" name="brand" class="form-control form-control-lg <?php echo (!empty($data['brand_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['brand']; ?>" placeholder="Mercedes, Volvo, Renault...">
                            <span class="invalid-feedback"><?php echo $data['brand_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Model -->
                        <div class="form-group mb-3">
                            <label for="model">Model: <sup>*</sup></label>
                            <input type="text" name="model" class="form-control form-control-lg <?php echo (!empty($data['model_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['model']; ?>" placeholder="Actros, FH16, T High...">
                            <span class="invalid-feedback"><?php echo $data['model_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Araç Tipi -->
                        <div class="form-group mb-3">
                            <label for="vehicle_type">Araç Tipi: <sup>*</sup></label>
                            <select name="vehicle_type" class="form-control form-control-lg <?php echo (!empty($data['vehicle_type_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="" <?php echo empty($data['vehicle_type']) ? 'selected' : ''; ?>>Seçiniz...</option>
                                <option value="Tır" <?php echo $data['vehicle_type'] == 'Tır' ? 'selected' : ''; ?>>Tır</option>
                                <option value="Kamyon" <?php echo $data['vehicle_type'] == 'Kamyon' ? 'selected' : ''; ?>>Kamyon</option>
                                <option value="Tanker" <?php echo $data['vehicle_type'] == 'Tanker' ? 'selected' : ''; ?>>Tanker</option>
                                <option value="Minibüs" <?php echo $data['vehicle_type'] == 'Minibüs' ? 'selected' : ''; ?>>Minibüs</option>
                                <option value="Otobüs" <?php echo $data['vehicle_type'] == 'Otobüs' ? 'selected' : ''; ?>>Otobüs</option>
                                <option value="Binek" <?php echo $data['vehicle_type'] == 'Binek' ? 'selected' : ''; ?>>Binek</option>
                                <option value="Diğer" <?php echo $data['vehicle_type'] == 'Diğer' ? 'selected' : ''; ?>>Diğer</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['vehicle_type_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Durum -->
                        <div class="form-group mb-3">
                            <label for="status">Durum: <sup>*</sup></label>
                            <select name="status" class="form-control form-control-lg <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="Aktif" <?php echo $data['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                                <option value="Pasif" <?php echo $data['status'] == 'Pasif' ? 'selected' : ''; ?>>Pasif</option>
                                <option value="Bakımda" <?php echo $data['status'] == 'Bakımda' ? 'selected' : ''; ?>>Bakımda</option>
                                <option value="Serviste" <?php echo $data['status'] == 'Serviste' ? 'selected' : ''; ?>>Serviste</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['status_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/entries" class="btn btn-light btn-lg"><i class="fas fa-arrow-left"></i> Geri</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-lg float-end"><i class="fas fa-save"></i> Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 