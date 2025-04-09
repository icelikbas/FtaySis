<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2><i class="fas fa-id-card"></i> Hızlı Sürücü Ekleme</h2>
            <p>Yeni bir sürücü eklemek için formu doldurun</p>
            <form action="<?php echo URLROOT; ?>/entries/quickDriver" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <!-- İsim -->
                        <div class="form-group mb-3">
                            <label for="name">İsim: <sup>*</sup></label>
                            <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['name']; ?>">
                            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Soyisim -->
                        <div class="form-group mb-3">
                            <label for="surname">Soyisim: <sup>*</sup></label>
                            <input type="text" name="surname" class="form-control form-control-lg <?php echo (!empty($data['surname_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['surname']; ?>">
                            <span class="invalid-feedback"><?php echo $data['surname_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- TC Kimlik No -->
                        <div class="form-group mb-3">
                            <label for="identity_number">TC Kimlik No: <sup>*</sup></label>
                            <input type="text" name="identity_number" class="form-control form-control-lg <?php echo (!empty($data['identity_number_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['identity_number']; ?>" maxlength="11">
                            <span class="invalid-feedback"><?php echo $data['identity_number_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Telefon -->
                        <div class="form-group mb-3">
                            <label for="phone">Telefon: <sup>*</sup></label>
                            <input type="text" name="phone" class="form-control form-control-lg <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['phone']; ?>" placeholder="05XX XXX XX XX">
                            <span class="invalid-feedback"><?php echo $data['phone_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Ehliyet No -->
                        <div class="form-group mb-3">
                            <label for="license_number">Ehliyet No: <sup>*</sup></label>
                            <input type="text" name="license_number" class="form-control form-control-lg <?php echo (!empty($data['license_number_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['license_number']; ?>">
                            <span class="invalid-feedback"><?php echo $data['license_number_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Ehliyet Sınıfı -->
                        <div class="form-group mb-3">
                            <label for="license_type">Ehliyet Sınıfı: <sup>*</sup></label>
                            <select name="license_type" class="form-control form-control-lg <?php echo (!empty($data['license_type_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="" <?php echo empty($data['license_type']) ? 'selected' : ''; ?>>Seçiniz...</option>
                                <option value="B" <?php echo $data['license_type'] == 'B' ? 'selected' : ''; ?>>B</option>
                                <option value="C" <?php echo $data['license_type'] == 'C' ? 'selected' : ''; ?>>C</option>
                                <option value="D" <?php echo $data['license_type'] == 'D' ? 'selected' : ''; ?>>D</option>
                                <option value="E" <?php echo $data['license_type'] == 'E' ? 'selected' : ''; ?>>E</option>
                                <option value="F" <?php echo $data['license_type'] == 'F' ? 'selected' : ''; ?>>F</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['license_type_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Durum -->
                        <div class="form-group mb-3">
                            <label for="status">Durum: <sup>*</sup></label>
                            <select name="status" class="form-control form-control-lg <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="Aktif" <?php echo $data['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                                <option value="Pasif" <?php echo $data['status'] == 'Pasif' ? 'selected' : ''; ?>>Pasif</option>
                                <option value="İzinli" <?php echo $data['status'] == 'İzinli' ? 'selected' : ''; ?>>İzinli</option>
                                <option value="Rapor" <?php echo $data['status'] == 'Rapor' ? 'selected' : ''; ?>>Rapor</option>
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
                        <button type="submit" class="btn btn-success btn-lg float-end"><i class="fas fa-save"></i> Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 