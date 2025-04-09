<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-users mr-2"></i>Kullanıcı Yönetimi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-user-plus mr-1"></i> Yeni Kullanıcı
        </a>
    </div>
</div>

<?php flash('register_success'); ?>
<?php flash('user_message'); ?>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Kullanıcı Listesi
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered data-table" id="usersTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Adı Soyadı</th>
                        <th>E-posta</th>
                        <th>Rol</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['users'] as $user) : ?>
                        <tr>
                            <td><?php echo $user->id; ?></td>
                            <td><?php echo $user->name; ?></td>
                            <td><?php echo $user->email; ?></td>
                            <td>
                                <?php if($user->role == 'admin') : ?>
                                    <span class="badge badge-primary">Yönetici</span>
                                <?php else : ?>
                                    <span class="badge badge-secondary">Kullanıcı</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d.m.Y H:i', strtotime($user->created_at)); ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/users/edit/<?php echo $user->id; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Düzenle
                                </a>
                                <?php if($user->id != $_SESSION['user_id']) : ?>
                                    <form class="d-inline" action="<?php echo URLROOT; ?>/users/delete/<?php echo $user->id; ?>" method="post">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                            <i class="fas fa-trash"></i> Sil
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTables'ı başlat
        const usersTable = initDataTable('usersTable', {
            "pageLength": 25,
            "order": [[ 0, "desc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 5 } // İşlemler sütunu sıralanabilir olmasın
            ]
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 