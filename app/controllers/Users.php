<?php
class Users extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    // Kullanıcı kaydı
    public function register() {
        // Sadece yönetici erişebilir
        if(!isAdmin()) {
            redirect('users/login');
        }

        // POST isteği kontrol
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini işle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini alma
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => trim($_POST['role']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Form doğrulama
            if(empty($data['name'])) {
                $data['name_err'] = 'Lütfen isim giriniz';
            }

            if(empty($data['email'])) {
                $data['email_err'] = 'Lütfen e-posta giriniz';
            } else {
                // E-posta kontrolü
                if($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'E-posta zaten kullanılıyor';
                }
            }

            if(empty($data['password'])) {
                $data['password_err'] = 'Lütfen şifre giriniz';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Şifre en az 6 karakter olmalıdır';
            }

            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Lütfen şifreyi tekrar giriniz';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Şifreler eşleşmiyor';
                }
            }

            // Hata yoksa kaydedip yönlendir
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Şifreyi hashleme
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Kayıt
                if($this->userModel->register($data)) {
                    flash('register_success', 'Kullanıcı başarıyla oluşturuldu.');
                    redirect('users');
                } else {
                    die('Bir hata oluştu');
                }
            } else {
                // Formu hata mesajlarıyla göster
                $this->view('users/register', $data);
            }

        } else {
            // Formu göster
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'role' => 'user',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            $this->view('users/register', $data);
        }
    }

    // Kullanıcı girişi
    public function login() {
        // Zaten giriş yapmışsa ana sayfaya yönlendir
        if(isLoggedIn()) {
            redirect('dashboard');
        }

        // POST isteği kontrol
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini işle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini alma
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Form doğrulama
            if(empty($data['email'])) {
                $data['email_err'] = 'Lütfen e-posta giriniz';
            }

            if(empty($data['password'])) {
                $data['password_err'] = 'Lütfen şifre giriniz';
            }

            // Kullanıcı kontrolü
            if($this->userModel->findUserByEmail($data['email'])) {
                // Kullanıcı bulundu
            } else {
                // Kullanıcı bulunamadı
                $data['email_err'] = 'Kullanıcı bulunamadı';
            }

            // Hata yoksa giriş yap
            if(empty($data['email_err']) && empty($data['password_err'])) {
                // Giriş doğrulama
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser) {
                    // Oturum oluştur
                    $this->createUserSession($loggedInUser);
                    
                    // Giriş işlemini logla
                    logLogin($data['email']);
                    
                    redirect('dashboard');
                } else {
                    $data['password_err'] = 'Şifre yanlış';
                    $this->view('users/login', $data);
                    
                    // Başarısız giriş denemesini logla
                    logError('Başarısız giriş denemesi', 'users', 'E-posta: ' . $data['email']);
                }
            } else {
                // Formu hata mesajlarıyla göster
                $this->view('users/login', $data);
            }

        } else {
            // Formu göster
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            $this->view('users/login', $data);
        }
    }

    // Kullanıcı oturumu oluştur
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role;
    }

    // Kullanıcı çıkışı
    public function logout() {
        if(isset($_SESSION['user_email'])) {
            // Çıkış işlemini logla
            logLogout($_SESSION['user_email']);
        }
        
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        redirect('users/login');
    }

    // Kullanıcı profili
    public function profile() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);

        $data = [
            'user' => $user
        ];

        $this->view('users/profile', $data);
    }

    // Tüm kullanıcıları listele (sadece admin)
    public function index() {
        if(!isAdmin()) {
            redirect('dashboard');
        }

        $users = $this->userModel->getUsers();

        $data = [
            'users' => $users
        ];

        $this->view('users/index', $data);
    }

    // Kullanıcı düzenleme (sadece admin)
    public function edit($id) {
        // Sadece yönetici erişebilir
        if(!isAdmin()) {
            redirect('dashboard');
        }

        // Kullanıcıyı getir
        $user = $this->userModel->getUserById($id);

        // Kullanıcı bulunamadıysa
        if(!$user) {
            flash('user_message', 'Kullanıcı bulunamadı', 'alert alert-danger');
            redirect('users');
        }

        // POST isteği kontrol
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini işle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini alma
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'role' => trim($_POST['role']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // Form doğrulama
            if(empty($data['name'])) {
                $data['name_err'] = 'Lütfen isim giriniz';
            }

            if(empty($data['email'])) {
                $data['email_err'] = 'Lütfen e-posta giriniz';
            } else {
                // E-posta kontrolü - eğer e-posta değiştiyse kontrol et
                if($data['email'] != $user->email && $this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'E-posta zaten kullanılıyor';
                }
            }

            // Şifre kontrol - boş ise değiştirme, değilse doğrula
            if(!empty($data['password']) && strlen($data['password']) < 6) {
                $data['password_err'] = 'Şifre en az 6 karakter olmalıdır';
            }

            // Hata yoksa güncelle
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err'])) {
                // Şifre güncelleme
                if(!empty($data['password'])) {
                    // Şifreyi hashleme
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                } else {
                    // Şifre boşsa mevcut şifreyi kullan
                    $data['password'] = $user->password;
                }
                
                // Güncelleme
                if($this->userModel->updateUser($data)) {
                    flash('user_message', 'Kullanıcı başarıyla güncellendi');
                    redirect('users');
                } else {
                    die('Bir hata oluştu');
                }
            } else {
                // Formu hata mesajlarıyla göster
                $this->view('users/edit', $data);
            }

        } else {
            // Form verilerini doldur
            $data = [
                'id' => $id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => '',
                'role' => $user->role,
                'name_err' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            $this->view('users/edit', $data);
        }
    }

    // Kullanıcı silme (sadece admin)
    public function delete($id) {
        // Sadece yönetici erişebilir
        if(!isAdmin()) {
            redirect('dashboard');
        }

        // POST kontrolü
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kendi kendini silmeyi engelle
            if($id == $_SESSION['user_id']) {
                flash('user_message', 'Kendi hesabınızı silemezsiniz', 'alert alert-danger');
                redirect('users');
                return;
            }

            // Kullanıcıyı getir
            $user = $this->userModel->getUserById($id);

            // Kullanıcı bulunamadıysa
            if(!$user) {
                flash('user_message', 'Kullanıcı bulunamadı', 'alert alert-danger');
                redirect('users');
                return;
            }

            // Kullanıcıyı sil
            if($this->userModel->deleteUser($id)) {
                flash('user_message', 'Kullanıcı başarıyla silindi');
                redirect('users');
            } else {
                flash('user_message', 'Kullanıcı silinemedi', 'alert alert-danger');
                redirect('users');
            }
        } else {
            redirect('users');
        }
    }
} 