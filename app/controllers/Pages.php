<?php
class Pages extends Controller {
    public function __construct() {
        // Gerekli modelleri yükleyin
    }

    // Ana sayfa (Karşılama Sayfası)
    public function index() {
        // Giriş yapıldıysa dashboard'a yönlendir
        if(isLoggedIn()) {
            redirect('dashboard');
        }

        $data = [
            'title' => 'Hoş Geldiniz',
            'description' => 'Filo Takip Sistemi ile tüm araçlarınızı kolayca yönetin.'
        ];

        $this->view('pages/welcome', $data);
    }

    // Hakkımızda sayfası
    public function about() {
        $data = [
            'title' => 'Hakkımızda',
            'description' => 'Filo Takip Sistemi, işletmelerin araç filolarını etkin bir şekilde yönetmelerine olanak tanıyan bir yazılım çözümüdür.'
        ];

        $this->view('pages/about', $data);
    }
} 