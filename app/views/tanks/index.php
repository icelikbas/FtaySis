<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="mb-0"><i class="fas fa-gas-pump me-2"></i> <?php echo $data['title']; ?></h1>
    </div>
    <div class="col-md-6 text-end d-flex align-items-center justify-content-end">
        <a href="<?php echo URLROOT; ?>/tanks/add" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Yeni Tank Ekle
        </a>
    </div>
</div>

<?php flash('tank_message'); ?>

<?php if(count($data['tanks']) > 0) : ?>
    <div class="row mb-3">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sabit Tanklar</h5>
                    <span class="badge bg-light text-primary"><?php echo count(array_filter($data['tanks'], function($tank) { return $tank->type == 'Sabit'; })); ?> Adet</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="sabitTanksTable" class="table table-striped table-hover table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 18%">Tank Adı</th>
                                    <th style="width: 10%">Kapasite (Lt)</th>
                                    <th style="width: 12%">Mevcut Miktar (Lt)</th>
                                    <th style="width: 15%">Doluluk (%)</th>
                                    <th style="width: 12%">Yakıt Tipi</th>
                                    <th style="width: 15%">Lokasyon</th>
                                    <th style="width: 8%">Durum</th>
                                    <th style="width: 10%">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['tanks'] as $tank) : ?>
                                    <?php if($tank->type == 'Sabit') : ?>
                                        <tr>
                                            <td class="align-middle fw-bold"><?php echo $tank->name; ?></td>
                                            <td class="align-middle text-end"><?php echo number_format($tank->capacity, 2); ?></td>
                                            <td class="align-middle text-end"><?php echo number_format($tank->current_amount, 2); ?></td>
                                            <td class="align-middle">
                                                <?php 
                                                    $fillRate = ($tank->capacity > 0) ? ($tank->current_amount / $tank->capacity) * 100 : 0;
                                                    $fillRateClass = ($fillRate > 75) ? 'success' : (($fillRate > 25) ? 'warning' : 'danger');
                                                ?>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2 text-<?php echo $fillRateClass; ?> fw-bold"><?php echo number_format($fillRate, 1); ?>%</span>
                                                    <div class="progress flex-grow-1" style="height: 10px;">
                                                        <div class="progress-bar bg-<?php echo $fillRateClass; ?>" 
                                                            role="progressbar" style="width: <?php echo $fillRate; ?>%"
                                                            aria-valuenow="<?php echo $fillRate; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle"><?php echo $tank->fuel_type ?? 'Belirtilmemiş'; ?></td>
                                            <td class="align-middle"><?php echo $tank->location; ?></td>
                                            <td class="align-middle text-center">
                                                <span class="badge bg-<?php echo ($tank->status == 'Aktif') ? 'success' : (($tank->status == 'Pasif') ? 'secondary' : 'warning'); ?>">
                                                    <?php echo $tank->status; ?>
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="btn-group">
                                                    <a href="<?php echo URLROOT; ?>/tanks/show/<?php echo $tank->id; ?>" class="btn btn-sm btn-outline-primary" title="Detay">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/tanks/edit/<?php echo $tank->id; ?>" class="btn btn-sm btn-outline-warning" title="Düzenle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Sil"
                                                            onclick="confirmDelete(<?php echo $tank->id; ?>, '<?php echo $tank->name; ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mobil Tanklar</h5>
                    <span class="badge bg-light text-success"><?php echo count(array_filter($data['tanks'], function($tank) { return $tank->type == 'Mobil'; })); ?> Adet</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="mobilTanksTable" class="table table-striped table-hover table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 18%">Tank Adı</th>
                                    <th style="width: 10%">Kapasite (Lt)</th>
                                    <th style="width: 12%">Mevcut Miktar (Lt)</th>
                                    <th style="width: 15%">Doluluk (%)</th>
                                    <th style="width: 12%">Yakıt Tipi</th>
                                    <th style="width: 15%">Lokasyon</th>
                                    <th style="width: 8%">Durum</th>
                                    <th style="width: 10%">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['tanks'] as $tank) : ?>
                                    <?php if($tank->type == 'Mobil') : ?>
                                        <tr>
                                            <td class="align-middle fw-bold"><?php echo $tank->name; ?></td>
                                            <td class="align-middle text-end"><?php echo number_format($tank->capacity, 2); ?></td>
                                            <td class="align-middle text-end"><?php echo number_format($tank->current_amount, 2); ?></td>
                                            <td class="align-middle">
                                                <?php 
                                                    $fillRate = ($tank->capacity > 0) ? ($tank->current_amount / $tank->capacity) * 100 : 0;
                                                    $fillRateClass = ($fillRate > 75) ? 'success' : (($fillRate > 25) ? 'warning' : 'danger');
                                                ?>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2 text-<?php echo $fillRateClass; ?> fw-bold"><?php echo number_format($fillRate, 1); ?>%</span>
                                                    <div class="progress flex-grow-1" style="height: 10px;">
                                                        <div class="progress-bar bg-<?php echo $fillRateClass; ?>" 
                                                            role="progressbar" style="width: <?php echo $fillRate; ?>%"
                                                            aria-valuenow="<?php echo $fillRate; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle"><?php echo $tank->fuel_type ?? 'Belirtilmemiş'; ?></td>
                                            <td class="align-middle"><?php echo $tank->location; ?></td>
                                            <td class="align-middle text-center">
                                                <span class="badge bg-<?php echo ($tank->status == 'Aktif') ? 'success' : (($tank->status == 'Pasif') ? 'secondary' : 'warning'); ?>">
                                                    <?php echo $tank->status; ?>
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="btn-group">
                                                    <a href="<?php echo URLROOT; ?>/tanks/show/<?php echo $tank->id; ?>" class="btn btn-sm btn-outline-primary" title="Detay">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/tanks/edit/<?php echo $tank->id; ?>" class="btn btn-sm btn-outline-warning" title="Düzenle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Sil"
                                                            onclick="confirmDelete(<?php echo $tank->id; ?>, '<?php echo $tank->name; ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <p class="alert alert-warning">Henüz kayıtlı yakıt tankı bulunmamaktadır.</p>
<?php endif; ?>

<!-- Silme Onay Modalı -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Tank Silme Onayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <p id="deleteModalBody">Bu tankı silmek istediğinizden emin misiniz?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="deleteForm" action="" method="post">
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('deleteModalBody').textContent = name + " isimli tankı silmek istediğinizden emin misiniz?";
        document.getElementById('deleteForm').action = "<?php echo URLROOT; ?>/tanks/delete/" + id;
        modal.show();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Tabloları DataTable olarak başlat
        if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            try {
                // Sabit Tanklar Tablosu
                if (document.getElementById('sabitTanksTable') && !$.fn.dataTable.isDataTable('#sabitTanksTable')) {
                    $('#sabitTanksTable').DataTable({
                        "responsive": true,
                        "scrollX": true,
                        "autoWidth": false,
                        "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null,
                        "paging": false,
                        "searching": false,
                        "info": false,
                        "columnDefs": [
                            { "width": "15%", "targets": 0 },
                            { "width": "10%", "targets": 1 },
                            { "width": "10%", "targets": 2 },
                            { "width": "15%", "targets": 3 },
                            { "width": "10%", "targets": 4 },
                            { "width": "15%", "targets": 5 },
                            { "width": "10%", "targets": 6 },
                            { "width": "15%", "targets": 7, "className": "text-center" }
                        ]
                    });
                }
                
                // Mobil Tanklar Tablosu
                if (document.getElementById('mobilTanksTable') && !$.fn.dataTable.isDataTable('#mobilTanksTable')) {
                    $('#mobilTanksTable').DataTable({
                        "responsive": true,
                        "scrollX": true,
                        "autoWidth": false,
                        "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null,
                        "paging": false,
                        "searching": false,
                        "info": false,
                        "columnDefs": [
                            { "width": "15%", "targets": 0 },
                            { "width": "10%", "targets": 1 },
                            { "width": "10%", "targets": 2 },
                            { "width": "15%", "targets": 3 },
                            { "width": "10%", "targets": 4 },
                            { "width": "15%", "targets": 5 },
                            { "width": "10%", "targets": 6 },
                            { "width": "15%", "targets": 7, "className": "text-center" }
                        ]
                    });
                }
                
                // Tablo genişliklerini ayarla
                $(window).trigger('resize');
            } catch (error) {
                console.error("DataTable başlatılırken hata oluştu:", error);
            }
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 