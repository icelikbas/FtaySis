<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-clipboard-list"></i> Görevlendirme Raporları</h1>
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
            <form method="GET" action="<?php echo URLROOT; ?>/reports/assignments" class="row">
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
                    <label for="status">Durum</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tümü</option>
                        <option value="Aktif" <?php echo $data['filters']['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Tamamlandı" <?php echo $data['filters']['status'] == 'Tamamlandı' ? 'selected' : ''; ?>>Tamamlandı</option>
                        <option value="İptal" <?php echo $data['filters']['status'] == 'İptal' ? 'selected' : ''; ?>>İptal</option>
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
                    <a href="<?php echo URLROOT; ?>/reports/assignments" class="btn btn-secondary ms-2">Sıfırla</a>
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
                                Toplam Görevlendirme</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['assignmentStats']['total']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                Devam Eden Görevler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['assignmentStats']['active']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-gray-300"></i>
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
                                Tamamlanan Görevler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['assignmentStats']['completed']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                İptal Edilen Görevler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['assignmentStats']['cancelled']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Araçların Görev Dağılımı -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-car"></i> En Çok Görevlendirilen Araçlar
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Araç</th>
                                    <th>Görev Sayısı</th>
                                    <th>Son Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['topVehicles'] as $vehicle) : ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $vehicle->vehicle_id; ?>">
                                                <?php echo $vehicle->plate_number; ?> - <?php echo $vehicle->brand; ?> <?php echo $vehicle->model; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $vehicle->assignment_count; ?></td>
                                        <td>
                                            <?php if ($vehicle->current_status == 'Aktif') : ?>
                                                <span class="badge bg-success">Görevde</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary">Boşta</span>
                                            <?php endif; ?>
                                        </td>
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
                    <i class="fas fa-user"></i> En Çok Görevlendirilen Sürücüler
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sürücü</th>
                                    <th>Görev Sayısı</th>
                                    <th>Son Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['topDrivers'] as $driver) : ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->driver_id; ?>">
                                                <?php echo $driver->driver_name; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $driver->assignment_count; ?></td>
                                        <td>
                                            <?php if ($driver->current_status == 'Aktif') : ?>
                                                <span class="badge bg-success">Görevde</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary">Boşta</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Görevlendirme Listesi -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Görevlendirme Listesi
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Görev Adı</th>
                            <th>Araç</th>
                            <th>Sürücü</th>
                            <th>Başlangıç</th>
                            <th>Bitiş</th>
                            <th>Durum</th>
                            <th>Lokasyon</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['assignments'] as $assignment) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/assignments/show/<?php echo $assignment->id; ?>">
                                        <?php echo 'Görev #' . $assignment->id; ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $assignment->vehicle_id; ?>">
                                        <?php echo $assignment->plate_number; ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $assignment->driver_id; ?>">
                                        <?php echo $assignment->driver_name; ?>
                                    </a>
                                </td>
                                <td><?php echo date('d.m.Y', strtotime($assignment->start_date)); ?></td>
                                <td>
                                    <?php 
                                    if ($assignment->end_date && $assignment->end_date != '0000-00-00') {
                                        echo date('d.m.Y', strtotime($assignment->end_date));
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($assignment->status == 'Aktif') : ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php elseif ($assignment->status == 'Tamamlandı') : ?>
                                        <span class="badge bg-primary">Tamamlandı</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">İptal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo isset($assignment->destination) ? $assignment->destination : 'Belirtilmemiş'; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/assignments/show/<?php echo $assignment->id; ?>" class="btn btn-sm btn-primary">
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
        const assignmentsReportTable = initDataTable('dataTable', {
            "pageLength": 25,
            "dom": '<"top"f>rt<"bottom"lip>',
            "stateSave": true
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 