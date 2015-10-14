<?php 
switch (trim(base64_decode($cmd)))
{
	
	case 'A01_home' 					: include('marketing/home/home_setup.php');break;
	case 'A01_ubah_password'			: include('marketing/utilitas/ubah_password/ubah_password_setup.php');break;
}

switch (trim(base64_decode($cmd)))
{	
	# Master
	case 'A01_lokasi' 					: die_mod('M01'); include('marketing/master/lokasi/lokasi_setup.php');break;
	case 'A01_desa' 					: die_mod('M02'); include('marketing/master/desa/desa_setup.php');break;
	case 'A01_nomor_va' 				: die_mod('M03'); include('marketing/master/nomor_va/nomor_va_setup.php');break;
	case 'A01_tipe' 					: die_mod('M04'); include('marketing/master/tipe/tipe_setup.php');break;
	case 'A01_faktor_strategis'			: die_mod('M05'); include('marketing/master/faktor_strategis/faktor_strategis_setup.php');break;
	case 'A01_jenis_unit' 				: die_mod('M06'); include('marketing/master/jenis_unit/jenis_unit_setup.php');break;
	case 'A01_jenis_penjualan' 			: die_mod('M07'); include('marketing/master/jenis_penjualan/jenis_penjualan_setup.php');break;
	case 'A01_lembaga_keuangan' 		: die_mod('M08'); include('marketing/master/lembaga_keuangan/lembaga_keuangan_setup.php');break;
	case 'A01_harga_tanah' 				: die_mod('M09'); include('marketing/master/harga_tanah/harga_tanah_setup.php');break;
	case 'A01_harga_bangunan' 			: die_mod('M10'); include('marketing/master/harga_bangunan/harga_bangunan_setup.php');break;
	case 'A01_jenis_pembayaran'			: die_mod('M11'); include('marketing/master/jenis_pembayaran/jenis_pembayaran_setup.php');break;
	case 'A01_pola_pembayaran'			: die_mod('M12'); include('marketing/master/pola_pembayaran/pola_pembayaran_setup.php');break;
	case 'A01_agen'						: die_mod('M13'); include('marketing/master/agen/agen_setup.php');break;
	case 'A01_koordinator'				: die_mod('M14'); include('marketing/master/koordinator/koordinator_setup.php');break;
	
	# Operasional
	case 'A01_persediaan_awal'			: die_mod('M15'); include('marketing/operasional/persediaan_awal/stock_awal/persediaan_awal_setup.php');break;
	
	# Transaksi



	case 'A01_reserve'					: die_mod('M16'); include('marketing/transaksi/reserve/reserve_setup.php');break;



	case 'A01_spp'						: die_mod('M16'); include('marketing/transaksi/spp/spp_setup.php');break;
	case 'A01_edit_stok_penjualan'		: die_mod('M17'); include('marketing/transaksi/edit_stok_penjualan/edit_stok_penjualan_setup.php');break;
	case 'A01_edit_spp_penjualan'		: die_mod('M18'); include('marketing/transaksi/edit_spp_penjualan/edit_spp_penjualan_setup.php');break;
	case 'A01_serah_terima'				: die_mod('M19'); include('marketing/transaksi/serah_terima/serah_terima_setup.php');break;
	case 'A01_informasi_bangunan'		: die_mod('M20'); include('marketing/transaksi/informasi_bangunan/informasi_bangunan_setup.php');break;

	# Laporan
	
	case 'A01_penjualan_p_unit'			: die_mod('M21'); include('marketing/laporan/penjualan_p_unit/penjualan_p_unit_setup.php');break;
	case 'A01_penjualan_p_class'		: die_mod('M22'); include('marketing/laporan/penjualan_p_class/penjualan_p_class_setup.php');break;
	case 'A01_penjualan_p_lokasi'		: die_mod('M23'); include('marketing/laporan/penjualan_p_lokasi/penjualan_p_lokasi_setup.php');break;
	case 'A01_laporan_reserve'			: die_mod('M24'); include('marketing/laporan/laporan_reserve/laporan_reserve_setup.php');break;
	case 'A01_laporan_persediaan_stok'	: die_mod('M25'); include('marketing/laporan/laporan_persediaan_stok/laporan_persediaan_stok_setup.php');break;
	
	
	# Utilitas
	
	case 'A01_parameter_program_mark'	: die_mod('M26');include('marketing/utilitas/parameter/parameter_setup.php');break;
	
	#tambahan modul baru
	case 'A01_detail_pola_pembayaran'	: die_mod('M27'); include('marketing/master/detail_pola_pembayaran/detail_pola_pembayaran_setup.php');break;	
	
	#User Management
	case 'A01_aplications'				: die_mod('A01'); include('marketing/utilitas/security_management/aplications/aplications_setup.php');break;
	case 'A01_modules'					: die_mod('A02'); include('marketing/utilitas/security_management/modules/modules_setup.php');break;
	case 'A01_manage_users'				: die_mod('A03'); include('marketing/utilitas/security_management/users/users_setup.php');break;
	case 'A01_manage_modules'			: die_mod('A04'); include('marketing/utilitas/security_management/manage_modules/manage_modules_setup.php');break;
	case 'A01_rights'					: die_mod('A05'); include('marketing/utilitas/security_management/rights/rights_setup.php');break;
	case 'A01_pemulihan_spp'			: die_mod('A06'); include('marketing/utilitas/pemulihan_spp/pemulihan_spp_setup.php');break;
	
}
?>
<?php 
switch (trim(base64_decode($cmd)))
{
	# Master
	
	case 'AO1_informasi_pembeli' 			: die_mod('C01'); include('marketing/collection_tunai/master/informasi_pembeli/informasi_pembeli_setup.php');break;
	case 'AO1_hari_libur' 					: die_mod('C02'); include('marketing/collection_tunai/master/cs_hari_libur/cs_hari_libur_setup.php');break;
	
	# Transaksi
	case 'A01_virtual_account'				: include('marketing/collection_tunai/transaksi/virtual_account/virtual_account_setup.php');break;
	case 'A01_ver_kwitansi_col'				: die_mod('C03'); include('marketing/collection_tunai/transaksi/pembayaran/pembayaran_setup.php');break;
	case 'A01_download_tagihan'				: die_mod('C04'); include('collection_tunai/transaksi/download_tagihan/download_tagihan_setup.php');break;
	case 'A01_pemulihan_wanprestasi'		: die_mod('C05'); include('collection_tunai/transaksi/pemulihan_wanprestasi/pemulihan_wanprestasi_setup.php');break;
	case 'A01_memo_pembatalan'				: die_mod('C06'); include('collection_tunai/transaksi/memo_pembatalan/memo_pembatalan_setup.php');break;
	
	#Surat
	case 'A01_pemberitahuan_jatuh_tempo'	: die_mod('C07'); include('marketing/collection_tunai/surat/pemberian_jatuh_tempo/pemberian_jatuh_tempo_setup.php');break;
	case 'A01_jatuh_tempo'					: die_mod('C08'); include('marketing/collection_tunai/surat/jatuh_tempo/jatuh_tempo_setup.php');break;
	case 'A01_somasi_pertama'				: die_mod('C09'); include('marketing/collection_tunai/surat/somasi_satu/somasi_satu_setup.php');break;
	case 'A01_somasi_kedua'					: die_mod('C10'); include('marketing/collection_tunai/surat/somasi_dua/somasi_dua_setup.php');break;
	case 'A01_somasi_ketiga'				: die_mod('C11'); include('marketing/collection_tunai/surat/somasi_tiga/somasi_tiga_setup.php');break;
	case 'A01_wanprestasi'					: die_mod('C12'); include('marketing/collection_tunai/surat/wanprestasi/wanprestasi_setup.php');break;
	case 'A01_administrasi_pembatalan'		: die_mod('C13'); include('marketing/collection_tunai/surat/pembatalan/pembatalan_setup.php');break;
	
	#Surat SPK
	case 'A01_pemberitahuan_spk'			: die_mod('C14'); include('marketing/collection_tunai/surat/spk/pemberitahuan_spk/pemberitahuan_spk_setup.php');break;
	case 'A01_perpanjangan_spk'				: die_mod('C15'); include('marketing/collection_tunai/surat/spk/perpanjangan_spk/perpanjangan_spk_setup.php');break;
	case 'A01_pembatalan_spk'				: die_mod('C16'); include('marketing/collection_tunai/surat/spk/pembatalan_spk/pembatalan_spk_setup.php');break;
	case 'A01_pemberitahuan_akad'			: die_mod('C17'); include('marketing/collection_tunai/surat/spk/pemberitahuan_akad/pemberitahuan_akad_setup.php');break;
	case 'A01_perpanjangan_akad'			: die_mod('C18'); include('marketing/collection_tunai/surat/spk/perpanjangan_akad/perpanjangan_akad_setup.php');break;
	case 'A01_pembatalan_akad'				: die_mod('C19'); include('marketing/collection_tunai/surat/spk/pembatalan_akad/pembatalan_akad_setup.php');break;
	case 'A01_pemberitahuan_spk_review'		: die_mod('C20'); include('marketing/collection_tunai/surat/spk/pemberitahuan_spk_review/pemberitahuan_spk_review_setup.php');break;
	case 'A01_pembatalan_spk_review'		: die_mod('C21'); include('marketing/collection_tunai/surat/spk/pembatalan_spk_review/pembatalan_spk_review_setup.php');break;
	case 'A01_pemberitahuan_plafon'			: die_mod('C22'); include('marketing/collection_tunai/surat/spk/pemberitahuan_plafon/pemberitahuan_plafon_setup.php');break;
	case 'A01_pembatalan_plafon'			: die_mod('C23'); include('marketing/collection_tunai/surat/spk/pembatalan_plafon/pembatalan_plafon_setup.php');break;
	case 'A01_pemberitahuan_tolak_kredit'	: die_mod('C24'); include('marketing/collection_tunai/surat/spk/pemberitahuan_tolak_kredit/pemberitahuan_tolak_kredit_setup.php');break;
	case 'A01_pembatalan_tolak_kredit'		: die_mod('C25'); include('marketing/collection_tunai/surat/spk/pembatalan_tolak_kredit/pembatalan_tolak_kredit_setup.php');break;
	
	
	#Pelaporan
	case 'A01_proyeksi_penagihan'			: die_mod('C26'); include('marketing/collection_tunai/laporan/proyeksi_penagihan/proyeksi_penagihan_setup.php');break;
	case 'A01_umur_piutang'					: die_mod('C27'); include('marketing/collection_tunai/laporan/umur_piutang/umur_piutang_setup.php');break;
	case 'A01_spp_lunas_tahunan'			: die_mod('C28'); include('marketing/collection_tunai/laporan/spp_lunas_tahunan/spp_lunas_tahunan_setup.php');break;
	case 'A01_pembebasan_denda'				: die_mod('C29'); include('marketing/collection_tunai/laporan/pembebasan_denda/pembebasan_denda_setup.php');break;
	case 'A01_pembatalan_spp'				: die_mod('C30'); include('marketing/collection_tunai/laporan/pembatalan_spp/pembatalan_spp_setup.php');break;
	case 'A01_surat_penagihan'				: die_mod('C31'); include('marketing/collection_tunai/laporan/surat_penagihan/surat_penagihan_setup.php');break;
	case 'A01_daftar_memo_pembatalan'		: die_mod('C32'); include('marketing/collection_tunai/laporan/daftar_memo_pembatalan/daftar_memo_pembatalan_setup.php');break;
	case 'A01_penerimaan_kwitansi'			: die_mod('C33'); include('marketing/collection_tunai/laporan/penerimaan_kwitansi/penerimaan_kwitansi_setup.php');break;
	case 'A01_penerimaan_lain'				: die_mod('C34'); include('marketing/collection_tunai/laporan/penerimaan_lain/penerimaan_lain_setup.php');break;
	case 'A01_rencana_realisasi_blok'		: die_mod('C35'); include('marketing/collection_tunai/laporan/rencana_realisasi_blok/rencana_realisasi_blok_setup.php');break;
	case 'A01_daftar_spp'					: die_mod('C36'); include('marketing/collection_tunai/laporan/daftar_spp/daftar_spp_setup.php');break;
	//Start Edited by Kurniawan
	//Bandung 14 Oktober 2015
	case 'A01_orlansoft'					: die_mod('C39'); include('marketing/collection_tunai/laporan/orlansoft/orlansoft_setup.php');break;
	//End Edited
	
	# Lain-lain
	case 'A01_parameter_program_coll'		: die_mod('C37'); include('marketing/collection_tunai/lain/parameter/parameter_setup.php');break;
	case 'A01_upload_penerimaan_va'			: die_mod('C38'); include('marketing/collection_tunai/lain/upload_penerimaan_va/upload_penerimaan_va_setup.php');break;

}
?>

<?php 
switch (trim(base64_decode($cmd)))
{
	# Master
	case 'A01_kelurahan' 				: die_mod('P01'); include('marketing/ppjb/master/kelurahan/kelurahan_setup.php');break;
	case 'A01_kecamatan' 				: die_mod('P02'); include('marketing/ppjb/master/kecamatan/kecamatan_setup.php');break;
	case 'A01_jenis_ppjb' 				: die_mod('P03'); include('marketing/ppjb/master/jppjb/jppjb_setup.php');break;
	case 'A01_jenis_add_ppjb'			: die_mod('P04'); include('marketing/ppjb/master/jappjb/jappjb_setup.php');break;
	case 'A01_tipe_bangunan'			: die_mod('P05'); include('marketing/ppjb/master/tipe_bangunan/tipe_bangunan_setup.php');break;
	
	# Transaksi
	case 'A01_ppjb' 					: die_mod('P06'); include('marketing/ppjb/transaksi/ppjb/ppjb_setup.php');break;
	case 'A01_verifikasi_ppjb'			: die_mod('P07'); include('marketing/ppjb/transaksi/verifikasi/verifikasi_setup.php');break;
	case 'A01_pembatalan_ppjb'			: die_mod('P08'); include('marketing/ppjb/transaksi/pembatalan/pembatalan_setup.php');break;
	case 'A01_pengalihan_hak'			: die_mod('P09'); include('marketing/ppjb/transaksi/pengalihan_hak/pengalihan_hak_setup.php');break;	
	
	# Laporan
	case 'A01_daftar_ppjb' 				: die_mod('P10'); include('marketing/ppjb/laporan/daftar_ppjb/daftar_ppjb_setup.php');break;
	case 'A01_daftar_spp_belum_ppjb'	: die_mod('P11'); include('marketing/ppjb/laporan/daftar_belum_ppjb/daftar_belum_ppjb_setup.php');break;
	
	# Lain-lain
	case 'A01_parameter_program_ppjb'	: die_mod('P12'); include('marketing/ppjb/lain/parameter/parameter_setup.php');break;
	
	
	//case 'C03_ubah_sandi' 			: include('ppjb/lain/xxx/xxx_setup.php');break;
}
?>

<?php 
switch (trim(base64_decode($cmd)))
{

	# Transaksi
	case 'A01_kuitansi' 				: die_mod('K01'); include('marketing/kredit/transaksi/kuitansi/kuitansi_setup.php');break;	
	case 'A01_kuitansi_lain'			: die_mod('K02'); include('marketing/kredit/transaksi/kuitansi_lain/kuitansi_lain_setup.php');break;	
	case 'A01_tanda_terima'				: die_mod('K03'); include('marketing/kredit/transaksi/tanda_terima/tanda_terima_setup.php');break;

	# Pelaporan
	case 'A01_laporan_kuitansi'			: die_mod('K04'); include('marketing/kredit/pelaporan/laporan_kuitansi/laporan_kuitansi_setup.php');break;
	case 'A01_laporan_kuitansi_lain'	: die_mod('K05'); include('marketing/kredit/pelaporan/laporan_kuitansi_lain/laporan_kuitansi_lain_setup.php');break;
	case 'A01_progres_penerimaan'		: die_mod('K06');include('marketing/kredit/pelaporan/progres_penerimaan/progres_penerimaan_setup.php');break;
	case 'A01_kartu_kuning'				: die_mod('K07'); include('marketing/kredit/pelaporan/kartu_kuning/kartu_kuning_setup.php');break;
	case 'A01_faktur_pajak'				: die_mod('K08');include('marketing/kredit/pelaporan/laporan_faktur_pajak/laporan_faktur_pajak_setup.php');break;
	
	# Utilitas
	case 'A01_parameter'				: die_mod('K09'); include('marketing/kredit/utilitas/parameter/parameter_setup.php');break;
	case 'A01_penomoran_fp'				: die_mod('K10');include('marketing/kredit/utilitas/penomoran_fp/penomoran_fp_setup.php');break;
	case 'A01_kartu_pembeli'			: die_mod('K11');include('marketing/kredit/utilitas/kartu_pembeli/kartu_pembeli_setup.php');break;
	
}

switch ($_SESSION['HOME'])
{
	//case 'home' 					: die_mod('PM01'); include('marketing/home/home_setup.php');$_SESSION['HOME'] = '-';
	case 'home' 					: include('marketing/home/home_setup.php');$_SESSION['HOME'] = '-';
	
}

?>