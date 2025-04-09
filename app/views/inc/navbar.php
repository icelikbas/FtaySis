<nav class="navbar navbar-expand-lg navbar-dark bg-gradient shadow-sm sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo URLROOT; ?>">
            <i class="fas fa-truck-moving mr-2"></i> <span class="font-weight-bold"><?php echo SITENAME; ?></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item mx-1">
                    <a class="nav-link px-3" href="<?php echo URLROOT; ?>">
                        <i class="fas fa-home mr-1"></i> Ana Sayfa
                    </a>
                </li>
                <?php if(!isLoggedIn()) : ?>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3" href="<?php echo URLROOT; ?>/pages/about">
                            <i class="fas fa-info-circle mr-1"></i> Hakkımızda
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(isLoggedIn()) : ?>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3" href="<?php echo URLROOT; ?>/dashboard">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3" href="<?php echo URLROOT; ?>/reports">
                            <i class="fas fa-chart-bar mr-1"></i> Raporlar
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ml-auto">
                <?php if(isLoggedIn()) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar-circle mr-2 bg-primary">
                                <span class="initials"><?php echo substr($_SESSION['user_name'], 0, 1); ?></span>
                            </div>
                            <span><?php echo $_SESSION['user_name']; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" aria-labelledby="navbarDropdown">
                            <?php if(isAdmin()) : ?>
                                <a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/admin/dashboard">
                                    <i class="fa fa-cog mr-2 text-primary"></i> Yönetim Paneli
                                </a>
                                <div class="dropdown-divider"></div>
                            <?php endif; ?>
                            <a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/users/profile">
                                <i class="fa fa-user-circle mr-2 text-info"></i> Profil
                            </a>
                            <a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/users/logout">
                                <i class="fa fa-sign-out-alt mr-2 text-danger"></i> Çıkış Yap
                            </a>
                        </div>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-outline-light px-3 ml-2" href="<?php echo URLROOT; ?>/users/login">
                            <i class="fa fa-sign-in-alt mr-1"></i> Giriş
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
.bg-gradient {
    background: linear-gradient(to right, #4e73df, #224abe);
}

.navbar {
    padding: 0.7rem 1rem;
}

.navbar-brand {
    font-size: 1.25rem;
    margin-right: 2rem;
}

.navbar .navbar-nav .nav-link {
    font-weight: 500;
    transition: all 0.2s;
    padding: 0.5rem 1rem;
    border-radius: 4px;
}

.navbar .navbar-nav .nav-link:hover {
    transform: translateY(-2px);
    background-color: rgba(255, 255, 255, 0.1);
}

.avatar-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.initials {
    font-size: 15px;
    line-height: 1;
    font-weight: bold;
}

.dropdown-item {
    font-weight: 500;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
}

@media (min-width: 992px) {
    .navbar-expand-lg .navbar-nav .nav-link {
        padding-right: 1rem;
        padding-left: 1rem;
    }
}
</style> 