<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Main Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
  <section>
      <nav class="navbar navbar-expand-lg bg-dark ">
        <div class="container-fluid">
          <a class="navbar-brand text-light" href="#">WELCOME</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link active text-light" aria-current="page" href="login.php">Login Admin</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </section>

    <!-- input resi -->
    <section>
      <div class="container-fluid" >
        <div class="border mt-3" style="border:1px solid black;border-radius:3px">
          <div class="box d-flex mt-3">
            <div class="resi p-2">
              <h2>Cek pengiriman</h2>
              <form action="#" method="POST">
                <div class="mb-3 mt-3 d-flex">
                  <input type="text" class="form-control" id="nomor pengiriman" style="width:250px" placeholder="nomor pengiriman">
                  <button type = "submit" class="btn btn-dark text-white" style="margin-left:5px">Entry</button>
                </div>
              </form>
            </div>
          </div>

          <div class="box d-flex mt-3" id="dataPengiriman">
            <table class="table table-bordered border-black m-2">
              <thead>
                <tr>
                  <th scope="col" class="col-2">Tanggal Resi</th>
                  <th scope="col " class="col-5">Kota</th>
                  <th scope="col " class="col-5">Keterangan</th>
                </tr>
                <!-- PHP DISINI -->

              </thead>
            </table>
          </div>
        </div>
      </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>