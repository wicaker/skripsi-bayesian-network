<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Rekomendasi Saham</title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		
		<!-- Style untuk Loading -->
		<style>
        #loading{
			background: whitesmoke;
			position: absolute;
			top: 140px;
			left: 82px;
			padding: 5px 10px;
			border: 1px solid #ccc;
		}
		</style>
	</head>
	<body>
		<!-- HEADER -->
		<nav class="navbar navbar-inverse" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#" style="color: white;"><b>Sistem Rekomendasi Pemilihan Sahan di Bursa Efek Indonesia Dengan Metode Bayesian</b></a>
				</div>
				<p class="navbar-text navbar-right hidden-xs" style="color: white;padding-right: 10px;">
					FOLLOW US ON &nbsp;
					<a target="_blank" style="background: #3b5998; padding: 0 5px; border-radius: 4px; color: #f7f7f7; text-decoration: none;" href="https://www.facebook.com/wicaker">Facebook</a> 
					<a target="_blank" style="background: #00aced; padding: 0 5px; border-radius: 4px; color: #ffffff; text-decoration: none;" href="https://twitter.com/">Twitter</a> 
					<a target="_blank" style="background: #d34836; padding: 0 5px; border-radius: 4px; color: #ffffff; text-decoration: none;" href="https://plus.google.com/">Google+</a>
				</p>
			</div>
		</nav>
		<!-- END HEADER -->
		
		<!-- Content -->
		<div style="padding: 0 15px;">
		<!-- Menu dengan Button -->
			<form method='post' action='bayesian.php'>
			<button type='submit' name='bayesian' class='btn btn-primary pull-right' >
					<span class='glyphicon glyphicon-check'></span>Hasil Analisa
			</button></form>
			<a href="form.php" class="btn btn-primary pull-right">
				<span class="glyphicon glyphicon-upload disabled"></span> Import Data
			</a>&emsp;&emsp;
			<a href="index.php" class="btn btn-success pull-left">
				<span class="glyphicon glyphicon-home active"></span> Home
			</a>
		<!-- Akhir Menu dengan Button -->
    		<section class="about" id="about">
      			<div class="container">
        			<div class="row">
          				<div class="col-sm-12">
            				<h2 class="text-center"><strong>Cara Menggunakan Sistem</strong></h2><hr>
          				</div>
          				<div class="col-sm-12">
            				<h4 class="text-justify">1. Buka Menu Import</h4><hr>
            				<h4 class="text-justify">2. Jika sistem sebelumnya sudah pernah digunakan, klik tombol "Hapus Data Sebelumnya"</h4><hr>
            				<h4 class="text-justify">3. Pilih choose file, klik atau pilih 6 data di minggu sebelumnya</h4><hr>
            				<h4 class="text-justify">4. Klik Priview, maka akan tampil data-data yang dipilih sebelumnya</h4><hr>
            				<h4 class="text-justify">5. Cek data apakah sudah lengkap, jika sudah lengkap silahkan klik "import"</h4><hr>
            				<h4 class="text-justify">6. Proses import selesai, Lanjut ke "Hasil Analisa" untuk melihat analisa</h4><hr>
            				<h4 class="text-justify">7. Selesai</h4><hr>
          				</div>
        			</div>
        		</div>
    		</section>	
		</div>
		<!-- Akhir Content -->

	</body>
</html>
