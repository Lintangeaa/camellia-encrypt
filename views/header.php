<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Camellia Encryption</title>
  <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
  <nav class="navbar">
    <ul>
      <li>
        <a href="index.php?page=home" class="<?= ($page == 'home') ? 'active' : '' ?>">Home</a>
      </li>
      <li>
        <a href="index.php?page=about" class="<?= ($page == 'about') ? 'active' : '' ?>">About</a>
      </li>
      <li>
        <a href="index.php?page=mahasiswa"
          class="<?= ($page == 'mahasiswa' || $page == 'add-mahasiswa' || $page == 'edit-mahasiswa') ? 'active' : '' ?>">Mahasiswa</a>
      </li>
    </ul>
  </nav>
</body>