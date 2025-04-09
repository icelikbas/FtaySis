<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-gas-pump"></i> Yakıt Raporları</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="#" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-print"></i> Yazdır
                </a>
                <a href="<?php echo URLROOT; ?>/reports" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Geri
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filtreler -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter"></i> Filtreler
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo URLROOT; ?>/reports/fuel" class="row">
                <div class="col-md-2 mb-3">
                    <label for="vehicle_id">Araç</label>
                    <select class="form-control" id="vehicle_id" name="vehicle_id">
                        <option value="">Tümü</option>
                        <?php foreach($data['vehicles'] as $vehicle) : ?>
                            <option value="<?php echo $vehicle->id; ?>" <?php echo $data['filters']['vehicle_id'] == $vehicle->id ? 'selected' : ''; ?>>
                                <?php echo $vehicle->plate_number; ?> - <?php echo $vehicle->brand; ?> <?php echo $vehicle->model; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="driver_id">Sürücü</label>
                    <select class="form-control" id="driver_id" name="driver_id">
                        <option value="">Tümü</option>
                        <?php foreach($data['drivers'] as $driver) : ?>
                            <option value="<?php echo $driver->id; ?>" <?php echo $data['filters']['driver_id'] == $driver->id ? 'selected' : ''; ?>>
                                <?php echo $driver->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="fuel_type">Yakıt Türü</label>
                    <select class="form-control" id="fuel_type" name="fuel_type">
                        <option value="">Tümü</option>
                        <option value="Benzin" <?php echo $data['filters']['fuel_type'] == 'Benzin' ? 'selected' : ''; ?>>Benzin</option>
                        <option value="Dizel" <?php echo $data['filters']['fuel_type'] == 'Dizel' ? 'selected' : ''; ?>>Dizel</option>
                        <option value="LPG" <?php echo $data['filters']['fuel_type'] == 'LPG' ? 'selected' : ''; ?>>LPG</option>
                        <option value="Elektrik" <?php echo $data['filters']['fuel_type'] == 'Elektrik' ? 'selected' : ''; ?>>Elektrik</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="start_date">Başlangıç Tarihi</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $data['filters']['start_date']; ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="end_date">Bitiş Tarihi</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $data['filters']['end_date']; ?>">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrele</button>
                    <a href="<?php echo URLROOT; ?>/reports/fuel" class="btn btn-secondary ms-2">Sıfırla</a>
                </div>
            </form>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Yakıt (Lt)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($data['fuelStats']->total_fuel) ? number_format($data['fuelStats']->total_fuel, 2, ',', '.') : '0,00'; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gas-pump fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Toplam Tutar (₺)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($data['fuelStats']->total_cost) ? number_format($data['fuelStats']->total_cost, 2, ',', '.') : '0,00'; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-lira-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ortalama Fiyat (₺/Lt)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($data['fuelStats']->avg_price_per_unit) ? number_format($data['fuelStats']->avg_price_per_unit, 2, ',', '.') : '0,00'; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Kayıt Sayısı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($data['fuelStats']->record_count) ? $data['fuelStats']->record_count : '0'; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Yakıt Türü Dağılımı -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie"></i> Yakıt Türü Dağılımı
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <div class="row">
                            <?php foreach($data['fuelTypeDistribution'] as $type) : ?>
                                <div class="col-md-3 mb-4 text-center">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo $type->fuel_type; ?></h6>
                                            <div class="display-4"><?php echo number_format($type->total_liters, 2, ',', '.'); ?> Lt</div>
                                            <div class="text-muted"><?php echo number_format($type->total_cost, 2, ',', '.'); ?> ₺</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Yakıt Kayıtları -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Yakıt Kayıtları
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered data-table" id="dataTable" width="100%" cellspacing="0"> <!-- data-table sınıfı eklendi -->
                    <thead>
                        <tr>
                            <th>Araç</th>
                            <th>Sürücü</th>
                            <th>Tarih</th>
                            <th>Yakıt Türü</th>
                            <th>Miktar (Lt)</th>
                            <th>Birim Fiyat (₺)</th>
                            <th>Toplam Tutar (₺)</th>
                            <th>Ödeme Türü</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['fuelRecords'] as $record) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $record->vehicle_id; ?>">
                                        <?php echo $record->plate_number; ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $record->driver_id; ?>">
                                        <?php echo $record->driver_name; ?>
                                    </a>
                                </td>
                                <td><?php echo date('d.m.Y', strtotime($record->date)); ?></td>
                                <td><?php echo $record->fuel_type; ?></td>
                                <td><?php echo number_format($record->amount, 2, ',', '.'); ?> Lt</td>
                                <td><?php echo number_format(($record->cost / $record->amount), 2, ',', '.'); ?> ₺</td>
                                <td><?php echo number_format($record->cost, 2, ',', '.'); ?> ₺</td>
                                <td><?php echo isset($record->payment_type) ? $record->payment_type : 'Nakit'; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/fuel/show/<?php echo $record->id; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTables'ı başlat
        const fuelReportTable = initDataTable('dataTable', {
            "pageLength": 25,
            "dom": '<"top"f>rt<"bottom"lip>',
            "stateSave": true
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
