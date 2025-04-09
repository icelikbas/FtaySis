<?php
/**
 * Layout Helpers - Ortak UI bileşenleri için yardımcı fonksiyonlar
 */

if (!function_exists('actionButtons')) {
    /**
     * Tablolarda kullanılan işlem butonlarını oluşturan yardımcı fonksiyon
     * 
     * @param int $id Kayıt ID
     * @param string $controllerName Controller adı (örn: 'companies', 'vehicles')
     * @param bool $showViewButton Görüntüle butonu gösterilecek mi
     * @param bool $showEditButton Düzenle butonu gösterilecek mi
     * @param bool $showDeleteButton Sil butonu gösterilecek mi
     * @param bool $needsConfirmation Silme işlemi için onay istenecek mi
     * @param string $deletePromptExtra Silme onayı için ek bilgi (örn: ad, kayıt tarihi)
     * @param bool $checkIsAdmin Silme ve düzenleme için admin kontrolü yapılacak mı
     * @param string $btnSize Buton boyutu (sm, md, lg)
     * @param string $btnStyle Buton stili (outline veya filled)
     * @return string HTML buton grubu
     */
    function actionButtons(
        $id, 
        $controllerName, 
        $showViewButton = true, 
        $showEditButton = true, 
        $showDeleteButton = true,
        $needsConfirmation = true,
        $deletePromptExtra = '',
        $checkIsAdmin = true,
        $btnSize = 'sm',
        $btnStyle = 'outline'
    ) {
        $html = '<div class="btn-group action-buttons" role="group">';
        
        // Görüntüle butonu
        if ($showViewButton) {
            $html .= '
                <a href="' . URLROOT . '/' . $controllerName . '/show/' . $id . '" 
                   class="btn btn-' . ($btnStyle === 'outline' ? 'outline-' : '') . 'primary btn-' . $btnSize . '" 
                   data-bs-toggle="tooltip" title="Detay">
                    <i class="fas fa-eye"></i>
                </a>';
        }
        
        // Düzenle butonu
        if ($showEditButton && (!$checkIsAdmin || isAdmin())) {
            $html .= '
                <a href="' . URLROOT . '/' . $controllerName . '/edit/' . $id . '" 
                   class="btn btn-' . ($btnStyle === 'outline' ? 'outline-' : '') . 'warning btn-' . $btnSize . '" 
                   data-bs-toggle="tooltip" title="Düzenle">
                    <i class="fas fa-edit"></i>
                </a>';
        }
        
        // Sil butonu
        if ($showDeleteButton && (!$checkIsAdmin || isAdmin())) {
            if ($needsConfirmation) {
                // JavaScript onay diyaloğu ile silme
                $confirmText = 'Bu kaydı silmek istediğinize emin misiniz?';
                if (!empty($deletePromptExtra)) {
                    $confirmText = htmlspecialchars($deletePromptExtra) . ' kaydını silmek istediğinize emin misiniz?';
                }
                
                $html .= '
                    <button type="button" 
                            class="btn btn-' . ($btnStyle === 'outline' ? 'outline-' : '') . 'danger btn-' . $btnSize . '" 
                            onclick="if(confirm(\'' . $confirmText . '\')) { window.location.href = \'' . URLROOT . '/' . $controllerName . '/delete/' . $id . '\'; }"
                            data-bs-toggle="tooltip" title="Sil">
                        <i class="fas fa-trash"></i>
                    </button>';
            } else {
                // Direkt silme bağlantısı (onay olmadan)
                $html .= '
                    <a href="' . URLROOT . '/' . $controllerName . '/delete/' . $id . '" 
                       class="btn btn-' . ($btnStyle === 'outline' ? 'outline-' : '') . 'danger btn-' . $btnSize . '" 
                       data-bs-toggle="tooltip" title="Sil">
                        <i class="fas fa-trash"></i>
                    </a>';
            }
        }
        
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('statusBadge')) {
    /**
     * Durum bilgisi için renkli badge oluşturur
     * 
     * @param string $status Durum metni (Aktif, Pasif, vb.)
     * @param array $colors Durum-renk eşleştirmeleri
     * @return string HTML badge
     */
    function statusBadge($status, $colors = []) {
        // Varsayılan renk eşleştirmeleri
        $defaultColors = [
            'Aktif' => 'success',
            'Pasif' => 'secondary',
            'Beklemede' => 'warning',
            'İptal' => 'danger',
            'Tamamlandı' => 'primary',
            'Arızalı' => 'danger',
            'Bakımda' => 'info'
        ];
        
        // Özel renkler ile varsayılan renkleri birleştir
        $colorMap = array_merge($defaultColors, $colors);
        
        // Durum için renk belirle, yoksa varsayılan kullan
        $color = isset($colorMap[$status]) ? $colorMap[$status] : 'secondary';
        
        return '<span class="badge bg-' . $color . '">' . $status . '</span>';
    }
} 