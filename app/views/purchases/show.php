<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/purchases" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h4><i class="fas fa-gas-pump mr-2"></i>Yakıt Alımı Detayları</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th width="180">Alım ID:</th>
                        <td><?php echo $data['purchase']->id; ?></td>
                    </tr>
                    <tr>
                        <th>Tedarikçi:</th>
                        <td><?php echo $data['purchase']->supplier_name; ?></td>
                    </tr>
                    <tr>
                        <th>Alım Tarihi:</th>
                        <td><?php echo date('d.m.Y', strtotime($data['purchase']->date)); ?></td>
                    </tr>
                    <tr>
                        <th>Yakıt Türü:</th>
                        <td><?php echo $data['purchase']->fuel_type; ?></td>
                    </tr>
                    <tr>
                        <th>Miktar:</th>
                        <td><?php echo number_format($data['purchase']->amount, 2); ?> Lt</td>
                    </tr>
                    <tr>
                        <th>Toplam Maliyet:</th>
                        <td><?php echo number_format($data['purchase']->cost, 2); ?> TL</td>
                    </tr>
                    <tr>
                        <th>Birim Fiyat:</th>
                        <td><?php echo number_format($data['purchase']->unit_price, 2); ?> TL/Lt</td>
                    </tr>
                    <tr>
                        <th>Yakıt Tankı:</th>
                        <td><?php echo $data['purchase']->tank_name; ?></td>
                    </tr>
                    <tr>
                        <th>Fatura Numarası:</th>
                        <td><?php echo $data['purchase']->invoice_number ?: 'Belirtilmemiş'; ?></td>
                    </tr>
                    <tr>
                        <th>İşlemi Yapan:</th>
                        <td><?php echo $data['purchase']->user_name; ?></td>
                    </tr>
                    <tr>
                        <th>Oluşturulma Tarihi:</th>
                        <td><?php echo date('d.m.Y H:i', strtotime($data['purchase']->created_at)); ?></td>
                    </tr>
                    <tr>
                        <th>Notlar:</th>
                        <td><?php echo $data['purchase']->notes ?: 'Belirtilmemiş'; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>İşlemler</h5>
                    </div>
                    <div class="card-body">
                        <a href="<?php echo URLROOT; ?>/purchases/edit/<?php echo $data['purchase']->id; ?>" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        <?php if(isAdmin()) : ?>
                            <button type="button" class="btn btn-danger btn-block" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> Sil
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Finansal Özet</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h2 class="text-success mb-0"><?php echo number_format($data['purchase']->cost, 2); ?> TL</h2>
                            <p class="text-muted">Toplam Maliyet</p>
                        </div>
                        <table class="table table-sm">
                            <tr>
                                <th>Miktar:</th>
                                <td class="text-end"><?php echo number_format($data['purchase']->amount, 2); ?> Lt</td>
                            </tr>
                            <tr>
                                <th>Birim Fiyat:</th>
                                <td class="text-end"><?php echo number_format($data['purchase']->unit_price, 2); ?> TL/Lt</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modalı -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Yakıt Alımı Silme Onayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <p>Bu yakıt alımı kaydını silmek istediğinizden emin misiniz?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Bu işlem geri alınamaz ve tanktaki yakıt miktarı güncellenecektir.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form action="<?php echo URLROOT; ?>/purchases/delete/<?php echo $data['purchase']->id; ?>" method="post">
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 