<?php
	require_once('../../../../../config/config.php');
	require_once('../../../../../config/terbilang.php');
	require_once('ppjb_proses.php');
?>

<?php
	$bilangan = new Terbilang;
	//echo $bilangan -> eja(100000000000000012);
	//Format Tanggal Berbahasa Indonesia 
	// Array Hari
	$array_hari = array(1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
	$hari = $array_hari[date('N')];
	
	//Format Tanggal 
	$tanggal = date('j');
	
	//Array Bulan 
	$array_bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	$bulan = $array_bulan[date('n')];
	
	//Format Tahun 
	$tahun = date('Y');
	
	$nama_file="PPJB ".$nama_pembeli." ". $tanggal . " " . $bulan . " " . $tahun .".doc";
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"" . basename($nama_file) . "\"");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<?php 
	$query = "
	SELECT *
	FROM
	CS_PARAMETER_PPJB";
	$obj = $conn->execute($query);
	
	//DATA PEMBELI
	$NAMA_PT 			= $obj->fields['NAMA_PT'];
	$NAMA_DEP 			= $obj->fields['NAMA_DEP'];
	$NAMA_PEJABAT 		= $obj->fields['NAMA_PEJABAT'];
	$NAMA_JABATAN 		= $obj->fields['NAMA_JABATAN'];
	$PEJABAT_PPJB 		= $obj->fields['PEJABAT_PPJB'];
	$JABATAN_PPJB 		= $obj->fields['JABATAN_PPJB'];
	$NOMOR_SK 			= $obj->fields['NOMOR_SK'];
	$TANGGAL_SK 		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SK'])));
	
	
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<style type="text/css">
			@media screen, print{
			body{
			
			@page {
			size: A4;
			margin: 0;
			}
			.page-break	{ display: block; page-break-before: always; height: 200px;}
			
			}
			
			p{
			text-align: justify;
			text-justify: inter-word;
			}
			}
			
			.line-sum {
			border:none;
			border-top:1px solid #000;
			margin:0;
			padding:0 0 2px 0;
			}
			
			.wrap {
			font-family: "Times New Roman", Times, serif;
			position: relative;
			margin: 0px 80px 0px 80px;
			}
			
			.left {
			float: left;
			width: 438px;
			padding:0 1px 0 1px;
			margin: 56px 0 0 0;
			}
			
			.mid {
			float: left;
			width: 24px;
			}
			
			#right {
			float: left;
			width: 334px;
			padding: 0 1px 0 1px;
			margin: 56px 0 0 0;
			}
			
			.clear { clear: both; }
			.text-left { text-align: left; }
			.text-right { text-align: right; }
			.va-top { vertical-align:top; }
		</style>
	</head>
	<body onload="window.print()">
		<p style="text-align: center;"><strong>PERJANJIAN PENGIKATAN JUAL BELI [PPJB]</strong></p>
		<p style="text-align: center;"><strong>TANAH DAN BANGUNAN</strong></p>
		
		<h4 style="text-align: center;">DI PROYEK PERUMAHAN BINTARO JAYA</h4>
		<p style="text-align: center;"><strong>Nomor: </strong><strong><?php echo $nomor; ?></strong></p>
		&nbsp;
		
		Pada hari ini <strong><?php echo $hari; ?></strong> tanggal <strong><?php echo $tanggal; ?></strong> bulan <strong><?php echo $bulan; ?></strong> tahun <strong><?php echo $tahun; ?></strong> [<strong><?php echo $bilangan -> eja($tahun);?></strong>] yang bertandatangan dibawah ini:
		
		&nbsp;
		<ol>
			<li><strong><?php echo $PEJABAT_PPJB ?></strong>, selaku <strong><?php echo $JABATAN_PPJB ?></strong> berdasarkan Surat Kuasa Direksi <?php echo $NAMA_PT ?> , Tbk. Nomor <strong><?php echo $NOMOR_SK ?> </strong>tertanggal <strong><?php echo $TANGGAL_SK ?> </strong>dari dan oleh karenanya bertindak untuk dan atas nama , badan hukum Indonesia berkedudukan di Jakarta, berkantor di Bintaro Trade Centre Blok K, Jl. Jend. Sudirman, Bintaro Jaya Sektor VII, Tangerang-15224, untuk selanjutnya dalam Perjanjian ini disebut <strong>JAYA</strong>;</li>
		</ol>
		&nbsp;
		<ol start="2">
			<li><strong><?php echo $nama_pembeli; ?></strong>, selaku pribadi, yang beralamat <strong>di </strong><strong><?php echo $alamat; ?></strong>, untuk selanjutnya dalam perjanjian ini disebut <strong>PEMBELI</strong>.</li>
		</ol>
		&nbsp;
		
		JAYA dan PEMBELI [untuk selanjutnya dalam Perjanjian ini disebut “Para Pihak”] terlebih dahulu menerangkan hal-hal sebagai berikut:
		
		&nbsp;
		<ol>
			<li>Bahwa JAYA adalah suatu perusahaan pembangun perumahan yang telah mendapat ijin untuk mengembangkan wilayah pemukiman Bintaro Jaya.</li>
		</ol>
		&nbsp;
		<ol start="2">
			<li>Bahwa JAYA bermaksud menjual sebagaimana PEMBELI bermaksud membeli sebagian dari tanah tersebut berikut bangunan yang didirikan di atasnya, serta kondisi lainnya yang akan diuraikan lebih lanjut di dalam perjanjian ini.</li>
		</ol>
		&nbsp;
		
		Berdasarkan hal-hal tersebut di atas, dengan ini Para Pihak sepakat untuk mengadakan Perjanjian Pengikatan Jual Beli Tanah dan Bangunan [selanjutnya disebut “PERJANJIAN”] sesuai dengan syarat dan kondisi-kondisi yang ditentukan dalam Pasal-Pasal di bawah ini:
		
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<h2 style="text-align: center;" >PASAL 1</h2>
		<p style="text-align: center;"><strong>OBYEK PERJANJIAN</strong></p>
		&nbsp;
		<ol>
			<li>JAYA dengan ini berjanji dan mengikat diri baik sekarang dan untuk kemudian pada waktunya menjual dan menyerahkan kepada PEMBELI sebagaimana PEMBELI dengan ini berjanji dan mengikatkan dirinya sekarang dan untuk kemudian pada waktunya membeli dan menerima penyerahan dari JAYA atas TANAH DAN BANGUNAN yang disebutkan pada ayat 2 Pasal ini.</li>
		</ol>
		&nbsp;
		<ol start="2">
			<li>Para Pihak sepakat bahwa yang menjadi obyek dari PERJANJIAN ini adalah sebagai berikut:</li>
		</ol>
		&nbsp;
		
		[i]    Sebidang tanah seluas <strong><?php echo $luas_tanah.' m&sup2;'; ?></strong> [<strong><em><?php echo $bilangan -> eja($luas_tanah);?></em></strong> meter persegi] yang terletak di Proyek Perumahan Bintaro Jaya, Kelurahan <strong><?php echo $nama_kelurahan ?></strong>, Kecamatan <strong><?php echo $nama_kecamatan ?></strong> Kabupaten Tangerang [untuk selanjutnya disebut “TANAH”].
		
		&nbsp;
		
		[ii]   Bangunan rumah tinggal seluas <strong><?php echo $luas_bangunan.' m&sup2;'; ?></strong> <strong>[</strong><strong><em><?php echo $bilangan -> eja($luas_bangunan);?></em></strong> meter persegi] yang terletak di proyek Bintaro Jaya, kaveling <strong>blok </strong><strong><?php echo $kode_blok; ?></strong>, type <strong><?php echo $tipe_bangunan ?></strong> yang berdiri di atas TANAH sesuai ayat 2 [i] pada Pasal ini [untuk selanjutnya disebut “BANGUNAN”].
		
		&nbsp;
		
		TANAH dan BANGUNAN tersebut di atas untuk selanjutnya disebut “TANAH DAN BANGUNAN”.
		
		&nbsp;
		<ol start="3">
			<li>Para Pihak sepakat bahwa apabila ukuran luas TANAH yang menjadi obyek dari PERJANJIAN ini berbeda luasnya dengan ukuran yang ditentukan dalam Surat Ukur/Gambar Situasi atau Sertifikat yang dikeluarkan oleh Kantor Badan Pertanahan Nasional , maka Para Pihak akan mematuhi serta mengikuti hasil pengukuran dari Kantor Badan Pertanahan Nasional Kabupaten yang bersangkutan, dan oleh karenanya Para Pihak akan mengadakan perhitungan satu sama lain sesuai dengan harga tanah yang berlaku pada saat ditanda-tanganinya PERJANJIAN ini.</li>
		</ol>
		&nbsp;
		<ol start="4">
			<li>PEMBELI tidak diijinkan untuk memperluas TANAH yang dibeli dari JAYA [Obyek Perjanjian Pasal 1 ayat 2] ke tanah sekelilingnya di luar tanah milik JAYA yang dibeli langsung ataupun tidak langsung dari pihak lain selain JAYA.</li>
		</ol>
		&nbsp;
		<ol start="5">
			<li>Dalam hal PEMBELI ingin memperluas tanah yang telah dimilikinya di wilayah Perumahan JAYA baik secara langsung maupun tidak langsung, maka tanah yang dibeli oleh PEMBELI harus tanah yang termasuk dalam pemilikan JAYA dan atau melalui proses pengalihan hak kepemilikan yang berasal dari JAYA.</li>
		</ol>
		
		<p style="text-align: center;"><strong>PASAL 2</strong></p>
		<p style="text-align: center;"><strong>HARGA TANAH DAN BANGUNAN SERTA CARA PEMBAYARAN</strong></p>
		&nbsp;
		<ol>
			<li>Para Pihak sepakat bahwa harga TANAH per meter persegi adalah sebesar <strong>Rp.</strong> <strong><?php echo $harga_tanah; ?></strong>,- (<strong><em><?php echo  $bilangan -> eja($harga_tanah); ?></em></strong> <strong><em>Rupiah</em></strong>).</li>
		</ol>
		&nbsp;
		
		<li>Para Pihak sepakat bahwa harga TANAH DAN BANGUNAN yang menjadi obyek PERJANJIAN ini adalah sebesar <strong>R</strong><strong>p. </strong><strong><?php echo $total_harga ?> </strong> [<strong><em><?php echo $bilangan -> eja($total_harga) ?></em></strong>].</li>
	</ol>
	&nbsp;
	
	Harga sebagaimana dimaksud butir a dan b pasal ini belum termasuk Pajak Pertambahan Nilai [PPN] yang dimaksud pada ayat 2 Pasal ini.
	
	&nbsp;
	<ol start="1">
		<li>PEMBELI menyetujui serta mengikatkan dirinya untuk membayar Pajak Pertambahan Nilai atas TANAH DAN BANGUNAN sesuai dengan ketentuan hukum yang berlaku pada saat atau bersamaan dengan setiap pembayaran sebagaimana yang dimaksud pada ayat 4 Pasal ini yaitu sebesar <strong><?php echo 'Rp. '.$total_ppn; ?></strong> [<strong><em><?php echo $bilangan->eja($total_ppn); ?></em></strong>]</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>PEMBELI sepakat serta mengikatkan diri untuk membayar pajak yang timbul sehubungan dengan PERJANJIAN ini selain PPN yang dimaksud ayat 2 Pasal ini.</li>
	</ol>
	&nbsp;
	<ol start="3">
		<li>PEMBELI menyetujui serta mengikatkan diri untuk melakukan pembayaran atas harga TANAH DAN BANGUNAN berikut PPN dengan cara-cara sebagai berikut:</li>
	</ol>
	&nbsp;
	<ol start="4">
		<li>Pembayaran Pertama [uang tanda jadi] sebesar <strong><?php echo 'Rp. '.$nilai_tanda_jadi; ?></strong> [<strong><em><?php echo  $bilangan -> eja($nilai_tanda_jadi); ?></em></strong>] dengan cara mengisi Surat Persetujuan Pembelian yang merupakan lampiran yang tidak dapat dipisahkan dengan PERJANJIAN ini dan untuk sahnya pembayaran tersebut, JAYA akan mengeluarkan kwitansi.</li>
		<li>Sisa pembayaran sebesar <strong></strong><strong><?php echo 'Rp. '.$sisa_pembayaran; ?></strong> [<strong><em><?php echo  $bilangan -> eja($sisa_pembayaran); ?></em></strong>] yang akan dibayarkan dengan cara sesuai dengan jadwal pembayaran terlampir yang merupakan bagian yang tidak terpisahkan dengan PERJANJIAN ini [Lampiran 1].</li>
	</ol>
	<br />
	<br />
	<br />
	<h2 style="text-align: center;" >PASAL 3</h2>
	<p style="text-align: center;"><strong>KELALAIAN PEMBAYARAN</strong></p>
	&nbsp;
	<ol>
		<li>Dalam hal PEMBELI terlambat atau lalai untuk membayar angsuran harga TANAH DAN BANGUNAN serta PPN sebagaimana dimaksud pada Pasal 2 ayat 4 di atas, maka PEMBELI dikenakan denda keterlambatan sebesar 1‰ [satu permil] dari jumlah angsuran yang telah jatuh tempo untuk tiap hari keterlambatan dengan maksimal 3% [tiga persen] dari nilai terhutang.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Dalam hal PEMBELI lalai membayar angsuran TANAH DAN BANGUNAN berikut PPN, denda-denda dan biaya-biaya lain yang terhutang sampai mencapai denda maksimal, maka PERJANJIAN ini menjadi batal demi hukum dan berlaku ketentuan-ketentuan tentang pembatalan PERJANJIAN.</li>
	</ol>
	&nbsp;
	<h2 style="text-align: center;" >PASAL 4</h2>
	<p style="text-align: center;"><strong>BANGUNAN RUMAH</strong></p>
	&nbsp;
	<ol>
		<li>Bangunan ready stock:</li>
	</ol>
	&nbsp;
	
	Dalam hal BANGUNAN yang menjadi obyek dari PERJANJIAN ini adalah Ready Stock, maka PEMBELI menyetujui serta mengikatkan dirinya untuk melakukan pembayaran atas harga rumah beserta PPN sebagaimana diatur pada Pasal 2 di atas.
	
	&nbsp;
	<ol start="2">
		<li>Bangunan bukan ready stock:</li>
	</ol>
	&nbsp;
	<ul>
		<li>Dalam hal BANGUNAN yang menjadi obyek dari PERJANJIAN ini bukan ready stock akan tetapi memerlukan suatu jangka waktu tertentu untuk membangun, maka JAYA dengan ini berjanji dan mengikatkan dirinya untuk melaksanakan pendirian BANGUNAN.</li>
	</ul>
	&nbsp;
	<ul>
		<li>JAYA berkewajiban untuk menyelesaikan pendirian BANGUNAN selambat-lambatnya <strong>«masa_bangun»</strong> bulan terhitung sejak ditandatanganinya PERJANJIAN ini, kecuali karena hal-hal yang disebabkan oleh atau terjadinya Force Majeure yang merupakan hal yang di luar kemampuan JAYA antara lain bencana alam, banjir, kebakaran, perang, pemogokan, huru-hara, dan peraturan-peraturan/kebijakan pemerintah.</li>
	</ul>
	&nbsp;
	<ul>
		<li>Atas keterlambatan penyelesaian BANGUNAN oleh JAYA, maka JAYA dikenakan denda sebesar 1% (satu persen) perbulan dari harga total BANGUNAN sebelum PPN, yang dihitung sejak jatuh temponya kewajiban tersebut sampai dengan denda maksimal sebesar 5% (lima persen). Denda tersebut akan dibayarkan setelah serah terima BANGUNAN dengan ketentuan PEMBELI tidak pernah melalaikan kewajiban-kewajibannya seperti yang tercantum dalam PERJANJIAN ini dan tidak membatalkan PERJANJIAN ini. Dalam hal keterlambatan tersebut disebabkan oleh adanya peristiwa Force Majeure, keterlambatan pemasangan instalasi listrik PLN, atau keterlambatan lainnya di luar kekuasaan JAYA, maka ketentuan mengenai denda ini tidak berlaku.</li>
	</ul>
	&nbsp;
	<ul>
		<li>Dalam hal PEMBELI lalai melakukan pembayaran sebagaimana diatur dalam Pasal 3 ayat 1 dan telah mencapai denda maksimal, maka JAYA tidak diwajibkan meneruskan pendirian BANGUNAN tersebut dan oleh karenanya jangka waktu penyerahan BANGUNAN beserta segala akibat hukumnya yang dimaksud pada ayat 2 angka 2.2. di atas maupun Pasal 5 di bawah ini tidak berlaku dan dalam hal demikian maka jangka waktu penyerahan BANGUNAN tersebut ditentukan sepenuhnya oleh JAYA.</li>
	</ul>
	&nbsp;
	<ul>
		<li>Sehubungan dengan pendirian BANGUNAN sebagaimana dimaksud pada ayat 2.1. Pasal ini, maka Para Pihak sepakat untuk mengatur pengadaan air bersih dan pengadaan listrik sebagai berikut:</li>
	</ul>
	&nbsp;
	<ol>
		<li>Pengadaan air bersih dilakukan JAYA dengan menyediakan sumber air bersih dari Deep Well, dalam hal diperlukan upaya khusus untuk memenuhi standar PAM maka segala biaya yang diperlukan untuk itu menjadi tanggungan PEMBELI.</li>
	</ol>
	&nbsp;
	<ol>
		<li>Pengadaan daya listrik dilakukan oleh JAYA dari jaringan yang disediakan oleh PLN sebesar <strong>«watt»</strong> watt, dalam hal diperlukan daya yang lebih besar maka penambahan biaya ditanggung PEMBELI.</li>
	</ol>
	&nbsp;
	<ul>
		<li>Para Pihak sepakat bahwa apabila dalam masa pendirian BANGUNAN pada Pasal 4 ayat 2.2 di atas terjadi kenaikan harga BANGUNAN yang disebabkan karena kebijakan pemerintah di bidang moneter, maka kenaikan atas harga BANGUNAN tersebut akan dibebankan kepada PEMBELI dan akan diberitahukan oleh JAYA kepada PEMBELI untuk selanjutnya diperhitungkan oleh JAYA dan kemudian harus dibayarkan oleh PEMBELI bersama-sama dengan pembayaran yang dimaksud pada Pasal 2 di atas.</li>
	</ul>
	&nbsp;
	<ul>
		<li>Dalam hal PEMBELI berkeberatan atas kenaikan harga yang ditetapkan oleh JAYA maka PERJANJIAN ini menjadi batal demi hukum dan berlaku ketentuan-ketentuan tentang pembatalan Perjanjian.</li>
	</ul>
	&nbsp;
	<h2 style="text-align: center;" >PASAL 5</h2>
	<p style="text-align: center;"><strong>SERAH TERIMA TANAH DAN BANGUNAN</strong></p>
	&nbsp;
	<ol>
		<li>Dalam hal PEMBELI selesai memenuhi kewajiban untuk membayar harga TANAH DAN BANGUNAN, PPN, denda-denda [jika ada] serta pajak dan biaya yang timbul dari PERJANJIAN ini serta BANGUNAN telah selesai, maka Para Pihak akan menandatangani Berita Acara Serah Terima TANAH DAN BANGUNAN yang merupakan bagian yang tidak dapat dipisahkan dari PERJANJIAN ini.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Dalam waktu 30 [tiga puluh] hari sebelum dilakukannya serah terima TANAH DAN BANGUNAN yang dimaksud dalam ayat 1 Pasal ini, JAYA akan memberitahukan secara tertulis tentang maksud dari serah terima TANAH DAN BANGUNAN kepada PEMBELI.</li>
	</ol>
	&nbsp;
	<ol start="3">
		<li>Apabila setelah jangka waktu surat pemberitahuan pada ayat 2 Pasal ini ternyata pihak PEMBELI atau karena satu dan lain hal tidak dapat untuk menandatangani Berita Acara Serah Terima tersebut, maka dengan lewatnya waktu tersebut:</li>
	</ol>
	&nbsp;
	<ul>
		<li>PEMBELI telah dianggap menerima TANAH DAN BANGUNAN yang menjadi obyek PERJANJIAN ini dan karenanya JAYA telah memenuhi kewajiban untuk menyerahkan TANAH DAN BANGUNAN dalam tenggang waktu yang dimaksud ayat 1 Pasal ini.</li>
	</ul>
	&nbsp;
	<ul>
		<li>Segala biaya dan beban lain yang terhutang antara lain tidak terbatas pada Iuran Pemeliharaan Lingkungan (IPL), tagihan listrik bulanan serta beban-beban lain yang dipungut oleh pihak yang berwajib, seluruhnya menjadi beban dan tanggungan PEMBELI.</li>
	</ul>
	&nbsp;
	<ol start="4">
		<li>Dalam hal terjadinya ketentuan pada ayat 3 Pasal ini, maka JAYA dibebaskan dari segala akibat maupun konsekuensi yang timbul karenanya, termasuk tetapi tidak terbatas pada pembayaran rekening listrik, air, Iuran Pemeliharaan Lingkungan (IPL), telepon, dan segala kewajiban yang lain. Dengan demikian, kewajiban-kewajiban tersebut menjadi tanggung jawab PEMBELI.</li>
	</ol>
	&nbsp;
	<ol start="5">
		<li>Dalam hal JAYA berhasil melaksanakan BANGUNAN lebih cepat dari jangka waktu yang dimaksud pada Pasal 4 ayat 2.2. di atas, dan dalam hal PEMBELI telah memenuhi kewajibannya untuk membayar harga TANAH DAN BANGUNAN berikut pajak dan biaya yang ditentukan dalam Pasal 2 di atas, maka TANAH DAN BANGUNAN yang menjadi obyek PERJANJIAN ini dapat diserahterimakan oleh JAYA kepada PEMBELI, satu dan lain hal dengan memperhatikan pada ketentuan ayat 1 sampai dengan ayat 4 Pasal ini.</li>
	</ol>
	<h3></h3>
	<h2 style="text-align: center;" >PASAL 6</h2>
	<p style="text-align: center;"><strong>PEMELIHARAAN BANGUNAN</strong></p>
	&nbsp;
	<ol>
		<li>Dengan dilakukannya serah terima TANAH DAN BANGUNAN maka artinya PEMBELI telah menerima TANAH DAN BANGUNAN dalam keadaan baik sehingga segala tanggung jawab untuk memelihara dan menjaganya menjadi tugas dan tanggung jawab PEMBELI sepenuhnya.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Terhitung sejak tanggal serah terima TANAH DAN BANGUNAN tersebut, JAYA berkewajiban untuk melakukan pemeliharaan atas struktur dan kebocoran atap BANGUNAN selama 12 [dua belas] bulan, sedangkan untuk non struktur selama 3 [tiga] bulan sesuai dengan ketentuan pemeliharaan yang berlaku, kecuali dalam hal terjadinya keadaan yang dimaksud pada ayat 3 Pasal ini.</li>
	</ol>
	&nbsp;
	<ol start="3">
		<li>Para Pihak sepakat bahwa apabila selama berlangsungnya masa pemeliharaan terjadi kerusakan-kerusakan pada BANGUNAN yang disebabkan oleh keadaan force majeure antara lain seperti gempa bumi, banjir, huru-hara, perang, kebakaran dan tindakan kekerasan oleh pihak lain baik secara perseorangan maupun secara masal, atau karena perbaikan dan perubahan yang dilakukan PEMBELI atau pihak ketiga yang berhubungan dengan PEMBELI, maka JAYA dibebaskan dari kewajiban untuk melakukan perbaikan atas kerusakan-kerusakan yang terjadi dan oleh karenanya hal tersebut merupakan beban dan tanggung jawab PEMBELI.</li>
	</ol>
	&nbsp;
	<h2 style="text-align: center;" >PASAL 7</h2>
	<p style="text-align: center;"><strong>PENGGUNAAN TANAH DAN BANGUNAN</strong></p>
	&nbsp;
	<ol>
		<li>PEMBELI dilarang menggunakan TANAH DAN BANGUNAN sebagaimana dimaksud Pasal 4 selain sebagai rumah tinggal. Segala akibat yang timbul karena penggunaan yang tidak sesuai dengan tujuan peruntukannya tersebut menjadi tanggungan PEMBELI.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>PEMBELI tidak diijinkan untuk mengubah batas dan ukuran TANAH yang telah ditetapkan oleh pihak JAYA, yang harus sesuai dengan transaksi pembelian yang telah dilakukan.</li>
	</ol>
	&nbsp;
	<ol start="3">
		<li>Sehubungan dengan penggunaan TANAH DAN BANGUNAN, PEMBELI terikat dan senantiasa harus mentaati Ketentuan Umum Lingkungan Perumahan, yang dikeluarkan oleh JAYA dan atau RT [Rukun Tetangga] setempat antara lain, tetapi tidak terbatas pada:</li>
	</ol>
	&nbsp;
	<ol>
		<li>Peraturan tentang Retribusi/pembayaran air bersih.</li>
		<li>Peraturan tentang Pemeliharaan dan Kebersihan Lingkungan.</li>
		<li>Peraturan tentang Perbaikan dan Perubahan Bangunan.</li>
	</ol>
	<strong> </strong>
	<p style="text-align: center;"><strong>PASAL 8</strong></p>
	<p style="text-align: center;"><strong>PERUBAHAN BANGUNAN</strong></p>
	<p style="text-align: center;"></p>
	
	<ol>
		<li>Para Pihak sepakat bahwa selama masa pendirian BANGUNAN, tanpa persetujuan dari JAYA, PEMBELI tidak diperkenankan untuk menghubungi dan memerintahkan para petugas JAYA di lapangan yang sifatnya melakukan pekerjaan tambah kurang atau perubahan atas BANGUNAN.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Dalam hal PEMBELI melakukan hal-hal yang disebut pada ayat 1 di atas, maka segala biaya yang timbul karenanya dan segala hal yang terjadi akibat keterlambatan atas penyerahan TANAH DAN BANGUNAN dalam jangka waktu yang dimaksud pada Pasal 4 dan Pasal 5 di atas menjadi beban dan tanggung jawab PEMBELI, dan oleh karenanya PEMBELI membebaskan JAYA dari segala tuntutan yang timbul karenanya.</li>
	</ol>
	&nbsp;
	<ol start="3">
		<li>Pada saat akan melakukan renovasi atas BANGUNAN, PEMBELI diwajibkan meminta persetujuan tertulis dari JAYA terlebih dahulu dan membayar Iuran Pemeliharan Lingkungan (IPL) dan Safe Deposit sesuai dengan ketentuan yang ditetapkan oleh JAYA, selambat-lambatnya 30 (tiga puluh) hari sebelum renovasi atas BANGUNAN dilakukan, sebagai jaminan yang akan diperhitungkan dengan kerusakan-kerusakan yang mungkin terjadi akibat renovasi atas BANGUNAN tersebut.</li>
	</ol>
	&nbsp;
	<ol start="4">
		<li>Untuk TANAH yang terletak di dalam Cluster di Perumahan Bintaro Jaya PEMBELI menyetujui serta mengikatkan dirinya pada ketentuan JAYA untuk tidak menggunakan pagar dalam melaksanakan pendirian bangunan di atas TANAH tersebut guna menjaga keserasian dengan bangunan-bangunan yang ada di sekitarnya.</li>
	</ol>
	&nbsp;
	<ol start="5">
		<li>Dalam hal PEMBELI melakukan hal-hal yang disebut pada ayat 1 dan ayat 3 di atas, maka pemeliharaan atas BANGUNAN yang menjadi kewajiban JAYA seperti yang dimaksud pada Pasal 6 ayat 2 akan dibebaskan dan PEMBELI setuju dengan membebaskan JAYA dari kewajibannya untuk pemeliharaan atas BANGUNAN.</li>
	</ol>
	&nbsp;
	<ol start="6">
		<li>Setiap pelanggaran atas ketentuan-ketentuan dalam PERJANJIAN ini oleh PEMBELI akan dikenakan sanksi oleh JAYA termasuk tapi tidak terbatas pada pembongkaran BANGUNAN dengan beban biaya PEMBELI sepenuhnya dan atau dapat dibatalkannya PERJANJIAN ini oleh JAYA.</li>
	</ol>
	
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<h2 style="text-align: center;" >PASAL 9</h2>
	
	<h3 style="text-align: center;">JAMINAN JAYA</h3>
	&nbsp;
	
	JAYA menjamin PEMBELI sepenuhnya bahwa obyek PERJANJIAN ini:
	
	&nbsp;
	<ol>
		<li>Adalah merupakan hak JAYA sepenuhnya dan tidak ada pihak lain yang ikut memiliki atau mempunyai hak yang lebih kuat.</li>
		<li>Saat ini tidak dalam keadaan sengketa dan tidak disita oleh instansi yang berwenang.</li>
		<li>Tidak terikat sebagai jaminan dalam bentuk apapun juga.</li>
	</ol>
	&nbsp;
	<p style="text-align: center;"><strong>PASAL 10</strong></p>
	<p style="text-align: center;"><strong>PAJAK-PAJAK DAN BIAYA-BIAYA</strong></p>
	&nbsp;
	<ol>
		<li>Terhitung sejak tanggal dilakukannya penandatanganan Surat Persetujuan Pembelian, maka segala pajak, iuran, dan beban lain yang terhutang atas TANAH dan BANGUNAN yang dipungut oleh instansi yang berwenang dan atau JAYA, menjadi beban dan tanggung jawab PEMBELI sepenuhnya. Khusus Pajak Bumi dan Bangunan (PBB) dan Iuran Pemeliharaan Lingkungan (IPL), wajib dibayarkan PEMBELI sejak saat serah terima TANAH dan BANGUNAN dari JAYA kepada PEMBELI.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Jika karena suatu peraturan atau keadaan tertentu, suatu pajak, iuran atau biaya yang menjadi tanggungan PEMBELI menurut PERJANJIAN ini harus dibayar oleh JAYA terlebih dahulu, maka PEMBELI berkewajiban untuk membayar kembali pembayaran tersebut sesuai tagihan yang diajukan oleh JAYA.</li>
	</ol>
	<strong> </strong>
	<p style="text-align: center;"><strong>PASAL 11</strong></p>
	<p style="text-align: center;"><strong>PENGALIHAN/PENGOPERAN HAK</strong></p>
	&nbsp;
	<ol>
		<li>Selama belum ditandatangani Akta Jual Beli, maka PEMBELI dilarang memindahkan segala hak dan kewajibannya termasuk dan tidak terbatas untuk menyewakan, menjual, menghibahkan, atau memberikan sebagai jaminan dengan cara apapun kepada pihak manapun atas TANAH DAN BANGUNAN atau atas PERJANJIAN ini kepada pihak lain.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Dalam hal PEMBELI bermaksud untuk mengalihkan segala hak dan kewajibannya berdasarkan PERJANJIAN ini, maka PEMBELI sudah harus melakukan pelunasan atas harga TANAH DAN BANGUNAN dan PEMBELI wajib mengajukan permohonan dan pernyataan tertulis kepada JAYA perihal pengalihan hak dimaksud untuk mendapatkan persetujuan tertulis dari JAYA.</li>
	</ol>
	&nbsp;
	<ol start="3">
		<li>Sehubungan dengan pengalihan segala hak dan kewajibannya berdasarkan PERJANJIAN ini, maka PEMBELI menyetujui serta mengikatkan dirinya untuk membayar kepada JAYA biaya administrasi sebesar <strong>10%</strong> [<strong>sepuluh </strong>persen] dari harga TANAH yang berlaku pada saat pengalihan dilaksanakan, pajak dan bea yang sudah maupun belum dibayarkan atas transaksi terdahulu harus dibayarkan kembali, pembayaran mana harus dilakukan oleh PEMBELI sebelum atau pada saat transaksi tersebut diadakan antara PEMBELI dengan pihak ketiga lainnya.</li>
	</ol>
	&nbsp;
	<ol start="4">
		<li>Dalam hal PEMBELI mendapatkan persetujuan tertulis dari JAYA, maka dalam jangka waktu yang ditetapkan oleh JAYA, PEMBELI berkewajiban menyerahkan seluruh dokumen-dokumen pembelian TANAH DAN BANGUNAN kepada JAYA untuk membatalkan seluruh hak dan kepentingannya atas TANAH DAN BANGUNAN tersebut.</li>
	</ol>
	&nbsp;
	<ol start="5">
		<li>Dalam hal PEMBELI melakukan pengalihan TANAH DAN BANGUNAN atau PERJANJIAN ini kepada pihak ketiga tanpa mendapat persetujuan tertulis dari JAYA atau dalam hal pelaksanaan pengalihan hak dan kewajiban tersebut telah mendapat persetujuan tertulis dari JAYA, akan tetapi PEMBELI tidak melaksanakan kewajiban pada ayat 3 dan atau ayat 4 Pasal ini, maka Para Pihak sepakat:</li>
	</ol>
	&nbsp;
	<ol>
		<li>JAYA dibebaskan dari kewajiban untuk melaksanakan jual beli di hadapan PPAT dengan pihak PEMBELI ataupun pihak ketiga yang mendapat hak daripadanya.</li>
	</ol>
	&nbsp;
	<ol start="5">
		<li>Sebagai akibat dari ketentuan ayat 5.a. di atas, maka JAYA dibebaskan dari kewajiban untuk menyerahkan Sertifikat TANAH kepada PEMBELI atau Pihak Ketiga yang mendapat hak daripadanya.</li>
	</ol>
	&nbsp;
	<ol>
		<li>JAYA berhak membatalkan secara sepihak PERJANJIAN ini.</li>
	</ol>
	&nbsp;
	<ol start="6">
		<li>Sehubungan dengan pengalihan TANAH DAN BANGUNAN sesuai dengan ketentuan-ketentuan di atas, PEMBELI dibebani kewajiban untuk menyampaikan kepada pihak ketiga yang menerima pengalihan tersebut bahwa syarat-syarat yang terdapat dalam PERJANJIAN ini mengikat pihak ketiga tersebut sepenuhnya.</li>
	</ol>
	&nbsp;
	<ol start="7">
		<li>Segala hal yang timbul sebagai akibat tidak dipenuhinya ketentuan-ketentuan pengalihan hak sebagaimana dimaksud Pasal ini oleh PEMBELI merupakan beban dan tanggung jawab PEMBELI sepenuhnya dan JAYA dibebaskan dari segala tuntutan dan atau gugatan yang timbul karenanya.</li>
	</ol>
	&nbsp;
	<ol start="8">
		<li>Terhitung sejak hari dan tanggal PERJANJIAN ini JAYA tidak dibenarkan untuk menjual atau untuk mengalihkan dalam bentuk apapun juga TANAH DAN BANGUNAN yang menjadi obyek jual beli berdasarkan PERJANJIAN ini kepada pihak ketiga lainnya dan setiap tindakan atau perbuatan semacam ini adalah tidak sah dan menjadi batal demi hukum.</li>
	</ol>
	<p style="text-align: center;"><strong> </strong></p>
	<p style="text-align: center;"><strong>PASAL 12</strong></p>
	<p style="text-align: center;"><strong>AKTA JUAL BELI</strong></p>
	&nbsp;
	<ol>
		<li>Para Pihak sepakat untuk melangsungkan dan menandatangani Akta Jual Beli atas TANAH DAN BANGUNAN di hadapan Pejabat Pembuat Akta Tanah [PPAT] dalam hal telah dipenuhi aspek-aspek sebagai berikut:</li>
	</ol>
	&nbsp;
	<ol>
		<li>Sertifikat Induk HGB atas TANAH telah diperoleh dan tercatat atas nama JAYA.</li>
		<li>Apabila pembelian dilakukan secara tunai, maka setelah PEMBELI melunasi kewajibannya untuk membayar harga TANAH DAN BANGUNAN seperti yang dimaksud pada Pasal 2 diatas berikut PPN, pajak, dan biaya lain serta denda-denda [jika ada].</li>
		<li>Apabila pembelian dilakukan secara KPR, maka setelah PEMBELI melunasi uang muka berikut PPN, pajak dan biaya-biaya lain serta denda-denda (jika ada). Akta Jual Beli akan dilakukan bersamaan atau setelah penandatanganan Akad Kredit.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Pada saat melangsungkan Jual beli TANAH DAN BANGUNAN di hadapan PPAT dan/atau pada waktu melangsungkan pengikatan, PEMBELI wajib membawa dan menyerahkan kepada JAYA asli surat-surat berikut kuitansi-kuitansi mengenai pembayaran lain yang telah dikeluarkan oleh JAYA.</li>
	</ol>
	&nbsp;
	<ol start="3">
		<li>Para Pihak sepakat bahwa biaya/honorarium PPAT yang timbul dalam pelaksanaan Akta Jual Beli, Biaya Balik Nama sertifikat dan biaya-biaya lainnya [jika ada] sehubungan dengan proses Akta Jual Beli dan Balik Nama Sertifikat adalah menjadi beban dan tanggung jawab PEMBELI dan dibayarkan kepada JAYA selambat-lambatnya 30 [tiga puluh] hari sebelum dilaksanakan tandatangan Akte Jual Beli.</li>
	</ol>
	&nbsp;
	<ol>
		<li>Para Pihak sepakat bahwa segala macam pajak yang timbul sebagai akibat dari dilaksanakannya Akte Jual Beli dan Balik Nama Sertifikat yaitu seperti Biaya Perolehan Hak atas Tanah dan Bangunan atau pajak-pajak yang lain adalah menjadi beban dan tanggung jawab PEMBELI dan dibayarkan kepada pihak yang berwenang untuk menerima atas pajak-pajak tersebut selambat-lambatnya 30 [tiga puluh] hari sebelum dilakukan tandatangan Akte Jual Beli.</li>
	</ol>
	<ol start="3">
		<li>Apabila di kemudian hari terjadi perubahan terhadap peraturan yang berkaitan dengan proses pembuatan Akta Jual Beli sampai dengan Balik Nama Sertifikat ke atas nama PEMBELI seperti termaksud dalam ayat 3.a. Pasal ini menimbulkan biaya-biaya yang harus dibayar oleh JAYA terlebih dahulu, maka PEMBELI terikat dan berkewajiban untuk membayar kembali kekurangan biaya-biaya yang menurut peraturan menjadi kewajiban PEMBELI tersebut sesuai dengan tagihan yang diajukan oleh JAYA.</li>
	</ol>
	<strong> </strong>
	<ol start="4">
		<li>Para Pihak sepakat bahwa ketentuan-ketentuan yang telah diatur dalam PERJANJIAN ini akan tetapi tidak diatur dalam Akta Jual Beli maka oleh kedua belah pihak ketentuan-ketentuan PERJANJIAN ini masih dianggap berlaku dan mengikat Para Pihak.</li>
	</ol>
	&nbsp;
	<ol start="5">
		<li>PEMBELI dengan ini menyatakan bahwa dirinya memenuhi syarat untuk memiliki TANAH dan BANGUNAN berdasarkan hukum yang berlaku di Indonesia dan apabila PEMBELI ternyata tidak memenuhi syarat untuk memiliki TANAH dan BANGUNAN, maka segala akibat yang timbul menjadi tanggungan PEMBELI sepenuhnya yang dengan ini membebaskan JAYA dari tanggung jawab tersebut, termasuk mengganti kerugian kepada JAYA.</li>
	</ol>
	<br />
	<br />
	<br />
	<br />
	<br />
	<h2 style="text-align: center;" >PASAL 13</h2>
	<p style="text-align: center;"><strong>FORCE MAJEURE</strong></p>
	<p style="text-align: center;"><strong> </strong></p>
	
	<ol>
		<li>Yang dimaksud dengan Force Majeure adalah peristiwa-peristiwa banjir, badai, gempa bumi, tanah longsor, dan bencana-bencana alam lainnya, epidemi, pandemi, peperangan, huru-hara, demonstrasi massa, pemogokan karyawan, perubahan peraturan oleh Pemerintah, kebakaran atau ledakan dan sebab-sebab lainnya di luar kuasa Para Pihak.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Sehubungan dengan Force Majeure tersebut, masing-masing pihak sepakat untuk tidak akan saling menuntut satu sama lain dan akan melakukan musyawarah untuk mufakat untuk menyelesaikan pelaksanaan kewajiban yang tertunda akibat terjadinya Force Majeure.</li>
	</ol>
	<h3></h3>
	<h3 style="text-align: center;">PASAL 14</h3>
	<h3 style="text-align: center;">KETENTUAN PEMBATALAN PERJANJIAN</h3>
	&nbsp;
	<ol>
		<li>Para Pihak sepakat bahwa PERJANJIAN ini menjadi batal demi hukum atau dapat dibatalkan apabila terjadi hal-hal sebagai berikut:</li>
	</ol>
	&nbsp;
	<ol>
		<li>Dalam hal PEMBELI tidak memenuhi kewajiban untuk membayar harga TANAH DAN BANGUNAN sebagaimana dimaksud pada ketentuan pada Pasal 2 di atas.</li>
		<li>Dalam hal PEMBELI melakukan pelanggaran terhadap ketentuan Pasal 7 di atas.</li>
		<li>Dalam hal PEMBELI melakukan pelanggaran terhadap ketentuan Pasal 8 di atas.</li>
		<li>Dalam hal PEMBELI melakukan pengalihan hak atas TANAH DAN BANGUNAN atau PERJANJIAN ini tanpa persetujuan tertulis dari JAYA.</li>
		<li>Dalam hal PEMBELI mengundurkan diri atau membatalkan transaksi jual beli TANAH DAN BANGUNAN karena sebab atau alasan apapun juga.</li>
		<li>Dalam hal PEMBELI lalai memberitahukan kepada JAYA mengenai perubahan alamat sebagaimana dimaksud Pasal 16 ayat 3 di bawah ini.</li>
		<li>Dalam hal PEMBELI melakukan wanprestasi terhadap Bank atau lembaga pemberi kredit kepada PEMBELI, termasuk tapi tidak terbatas pada kelalaian Pembeli untuk membayar angsuran kepada Bank atau lembaga pemberi kredit (untuk pembelian yang menggunakan fasilitas pinjaman dari Bank atau lembaga pemberi kredit)</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Para Pihak sepakat bahwa sehubungan dengan batalnya PERJANJIAN ini, maka Para Pihak melepaskan ketentuan Pasal 1266 dan Pasal 1267 Kitab Undang-undang Hukum Perdata [KUHPer] yang mengatur tentang batalnya suatu Perjanjian.</li>
	</ol>
	&nbsp;
	<ol start="3">
		<li>Para Pihak sepakat bahwa dalam hal PERJANJIAN ini batal demi hukum atau dibatalkan oleh JAYA, maka berlaku ketentuan-ketentuan sebagai berikut:</li>
	</ol>
	&nbsp;
	<ol start="1">
		<li>Dalam hal pembelian dilakukan melalui fasilitas pinjaman dari Bank atau lembaga pemberi kredit, maka dengan diterimanya oleh JAYA dari Bank atau lembaga pemberi fasilitas kredit mengenai Surat Pemberitahuan tentang wanprestasinya PEMBELI, maka PEMBELI setuju bahwa JAYA, Bank atau lembaga pemberi fasilitas kredit, penerima subrogasi, atau kuasa dari Bank atau lembaga pemberi fasilitas kredit dimaksud, baik sendiri-sendiri maupun bersama-sama dapat membatalkan Perjanjian ini dan melakukan pengosongan TANAH DAN BANGUNAN.</li>
	
		<li>TANAH DAN BANGUNAN yang menjadi objek PERJANJIAN ini tetap merupakan hak milik JAYA sepenuhnya. Oleh karenanya, dalam hal telah dilakukan serah terima TANAH DAN BANGUNAN dari JAYA kepada PEMBELI, PEMBELI berkewajiban untuk menyerahkan kembali kepada JAYA, TANAH DAN BANGUNAN beserta kelengkapannya termasuk kunci-kunci rumah, dalam keadaan kosong dan baik seperti pada saat penyerahan TANAH DAN BANGUNAN dari JAYA kepada PEMBELI, tidak dikuasai oleh siapapun, serta bebas dari barang-barang PEMBELI atau pihak lain, selambat-lambatnya dalam waktu 14 [empat belas] hari terhitung sejak hari dan tanggal PERJANJIAN ini menjadi batal.</li>
	
		<li>Apabila PEMBELI tetap tidak mengosongkan TANAH DAN BANGUNAN dalam jangka waktu yang ditentukan dalam ayat 3 butir b pasal ini, maka PEMBELI dengan ini memberi kuasa yang tidak dapat dicabut kembali baik sekarang maupun di kemudian hari kepada JAYA untuk mengosongkan sendiri dan atau meminta bantuan pihak lain untuk mengosongkan TANAH DAN BANGUNAN. Tanpa adanya Kuasa ini, PERJANJIAN ini tidak akan pernah dibuat.</li>
	
		<li>Dalam hal terjadinya pengosongan dan penyerahan kembali TANAH DAN BANGUNAN sebagaimana dimaksud ayat 3 butir b dan c pasal ini, PEMBELI bertanggung jawab sepenuhnya atas kerusakan yang timbul pada BANGUNAN dan atau setiap bagian darinya.</li>
	
		<li>Segala biaya yang timbul akibat tindakan pengosongan, pemindahan, penyimpanan barang-barang, dan biaya perbaikan kerusakan sebagaimana dimaksud ayat 3 butir d pasal ini menjadi tanggung jawab PEMBELI dan harus dibayar oleh PEMBELI kepada JAYA selambat-lambatnya 14 (empat belas) hari setelah disampaikannya tagihan oleh JAYA kepada PEMBELI. Bilamana PEMBELI tidak menyelesaikan pembayaran atas kewajiban-kewajibannya, maka dengan ini PEMBELI juga memberi kuasa kepada JAYA untuk menjual barang-barang yang ada pada saat tindakan pengosongan dengan harga dan syarat-syarat yang ditetapkan oleh JAYA serta menggunakan hasil penjualannya untuk membayar seluruh kewajiban PEMBELI.</li>
	
		<li>Dalam kejadian sebagaimana disebutkan dalam ayat 3 butir e pasal ini, PEMBELI membebaskan JAYA sebagai penerima kuasa, dari tuntutan apapun dan JAYA juga tidak dapat dibebani kewajiban berupa apapun, baik kepada PEMBELI atau kepada pihak lain yang mendalilkan berhak atas sebagian atau keseluruhan barang yang ada pada TANAH DAN BANGUNAN yang dikosongkan.</li>
	
		<li>Dalam hal pembayaran atas harga TANAH DAN BANGUNAN yang dilakukan oleh PEMBELI belum mencapai 20% [dua puluh persen] dari harga TANAH DAN BANGUNAN, maka keseluruhan pembayaran tersebut menjadi hak JAYA, PEMBELI baik sekarang maupun di kemudian hari melepaskan JAYA dari segala tuntutan yang berkaitan dengan pembayaran tersebut.</li>
	
		<li>Dalam hal pembayaran atas harga TANAH DAN BANGUNAN yang dilakukan oleh PEMBELI telah melebihi 20% [duapuluh persen] dari harga TANAH DAN BANGUNAN maka JAYA berhak untuk memotong sebesar [ persen] dari harga TANAH DAN BANGUNAN serta pajak dan bea yang sudah dibayarkan tidak dapat dikembalikan, dan JAYA dibebaskan dari pembayaran biaya administrasi atau ganti kerugian dalam bentuk apapun juga, sedangkan atas sisa pembayaran oleh PEMBELI wajib dikembalikan oleh JAYA kepada PEMBELI selambat-lambatnya dalam waktu 30 [Tiga puluh] hari sejak ditanda-tanganinya dan dikembalikannya dokumen-dokumen yang diperlukan.</li>
	</ol>
	&nbsp;
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<h2 style="text-align: center;" >PASAL 15</h2>
	<p style="text-align: center;"><strong>PENYELESAIAN SENGKETA </strong></p>
	&nbsp;
	<ol>
		<li>Dalam hal terjadinya sengketa antara Para Pihak sehubungan dengan pelaksanaan PERJANJIAN ini, maka Para Pihak akan menyelesaikannya dengan jalan musyawarah.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>Apabila upaya untuk menyelesaikan sengketa dengan jalan musyawarah tidak membawa hasil, maka Para Pihak dengan ini memilih domisili yang tetap dan tidak berubah pada Kantor Panitera Pengadilan Negeri untuk menyelesaikan sengketa tersebut.</li>
	</ol>
	
	<h2 style="text-align: center;" >PASAL 16</h2>
	<p style="text-align: center;"><strong>P E N U T U P</strong></p>
	&nbsp;
	<ol>
		<li>Selama hak-hak atas TANAH DAN BANGUNAN tersebut belum dialihkan oleh JAYA kepada PEMBELI secara resmi di hadapan pejabat Pembuat Akta Tanah yang berwenang maka tanpa persetujuan tertulis terlebih dahulu dari JAYA, PEMBELI dilarang menjaminkan, membebankan dengan Hak Tanggungan atau dengan cara apapun juga menyerahkan TANAH tersebut berikut BANGUNAN sebagai jaminan atas tanggungan hutang dan segala bentuk peralihan hak lainnya kepada pihak lain.</li>
	</ol>
	&nbsp;
	<ol start="2">
		<li>PERJANJIAN ini tidak berakhir dengan meninggalnya atau bubarnya salah satu pihak dalam PERJANJIAN ini akan tetapi diteruskan dan tetap beralih kepada para ahli waris, para penerus hak atau pengganti hak dari kedua belah pihak. Dalam hal PEMBELI meninggal dunia, maka dalam jangka waktu 60 (enam puluh) hari sejak meninggalnya PEMBELI, ahli waris atau pengganti hak yang sah dari PEMBELI menurut Undang-Undang wajib memberikan bukti keterangan waris dari pihak yang berwenang, yang menunjukkan bahwa dirinya adalah sebagai ahli waris yang sah kepada JAYA. Apabila terjadi pengalihan hak dan kewajiban kepada ahli waris yang sah dari PEMBELI, maka seluruh ahli waris dari PEMBELI tersebut dianggap telah mengetahui seluruh hak dan kewajiban PEMBELI dalam PERJANJIAN ini. Segala beban dan biaya yang mungkin timbul berkenaan dengan pengalihan hak tersebut di atas, menjadi beban ahli waris atau pengganti hak yang sah dari PEMBELI.</li>
	</ol>
	&nbsp;
	<ol start="3">
		<li>Perubahan alamat PEMBELI wajib diinformasikan kepada JAYA melalui Bagian Penagihan Biro Purna Jual, selambat-lambatnya 3 [tiga] hari sejak perubahan alamat tersebut dilakukan. Segala akibat yang timbul atas kelalaian memberikan informasi tentang perubahan alamat tersebut menjadi tanggung jawab PEMBELI, termasuk tapi tidak terbatas pada dapat dibatalkannya PERJANJIAN ini oleh JAYA.</li>
	</ol>
	&nbsp;
	<ol start="4">
		<li>Seluruh lampiran yang disertakan dalam Perjanjian ini merupakan satu kesatuan dan bagian yang tidak terpisahkan dari PERJANJIAN ini.</li>
	</ol>
	&nbsp;
	<ol start="5">
		<li>Dalam hal dikemudian hari dirasakan perlu oleh Para Pihak untuk melakukan perubahan dan atau penambahan atas isi PERJANJIAN ini, maka Para Pihak akan merundingkannya kedalam sesuatu addendum yang merupakan lampiran yang tidak terpisahkan dari PERJANJIAN ini.</li>
	</ol>
	&nbsp;
	
	Demikian PERJANJIAN ini dibuat dan ditandatangani oleh Para Pihak pada hari dan tanggal yang disebut pada halaman pertama PERJANJIAN, dibuat dalam rangkap 2 [dua] yang dibubuhi materai secukupnya serta mempunyai kekuatan hukum yang sama.
	
	&nbsp;
	
	&nbsp;
	
	&nbsp;
	<table style="height: 441px;" width="792">
		<tbody>
			<tr>
				<td width="311"><strong>PEMBELI</strong>
					
					<strong> </strong>
					
					<strong> </strong>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
				<strong> </strong></td>
				<td width="311">
					<strong>JAYA</strong><br />
					<strong>PT Jaya Real Property, Tbk.</strong>
					
					<strong> </strong>
					
					<strong> </strong>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
				</td>
			</tr>
			<tr>
				<td width="311">
					<p><?php echo $nama_pembeli; ?></p>
				</td>
				<td width="311"><strong><span style="text-decoration: underline;"><?php echo $PEJABAT_PPJB ?></span></strong> <br />
					
				<?php echo $JABATAN_PPJB ?></td>
			</tr>
		</tbody>
	</table>	
	
</body>
</html>