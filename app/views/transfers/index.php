<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="mb-0"><i class="fas fa-exchange-alt me-2"></i> Yakıt Transferleri</h1>
    </div>
    <div class="col-md-6 text-end d-flex align-items-center justify-content-end">
        <a href="<?php echo URLROOT; ?>/transfers/add" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Yeni Transfer Ekle
        </a>
    </div>
</div>

<?php flash('transfer_message'); ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Yakıt Transferleri</h5>
        <span class="badge bg-light text-primary"><?php echo count($data['transfers']); ?> Adet</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered mb-0" id="transfersTable" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 10%">Tarih</th>
                        <th style="width: 15%">Kaynak Tank</th>
                        <th style="width: 15%">Hedef Tank</th>
                        <th style="width: 10%">Yakıt Tipi</th>
                        <th style="width: 10%">Miktar (Lt)</th>
                        <th style="width: 15%">İşlem Yapan</th>
                        <th style="width: 15%">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['transfers'] as $transfer) : ?>
                        <tr>
                            <td class="align-middle"><?php echo $transfer->id; ?></td>
                            <td class="align-middle"><?php echo date('d.m.Y', strtotime($transfer->transfer_date)); ?></td>
                            <td class="align-middle fw-bold"><?php echo $transfer->source_name; ?></td>
                            <td class="align-middle fw-bold"><?php echo $transfer->destination_name; ?></td>
                            <td class="align-middle"><?php echo $transfer->fuel_type; ?></td>
                            <td class="align-middle text-end"><?php echo number_format($transfer->amount, 2); ?> Lt</td>
                            <td class="align-middle"><?php echo $transfer->user_name; ?></td>
                            <td class="align-middle text-center">
                                <div class="btn-group">
                                    <a href="<?php echo URLROOT; ?>/transfers/show/<?php echo $transfer->id; ?>" class="btn btn-sm btn-outline-primary" title="Detay">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/transfers/edit/<?php echo $transfer->id; ?>" class="btn btn-sm btn-outline-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if(isAdmin()) : ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Sil" 
                                                onclick="confirmDelete(<?php echo $transfer->id; ?>, '<?php echo date('d.m.Y', strtotime($transfer->transfer_date)) . ' - ' . $transfer->source_name . ' → ' . $transfer->destination_name . ' (' . number_format($transfer->amount, 2) . ' Lt)'; ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Transfer İstatistikleri -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white text-center">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i> Toplam Transfer</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <?php 
                    $totalAmount = 0;
                    foreach($data['transfers'] as $transfer) {
                        $totalAmount += $transfer->amount;
                    }
                ?>
                <h2 class="mb-0 display-5"><?php echo number_format($totalAmount, 2); ?> <small class="text-muted">Lt</small></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-info text-white text-center">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Aylık Transfer</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <?php 
                    $monthlyAmount = 0;
                    $currentMonth = date('m');
                    $currentYear = date('Y');
                    foreach($data['transfers'] as $transfer) {
                        $transferMonth = date('m', strtotime($transfer->transfer_date));
                        $transferYear = date('Y', strtotime($transfer->transfer_date));
                        if($transferMonth == $currentMonth && $transferYear == $currentYear) {
                            $monthlyAmount += $transfer->amount;
                        }
                    }
                ?>
                <h2 class="mb-0 display-5"><?php echo number_format($monthlyAmount, 2); ?> <small class="text-muted">Lt</small></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-warning text-dark text-center">
                <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Ortalama Transfer</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <?php 
                    $avgAmount = (count($data['transfers']) > 0) ? ($totalAmount / count($data['transfers'])) : 0;
                ?>
                <h2 class="mb-0 display-5"><?php echo number_format($avgAmount, 2); ?> <small class="text-muted">Lt</small></h2>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modalı -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-trash me-2"></i> Transfer Silme Onayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <p id="deleteModalBody" class="mb-2">Bu transferi silmek istediğinizden emin misiniz?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Bu işlem geri alınamaz ve tanklardaki yakıt miktarları güncellenecektir.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> İptal
                </button>
                <form id="deleteForm" action="" method="post">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Evet, Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, details) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('deleteModalBody').textContent = details + " transferini silmek istediğinizden emin misiniz?";
        document.getElementById('deleteForm').action = "<?php echo URLROOT; ?>/transfers/delete/" + id;
        modal.show();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // DataTables'ı başlat
        if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            try {
                // Transferler Tablosu
                if (document.getElementById('transfersTable') && !$.fn.dataTable.isDataTable('#transfersTable')) {
                    $('#transfersTable').DataTable({
                        "responsive": true,
                        "scrollX": true,
                        "autoWidth": false,
                        "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null,
                        "pageLength": 25,
                        "order": [[ 0, "desc" ]]
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