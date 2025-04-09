<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-money-bill-wave"></i> Maliyet Raporları</h1>
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
            <form method="GET" action="<?php echo URLROOT; ?>/reports/costs" class="row">
                <div class="col-md-3 mb-3">
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
                <div class="col-md-3 mb-3">
                    <label for="cost_type">Maliyet Türü</label>
                    <select class="form-control" id="cost_type" name="cost_type">
                        <option value="">Tümü</option>
                        <option value="fuel" <?php echo $data['filters']['cost_type'] == 'fuel' ? 'selected' : ''; ?>>Yakıt</option>
                        <option value="maintenance" <?php echo $data['filters']['cost_type'] == 'maintenance' ? 'selected' : ''; ?>>Bakım</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="start_date">Başlangıç Tarihi</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($data['filters']['start_date']); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="end_date">Bitiş Tarihi</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($data['filters']['end_date']); ?>">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrele</button>
                    <a href="<?php echo URLROOT; ?>/reports/costs" class="btn btn-secondary ms-2">Sıfırla</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Toplam Maliyet Özeti -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie"></i> Toplam Maliyet Özeti
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Toplam Maliyet</h5>
                                    <div class="display-4"><?php echo number_format($data['costSummary']['total_cost'], 2, ',', '.'); ?> ₺</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Yakıt Maliyeti</h5>
                                    <div class="display-4"><?php echo number_format($data['costSummary']['fuel_cost'], 2, ',', '.'); ?> ₺</div>
                                    <div class="small"><?php echo $data['costSummary']['total_cost'] > 0 ? number_format(($data['costSummary']['fuel_cost'] / $data['costSummary']['total_cost']) * 100, 2) : 0; ?>%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Bakım Maliyeti</h5>
                                    <div class="display-4"><?php echo number_format($data['costSummary']['maintenance_cost'], 2, ',', '.'); ?> ₺</div>
                                    <div class="small"><?php echo $data['costSummary']['total_cost'] > 0 ? number_format(($data['costSummary']['maintenance_cost'] / $data['costSummary']['total_cost']) * 100, 2) : 0; ?>%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-chart-line"></i> Aylık Maliyet Dağılımı
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Ay</th>
                                                    <th>Yakıt Maliyeti (₺)</th>
                                                    <th>Bakım Maliyeti (₺)</th>
                                                    <th>Toplam (₺)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($data['monthlyCosts'] as $month) : ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($month->month_name); ?> <?php echo $month->year; ?></td>
                                                        <td><?php echo number_format($month->fuel_cost, 2, ',', '.'); ?></td>
                                                        <td><?php echo number_format($month->maintenance_cost, 2, ',', '.'); ?></td>
                                                        <td><?php echo number_format($month->total_cost, 2, ',', '.'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar"></i> Araç Başına Maliyet
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Araç</th>
                                                    <th>Yakıt Maliyeti (₺)</th>
                                                    <th>Bakım Maliyeti (₺)</th>
                                                    <th>Toplam (₺)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($data['vehicleCosts'] as $vehicle) : ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $vehicle->vehicle_id; ?>">
                                                                <?php echo htmlspecialchars($vehicle->plate_number); ?>
                                                            </a>
                                                        </td>
                                                        <td><?php echo number_format($vehicle->fuel_cost, 2, ',', '.'); ?></td>
                                                        <td><?php echo number_format($vehicle->maintenance_cost, 2, ',', '.'); ?></td>
                                                        <td><?php echo number_format($vehicle->total_cost, 2, ',', '.'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detaylı Maliyet Tablosu -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Detaylı Maliyet Kayıtları
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Araç</th>
                            <th>Maliyet Türü</th>
                            <th>Açıklama</th>
                            <th>Tutar (₺)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['costDetails'])): ?>
                            <?php foreach($data['costDetails'] as $cost) : ?>
                                <tr>
                                    <td><?php echo date('d.m.Y', strtotime($cost->date)); ?></td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $cost->vehicle_id; ?>">
                                            <?php echo htmlspecialchars($cost->plate_number); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($cost->cost_type == 'fuel') : ?>
                                            <span class="badge bg-success">Yakıt</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning">Bakım</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($cost->description); ?></td>
                                    <td><?php echo number_format($cost->amount, 2, ',', '.'); ?> ₺</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Seçilen tarih aralığında kayıt bulunamadı.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTables'ı başlat
        const costsReportTable = initDataTable('dataTable', {
            "pageLength": 25,
            "dom": '<"top"f>rt<"bottom"lip>',
            "stateSave": true
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 