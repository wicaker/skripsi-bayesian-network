<?php
// Load file koneksi.php
include "koneksi.php";

if(isset($_POST['bayesian'])){
$sql = $pdo->prepare("SELECT * FROM data_saham ORDER BY saham ASC, tanggal ASC");
$sql->execute(); // Eksekusi querynya

$sort_saham = []; // array menyimpan data saham 
while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql pindahkan ke array
	$sortir = [];
	array_push($sortir, $data['saham'],$data['close'],$data['volume'],$data['offer_volume'],$data['bid_volume'],$data['foreign_sell'],$data['foreign_buy'],$data['kurs']);
	array_push($sort_saham,$sortir);
};

echo ($sort_saham);

array_push($sort_saham," "); //tambah array kosong di belakang untuk perulangan berikutnya
$kondisi_saham = [];$kondisi_close = [];$kondisi_volume= [];$kondisi_ov = [];$kondisi_bv = [];
$kondisi_fs = [];$kondisi_fb = [];$kondisi_kurs = []; // untuk menyimpan array naik atau turun
					
for ($i=1; $i < 8 ; $i++) { 
	$kondisi = []; // menyimpan kondisi setiap saham sementara
	for($j=1; $j<count($sort_saham) ; $j++){
		if($sort_saham[$j-1][0] == $sort_saham[$j][0]){
			if ($sort_saham[$j-1][$i] < $sort_saham[$j][$i]) {
				array_push($kondisi, "naik");
			}
			else if ($sort_saham[$j-1][$i] == $sort_saham[$j][$i]) {
				array_push($kondisi, "tetap");
			}
			else if($sort_saham[$j-1][$i] > $sort_saham[$j][$i]){
				array_push($kondisi, "turun");
			}
		}
		else if($sort_saham[$j-1][0] != $sort_saham[$j][0]){
			if ($i == 1 ) {
				array_push($kondisi, $sort_saham[$j-1][0]);
				array_push($kondisi_close, $kondisi);
				$kondisi = [];
			}
			else if ($i == 2) {
				array_push($kondisi_volume, $kondisi);
				$kondisi = [];
			}
			else if ($i == 3) {
				array_push($kondisi_ov, $kondisi);
				$kondisi = [];
			}
			else if ($i == 4) {
				array_push($kondisi_bv, $kondisi);
				$kondisi = [];
			}
			else if ($i == 5) {
				array_push($kondisi_fs, $kondisi);
				$kondisi = [];
			}
			else if ($i == 6) {
				array_push($kondisi_fb, $kondisi);
				$kondisi = [];
			}
			else if ($i == 7) {
				array_push($kondisi_kurs, $kondisi);
				$kondisi = [];
			}
			else{
				continue;
			}

		}
	}
}

$p_kondisi_saham = [];$p_kondisi_close = [[],[],[]];$p_kondisi_volume= [[],[],[]];$p_kondisi_ov = [[],[],[]];$p_kondisi_bv = [[],[],[]];$p_kondisi_fs = [[],[],[]];$p_kondisi_fb = [[],[],[]];$p_kondisi_kurs = [[],[],[]]; //menyimpan peluang setiap variable
$p_naik=[]; $p_turun = []; $p_tetap = []; $rekomendasi= []; // menyimpan peluang masing-masing saham

for ($i=0; $i < count($kondisi_close); $i++) { // untuk menyimpan nama-nama saham yang akan di bawah ke table hasil
	array_push($p_kondisi_saham,$kondisi_close[$i][5]);
}
// membuat perulangan untuk mencari jumlah naik,turun,tetap masing variable			
for ($i=0; $i < count($kondisi_close); $i++) {
	for ($j=0; $j < count($kondisi_close[$i])-1 ; $j++) { 
		if($kondisi_close[$i][$j] == "naik"){
			array_push($p_kondisi_close[0],"n");
		}
		else if ($kondisi_close[$i][$j] == "tetap") {
			array_push($p_kondisi_close[1],"te");
		}
		else if ($kondisi_close[$i][$j] == "turun") {
			array_push($p_kondisi_close[2],"tu");
		}
	}

	for ($j=0; $j < count($kondisi_close[$i])-1 ; $j++) { 
		if($kondisi_volume[$i][$j] == "naik"){
			array_push($p_kondisi_volume[0],"n");
		}
		else if ($kondisi_volume[$i][$j] == "tetap") {
			array_push($p_kondisi_volume[1],"te");
		}
		else if ($kondisi_volume[$i][$j] == "turun") {
			array_push($p_kondisi_volume[2],"tu");
		}
	}
	
	for ($j=0; $j < count($kondisi_close[$i])-1 ; $j++) { 
		if($kondisi_ov[$i][$j] == "naik"){
			array_push($p_kondisi_ov[0],"n");
		}
		else if ($kondisi_ov[$i][$j] == "tetap") {
			array_push($p_kondisi_ov[1],"te");
		}
		else if ($kondisi_ov[$i][$j] == "turun") {
			array_push($p_kondisi_ov[2],"tu");
		}
	}
						
	for ($j=0; $j < count($kondisi_close[$i])-1 ; $j++) { 
		if($kondisi_bv[$i][$j] == "naik"){
			array_push($p_kondisi_bv[0],"n");
		}
		else if ($kondisi_bv[$i][$j] == "tetap") {
			array_push($p_kondisi_bv[1],"te");
		}
		else if ($kondisi_bv[$i][$j] == "turun") {
			array_push($p_kondisi_bv[2],"tu");
		}
	}

	for ($j=0; $j < count($kondisi_close[$i])-1 ; $j++) { 
		if($kondisi_fs[$i][$j] == "naik"){
			array_push($p_kondisi_fs[0],"n");
		}
		else if ($kondisi_fs[$i][$j] == "tetap") {
			array_push($p_kondisi_fs[1],"te");
		}
		else if ($kondisi_fs[$i][$j] == "turun") {
			array_push($p_kondisi_fs[2],"tu");
		}
	}
	
	for ($j=0; $j < count($kondisi_close[$i])-1 ; $j++) { 
		if($kondisi_fb[$i][$j] == "naik"){
			array_push($p_kondisi_fb[0],"n");
		}
		else if ($kondisi_fb[$i][$j] == "tetap") {
			array_push($p_kondisi_fb[1],"te");
		}
		else if ($kondisi_fb[$i][$j] == "turun") {
			array_push($p_kondisi_fb[2],"tu");
		}
	}
	
	for ($j=0; $j < count($kondisi_close[$i])-1 ; $j++) { 
		if($kondisi_kurs[$i][$j] == "naik"){
			array_push($p_kondisi_kurs[0],"n");
		}
		else if ($kondisi_kurs[$i][$j] == "tetap") {
			array_push($p_kondisi_kurs[1],"te");
		}
		else if ($kondisi_kurs[$i][$j] == "turun") {
			array_push($p_kondisi_kurs[2],"tu");
		}
	}
	//menghitung peluang naik dengan bayesian
	$naik = (count($p_kondisi_close[2])/5 + count($p_kondisi_volume[0])/5 + count($p_kondisi_ov[2])/5 + count($p_kondisi_bv[0])/5 + count($p_kondisi_fs[2])/5 + count($p_kondisi_fb[0])/5 + count($p_kondisi_kurs[2])/5)/7;
	//menghitung peluang turun dengan bayesian
	$turun = (count($p_kondisi_close[0])/5 + count($p_kondisi_volume[2])/5 + count($p_kondisi_ov[0])/5 + count($p_kondisi_bv[2])/5 + count($p_kondisi_fs[0])/5 + count($p_kondisi_fb[2])/5 + count($p_kondisi_kurs[0])/5)/7;
	//menghitung peluang tetap dengan bayesian
	$tetap = 1- ($naik + $turun);
	array_push($p_naik, $naik); // push isi variable ke p_naik
	array_push($p_turun, $turun); // push isi variable ke p_turun
	array_push($p_tetap,$tetap); //// push isi variable ke p_tetap
	
	$p_kondisi_close = [[],[],[]];$p_kondisi_volume= [[],[],[]];$p_kondisi_ov = [[],[],[]];$p_kondisi_bv = [[],[],[]];$p_kondisi_fs = [[],[],[]];$p_kondisi_fb = [[],[],[]];$p_kondisi_kurs = [[],[],[]]; // kosongkan kembali
}

for ($i=0; $i < count($p_naik); $i++) { // untuk menyimpan nama-nama saham yang akan di bawah ke table hasil
	if ($p_naik[$i] > 0.5) {
		array_push($rekomendasi, "beli");
	}
	else{
		array_push($rekomendasi, "jual");
	}
}				
//echo '<pre>'; print_r($p_kondisi_saham);

// // Proses simpan ke Database
$sql = "INSERT INTO `hasil_analisa`(`saham`, `p_naik`, `p_turun`, `rekomendasi`) VALUES (:saham,:p_naik,:p_turun,:rekomendasi)";
$stmt= $pdo->prepare($sql);

for ($i=0; $i < count($p_kondisi_saham); $i++) { 
	$p_kondisi_saham1 = $p_kondisi_saham[$i];
	$p_naik1 = $p_naik[$i];
	$p_turun1 = $p_turun[$i];
	$rekomendasi1 = $rekomendasi[$i];
	$stmt->bindParam(':saham', $p_kondisi_saham1); 
	$stmt->bindParam(':p_naik', $p_naik1); 
	$stmt->bindParam(':p_turun', $p_turun1);
	$stmt->bindParam(':rekomendasi', $rekomendasi1); 
	$stmt->execute(); // Eksekusi query insert
}

}
header('location: result.php'); // Redirect ke halaman result

?>