<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="sidebar-sticky">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-2 mb-1 text-muted">
                <span>Genel</span>
            </h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'dashboard') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'reports') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/reports">
                        <i class="fas fa-chart-bar me-2"></i>
                        Raporlar
                    </a>
                </li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Filo Yönetimi</span>
            </h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'companies') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/companies">
                        <i class="fas fa-building me-2"></i>
                        Şirketler
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'vehicles') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/vehicles">
                        <i class="fas fa-truck me-2"></i>
                        Araçlar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'insurance') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/insurance">
                        <i class="fas fa-shield-alt me-2"></i>
                        Muayene ve Sigorta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'drivers') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/drivers">
                        <i class="fas fa-id-card me-2"></i>
                        Sürücüler
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'licensetypes') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/licensetypes">
                        <i class="fas fa-id-badge me-2"></i>
                        Ehliyet Tipleri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'certificates') !== false && strpos($_GET['url'] ?? '', 'certificateTypes') === false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/certificates">
                        <i class="fas fa-certificate me-2"></i>
                        Sertifikalar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'certificateTypes') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/certificateTypes">
                        <i class="fas fa-list-alt me-2"></i>
                        Sertifika Türleri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'assignments') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/assignments">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Görevlendirmeler
                    </a>
                </li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Bakım & Yakıt</span>
            </h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'tanks') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/tanks">
                        <i class="fas fa-gas-pump me-2"></i>
                        Yakıt Tankları
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'purchases') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/purchases">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Yakıt Alımları
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'transfers') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/transfers">
                        <i class="fas fa-exchange-alt me-2"></i>
                        Yakıt Transferleri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'fuel') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/fuel">
                        <i class="fas fa-gas-pump me-2"></i>
                        Yakıt Kayıtları
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'maintenance') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/maintenance">
                        <i class="fas fa-tools me-2"></i> Bakım Kayıtları
                    </a>
                </li>
            </ul>

            <?php if(isAdmin()) : ?>
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Yönetim</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'users') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/users">
                            <i class="fas fa-users me-2"></i>
                            Kullanıcılar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'settings') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/settings">
                            <i class="fas fa-cog me-2"></i>
                            Ayarlar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'logs') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/logs">
                            <i class="fas fa-list me-2"></i>
                            Sistem Logları
                        </a>
                    </li>
                </ul>
            <?php endif; ?>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Veri İşlemleri</span>
            </h6>
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'entries') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/entries">
                        <i class="fas fa-folder-plus me-2"></i>
                        Veri Girişi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'lists') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/lists">
                        <i class="fas fa-list-alt me-2"></i>
                        Listeler
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_GET['url'] ?? '', 'exports') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/exports">
                        <i class="fas fa-file-export me-2"></i>
                        Dışa Aktarım
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav> 