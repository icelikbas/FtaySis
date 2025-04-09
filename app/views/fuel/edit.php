<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-gas-pump"></i> Yakıt Kaydı Düzenle</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/fuel" class="btn btn-light float-right">
            <i class="fa fa-backward"></i> Geri
        </a>
    </div>
</div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light">
    <form action="<?php echo URLROOT; ?>/fuel/edit/<?php echo $data['id']; ?>" method="post">
                <!-- Araç ve Sürücü Bilgileri -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-car"></i> Araç ve Sürücü Bilgileri</h5>
                    </div>
            <div class="col-md-6">
                <div class="form-group">
                            <label for="vehicle_id">Araç <sup class="text-danger">*</sup></label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control <?php echo (!empty($data['vehicle_id_err'])) ? 'is-invalid' : ''; ?>">
                        <option value="">Araç Seçin</option>
                        <?php foreach($data['vehicles'] as $vehicle) : ?>
                            <option value="<?php echo $vehicle->id; ?>" <?php echo ($data['vehicle_id'] == $vehicle->id) ? 'selected' : ''; ?>>
                                        <?php echo $vehicle->vehicle_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data['vehicle_id_err']; ?></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                            <label for="driver_id">Sürücü</label>
                            <select name="driver_id" id="driver_id" class="form-control <?php echo (!empty($data['driver_id_err'])) ? 'is-invalid' : ''; ?>">
                        <option value="">Sürücü Seçin</option>
                        <?php foreach($data['drivers'] as $driver) : ?>
                            <option value="<?php echo $driver->id; ?>" <?php echo ($data['driver_id'] == $driver->id) ? 'selected' : ''; ?>>
                                <?php echo $driver->full_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                            <span class="invalid-feedback"><?php echo $data['driver_id_err']; ?></span>
                </div>
            </div>
        </div>
        
                <!-- Yakıt Bilgileri -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-gas-pump"></i> Yakıt Bilgileri</h5>
                    </div>
            <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tank_id">Yakıt Tankı <sup class="text-danger">*</sup></label>
                            <select name="tank_id" id="tank_id" class="form-control <?php echo (!empty($data['tank_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Tank Seçin</option>
                                <?php foreach($data['tanks'] as $tank) : ?>
                                    <option value="<?php echo $tank->id; ?>" <?php echo ($data['tank_id'] == $tank->id) ? 'selected' : ''; ?> 
                                            data-amount="<?php echo $tank->current_amount; ?>" data-type="<?php echo $tank->type; ?>"
                                            data-fuel-type="<?php echo $tank->fuel_type; ?>">
                                        <?php echo $tank->name; ?> (<?php echo $tank->current_amount; ?> lt - <?php echo $tank->fuel_type; ?>)
                                    </option>
                                <?php endforeach; ?>
                    </select>
                            <span class="invalid-feedback"><?php echo $data['tank_id_err']; ?></span>
                            <small id="tankInfo" class="form-text text-muted"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="fuel_type">Yakıt Türü <sup class="text-danger">*</sup></label>
                            <input type="text" id="fuel_type_display" class="form-control" value="<?php echo $data['fuel_type']; ?>" readonly disabled>
                            <input type="hidden" name="fuel_type" id="fuel_type" value="<?php echo $data['fuel_type']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="amount">Miktar (Litre) <sup class="text-danger">*</sup></label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control <?php echo (!empty($data['amount_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['amount']; ?>" placeholder="0.00">
                            <span class="invalid-feedback"><?php echo $data['amount_err']; ?></span>
                            <small id="amountHelp" class="form-text text-muted"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="cost">Tutar (TL) <small><i>(opsiyonel)</i></small></label>
                            <input type="number" step="0.01" name="cost" id="cost" class="form-control <?php echo (!empty($data['cost_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['cost']; ?>" placeholder="0.00">
                            <span class="invalid-feedback"><?php echo $data['cost_err']; ?></span>
                            <small id="lastUnitPriceInfo" class="form-text text-muted"></small>
                        </div>
                    </div>
                </div>

                <!-- Diğer Bilgiler -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Sayaç Bilgileri</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="km_reading">Kilometre</label>
                            <input type="number" name="km_reading" id="km_reading" class="form-control <?php echo (!empty($data['km_reading_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['km_reading']; ?>" placeholder="0">
                            <span class="invalid-feedback"><?php echo $data['km_reading_err']; ?></span>
                            <small class="form-text text-muted">Kilometre bilgisi olan araçlar için</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="hour_reading">Çalışma Saati</label>
                            <input type="number" step="0.01" name="hour_reading" id="hour_reading" class="form-control <?php echo (!empty($data['hour_reading_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['hour_reading']; ?>" placeholder="0.00">
                            <span class="invalid-feedback"><?php echo $data['hour_reading_err']; ?></span>
                            <small class="form-text text-muted">Saat bilgisi olan araçlar için</small>
                        </div>
                </div>
            </div>
            
                <!-- Tarih ve Notlar -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-calendar-alt"></i> Tarih Bilgileri</h5>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="date">Tarih <sup class="text-danger">*</sup></label>
                    <input type="date" name="date" id="date" class="form-control <?php echo (!empty($data['date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['date']; ?>">
                    <span class="invalid-feedback"><?php echo $data['date_err']; ?></span>
                </div>
            </div>
                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <label for="notes">Notlar</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Ek notlar..."><?php echo $data['notes']; ?></textarea>
                </div>
            </div>
        </div>
        
        <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                </div>
                    <div class="col-md-6">
                        <a href="<?php echo URLROOT; ?>/fuel/show/<?php echo $data['id']; ?>" class="btn btn-light btn-block">
                            <i class="fas fa-times"></i> İptal
                        </a>
            </div>
                </div>
            </form>
                </div>
            </div>
        </div>
        
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Orijinal yakıt miktarı
        const originalAmount = <?php echo $data['amount']; ?>;
        
        // Tank seçildiğinde bilgileri göster
        document.getElementById('tank_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const tankInfo = document.getElementById('tankInfo');
            const amountInput = document.getElementById('amount');
            const amountHelp = document.getElementById('amountHelp');
            const fuelTypeDisplay = document.getElementById('fuel_type_display');
            const fuelTypeInput = document.getElementById('fuel_type');
            
            if (this.value === '') {
                tankInfo.textContent = '';
                amountHelp.textContent = '';
                fuelTypeDisplay.value = '';
                fuelTypeInput.value = '';
            } else {
                const currentAmount = selectedOption.getAttribute('data-amount');
                const tankType = selectedOption.getAttribute('data-type');
                const fuelType = selectedOption.getAttribute('data-fuel-type');
                
                // Eğer aynı tank ise, kullanılabilir miktar = mevcut miktar + orijinal miktar
                let availableAmount = parseFloat(currentAmount);
                if (this.value == <?php echo $data['tank_id']; ?>) {
                    availableAmount += parseFloat(originalAmount);
                }
                
                tankInfo.innerHTML = `<i class="fas fa-info-circle text-info"></i> Seçilen Tank: <strong>${tankType}</strong>, Mevcut Miktar: <strong>${currentAmount} litre</strong>`;
                amountHelp.innerHTML = `<i class="fas fa-exclamation-circle text-warning"></i> Maksimum çekilebilecek miktar: <strong>${availableAmount} litre</strong>`;
                fuelTypeDisplay.value = fuelType;
                fuelTypeInput.value = fuelType;
                
                // Miktar input alanı için maksimum değeri ayarla
                amountInput.max = availableAmount;
                
                // Yakıt türü değişince seçilen yakıt türü için son birim fiyatı al
                getLastFuelUnitPrice(fuelType);
            }
        });
        
        // Yakıt miktarı değiştiğinde tutar hesapla
        document.getElementById('amount').addEventListener('input', function() {
            calculateCost();
        });
        
        // Yakıt türü için son birim fiyatı al
        function getLastFuelUnitPrice(fuelType) {
            if (!fuelType) return;
            
            fetch('<?php echo URLROOT; ?>/fuel/getLastUnitPrice/' + encodeURIComponent(fuelType))
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.unit_price > 0) {
                        window.lastUnitPrice = data.unit_price;
                        document.getElementById('lastUnitPriceInfo').innerHTML = 
                            `<i class="fas fa-info-circle text-info"></i> Son birim fiyat: <strong>${data.unit_price.toFixed(2)} TL/lt</strong>`;
                        
                        // Eğer miktar zaten girilmişse ve tutar değiştirildiyse tutarı hesapla
                        if (!document.getElementById('cost').value || document.getElementById('cost').value == '0') {
                            calculateCost();
                        }
                    } else {
                        window.lastUnitPrice = 0;
                        document.getElementById('lastUnitPriceInfo').innerHTML = '';
                    }
                })
                .catch(error => {
                    console.error('Birim fiyat bilgisi alınırken hata oluştu:', error.message);
                    window.lastUnitPrice = 0;
                    document.getElementById('lastUnitPriceInfo').innerHTML = '';
                });
        }
        
        // Tutar hesaplama fonksiyonu
        function calculateCost() {
            const amountInput = document.getElementById('amount');
            const costInput = document.getElementById('cost');
            
            if (amountInput.value && window.lastUnitPrice > 0) {
                const amount = parseFloat(amountInput.value);
                if (!isNaN(amount) && amount > 0) {
                    // Birim fiyat ile çarparak tutarı hesapla
                    const calculatedCost = amount * window.lastUnitPrice;
                    costInput.value = calculatedCost.toFixed(2);
                }
            }
        }
        
        // Araç seçildiğinde ilgili sürücüyü otomatik seç
        document.getElementById('vehicle_id').addEventListener('change', function() {
            const vehicleId = this.value;
            if (vehicleId) {
                // AJAX ile aracın atanmış sürücüsünü kontrol et
                fetch('<?php echo URLROOT; ?>/assignments/getDriverForVehicle/' + vehicleId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.driver_id) {
                            document.getElementById('driver_id').value = data.driver_id;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                
                // Aracın kilometre ve saat bilgilerini getir
                fetch('<?php echo URLROOT; ?>/vehicles/getLastKm/' + vehicleId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            if (data.last_km) {
                                document.getElementById('km_reading').value = data.last_km;
                            }
                            if (data.last_hour) {
                                document.getElementById('hour_reading').value = data.last_hour;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
        
        // Eğer yakıt miktarı geçerli bir değer ise, tanktaki mevcut miktarla karşılaştır
        document.getElementById('amount').addEventListener('input', function() {
            const tankSelect = document.getElementById('tank_id');
            const selectedOption = tankSelect.options[tankSelect.selectedIndex];
            const amountHelp = document.getElementById('amountHelp');
            
            if (tankSelect.value && this.value) {
                let availableAmount = parseFloat(selectedOption.getAttribute('data-amount'));
                
                // Eğer aynı tank ise, kullanılabilir miktar = mevcut miktar + orijinal miktar
                if (tankSelect.value == <?php echo $data['tank_id']; ?>) {
                    availableAmount += parseFloat(originalAmount);
                }
                
                const enteredAmount = parseFloat(this.value);
                
                if (enteredAmount > availableAmount) {
                    this.classList.add('is-invalid');
                    amountHelp.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Girilen miktar tank kapasitesini aşıyor!</span>`;
                } else {
                    this.classList.remove('is-invalid');
                    amountHelp.innerHTML = `<i class="fas fa-exclamation-circle text-warning"></i> Maksimum çekilebilecek miktar: <strong>${availableAmount} litre</strong>`;
                }
            }
        });
        
        // Sayfa yüklendiğinde tank seçili ise yakıt türünü güncelle
        const tankSelect = document.getElementById('tank_id');
        if (tankSelect.value !== '') {
            tankSelect.dispatchEvent(new Event('change'));
        }
        
        // Sayfa yüklendiğinde araç seçili ise sürücüyü otomatik getir
        const vehicleSelect = document.getElementById('vehicle_id');
        if (vehicleSelect.value !== '') {
            vehicleSelect.dispatchEvent(new Event('change'));
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 