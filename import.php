<?php
// Load file koneksi.php
include "koneksi.php";

if(isset($_POST['import'])){ // Jika user mengklik tombol Import
	// Load librari PHPExcel nya
	require_once 'PHPExcel/PHPExcel.php';
	
	$inputFileType = 'CSV';
	$dir = 'tmp/';
	if(is_dir($dir))
	{
		if($handle = opendir($dir))
		{
			//tampilkan semua file dalam folder kecuali
			while(($file = readdir($handle)) !== false)
			{
				echo '<a target="_blank" href="'.$dir.$file.'">'.$file.'</a><br>'."\n";

				$inputFileName = $dir.$file;
	
				$reader = PHPExcel_IOFactory::createReader($inputFileType);
				$excel = $reader->load($inputFileName);
				
				// Buat query Insert
				$sql = $pdo->prepare("INSERT INTO data_saham VALUES(:tanggal,:saham,:close,:volume,:ov,:bv,:fs,:fb,:kurs)");
				//if(empty($tanggal) && empty($saham) && empty($close) && empty($volume) && empty($offer_volume) && empty($bid_volume) && empty($foreign_sell) && empty($foreign_buy) && empty($kurs))
				
				$numrow = 1;
				$worksheet = $excel->getActiveSheet();
				foreach ($worksheet->getRowIterator() as $row) {
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
						$tanggal = $get[0]; // Ambil data NIS
						$saham = $get[1]; // Ambil data nama
						$close = $get[2]; // Ambil data jenis kelamin
						$volume = $get[3]; // Ambil data telepon
						$offer_volume = $get[4]; // Ambil data alamat
						$bid_volume = $get[5]; // Ambil data NIS
						$foreign_sell = $get[6]; // Ambil data nama
						$foreign_buy = $get[7]; // Ambil data jenis kelamin
						$kurs = $get[8]; // Ambil data telepon
						
						// Cek jika semua data tidak diisi
						if(empty($tanggal) && empty($saham) && empty($close) && empty($volume) && empty($offer_volume) && empty($bid_volume) && empty($foreign_sell) && empty($foreign_buy) && empty($kurs))
							continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)
						
						// Proses simpan ke Database
						$sql->bindParam(':tanggal', $tanggal);
						$sql->bindParam(':saham', $saham);
						$sql->bindParam(':close', $close);
						$sql->bindParam(':volume', $volume);
						$sql->bindParam(':ov', $offer_volume);
						$sql->bindParam(':bv', $bid_volume);
						$sql->bindParam(':fs', $foreign_sell);
						$sql->bindParam(':fb', $foreign_buy);
						$sql->bindParam(':kurs', $kurs);
						$sql->execute(); // Eksekusi query insert
					}
					
					$numrow++; // Tambah 1 setiap kali looping
				}
			}
			closedir($handle);
		}
	}

	
}

header('location: index.php'); // Redirect ke halaman awal
?>
