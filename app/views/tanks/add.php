<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/tanks" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-gas-pump mr-2"></i>Yeni Yakıt Tankı Ekle</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/tanks/add" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Tank Adı <sup>*</sup></label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                        <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">Tank Tipi <sup>*</sup></label>
                        <select name="type" class="form-control <?php echo (!empty($data['type_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="" <?php echo ($data['type'] == '') ? 'selected' : ''; ?>>-- Tank Tipi Seçin --</option>
                            <option value="Sabit" <?php echo ($data['type'] == 'Sabit') ? 'selected' : ''; ?>>Sabit</option>
                            <option value="Mobil" <?php echo ($data['type'] == 'Mobil') ? 'selected' : ''; ?>>Mobil</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['type_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="capacity">Kapasite (Lt) <sup>*</sup></label>
                        <input type="number" step="0.01" name="capacity" class="form-control <?php echo (!empty($data['capacity_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['capacity']; ?>">
                        <span class="invalid-feedback"><?php echo $data['capacity_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="current_amount">Mevcut Miktar (Lt)</label>
                        <input type="number" step="0.01" name="current_amount" class="form-control <?php echo (!empty($data['current_amount_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['current_amount']; ?>">
                        <span class="invalid-feedback"><?php echo $data['current_amount_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="location">Lokasyon</label>
                        <input type="text" name="location" class="form-control" value="<?php echo $data['location']; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fuel_type">Yakıt Tipi <sup>*</sup></label>
                        <select name="fuel_type" class="form-control <?php echo (!empty($data['fuel_type_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="" <?php echo ($data['fuel_type'] == '') ? 'selected' : ''; ?>>-- Yakıt Tipi Seçin --</option>
                            <option value="Benzin" <?php echo ($data['fuel_type'] == 'Benzin') ? 'selected' : ''; ?>>Benzin</option>
                            <option value="Dizel" <?php echo ($data['fuel_type'] == 'Dizel') ? 'selected' : ''; ?>>Dizel</option>
                            <option value="LPG" <?php echo ($data['fuel_type'] == 'LPG') ? 'selected' : ''; ?>>LPG</option>
                            <option value="Elektrik" <?php echo ($data['fuel_type'] == 'Elektrik') ? 'selected' : ''; ?>>Elektrik</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['fuel_type_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Durum <sup>*</sup></label>
                        <select name="status" class="form-control">
                            <option value="Aktif" <?php echo ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Pasif" <?php echo ($data['status'] == 'Pasif') ? 'selected' : ''; ?>>Pasif</option>
                            <option value="Bakımda" <?php echo ($data['status'] == 'Bakımda') ? 'selected' : ''; ?>>Bakımda</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <input type="submit" value="Tank Ekle" class="btn btn-primary">
                    <a href="<?php echo URLROOT; ?>/tanks" class="btn btn-secondary">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 