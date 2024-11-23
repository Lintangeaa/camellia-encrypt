<div class="container">
  <h1>List of Mahasiswa</h1>

  <!-- Button to add mahasiswa -->
  <a href="index.php?page=add-mahasiswa">
    <button class="btn">Tambah Mahasiswa</button>
  </a>

  <table class="list-mahasiswa">
    <thead>
      <tr>
        <th>#</th>
        <th>NPM</th>
        <th>Alamat</th>
        <th>No HP</th>
        <th>Email</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = $conn->prepare("SELECT * FROM mahasiswa");
      $stmt->execute();
      $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $count = 1;
      foreach ($entries as $entry) {
        // Decrypt alamat and no_hp using RSA and Camellia
        $decryptedAlamatRSA = rsaDecrypt($entry['alamat'], $privateKey);
        $decryptedNoHpRSA = rsaDecrypt($entry['no_hp'], $privateKey);
        try {
          $decryptedAlamat = camelliaDecrypt($decryptedAlamatRSA, '0123456789abcdef0123456789abcdef');
          $decryptedNoHp = camelliaDecrypt($decryptedNoHpRSA, '0123456789abcdef0123456789abcdef');
        } catch (Exception $e) {
          $decryptedAlamat = 'Error decrypting';
          $decryptedNoHp = 'Error decrypting';
        }

        // Display each mahasiswa record
        echo "<tr>
                <td>{$count}</td>
                <td>{$entry['npm']}</td>
                <td>{$decryptedAlamat}</td>
                <td>{$decryptedNoHp}</td>
                <td>{$entry['email']}</td>
                <td>
                  <a href='index.php?page=edit-mahasiswa&npm={$entry['npm']}'>
                    <button class='btn-edit'>Edit</button>
                  </a>
                  <a href='index.php?page=mahasiswa&delete={$entry['npm']}'>
                    <button class='btn-delete'>Delete</button>
                  </a>
                </td>
              </tr>";

        $count++;
      }
      ?>
    </tbody>
  </table>
</div>

<?php
// Handle the deletion process
if (isset($_GET['delete'])) {
  $npmToDelete = $_GET['delete'];

  // Prepare the delete query
  $deleteStmt = $conn->prepare("DELETE FROM mahasiswa WHERE npm = :npm");
  $deleteStmt->bindParam(':npm', $npmToDelete);
  $deleteStmt->execute();

  // Redirect to the mahasiswa list page after deletion
  header("Location: index.php?page=mahasiswa");
  exit();
}
?>