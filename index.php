<?php
include 'services/camellia.php';
include 'services/rsa.php';
include 'config/db.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include 'views/header.php';

switch ($page) {
    case 'home':
        include 'views/home.php';
        break;
    case 'about':
        include 'views/about.php';
        break;
    case 'mahasiswa':
        include 'views/mahasiswa.php';
        break;
    case 'add-mahasiswa':
        include 'views/add-mahasiswa.php';
        break;
    case 'edit-mahasiswa':
        include 'views/edit-mahasiswa.php';
        break;
    default:
        include 'views/home.php';
}

include 'views/footer.php';
?>