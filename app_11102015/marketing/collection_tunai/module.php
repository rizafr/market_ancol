<?php 
switch (trim(base64_decode($cmd)))
{
	# Master
	
	case 'CO1_informasi_pembeli' 		: die_mod('COF01'); die_ha('COF01', 'R'); include('collection_tunai/master/informasi_pembeli/informasi_pembeli_setup.php');break;
	case 'CO1_hari_libur' 				: die_mod('COF02'); die_ha('COF02', 'R'); include('collection_tunai/master/cs_hari_libur/cs_hari_libur_setup.php');break;
	
	# Transaksi
	## denda_keterlambatan
		case 'C01_entry' 					: die_mod('COT01'); die_ha('COT01', 'R'); include('collection_tunai/transaksi/denda_keterlambatan/entry/entry_setup.php');break;
		case 'C01_otoritas'					: die_mod('COT02'); die_ha('COT02', 'R'); include('collection_tunai/transaksi/denda_keterlambatan/otorisasi/otorisasi_setup.php');break;
	case 'C01_virtual_account'			: include('collection_tunai/transaksi/virtual_account/virtual_account_setup.php');break;
	case 'C01_ver_kwitansi_col'			: die_mod('COT03'); die_ha('COT03', 'R'); include('collection_tunai/transaksi/ver_kwitansi_col/ver_kwitansi_col_setup.php');break;
	case 'C01_ver_kwitansi_keu' 		: die_mod('COT04'); die_ha('COT04', 'R'); include('collection_tunai/transaksi/ver_kwitansi_keu/ver_kwitansi_keu_setup.php');break;
	case 'C01_pemulihan_wanprestasi'	: die_mod('COT05'); die_ha('COT05', 'R'); include('collection_tunai/transaksi/pemulihan_wanprestasi/pemulihan_wanprestasi_setup.php');break;
	case 'C01_memo_pembatalan'			: die_mod('COT07'); die_ha('COT07', 'R'); include('collection_tunai/transaksi/memo_pembatalan/memo_pembatalan_setup.php');break;
	
	#Surat
	case 'C01_pemberitahuan_jatuh_tempo'	: die_mod('COS01'); die_ha('COS01', 'R'); include('collection_tunai/surat/pemberian_jatuh_tempo/pemberian_jatuh_tempo_setup.php');break;
	case 'C01_jatuh_tempo'					: include('collection_tunai/surat/jatuh_tempo/jatuh_tempo_setup.php');break;
	case 'C01_somasi_pertama'				: die_mod('COS02'); die_ha('COS02', 'R'); include('collection_tunai/surat/somasi_satu/somasi_satu_setup.php');break;
	case 'C01_somasi_kedua'					: die_mod('COS03'); die_ha('COS03', 'R'); include('collection_tunai/surat/somasi_dua/somasi_dua_setup.php');break;
	case 'C01_somasi_ketiga'				: die_mod('COS04'); die_ha('COS04', 'R'); include('collection_tunai/surat/somasi_tiga/somasi_tiga_setup.php');break;
	/*case 'C01_wanprestasi'					: die_mod('COS05'); die_ha('COS05', 'R'); include('ppjb/transaksi/ppjb/ppjb_setup.php');break;
	case 'C01_administrasi_pembatalan'		: die_mod('COS06'); die_ha('COS06', 'R'); include('ppjb/transaksi/ppjb/ppjb_setup.php');break;
	*/
	
	/*
	# Pelaporan
	case 'C01_proyeksi_penagihan' 		: die_mod('COP01'); die_ha('COP01', 'R'); include('ppjb/laporan/daftar_ppjb/daftar_ppjb_setup.php');break;
	case 'C01_umur_piutang'				: die_mod('COP02'); die_ha('COP02', 'R'); include('ppjb/laporan/daftar_belum_ppjb/daftar_belum_ppjb_setup.php');break;
	case 'C01_spp_lunas_tahunan' 		: die_mod('COP07'); die_ha('COP07', 'R'); include('ppjb/laporan/daftar_ppjb/daftar_ppjb_setup.php');break;
	case 'C01_pembebasan_denda'			: die_mod('COP03'); die_ha('COP03', 'R'); include('ppjb/laporan/daftar_belum_ppjb/daftar_belum_ppjb_setup.php');break;
	case 'C01_pembatalan_spp'	 		: die_mod('COP04'); die_ha('COP04', 'R'); include('ppjb/laporan/daftar_ppjb/daftar_ppjb_setup.php');break;
	case 'C01_status_surat_penagihan'	: die_mod('COP05'); die_ha('COP05', 'R'); include('ppjb/laporan/daftar_belum_ppjb/daftar_belum_ppjb_setup.php');break;
	case 'C01_laporan_tanda_jadi' 		: die_mod('COP06'); die_ha('COP06', 'R'); include('ppjb/laporan/daftar_ppjb/daftar_ppjb_setup.php');break;
	case 'C01_laporan_lain_lain'		: die_mod('COP06'); die_ha('COP06', 'R'); include('ppjb/laporan/daftar_belum_ppjb/daftar_belum_ppjb_setup.php');break;
	*/
	
	# Lain-lain
	case 'C01_parameter_program'		: die_mod('COL01'); die_ha('COL01', 'R'); include('collection_tunai/lain/parameter/parameter_setup.php');break;
	case 'C01_registrasi_user'			: die_mod('COL02'); die_ha('COL02', 'R'); include('collection_tunai/lain/registrasi_user/users_setup.php');break;
	case 'C01_nomor_surat'				: die_mod('COL03'); die_ha('COL03', 'R'); include('collection_tunai/lain/nomor_surat/nomor_surat_setup.php');break;
	case 'C01_spp_tidak_valid'			: include('collection_tunai/lain/spp_tidak_valid/spp_tidak_valid_setup.php');break;
}
