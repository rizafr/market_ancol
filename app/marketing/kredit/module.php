<?php 
switch (trim(base64_decode($cmd)))
{

	# Transaksi
	case 'c04_kuitansi' 					: include('kredit/transaksi/kuitansi/kuitansi_setup.php');break;	
	case 'c04_kuitansi_lain'				: include('kredit/transaksi/kuitansi_lain/kuitansi_lain_setup.php');break;	
	case 'c04_tanda_terima'					: include('kredit/transaksi/tanda_terima/tanda_terima_setup.php');break;
	
	# Pelaporan
	case 'c04_laporan_kuitansi'				: include('kredit/pelaporan/laporan_kuitansi/laporan_kuitansi_setup.php');break;
	case 'c04_laporan_kuitansi_lain'		: include('kredit/pelaporan/laporan_kuitansi_lain/laporan_kuitansi_lain_setup.php');break;
	case 'c04_progres_penerimaan'			: include('kredit/pelaporan/progres_penerimaan/progres_penerimaan_setup.php');break;
	case 'c04_kartu_kuning'					: include('kredit/pelaporan/kartu_kuning/kartu_kuning_setup.php');break;
	case 'c04_faktur_pajak'					: include('kredit/pelaporan/laporan_faktur_pajak/laporan_faktur_pajak_setup.php');break;
	
	# Utilitas
	case 'c04_parameter'					: include('kredit/utilitas/parameter/parameter_setup.php');break;
	case 'c04_denda'						: include('kredit/utilitas/denda/denda_setup.php');break;
	case 'c04_kartu_pembeli'				: include('kredit/utilitas/kartu_pembeli/kartu_pembeli_setup.php');break;
	case 'c04_penomoran_fp'					: include('kredit/utilitas/penomoran_fp/penomoran_fp_setup.php');break;
}
