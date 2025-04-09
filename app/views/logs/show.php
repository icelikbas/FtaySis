<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-list mr-2"></i>Log Detayı</h2>
        </div>
        <div class="col-md-6">
            <a href="<?php echo URLROOT; ?>/logs" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Loglara Dön
            </a>
        </div>
    </div>

    <?php flash('log_message'); ?>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list mr-2"></i> 
                        ID: <?php echo $data['log']->id; ?> - 
                        <?php echo ucfirst($data['log']->action); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">ID:</label>
                                <p class="form-control-static"><?php echo $data['log']->id; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tür:</label>
                                <?php
                                    $badgeClass = 'badge-secondary';
                                    switch($data['log']->type) {
                                        case 'login':
                                            $badgeClass = 'badge-success';
                                            break;
                                        case 'logout':
                                            $badgeClass = 'badge-info';
                                            break;
                                        case 'create':
                                            $badgeClass = 'badge-primary';
                                            break;
                                        case 'update':
                                            $badgeClass = 'badge-warning';
                                            break;
                                        case 'delete':
                                            $badgeClass = 'badge-danger';
                                            break;
                                        case 'error':
                                            $badgeClass = 'badge-danger';
                                            break;
                                    }
                                ?>
                                <p>
                                    <span class="badge <?php echo $badgeClass; ?> py-2 px-3">
                                        <?php echo ucfirst($data['log']->type); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Kullanıcı:</label>
                                <p>
                                    <?php if($data['log']->user_id) : ?>
                                        <a href="<?php echo URLROOT; ?>/logs/user/<?php echo $data['log']->user_id; ?>">
                                            <?php echo $data['log']->user_name; ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-muted">Sistem</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">IP Adresi:</label>
                                <p><?php echo $data['log']->ip_address; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">İşlem:</label>
                                <p><?php echo $data['log']->action; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tarih:</label>
                                <p><?php echo date('d.m.Y H:i:s', strtotime($data['log']->created_at)); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Detaylar:</label>
                        <?php if(!empty($data['log']->details)) : ?>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <pre class="mb-0"><?php echo $data['log']->details; ?></pre>
                                </div>
                            </div>
                        <?php else : ?>
                            <p class="text-muted">Detay bilgisi bulunmamaktadır.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <form action="<?php echo URLROOT; ?>/logs/delete/<?php echo $data['log']->id; ?>" method="POST" class="float-right">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bu log kaydını silmek istediğinize emin misiniz?')">
                            <i class="fas fa-trash-alt mr-1"></i> Logu Sil
                        </button>
                    </form>
                    <a href="<?php echo URLROOT; ?>/logs" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 