<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="mb-0"><i class="fas fa-gas-pump me-2"></i> <?php echo $data['title']; ?></h1>
    </div>
    <div class="col-md-6 text-end d-flex align-items-center justify-content-end">
        <a href="<?php echo URLROOT; ?>/purchases/add" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Yeni Yakıt Alımı
        </a>
    </div>
</div>

<?php flash('purchase_message'); ?>

<?php if(count($data['purchases']) > 0) : ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Yakıt Alımları</h5>
            <span class="badge bg-light text-primary"><?php echo count($data['purchases']); ?> Adet</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="purchasesTable" class="table table-striped table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 8%">Tarih</th>
                            <th style="width: 12%">Tedarikçi</th>
                            <th style="width: 12%">Tank</th>
                            <th style="width: 10%">Yakıt Tipi</th>
                            <th style="width: 10%">Miktar (Lt)</th>
                            <th style="width: 10%">Maliyet (TL)</th>
                            <th style="width: 10%">Birim Fiyat (TL/Lt)</th>
                            <th style="width: 10%">Fatura No</th>
                            <th style="width: 10%">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['purchases'] as $purchase) : ?>
                            <tr>
                                <td class="align-middle"><?php echo $purchase->id; ?></td>
                                <td class="align-middle"><?php echo date('d.m.Y', strtotime($purchase->date)); ?></td>
                                <td class="align-middle fw-bold"><?php echo $purchase->supplier_name; ?></td>
                                <td class="align-middle"><?php echo $purchase->tank_name; ?></td>
                                <td class="align-middle"><?php echo $purchase->fuel_type; ?></td>
                                <td class="align-middle text-end"><?php echo number_format($purchase->amount, 2); ?></td>
                                <td class="align-middle text-end"><?php echo number_format($purchase->cost, 2); ?></td>
                                <td class="align-middle text-end"><?php echo number_format($purchase->unit_price, 2); ?></td>
                                <td class="align-middle"><?php echo $purchase->invoice_number ?? '-'; ?></td>
                                <td class="align-middle text-center">
                                    <div class="btn-group">
                                        <a href="<?php echo URLROOT; ?>/purchases/show/<?php echo $purchase->id; ?>" class="btn btn-sm btn-outline-primary" title="Detay">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/purchases/edit/<?php echo $purchase->id; ?>" class="btn btn-sm btn-outline-warning" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Sil" 
                                                onclick="confirmDelete(<?php echo $purchase->id; ?>, '<?php echo date('d.m.Y', strtotime($purchase->date)) . ' - ' . $purchase->supplier_name . ' - ' . number_format($purchase->amount, 2) . ' Lt'; ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-circle me-2"></i> Henüz kayıtlı yakıt alımı bulunmamaktadır.
    </div>
<?php endif; ?>

<!-- İstatistikler -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white text-center">
                <h5 class="mb-0"><i class="fas fa-gas-pump me-2"></i> Toplam Alım</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <?php 
                    $totalAmount = 0;
                    foreach($data['purchases'] as $purchase) {
                        $totalAmount += $purchase->amount;
                    }
                ?>
                <h2 class="mb-0 display-5"><?php echo number_format($totalAmount, 2); ?> <small class="text-muted">Lt</small></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-info text-white text-center">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Toplam Maliyet</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <?php 
                    $totalCost = 0;
                    foreach($data['purchases'] as $purchase) {
                        $totalCost += $purchase->cost;
                    }
                ?>
                <h2 class="mb-0 display-5"><?php echo number_format($totalCost, 2); ?> <small class="text-muted">TL</small></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-warning text-dark text-center">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> Ortalama Birim Fiyat</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <?php 
                    $avgUnitPrice = ($totalAmount > 0) ? ($totalCost / $totalAmount) : 0;
                ?>
                <h2 class="mb-0 display-5"><?php echo number_format($avgUnitPrice, 2); ?> <small class="text-muted">TL/Lt</small></h2>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modalı -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-trash me-2"></i> Yakıt Alımı Silme Onayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <p id="deleteModalBody" class="mb-2">Bu yakıt alımını silmek istediğinizden emin misiniz?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Bu işlem geri alınamaz ve tanktaki yakıt miktarı güncellenecektir.
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
        document.getElementById('deleteModalBody').textContent = details + " yakıt alımını silmek istediğinizden emin misiniz?";
        document.getElementById('deleteForm').action = "<?php echo URLROOT; ?>/purchases/delete/" + id;
        modal.show();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // DataTable'ı başlat
        if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            try {
                // Yakıt Alımları Tablosu
                if (document.getElementById('purchasesTable') && !$.fn.dataTable.isDataTable('#purchasesTable')) {
                    $('#purchasesTable').DataTable({
                        "responsive": true,
                        "scrollX": true,
                        "autoWidth": false,
                        "language": typeof datatablesLangTR !== 'undefined' ? datatablesLangTR : null,
                        "order": [[0, "desc"]] // ID'ye göre azalan sıralama (en yeni en üstte)
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