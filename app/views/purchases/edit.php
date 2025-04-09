<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/purchases" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-gas-pump mr-2"></i>Yakıt Alımı Düzenle</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/purchases/edit/<?php echo $data['id']; ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="supplier_name">Tedarikçi Adı <sup>*</sup></label>
                        <input type="text" name="supplier_name" class="form-control <?php echo (!empty($data['supplier_name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['supplier_name']; ?>">
                        <span class="invalid-feedback"><?php echo $data['supplier_name_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="tank_id">Yakıt Tankı <sup>*</sup></label>
                        <select name="tank_id" id="tank_id" class="form-control <?php echo (!empty($data['tank_id_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="">-- Tank Seçin --</option>
                            <?php foreach($data['tanks'] as $tank) : ?>
                                <option value="<?php echo $tank->id; ?>" <?php echo ($data['tank_id'] == $tank->id) ? 'selected' : ''; ?> data-capacity="<?php echo $tank->capacity; ?>" data-current="<?php echo $tank->current_amount; ?>" data-fuel-type="<?php echo $tank->fuel_type ?? ''; ?>">
                                    <?php echo $tank->name . ' (' . number_format($tank->current_amount, 2) . '/' . number_format($tank->capacity, 2) . ' Lt)'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['tank_id_err']; ?></span>
                        <small id="tankInfo" class="form-text text-muted"></small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="fuel_type">Yakıt Tipi <sup>*</sup></label>
                        <select name="fuel_type" id="fuel_type" class="form-control <?php echo (!empty($data['fuel_type_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="">-- Yakıt Tipi Seçin --</option>
                            <option value="Benzin" <?php echo ($data['fuel_type'] == 'Benzin') ? 'selected' : ''; ?>>Benzin</option>
                            <option value="Dizel" <?php echo ($data['fuel_type'] == 'Dizel') ? 'selected' : ''; ?>>Dizel</option>
                            <option value="LPG" <?php echo ($data['fuel_type'] == 'LPG') ? 'selected' : ''; ?>>LPG</option>
                            <option value="Elektrik" <?php echo ($data['fuel_type'] == 'Elektrik') ? 'selected' : ''; ?>>Elektrik</option>
                            <option value="Euro Dizel" <?php echo ($data['fuel_type'] == 'Euro Dizel') ? 'selected' : ''; ?>>Euro Dizel</option>
                            <option value="Premium Benzin" <?php echo ($data['fuel_type'] == 'Premium Benzin') ? 'selected' : ''; ?>>Premium Benzin</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['fuel_type_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="date">Alım Tarihi <sup>*</sup></label>
                        <input type="date" name="date" class="form-control <?php echo (!empty($data['date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['date_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="amount">Miktar (Lt) <sup>*</sup></label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control <?php echo (!empty($data['amount_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['amount']; ?>">
                        <span class="invalid-feedback"><?php echo $data['amount_err']; ?></span>
                        <small id="amountHelp" class="form-text text-muted"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="cost">Toplam Maliyet (TL) <sup>*</sup></label>
                        <input type="number" step="0.01" name="cost" id="cost" class="form-control <?php echo (!empty($data['cost_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['cost']; ?>">
                        <span class="invalid-feedback"><?php echo $data['cost_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="unit_price">Birim Fiyat (TL/Lt) <sup>*</sup></label>
                        <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control <?php echo (!empty($data['unit_price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['unit_price']; ?>">
                        <span class="invalid-feedback"><?php echo $data['unit_price_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="invoice_number">Fatura Numarası</label>
                        <input type="text" name="invoice_number" class="form-control" value="<?php echo $data['invoice_number']; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="notes">Notlar</label>
                        <textarea name="notes" class="form-control" rows="3"><?php echo $data['notes']; ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Uyarı: Yakıt alımı bilgilerini değiştirmeniz, ilgili tankın mevcut yakıt miktarını etkileyecektir.
            </div>
            
            <div class="row">
                <div class="col">
                    <input type="submit" value="Yakıt Alımı Güncelle" class="btn btn-warning">
                    <a href="<?php echo URLROOT; ?>/purchases" class="btn btn-secondary">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Sayfa yüklendiğinde tank bilgilerini göster
    window.addEventListener('DOMContentLoaded', (event) => {
        updateTankInfo();
    });
    
    // Tank seçildiğinde bilgi göster
    document.getElementById('tank_id').addEventListener('change', function() {
        updateTankInfo();
    });
    
    function updateTankInfo() {
        const tankSelect = document.getElementById('tank_id');
        const selectedOption = tankSelect.options[tankSelect.selectedIndex];
        const tankInfo = document.getElementById('tankInfo');
        
        if(tankSelect.value) {
            const capacity = selectedOption.dataset.capacity || 0;
            const current = selectedOption.dataset.current || 0;
            const fuelType = selectedOption.dataset.fuelType || '';
            const available = capacity - current;
            
            tankInfo.innerHTML = `Seçilen tank kapasitesi: <strong>${parseFloat(capacity).toFixed(2)} Lt</strong>, 
                                  Mevcut miktar: <strong>${parseFloat(current).toFixed(2)} Lt</strong>, 
                                  Kalan kapasite: <strong>${parseFloat(available).toFixed(2)} Lt</strong>`;
            
            // Yakıt tipini otomatik seç - yeni tank seçildiğinde
            if(fuelType && !document.getElementById('fuel_type').value) {
                const fuelTypeSelect = document.getElementById('fuel_type');
                for(let i = 0; i < fuelTypeSelect.options.length; i++) {
                    if(fuelTypeSelect.options[i].value === fuelType) {
                        fuelTypeSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        } else {
            tankInfo.innerHTML = '';
        }
    }
    
    // Miktar ve maliyet değiştiğinde birim fiyatı hesapla
    function calculateUnitPrice() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const cost = parseFloat(document.getElementById('cost').value) || 0;
        const unitPriceField = document.getElementById('unit_price');
        
        if(amount > 0 && cost > 0) {
            const unitPrice = cost / amount;
            unitPriceField.value = unitPrice.toFixed(2);
        }
    }
    
    document.getElementById('amount').addEventListener('input', calculateUnitPrice);
    document.getElementById('cost').addEventListener('input', calculateUnitPrice);
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 