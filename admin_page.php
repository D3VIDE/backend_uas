<?php
session_start();
include("DatabaseConnection.php");

$Admin = isset($_SESSION["nama_admin"]) ? $_SESSION["nama_admin"] : "";
$alertMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["tanggal"]) && isset($_POST["nomor_resi"])) {
    $tanggal = $_POST["tanggal"];
    $NoResi = $_POST["nomor_resi"];
    $query = "INSERT INTO transaksi_resi_pengiriman (nomor_resi, tanggal_resi) VALUES (?, ?)";
    $query2 = "INSERT INTO detail_log_pengiriman (nomor_resi, tanggal) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $NoResi, $tanggal);
    $stmt->execute();
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("ss", $NoResi, $tanggal);
    $stmt2->execute();
    header("Location: admin_page.php");
    exit();
  }
  if (isset($_POST["kota"]) && isset($_POST["keterangan"]) && isset($_POST["nomor_resi_log"])) {
    $kota = $_POST["kota"];
    $keterangan = $_POST["keterangan"];
    $noResiLog = $_POST["nomor_resi_log"];

    // Check if the nomor_resi exists in transaksi_resi_pengiriman
    $checkResiQuery = "SELECT * FROM transaksi_resi_pengiriman WHERE nomor_resi = ?";
    $stmtCheck = $conn->prepare($checkResiQuery);
    $stmtCheck->bind_param("s", $noResiLog);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
      // If the nomor_resi exists, update the log entry
      $updateLogQuery = "UPDATE detail_log_pengiriman SET kota = ?, keterangan = ? WHERE nomor_resi = ?";
      $stmtUpdateLog = $conn->prepare($updateLogQuery);
      $stmtUpdateLog->bind_param("sss", $kota, $keterangan, $noResiLog);
      $stmtUpdateLog->execute();
      header("Location: admin_page.php");
      exit();
    } else {
      // If nomor_resi doesn't exist, show an error message
      $alertMessage = "The nomor_resi does not exist in the database.";
    }
  }

  if (isset($_POST['delete'])) {
    $nomorResi = $_POST['nomor_resi']; // Mengambil nomor resi dari formulir

    // Membuat query untuk menghapus data dari detail_log_pengiriman dan transaksi_resi_pengiriman
    $query = "DELETE FROM detail_log_pengiriman WHERE nomor_resi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nomorResi);
    $stmt->execute();

    // Menghapus dari transaksi_resi_pengiriman
    $query2 = "DELETE FROM transaksi_resi_pengiriman WHERE nomor_resi = ?";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("s", $nomorResi);
    $stmt2->execute();

    // Mengarahkan ulang ke halaman admin setelah penghapusan
    header("Location: admin_page.php");
    exit();
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <section>
    <nav class="navbar navbar-expand-lg bg-dark ">
      <div class="container-fluid">
        <a class="navbar-brand text-light" href="#">Hello, <?php echo "$Admin" ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active text-light" aria-current="page" href="#dataPengiriman">Data Resi Pengiriman</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-light" href="#">User Admin</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-light" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </section>

  <section>
    <div class="container-fluid">
      <div class="border mt-3" style="border:1px solid black;border-radius:3px">
        <div class="box d-flex mt-3">
          <div class="resi p-2">
            <h2>Entry Nomor Resi</h2>
            <form action="#" method="POST">
              <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal: </label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" style="width:250px">
              </div>
              <div class="mb-3">
                <label for="NomorResi" class="form-label">Nomor Resi: </label>
                <input type="text" class="form-control" id="nomor_resi" name="nomor_resi" style="width:250px"
                  placeholder="RS-">
              </div>
              <div class="mb-3">
                <button type="submit" class="btn btn-dark text-white" style="width:250px;">Entry</button>
              </div>
            </form>
          </div>
        </div>

        <div class="box d-flex mt-3" id="dataPengiriman">
          <table class="table table-bordered border-black m-2">
            <thead>
              <tr>
                <th scope="col" class="col-2">Tanggal Resi</th>
                <th scope="col " class="col-5">Nomor Resi</th>
                <th scope="col " class="col-5">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM transaksi_resi_pengiriman";
              $result = $conn->query($query);
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td style='text-align:center'>" . $row["tanggal_resi"] . "</td>";
                  echo "<td>" . $row["nomor_resi"] . "</td>";
                  echo "<td>
                                    <a href='#' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#entryLogModal' data-nomor-resi='" . $row["nomor_resi"] . "'>Entry Log</a>
                                    <form action='' method='POST' style='display:inline;'>
                                        <input type='hidden' name='nomor_resi' value='" . $row['nomor_resi'] . "'>
                                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                    </form>
                                  </td>";
                  echo "</tr>";
                }
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="box  mt-3 p-2">
          <h2>Add New Admin</h2>
          <form action="#" method="POST">
            <div class="mb-3">
              <label for="username" class="form-label">Username: </label>
              <input type="text" class="form-control" id="username" name="username" style="width:250px" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password: </label>
              <input type="password" class="form-control" id="password" name="password" style="width:250px" required>
            </div>
            <div class="mb-3">
              <label for="nama_admin" class="form-label">Nama Admin: </label>
              <input type="text" class="form-control" id="nama_admin" name="nama_admin" style="width:250px" required>
            </div>
            <div class="mb-3">
              <button type="submit" class="btn btn-dark text-white" style="width:250px;">Add Admin</button>
            </div>
          </form>
        </div>
        <!-- Display Admins Table -->
        <div class="border mt-3">
          <div class="box d-flex mt-3">
            <div class="resi p-2">
              <h2>Manage Admins</h2>
              <table class="table table-bordered border-black m-2">
                <thead>
                  <tr>
                    <th scope="col" class="col-2">Username</th>
                    <th scope="col" class="col-5">Nama Admin</th>
                    <th scope="col" class="col-2">Status Aktif</th>
                    <th scope="col" class="col-3">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Handle Add Admin
                  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['nama_admin'])) {
                    $username = $_POST['username'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password storage
                    $nama_admin = $_POST['nama_admin'];

                    $query = "INSERT INTO user_admin (username, password, nama_admin) VALUES ('$username', '$password', '$nama_admin')";
                    if ($conn->query($query) === TRUE) {
                      echo "<div class='alert alert-success'>New admin added successfully.</div>";
                    } else {
                      echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                    }
                  }

                  // Handle Status Update (Activate/Deactivate)
                  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
                    $username = $_POST['username'];
                    $status_aktif = $_POST['status_aktif']; // 1 for active, 0 for inactive
                  
                    $query = "UPDATE user_admin SET status_aktif = $status_aktif WHERE username = '$username'";
                    if ($conn->query($query) === TRUE) {
                      echo "<div class='alert alert-success'>Admin status updated successfully.</div>";
                    } else {
                      echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                    }
                  }

                  // Display Admins
                  $query = "SELECT * FROM user_admin";
                  $result = $conn->query($query);
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $status = $row["status_aktif"] ? "Active" : "Inactive";
                      $status_toggle = $row["status_aktif"] ? 0 : 1; // Toggle status
                      echo "<tr>";
                      echo "<td>" . $row["username"] . "</td>";
                      echo "<td>" . $row["nama_admin"] . "</td>";
                      echo "<td>" . $status . "</td>";
                      echo "<td>
                                        <form action='' method='POST'>
                                            <input type='hidden' name='username' value='" . $row['username'] . "'>
                                            <input type='hidden' name='status_aktif' value='" . $status_toggle . "'>
                                            <button type='submit' name='update_status' class='btn btn-warning'>" . ($status == "Active" ? "Deactivate" : "Activate") . "</button>
                                        </form>
                                      </td>";
                      echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan='4'>No admins found.</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

  </section>

  <!-- Modal for Entry Log -->
  <div class="modal fade" id="entryLogModal" tabindex="-1" aria-labelledby="entryLogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="entryLogModalLabel">Entry Log Pengiriman</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body ">
          <form action="#" method="POST">
            <div class="mb-3">
              <label for="kota" class="form-label">Kota</label>
              <input type="text" class="form-control" id="kota" name="kota" required>
            </div>
            <div class="mb-3">
              <label for="keterangan" class="form-label">Keterangan</label>
              <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
            </div>
            <input type="hidden" id="nomor_resi_log" name="nomor_resi_log">
            <div class="mb-3 d-flex justify-content-center">
              <button type="submit" class="btn btn-dark text-white"
                style="width:250px;text-align:center">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script>

    var entryLogModal = document.getElementById('entryLogModal'); //ambil id entryLogModal
    entryLogModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var nomorResi = button.getAttribute('data-nomor-resi'); //ambil nomor resi
      var inputNomorResi = entryLogModal.querySelector('#nomor_resi_log');
      inputNomorResi.value = nomorResi;
    });
  </script>
</body>

</html>