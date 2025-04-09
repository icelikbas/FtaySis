# Filo Takip Sistemi

Filo Takip Sistemi, işletmelerin araç filolarını etkin bir şekilde yönetmelerine olanak tanıyan MVC tabanlı bir PHP uygulamasıdır.

## Özellikler

- Kullanıcı yönetimi (giriş, çıkış, profil)
- Admin ve normal kullanıcı rolleri
- Araç yönetimi ve takibi
- Sürücü yönetimi
- Araç-Sürücü görevlendirmeleri
- Yakıt kayıtları
- Bakım işlemleri
- İstatistikler ve raporlama
- Responsive tasarım

## Teknolojiler

- PHP 7.4+
- MySQL 5.7+
- HTML5, CSS3
- Bootstrap 4
- jQuery
- DataTables
- Chart.js
- Font Awesome

## Kurulum

1. Projeyi indirin ve web sunucunuzun htdocs klasörüne (XAMPP için) kopyalayın.
2. MySQL veritabanınızda `filo_takip.sql` dosyasını çalıştırarak veritabanını oluşturun.
3. `app/config/config.php` dosyasını açın ve veritabanı bağlantı bilgilerinizi güncelleyin:
   ```php
   // Veritabanı Parametreleri
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'filo_takip');
   ```
4. Ayrıca URL kök dizinini sunucu yapınıza göre güncelleyin:
   ```php
   // URL Kök Dizini
   define('URLROOT', 'http://localhost/filo_takip');
   ```
5. Web tarayıcınızdan uygulamaya erişin.

## Giriş Bilgileri

Sisteme giriş yapmak için aşağıdaki bilgileri kullanabilirsiniz:

### Admin kullanıcısı
- **E-posta:** admin@filotak.ip
- **Şifre:** admin123

### Normal kullanıcı
- **E-posta:** user@filotak.ip
- **Şifre:** user123

## Kullanım

- Admin kullanıcısı tüm sistem ayarlarına ve yönetim bölümlerine erişebilir.
- Normal kullanıcılar veri girişi yapabilir ve raporları görüntüleyebilir.
- Sistem, kullanıcı rolüne göre menü seçeneklerini dinamik olarak gösterir.

## Lisans

Bu proje açık kaynaklıdır ve eğitim amaçlarıyla kullanılmak üzere tasarlanmıştır.

## İletişim

Sorularınız ve önerileriniz için lütfen iletişime geçin. 