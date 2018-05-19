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
		
		<!-- Load File jquery.min.js yang ada difolder js -->
		<script src="js/jquery.min.js"></script>
		
		<script>
		$(document).ready(function(){
			// Sembunyikan alert validasi kosong
			$("#kosong").hide();
		});
		</script>
	</head>
	<body>
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
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<!-- <a href="index.php" class="btn btn-danger pull-right">
				<span class="glyphicon glyphicon-remove"></span> Cancel
			</a> -->
			<form method='post' action='bayesian.php'>
			<button type='submit' name='bayesian' class='btn btn-primary pull-right' >
					<span class='glyphicon glyphicon-check'></span>Hasil Analisa
			</button></form>			
			<a href="form.php" class="btn btn-success pull-right">
				<span class="glyphicon glyphicon-upload disabled"></span> Import Data
			</a>&emsp;&emsp;
			<a href="index.php" class="btn btn-primary pull-left">
				<span class="glyphicon glyphicon-home active"></span> Home
			</a>
			
			
			<h3>Form Import Data</h3>
			<hr>
			
			<!-- Buat sebuah tag form dan arahkan action nya ke file ini lagi -->
			<form method="post" action="" enctype="multipart/form-data" class="pull-left">
			<!-- 	<a href="Format.csv" class="btn btn-default">
					<span class="glyphicon glyphicon-download"></span>
					Download Format
				</a> -->
				<button type='submit' name='hapus' class='btn btn-danger'>
					<span class='glyphicon glyphicon-trash'></span>Hapus Data Sebelumnya
				</button>
				<br><br>
				<input  multiple="" type="file" name="filesToUpload[]" class="pull-left"></br></br>

				<button type="submit" name="preview" class="btn btn-success btn-sm">
					<span class="glyphicon glyphicon-eye-open"></span> Preview
				</button></br></br>

			</form>
			
			<hr>
			
			<!-- Buat Preview Data -->
			<?php
			// Jika user telah mengklik tombol Preview
			if(isset($_POST['preview'])){

				foreach($_FILES['filesToUpload']['name'] as $index=>$file) {

					$nama_file_baru = 'data'.$index.'.csv';
				
					// Cek apakah terdapat file data.xlsx pada folder tmp
					if(is_file('tmp/'.$nama_file_baru)) // Jika file tersebut ada
						unlink('tmp/'.$nama_file_baru); // Hapus file tersebut
					
					$nama_file = $_FILES['filesToUpload']['name'][$index]; // Ambil nama file y
					$tmp_file = $_FILES['filesToUpload']['tmp_name'][$index];
					$ext = pathinfo($nama_file, PATHINFO_EXTENSION); // Ambil ekstensi file yang akan diupload
					
					// Cek apakah file yang diupload adalah file CSV
					if($ext == "csv"){
						// Upload file yang dipilih ke folder tmp
						move_uploaded_file($tmp_file, 'tmp/'.$nama_file_baru);
						
						// Load librari PHPExcel nya
						require_once 'PHPExcel/PHPExcel.php';
						
						$inputFileType = 'CSV';
						$inputFileName = 'tmp/data'.$index.'.csv';

						$reader = PHPExcel_IOFactory::createReader($inputFileType);
						$excel = $reader->load($inputFileName);
						
						if($index == 0) {
							// Buat sebuah tag form untuk proses import data ke database
							echo "<form method='post' action='import.php'>";
							
							
							// Buat sebuah div untuk alert validasi kosong
							echo "<div class='alert alert-danger' id='kosong'>
							Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum lengkap diisi.
							</div>";
							
							echo "<table class='table table-bordered'>
							<tr>
								<th colspan='9' class='text-center'>Preview Data</th>
							</tr>
							<tr>
								<th>Tanggal</th>
								<th>Kode Saham</th>
								<th>Close Price</th>
								<th>Volume</th>
								<th>Offer Volume</th>
								<th>Bid Volume</th>
								<th>Foreign Sell</th>
								<th>Foreign Buy</th>
								<th>Kurs</th>
							</tr>";
						}
						
						
						$numrow = 1;
						$kosong = 0;
						$worksheet = $excel->getActiveSheet();
						foreach ($worksheet->getRowIterator() as $row) { // Lakukan perulangan dari data yang ada di csv
							// Cek $numrow apakah lebih dari 1
							// Artinya karena baris pertama adalah nama-nama kolom
							// Jadi dilewat saja, tidak usah diimport
							if($numrow > 1){
								// START -->
								// Skrip untuk mengambil value nya
								$cellIterator = $row->getCellIterator();
								$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
								
								$get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
								foreach ($cellIterator as $cell) {
									array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
								}
								// <-- END
								
								// Ambil data value yang telah di ambil dan dimasukkan ke variabel $get
								$tanggal = $get[0]; // Ambil data tanggal
								$saham = $get[1]; // Ambil data nama saham
								$close = $get[2]; // Ambil data close
								$volume = $get[3]; // Ambil data volume
								$offer_volume = $get[4]; // Ambil data offer volume
								$bid_volume = $get[5]; // Ambil data bid volume
								$foreign_sell = $get[6]; // Ambil data foreign sell
								$foreign_buy = $get[7]; // Ambil data foreign buy
								$kurs = $get[8]; // Ambil data kurs
								
								// Cek jika semua data tidak diisi
								if(empty($tanggal) && empty($saham) && empty($close) && empty($volume) && empty($offer_volume) && empty($bid_volume) && empty($foreign_sell) && empty($foreign_buy) && empty($kurs))
									continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)
								
								// Validasi apakah semua data telah diisi
								$tanggal_td = ( ! empty($tanggal))? "" : " style='background: #E07171;'"; // Jika tanggal kosong, beri warna merah
								$saham_td = ( ! empty($saham))? "" : " style='background: #E07171;'"; // Jika Nama saham kosong, beri warna merah
								$close_td = ( ! empty($close))? "" : " style='background: #E07171;'"; // Jika close kosong, beri warna merah
								$volume_td = ( ! empty($volume))? "" : " style='background: #E07171;'"; // Jika volume kosong, beri warna merah
								$ov_td = ( ! empty($offer_volume))? "" : " style='background: #E07171;'"; // Jika offer volume kosong, beri warna merah
								$bv_td = ( ! empty($bid_volume))? "" : " style='background: #E07171;'"; // Jika bid volume, beri warna merah
								$fs_td = ( ! empty($foreign_sell))? "" : " style='background: #E07171;'"; // Jika foreign sell, beri warna merah
								$fb_td = ( ! empty($foreign_buy))? "" : " style='background: #E07171;'"; // Jika foreign buy kosong, beri warna merah
								$kurs_td = ( ! empty($kurs))? "" : " style='background: #E07171;'"; // Jika kurs kosong, beri warna merah
								
								// Jika salah satu data ada yang kosong
								if(empty($tanggal) or empty($saham) or empty($close) or empty($volume) or empty($offer_volume) or empty($bid_volume) or empty($foreign_sell) or empty($foreign_buy) or empty($kurs)){
									$kosong++; // Tambah 1 variabel $kosong
								}
								
								echo "<tr>";
								echo "<td".$tanggal_td.">".$tanggal."</td>";
								echo "<td".$saham_td.">".$saham."</td>";
								echo "<td".$close_td.">".$close."</td>";
								echo "<td".$volume_td.">".$volume."</td>";
								echo "<td".$ov_td.">".$offer_volume."</td>";
								echo "<td".$bv_td.">".$bid_volume."</td>";
								echo "<td".$fs_td.">".$foreign_sell."</td>";
								echo "<td".$fb_td.">".$foreign_buy."</td>";
								echo "<td".$kurs_td.">".$kurs."</td>";
								echo "</tr>";
							}
							
							$numrow++; // Tambah 1 setiap kali looping
						}

						if($index == count($_FILES['filesToUpload']['name'])-1) {
							echo "</table>";
						
							// Cek apakah variabel kosong lebih dari 1
							// Jika lebih dari 1, berarti ada data yang masih kosong
							if($kosong > 1){
							?>	
								<script>
								$(document).ready(function(){
									// Ubah isi dari tag span dengan id jumlah_kosong dengan isi dari variabel kosong
									$("#jumlah_kosong").html('<?php echo $kosong; ?>');
									
									$("#kosong").show(); // Munculkan alert validasi kosong
								});
								</script>
							<?php
							}else{ // Jika semua data sudah diisi
								echo "<hr>";
								
								// Buat sebuah tombol untuk mengimport data ke database
								echo "<button type='submit' name='import' class='btn btn-primary'><span class='glyphicon glyphicon-upload'></span> Import</button>";
								
							}
							
							echo "</form>";
						}
						
						
					}else{ // Jika file yang diupload bukan File CSV
						// Munculkan pesan validasi
						echo "<div class='alert alert-danger'>
						Hanya File CSV (.csv) yang diperbolehkan
						</div>";
					}
				}

				
				
			}

			if(isset($_POST['hapus'])){
				include "koneksi.php";
				$sql = $pdo->prepare("TRUNCATE TABLE data_saham ");
				$sql->execute(); 
				$sql = $pdo->prepare("TRUNCATE TABLE hasil_analisa ");
				$sql->execute(); 
			}	
			
			?>
		</div>
	</body>
</html>

