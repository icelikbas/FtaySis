<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-car"></i> Araç Raporları</h1>
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
            <form method="GET" action="<?php echo URLROOT; ?>/reports/vehicles" class="row">
                <div class="col-md-2 mb-3">
                    <label for="status">Durum</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tümü</option>
                        <option value="Aktif" <?php echo $data['filters']['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Pasif" <?php echo $data['filters']['status'] == 'Pasif' ? 'selected' : ''; ?>>Pasif</option>
                        <option value="Bakımda" <?php echo $data['filters']['status'] == 'Bakımda' ? 'selected' : ''; ?>>Bakımda</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="type">Araç Tipi</label>
                    <select class="form-control" id="type" name="type">
                        <option value="">Tümü</option>
                        <option value="Otomobil" <?php echo $data['filters']['type'] == 'Otomobil' ? 'selected' : ''; ?>>Otomobil</option>
                        <option value="Kamyonet" <?php echo $data['filters']['type'] == 'Kamyonet' ? 'selected' : ''; ?>>Kamyonet</option>
                        <option value="Kamyon" <?php echo $data['filters']['type'] == 'Kamyon' ? 'selected' : ''; ?>>Kamyon</option>
                        <option value="Otobüs" <?php echo $data['filters']['type'] == 'Otobüs' ? 'selected' : ''; ?>>Otobüs</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="year">Üretim Yılı</label>
                    <select class="form-control" id="year" name="year">
                        <option value="">Tümü</option>
                        <?php 
                        $currentYear = date('Y');
                        for($y = $currentYear; $y >= 2000; $y--) : ?>
                            <option value="<?php echo $y; ?>" <?php echo $data['filters']['year'] == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                        <?php endfor; ?>
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
                    <a href="<?php echo URLROOT; ?>/reports/vehicles" class="btn btn-secondary ms-2">Sıfırla</a>
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
                                Toplam Araç</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['vehicleStats']['total']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
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
                                Aktif Araçlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['vehicleStats']['active']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Bakımda</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['vehicleStats']['maintenance']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
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
                                Pasif Araçlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['vehicleStats']['inactive']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Araç Dağılımı -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie"></i> Araç Tipi Dağılımı
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <div class="row">
                            <?php foreach($data['vehicleTypeDistribution'] as $type) : ?>
                                <div class="col-md-3 mb-4 text-center">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo $type->vehicle_type; ?></h6>
                                            <div class="display-4"><?php echo $type->count; ?></div>
                                            <div class="text-muted">Araç</div>
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

    <!-- Araç Listesi -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Araç Listesi
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Plaka</th>
                            <th>Marka</th>
                            <th>Model</th>
                            <th>Yıl</th>
                            <th>Araç Tipi</th>
                            <th>Durum</th>
                            <th>Yakıt Tüketimi</th>
                            <th>Bakım Maliyeti</th>
                            <th>Görevlendirme</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['vehicles'] as $vehicle) : ?>
                            <tr>
                                <td><a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $vehicle->id; ?>"><?php echo $vehicle->plate_number; ?></a></td>
                                <td><?php echo $vehicle->brand; ?></td>
                                <td><?php echo $vehicle->model; ?></td>
                                <td><?php echo $vehicle->year; ?></td>
                                <td><?php echo $vehicle->vehicle_type; ?></td>
                                <td>
                                    <?php if ($vehicle->status == 'Aktif') : ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php elseif ($vehicle->status == 'Bakımda') : ?>
                                        <span class="badge bg-warning">Bakımda</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Pasif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    if (isset($vehicle->fuelStats->total_fuel)) {
                                        echo number_format($vehicle->fuelStats->total_fuel, 2, ',', '.') . ' Lt <br>';
                                        echo '<small class="text-muted">' . number_format($vehicle->fuelStats->total_cost, 2, ',', '.') . ' ₺</small>';
                                    } else {
                                        echo '0 Lt';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php echo number_format($vehicle->maintenanceCost, 2, ',', '.') . ' ₺'; ?>
                                </td>
                                <td>
                                    <?php echo $vehicle->assignment_count; ?> görev
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
        const vehiclesReportTable = initDataTable('dataTable', {
            "pageLength": 25,
            "dom": '<"top"f>rt<"bottom"lip>',
            "stateSave": true
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 