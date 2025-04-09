<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-list mr-2"></i><?php echo $data['title']; ?></h2>
        </div>
        <div class="col-md-6">
            <div class="float-right">
                <a href="<?php echo URLROOT; ?>/logs/stats" class="btn btn-info">
                    <i class="fas fa-chart-bar mr-1"></i> İstatistikler
                </a>
                <a href="<?php echo URLROOT; ?>/logs/clean" class="btn btn-warning">
                    <i class="fas fa-broom mr-1"></i> Logları Temizle
                </a>
                <a href="<?php echo URLROOT; ?>/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Geri Dön
                </a>
            </div>
        </div>
    </div>

    <?php flash('log_message'); ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list mr-2"></i> Sistem Logları</h5>
        </div>
        <div class="card-body">
            <!-- Filtreleme Seçenekleri -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="btn-group">
                        <a href="<?php echo URLROOT; ?>/logs" class="btn btn-outline-primary <?php echo !isset($data['type']) ? 'active' : ''; ?>">
                            Tümü
                        </a>
                        <a href="<?php echo URLROOT; ?>/logs/type/login" class="btn btn-outline-primary <?php echo (isset($data['type']) && $data['type'] == 'login') ? 'active' : ''; ?>">
                            Giriş
                        </a>
                        <a href="<?php echo URLROOT; ?>/logs/type/logout" class="btn btn-outline-primary <?php echo (isset($data['type']) && $data['type'] == 'logout') ? 'active' : ''; ?>">
                            Çıkış
                        </a>
                        <a href="<?php echo URLROOT; ?>/logs/type/create" class="btn btn-outline-primary <?php echo (isset($data['type']) && $data['type'] == 'create') ? 'active' : ''; ?>">
                            Oluşturma
                        </a>
                        <a href="<?php echo URLROOT; ?>/logs/type/update" class="btn btn-outline-primary <?php echo (isset($data['type']) && $data['type'] == 'update') ? 'active' : ''; ?>">
                            Güncelleme
                        </a>
                        <a href="<?php echo URLROOT; ?>/logs/type/delete" class="btn btn-outline-primary <?php echo (isset($data['type']) && $data['type'] == 'delete') ? 'active' : ''; ?>">
                            Silme
                        </a>
                        <a href="<?php echo URLROOT; ?>/logs/type/error" class="btn btn-outline-primary <?php echo (isset($data['type']) && $data['type'] == 'error') ? 'active' : ''; ?>">
                            Hatalar
                        </a>
                    </div>
                </div>
            </div>

            <?php if(!empty($data['logs'])) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">Kullanıcı</th>
                                <th width="15%">İşlem</th>
                                <th width="10%">Tür</th>
                                <th width="15%">IP Adresi</th>
                                <th width="20%">Tarih</th>
                                <th width="20%">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['logs'] as $log) : ?>
                                <tr>
                                    <td><?php echo $log->id; ?></td>
                                    <td>
                                        <?php if($log->user_id) : ?>
                                            <a href="<?php echo URLROOT; ?>/logs/user/<?php echo $log->user_id; ?>">
                                                <?php echo $log->user_name; ?>
                                            </a>
                                        <?php else : ?>
                                            <span class="text-muted">Sistem</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $log->action; ?></td>
                                    <td>
                                        <?php
                                            $badgeClass = 'badge-secondary';
                                            switch($log->type) {
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
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($log->type); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $log->ip_address; ?></td>
                                    <td><?php echo date('d.m.Y H:i:s', strtotime($log->created_at)); ?></td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/logs/show/<?php echo $log->id; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye mr-1"></i> Detay
                                        </a>
                                        <form class="d-inline" action="<?php echo URLROOT; ?>/logs/delete/<?php echo $log->id; ?>" method="POST">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu log kaydını silmek istediğinize emin misiniz?')">
                                                <i class="fas fa-trash-alt mr-1"></i> Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i> Gösterilecek log kaydı bulunamadı.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 