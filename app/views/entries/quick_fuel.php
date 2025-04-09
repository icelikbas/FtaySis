<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2><i class="fas fa-gas-pump"></i> Hızlı Yakıt Kaydı</h2>
            <p>Yeni bir yakıt alım kaydı eklemek için formu doldurun</p>
            <form action="<?php echo URLROOT; ?>/entries/quickFuel" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Araç -->
                        <div class="form-group mb-3">
                            <label for="vehicle_id">Araç: <sup>*</sup></label>
                            <select name="vehicle_id" class="form-control form-control-lg <?php echo (!empty($data['vehicle_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Araç Seçiniz</option>
                                <?php foreach($data['vehicles'] as $vehicle) : ?>
                                    <option value="<?php echo $vehicle->id; ?>" <?php echo $data['vehicle_id'] == $vehicle->id ? 'selected' : ''; ?>>
                                        <?php echo $vehicle->plate_number . ' - ' . $vehicle->brand . ' ' . $vehicle->model; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['vehicle_id_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Sürücü -->
                        <div class="form-group mb-3">
                            <label for="driver_id">Sürücü:</label>
                            <select name="driver_id" class="form-control form-control-lg">
                                <option value="">Sürücü Seçiniz (Opsiyonel)</option>
                                <?php foreach($data['drivers'] as $driver) : ?>
                                    <option value="<?php echo $driver->id; ?>" <?php echo $data['driver_id'] == $driver->id ? 'selected' : ''; ?>>
                                        <?php echo $driver->name . ' ' . $driver->surname; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Yakıt Tipi -->
                        <div class="form-group mb-3">
                            <label for="fuel_type">Yakıt Tipi: <sup>*</sup></label>
                            <select name="fuel_type" class="form-control form-control-lg <?php echo (!empty($data['fuel_type_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="" <?php echo empty($data['fuel_type']) ? 'selected' : ''; ?>>Seçiniz...</option>
                                <option value="Dizel" <?php echo $data['fuel_type'] == 'Dizel' ? 'selected' : ''; ?>>Dizel</option>
                                <option value="Benzin" <?php echo $data['fuel_type'] == 'Benzin' ? 'selected' : ''; ?>>Benzin</option>
                                <option value="LPG" <?php echo $data['fuel_type'] == 'LPG' ? 'selected' : ''; ?>>LPG</option>
                                <option value="Elektrik" <?php echo $data['fuel_type'] == 'Elektrik' ? 'selected' : ''; ?>>Elektrik</option>
                                <option value="Euro Dizel" <?php echo $data['fuel_type'] == 'Euro Dizel' ? 'selected' : ''; ?>>Euro Dizel</option>
                                <option value="Premium Benzin" <?php echo $data['fuel_type'] == 'Premium Benzin' ? 'selected' : ''; ?>>Premium Benzin</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['fuel_type_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Kilometre Bilgisi -->
                        <div class="form-group mb-3">
                            <label for="km_reading">Kilometre: <sup>*</sup></label>
                            <input type="number" name="km_reading" class="form-control form-control-lg <?php echo (!empty($data['km_reading_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['km_reading']; ?>" placeholder="Örn: 125000">
                            <span class="invalid-feedback"><?php echo $data['km_reading_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Miktar -->
                        <div class="form-group mb-3">
                            <label for="amount">Miktar (Litre): <sup>*</sup></label>
                            <input type="text" name="amount" class="form-control form-control-lg <?php echo (!empty($data['amount_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['amount']; ?>" placeholder="Örn: 45.5">
                            <span class="invalid-feedback"><?php echo $data['amount_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Maliyet -->
                        <div class="form-group mb-3">
                            <label for="cost">Toplam Maliyet (TL): <sup>*</sup></label>
                            <input type="text" name="cost" class="form-control form-control-lg <?php echo (!empty($data['cost_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['cost']; ?>" placeholder="Örn: 750.25">
                            <span class="invalid-feedback"><?php echo $data['cost_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Tarih -->
                        <div class="form-group mb-3">
                            <label for="date">Tarih: <sup>*</sup></label>
                            <input type="date" name="date" class="form-control form-control-lg <?php echo (!empty($data['date_err'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo $data['date']; ?>">
                            <span class="invalid-feedback"><?php echo $data['date_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <!-- Notlar -->
                        <div class="form-group mb-3">
                            <label for="notes">Notlar:</label>
                            <textarea name="notes" class="form-control form-control-lg" rows="3"><?php echo $data['notes']; ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/entries" class="btn btn-light btn-lg"><i class="fas fa-arrow-left"></i> Geri</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-danger btn-lg float-end"><i class="fas fa-save"></i> Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 