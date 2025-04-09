<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/transfers" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h4><i class="fas fa-exchange-alt mr-2"></i>Transfer Detayları</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th width="150">Transfer ID:</th>
                        <td><?php echo $data['transfer']->id; ?></td>
                    </tr>
                    <tr>
                        <th>Transfer Tarihi:</th>
                        <td><?php echo date('d.m.Y', strtotime($data['transfer']->transfer_date)); ?></td>
                    </tr>
                    <tr>
                        <th>Kaynak Tank:</th>
                        <td><?php echo $data['transfer']->source_name; ?></td>
                    </tr>
                    <tr>
                        <th>Hedef Tank:</th>
                        <td><?php echo $data['transfer']->destination_name; ?></td>
                    </tr>
                    <tr>
                        <th>Yakıt Tipi:</th>
                        <td><?php echo $data['transfer']->fuel_type; ?></td>
                    </tr>
                    <tr>
                        <th>Transfer Miktarı:</th>
                        <td><?php echo number_format($data['transfer']->amount, 2); ?> Lt</td>
                    </tr>
                    <tr>
                        <th>İşlem Yapan:</th>
                        <td><?php echo $data['transfer']->user_name; ?></td>
                    </tr>
                    <tr>
                        <th>Notlar:</th>
                        <td><?php echo $data['transfer']->notes ?: '-'; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>İşlemler</h5>
                    </div>
                    <div class="card-body">
                        <a href="<?php echo URLROOT; ?>/transfers/edit/<?php echo $data['transfer']->id; ?>" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        <?php if(isAdmin()) : ?>
                            <form action="<?php echo URLROOT; ?>/transfers/delete/<?php echo $data['transfer']->id; ?>" method="post">
                                <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Bu transferi silmek istediğinize emin misiniz?');">
                                    <i class="fas fa-trash"></i> Sil
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tank Durumları -->
<div class="card mb-4">
    <div class="card-header">
        <h5><i class="fas fa-gas-pump mr-2"></i>Tank Durumları</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6>Kaynak Tank</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Tank Adı:</th>
                                <td><?php echo $data['transfer']->source_name; ?></td>
                            </tr>
                            <tr>
                                <th>Mevcut Miktar:</th>
                                <td><?php echo number_format($data['transfer']->source_current_amount, 2); ?> Lt</td>
                            </tr>
                            <tr>
                                <th>Kapasite:</th>
                                <td><?php echo number_format($data['transfer']->source_capacity, 2); ?> Lt</td>
                            </tr>
                            <tr>
                                <th>Doluluk Oranı:</th>
                                <td>
                                    <?php 
                                        $sourceFillRate = ($data['transfer']->source_capacity > 0) ? 
                                            ($data['transfer']->source_current_amount / $data['transfer']->source_capacity) * 100 : 0;
                                        echo number_format($sourceFillRate, 1) . '%';
                                    ?>
                                    <div class="progress mt-1" style="height: 10px;">
                                        <div class="progress-bar bg-<?php echo ($sourceFillRate > 75) ? 'success' : (($sourceFillRate > 25) ? 'warning' : 'danger'); ?>" 
                                             role="progressbar" style="width: <?php echo $sourceFillRate; ?>%"
                                             aria-valuenow="<?php echo $sourceFillRate; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6>Hedef Tank</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Tank Adı:</th>
                                <td><?php echo $data['transfer']->destination_name; ?></td>
                            </tr>
                            <tr>
                                <th>Mevcut Miktar:</th>
                                <td><?php echo number_format($data['transfer']->destination_current_amount, 2); ?> Lt</td>
                            </tr>
                            <tr>
                                <th>Kapasite:</th>
                                <td><?php echo number_format($data['transfer']->destination_capacity, 2); ?> Lt</td>
                            </tr>
                            <tr>
                                <th>Doluluk Oranı:</th>
                                <td>
                                    <?php 
                                        $destFillRate = ($data['transfer']->destination_capacity > 0) ? 
                                            ($data['transfer']->destination_current_amount / $data['transfer']->destination_capacity) * 100 : 0;
                                        echo number_format($destFillRate, 1) . '%';
                                    ?>
                                    <div class="progress mt-1" style="height: 10px;">
                                        <div class="progress-bar bg-<?php echo ($destFillRate > 75) ? 'success' : (($destFillRate > 25) ? 'warning' : 'danger'); ?>" 
                                             role="progressbar" style="width: <?php echo $destFillRate; ?>%"
                                             aria-valuenow="<?php echo $destFillRate; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 