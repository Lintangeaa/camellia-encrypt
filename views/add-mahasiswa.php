<div class="container">
  <h1>Tambah Mahasiswa</h1>
  <form action="index.php?page=add-mahasiswa" method="POST">
    <div class="form-group">
      <label for="npm">NPM:</label>
      <input type="number" id="npm" name="npm" required class="form-control">
    </div>
    <div class="form-group">
      <label for="alamat">Alamat:</label>
      <input type="text" id="alamat" name="alamat" required class="form-control">
    </div>
    <div class="form-group">
      <label for="no_hp">No HP:</label>
      <input type="number" id="no_hp" name="no_hp" required class="form-control">
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required class="form-control">
    </div>
    <div class="form-group">
      <button type="submit" class="btn">Submit</button>
    </div>
  </form>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npm = $_POST['npm'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM mahasiswa WHERE npm = :npm");
    $stmt->bindParam(':npm', $npm);
    $stmt->execute();
    $existingNpmCount = $stmt->fetchColumn();

    if ($existingNpmCount > 0) {
      echo '<div class="alert-error">NPM sudah terdaftar!</div>';
    } else {
      $camelliaKey = '0123456789abcdef0123456789abcdef';
      $encryptedAlamat = camelliaEncrypt($alamat, $camelliaKey);
      $encryptedNoHp = camelliaEncrypt($no_hp, $camelliaKey);

      $rsaEncryptedAlamat = rsaEncrypt($encryptedAlamat, $publicKey);
      $rsaEncryptedNoHp = rsaEncrypt($encryptedNoHp, $publicKey);

      try {
        // Insert the new mahasiswa data into the database
        $stmt = $conn->prepare("INSERT INTO mahasiswa (npm, alamat, no_hp, email) VALUES (:npm, :alamat, :no_hp, :email)");
        $stmt->bindParam(':npm', $npm);
        $stmt->bindParam(':alamat', $rsaEncryptedAlamat);
        $stmt->bindParam(':no_hp', $rsaEncryptedNoHp);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Show success message and redirect after a few seconds
        echo '<div class="alert-success">Mahasiswa Berhasil Ditambahkan!</div>';
        header('Refresh: 3; url=index.php?page=mahasiswa');
        exit;
      } catch (Exception $e) {
        // Display error if insertion fails
        echo '<p>Error: ' . $e->getMessage() . '</p>';
      }
    }
  }
  ?>
</div>