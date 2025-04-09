<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-tools"></i> Bakım Raporları</h1>
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
            <form method="GET" action="<?php echo URLROOT; ?>/reports/maintenance" class="row">
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
                    <label for="maintenance_type">Bakım Türü</label>
                    <select class="form-control" id="maintenance_type" name="maintenance_type">
                        <option value="">Tümü</option>
                        <option value="Periyodik Bakım" <?php echo $data['filters']['maintenance_type'] == 'Periyodik Bakım' ? 'selected' : ''; ?>>Periyodik Bakım</option>
                        <option value="Arıza" <?php echo $data['filters']['maintenance_type'] == 'Arıza' ? 'selected' : ''; ?>>Arıza</option>
                        <option value="Kaza Onarım" <?php echo $data['filters']['maintenance_type'] == 'Kaza Onarım' ? 'selected' : ''; ?>>Kaza Onarım</option>
                        <option value="Motor" <?php echo $data['filters']['maintenance_type'] == 'Motor' ? 'selected' : ''; ?>>Motor</option>
                        <option value="Şanzıman" <?php echo $data['filters']['maintenance_type'] == 'Şanzıman' ? 'selected' : ''; ?>>Şanzıman</option>
                        <option value="Fren Sistemi" <?php echo $data['filters']['maintenance_type'] == 'Fren Sistemi' ? 'selected' : ''; ?>>Fren Sistemi</option>
                        <option value="Lastik Değişimi" <?php echo $data['filters']['maintenance_type'] == 'Lastik Değişimi' ? 'selected' : ''; ?>>Lastik Değişimi</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status">Durum</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tümü</option>
                        <option value="Tamamlandı" <?php echo $data['filters']['status'] == 'Tamamlandı' ? 'selected' : ''; ?>>Tamamlandı</option>
                        <option value="Devam Ediyor" <?php echo $data['filters']['status'] == 'Devam Ediyor' ? 'selected' : ''; ?>>Devam Ediyor</option>
                        <option value="Planlandı" <?php echo $data['filters']['status'] == 'Planlandı' ? 'selected' : ''; ?>>Planlandı</option>
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
                    <a href="<?php echo URLROOT; ?>/reports/maintenance" class="btn btn-secondary ms-2">Sıfırla</a>
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
                                Toplam Bakım Sayısı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['maintenanceStats']['total']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
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
                                Toplam Bakım Maliyeti (₺)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($data['maintenanceStats']['total_cost'], 2, ',', '.'); ?></div>
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
                                Devam Eden Bakımlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['maintenanceStats']['ongoing']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Ortalama Bakım Maliyeti (₺)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($data['maintenanceStats']['avg_cost'], 2, ',', '.'); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bakım Türü Dağılımı -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie"></i> Bakım Türü Dağılımı
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <div class="row">
                            <?php foreach($data['maintenanceTypeDistribution'] as $type) : ?>
                                <div class="col-md-3 mb-4 text-center">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo $type->maintenance_type; ?></h6>
                                            <div class="display-4"><?php echo $type->count; ?></div>
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

    <!-- Bakım Kayıtları -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Bakım Kayıtları
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Araç</th>
                            <th>Bakım Türü</th>
                            <th>Başlangıç Tarihi</th>
                            <th>Bitiş Tarihi</th>
                            <th>Servis</th>
                            <th>Maliyet (₺)</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['maintenanceRecords'] as $record) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $record->vehicle_id; ?>">
                                        <?php echo $record->plate_number; ?>
                                    </a>
                                </td>
                                <td><?php echo $record->maintenance_type; ?></td>
                                <td><?php echo date('d.m.Y', strtotime($record->start_date)); ?></td>
                                <td>
                                    <?php 
                                    if ($record->end_date && $record->end_date != '0000-00-00') {
                                        echo date('d.m.Y', strtotime($record->end_date));
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $record->service_provider; ?></td>
                                <td><?php echo number_format($record->cost, 2, ',', '.'); ?> ₺</td>
                                <td>
                                    <?php if ($record->status == 'Tamamlandı') : ?>
                                        <span class="badge bg-success">Tamamlandı</span>
                                    <?php elseif ($record->status == 'Devam Ediyor') : ?>
                                        <span class="badge bg-warning">Devam Ediyor</span>
                                    <?php else : ?>
                                        <span class="badge bg-info">Planlandı</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/maintenanceRecords/show/<?php echo $record->id; ?>" class="btn btn-sm btn-primary">
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
        const maintenanceReportTable = initDataTable('dataTable', {
            "pageLength": 25,
            "dom": '<"top"f>rt<"bottom"lip>',
            "stateSave": true
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 