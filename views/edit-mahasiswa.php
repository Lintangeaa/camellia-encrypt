<?php
// Get the npm from the query string
$npm = $_GET['npm'];

// Fetch the mahasiswa data based on npm
$stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE npm = :npm");
$stmt->bindParam(':npm', $npm);
$stmt->execute();
$mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mahasiswa) {
  // If no mahasiswa found, redirect to mahasiswa list
  header('Location: index.php?page=mahasiswa');
  exit();
}

// Decrypt the existing data
$decryptedAlamatRSA = rsaDecrypt($mahasiswa['alamat'], $privateKey);
$decryptedNoHpRSA = rsaDecrypt($mahasiswa['no_hp'], $privateKey);
try {
  $decryptedAlamat = camelliaDecrypt($decryptedAlamatRSA, '0123456789abcdef0123456789abcdef');
  $decryptedNoHp = camelliaDecrypt($decryptedNoHpRSA, '0123456789abcdef0123456789abcdef');
} catch (Exception $e) {
  $decryptedAlamat = 'Error decrypting';
  $decryptedNoHp = 'Error decrypting';
}

// Handle the form submission to update the mahasiswa data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the updated values from the form
  $alamat = $_POST['alamat'];
  $no_hp = $_POST['no_hp'];
  $email = $_POST['email'];

  // Encrypt the new values using Camellia
  $encryptedAlamat = camelliaEncrypt($alamat, '0123456789abcdef0123456789abcdef');
  $encryptedNoHp = camelliaEncrypt($no_hp, '0123456789abcdef0123456789abcdef');

  // RSA encrypt the Camellia-encrypted data
  $rsaEncryptedAlamat = rsaEncrypt($encryptedAlamat, $publicKey);
  $rsaEncryptedNoHp = rsaEncrypt($encryptedNoHp, $publicKey);

  // Update the database with the new values
  $updateStmt = $conn->prepare("UPDATE mahasiswa SET alamat = :alamat, no_hp = :no_hp, email = :email WHERE npm = :npm");
  $updateStmt->bindParam(':npm', $npm);
  $updateStmt->bindParam(':alamat', $rsaEncryptedAlamat);
  $updateStmt->bindParam(':no_hp', $rsaEncryptedNoHp);
  $updateStmt->bindParam(':email', $email);
  $updateStmt->execute();

  // Redirect to mahasiswa page after update
  header('Location: index.php?page=mahasiswa');
  exit();
}
?>

<div class="container">
  <h1>Edit Mahasiswa</h1>

  <!-- Form to edit mahasiswa -->
  <form action="index.php?page=edit-mahasiswa&npm=<?php echo $npm; ?>" method="POST">
    <div class="form-group">
      <label for="npm">NPM:</label>
      <input type="number" id="npm" name="npm" value="<?php echo $mahasiswa['npm']; ?>" readonly class="form-control">
    </div>
    <div class="form-group">
      <label for="alamat">Alamat:</label>
      <input type="text" id="alamat" name="alamat" value="<?php echo $decryptedAlamat; ?>" required
        class="form-control">
    </div>
    <div class="form-group">
      <label for="no_hp">No HP:</label>
      <input type="number" id="no_hp" name="no_hp" value="<?php echo $decryptedNoHp; ?>" required class="form-control">
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" value="<?php echo $mahasiswa['email']; ?>" required
        class="form-control">
    </div>
    <div class="form-group">
      <button type="submit" class="btn">Update</button>
    </div>
  </form>
</div>