<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-clipboard-list"></i> Görevlendirme Detayları</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/assignments" class="btn btn-secondary float-right">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<?php flash('assignment_message'); ?>

<div class="card mb-3">
    <div class="card-header">
        <h4>Görevlendirme Bilgileri</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>Görevlendirme ID:</th>
                        <td><?php echo $data['assignment']->id; ?></td>
                    </tr>
                    <tr>
                        <th>Araç:</th>
                        <td>
                            <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['assignment']->vehicle_id; ?>">
                                <?php echo $data['assignment']->plate_number; ?> - 
                                <?php echo $data['assignment']->vehicle_brand; ?> 
                                <?php echo $data['assignment']->vehicle_model; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Sürücü:</th>
                        <td>
                            <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $data['assignment']->driver_id; ?>">
                                <?php echo $data['assignment']->driver_name; ?> 
                                <?php echo $data['assignment']->driver_surname; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Başlangıç Tarihi:</th>
                        <td><?php echo date('d.m.Y', strtotime($data['assignment']->start_date)); ?></td>
                    </tr>
                    <tr>
                        <th>Bitiş Tarihi:</th>
                        <td>
                            <?php if($data['assignment']->end_date): ?>
                                <?php echo date('d.m.Y', strtotime($data['assignment']->end_date)); ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>Durum:</th>
                        <td>
                            <?php if($data['assignment']->status == 'Aktif'): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php elseif($data['assignment']->status == 'Tamamlandı'): ?>
                                <span class="badge badge-info">Tamamlandı</span>
                            <?php elseif($data['assignment']->status == 'İptal'): ?>
                                <span class="badge badge-danger">İptal</span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php echo $data['assignment']->status; ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Kayıt Tarihi:</th>
                        <td><?php echo date('d.m.Y H:i', strtotime($data['assignment']->created_at)); ?></td>
                    </tr>
                    <tr>
                        <th>Notlar:</th>
                        <td>
                            <?php if(!empty($data['assignment']->notes)): ?>
                                <?php echo $data['assignment']->notes; ?>
                            <?php else: ?>
                                <span class="text-muted">Not bulunmuyor</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Durum Güncelleme Kartı -->
<?php if($data['assignment']->status == 'Aktif'): ?>
<div class="card mb-3">
    <div class="card-header">
        <h4>Durumu Güncelle</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/assignments/updateStatus/<?php echo $data['assignment']->id; ?>" method="post" class="form-inline">
            <div class="form-group mr-2">
                <select name="status" class="form-control">
                    <option value="Aktif" selected>Aktif</option>
                    <option value="Tamamlandı">Tamamlandı</option>
                    <option value="İptal">İptal</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- İşlemler Kartı -->
<div class="card mb-3">
    <div class="card-header">
        <h4>İşlemler</h4>
    </div>
    <div class="card-body">
        <a href="<?php echo URLROOT; ?>/assignments/edit/<?php echo $data['assignment']->id; ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Düzenle
        </a>
        
        <?php if(isAdmin()): ?>
            <form class="d-inline" action="<?php echo URLROOT; ?>/assignments/delete/<?php echo $data['assignment']->id; ?>" method="post">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Bu görevlendirmeyi silmek istediğinize emin misiniz?');">
                    <i class="fas fa-trash"></i> Sil
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 