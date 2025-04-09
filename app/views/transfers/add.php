<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/transfers" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-exchange-alt mr-2"></i>Yeni Yakıt Transferi Ekle</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/transfers/add" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="source_tank_id">Kaynak Tank <sup>*</sup></label>
                        <select name="source_tank_id" class="form-control <?php echo (!empty($data['source_tank_id_err'])) ? 'is-invalid' : ''; ?>" id="source_tank">
                            <option value="">-- Kaynak Tank Seçin --</option>
                            <?php foreach($data['tanks'] as $tank) : ?>
                                <option value="<?php echo $tank->id; ?>" <?php echo ($data['source_tank_id'] == $tank->id) ? 'selected' : ''; ?>>
                                    <?php echo $tank->name . ' (' . number_format($tank->current_amount, 2) . ' Lt)'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['source_tank_id_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="destination_tank_id">Hedef Tank <sup>*</sup></label>
                        <select name="destination_tank_id" class="form-control <?php echo (!empty($data['destination_tank_id_err'])) ? 'is-invalid' : ''; ?>" id="destination_tank">
                            <option value="">-- Hedef Tank Seçin --</option>
                            <?php foreach($data['tanks'] as $tank) : ?>
                                <option value="<?php echo $tank->id; ?>" <?php echo ($data['destination_tank_id'] == $tank->id) ? 'selected' : ''; ?>>
                                    <?php echo $tank->name . ' (' . number_format($tank->current_amount, 2) . ' Lt)'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['destination_tank_id_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fuel_type">Yakıt Tipi <sup>*</sup></label>
                        <select name="fuel_type" class="form-control <?php echo (!empty($data['fuel_type_err'])) ? 'is-invalid' : ''; ?>" id="fuel_type">
                            <option value="">-- Yakıt Tipi Seçin --</option>
                            <option value="Benzin" <?php echo ($data['fuel_type'] == 'Benzin') ? 'selected' : ''; ?>>Benzin</option>
                            <option value="Dizel" <?php echo ($data['fuel_type'] == 'Dizel') ? 'selected' : ''; ?>>Dizel</option>
                            <option value="LPG" <?php echo ($data['fuel_type'] == 'LPG') ? 'selected' : ''; ?>>LPG</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['fuel_type_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="amount">Transfer Miktarı (Lt) <sup>*</sup></label>
                        <input type="number" step="0.01" name="amount" class="form-control <?php echo (!empty($data['amount_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['amount']; ?>">
                        <span class="invalid-feedback"><?php echo $data['amount_err']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date">Transfer Tarihi <sup>*</sup></label>
                        <input type="date" name="date" class="form-control <?php echo (!empty($data['date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['date_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="notes">Notlar</label>
                        <textarea name="notes" class="form-control" rows="1"><?php echo $data['notes']; ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <input type="submit" value="Transfer Ekle" class="btn btn-primary">
                    <a href="<?php echo URLROOT; ?>/transfers" class="btn btn-secondary">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('source_tank').addEventListener('change', function() {
    var sourceTank = this.value;
    var destinationTank = document.getElementById('destination_tank');
    
    // Hedef tank seçeneklerini güncelle
    for(var i = 0; i < destinationTank.options.length; i++) {
        if(destinationTank.options[i].value === sourceTank) {
            destinationTank.options[i].disabled = true;
        } else {
            destinationTank.options[i].disabled = false;
        }
    }
    
    // Eğer seçili hedef tank kaynak tank ile aynıysa, seçimi kaldır
    if(destinationTank.value === sourceTank) {
        destinationTank.value = '';
    }
});

document.getElementById('destination_tank').addEventListener('change', function() {
    var destinationTank = this.value;
    var sourceTank = document.getElementById('source_tank');
    
    // Kaynak tank seçeneklerini güncelle
    for(var i = 0; i < sourceTank.options.length; i++) {
        if(sourceTank.options[i].value === destinationTank) {
            sourceTank.options[i].disabled = true;
        } else {
            sourceTank.options[i].disabled = false;
        }
    }
    
    // Eğer seçili kaynak tank hedef tank ile aynıysa, seçimi kaldır
    if(sourceTank.value === destinationTank) {
        sourceTank.value = '';
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 