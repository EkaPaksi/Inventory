<?php
include "root.php";

if (isset($_GET['action'])) {
	$action=$_GET['action'];
	if ($action=="login") {
		$root->login($_POST['username'],$_POST['pass'],$_POST['loginas']);
	}
	if ($action=="logout") {
		session_start();
		session_destroy();
		$root->redirect("index.php");
	}
	if ($action=="tambah_barang") {
		$root->tambah_barang($_POST['nama_barang'],$_POST['stok'],$_POST['harga_beli'],$_POST['harga_jual'],$_POST['kategori']);
	}
	if ($action=="tambah_kategori") {
		$root->tambah_kategori($_POST['nama_kategori']);
	}
	if ($action=="hapus_kategori") {
		$root->hapus_kategori($_GET['id_kategori']);
	}
	if ($action=="edit_kategori") {
		$root->aksi_edit_kategori($_POST['id_kategori'],$_POST['nama_kategori']);
	}
	if ($action=="hapus_barang") {
		$root->hapus_barang($_GET['id_barang']);
	}
	if ($action=="edit_barang") {
		$root->aksi_edit_barang($_POST['id_barang'],$_POST['nama_barang'],$_POST['stok'],$_POST['harga_beli'],$_POST['harga_jual'],$_POST['kategori']);
	}
	if ($action=="tambah_kasir") {
		$root->tambah_kasir($_POST['nama_kasir'],$_POST['password']);
	}
	if ($action=="hapus_user") {
		$root->hapus_user($_GET['id_user']);
	}
	if ($action=="edit_kasir") {
		$root->aksi_edit_kasir($_POST['nama_kasir'],$_POST['password'],$_POST['id']);
	}
	if ($action=="edit_admin") {
		$root->aksi_edit_admin($_POST['username'],$_POST['password']);
	}
	if ($action=="reset_admin") {
		$pass=sha1("admin");
		$q=$root->con->query("update user set username='admin',password='$pass',date_created=date_created where id='1'");
		if ($q === TRUE) {
			$root->alert("admin berhasil direset, username & password = 'admin'");
			session_start();
			session_destroy();
			$root->redirect("index.php");
		}
	}
	if ($action=="tambah_tempo") {
		$root->tambah_tempo($_POST['id_barang'],$_POST['jumlah'],$_POST['trx']);
	}
	if ($action=="hapus_tempo") {
		$root->hapus_tempo($_GET['id_tempo'],$_GET['id_barang'],$_GET['jumbel']);
	}
	if ($action=="tambah_tempo_dtg") {
		$root->tambah_tempo_dtg($_POST['id_barang'],$_POST['jumlah'],$_POST['trx']);
	}
	if ($action=="tambah_tempo_minta") {
		$root->tambah_tempo_minta($_POST['id_barang'],$_POST['jumlah'],$_POST['trx'],$_POST['ket']);
	}
	if ($action=="hapus_tempo") {
		$root->hapus_tempo_dtg($_GET['id_tempo'],$_GET['id_barang'],$_GET['jumbel']);
	}
	if ($action=="selesai_transaksi") {
		session_start();
		$trx=date("d")."/OUT/".$_SESSION['id']."/".date("m/y");

			$query=$root->con->query("insert into transaksi set kode_kasir='$_SESSION[id]',total_bayar='$_POST[total_bayar]',no_invoice='$trx',nama_pembeli='$_POST[nama_pembeli]'");

		$trx2=date("d")."/OUT/".$_SESSION['id']."/".date("y");
		$get1=$root->con->query("select *  from transaksi where no_invoice='$trx'");
		$datatrx=$get1->fetch_assoc();
		$id_transaksi2=$datatrx['id_transaksi'];

		$query2=$root->con->query("select * from tempo where trx='$trx2'");
		while ($f=$query2->fetch_assoc()) {
			$root->con->query("insert into sub_transaksi set id_barang='$f[id_barang]',id_transaksi='$id_transaksi2',jumlah_beli='$f[jumlah_beli]',total_harga='$f[total_harga]',no_invoice='$trx'");
		}
		$root->con->query("delete from tempo where trx='$trx2'");
		$root->alert("Transaksi berhasil");
		$root->redirect("transaksi.php");


	}
	if ($action=="selesai_transaksi_tambah") {
		session_start();
		$trx=date("d")."/IN/".$_SESSION['id']."/".date("m/y");

			$query=$root->con->query("insert into transaksi_tambah set kode_kasir='$_SESSION[id]',total_bayar='$_POST[total_bayar]',no_invoice='$trx',nama_pembeli='$_POST[nama_pembeli]'");

		$trx2=date("d")."/IN/".$_SESSION['id']."/".date("y");
		$get1=$root->con->query("select *  from transaksi_tambah where no_invoice='$trx'");
		$datatrx=$get1->fetch_assoc();
		$id_transaksi2=$datatrx['id_transaksi'];

		$query2=$root->con->query("select * from tempo where trx='$trx2'");
		while ($f=$query2->fetch_assoc()) {
			$root->con->query("insert into sub_transaksi_tambah set id_barang='$f[id_barang]',id_transaksi='$id_transaksi2',jumlah_beli='$f[jumlah_beli]',total_harga='$f[total_harga]',no_invoice='$trx'");
		}
		$root->con->query("delete from tempo where trx='$trx2'");
		$root->alert("Transaksi berhasil");
		$root->redirect("transaksitambah.php");


	}
	if ($action=="selesai_transaksi_minta") {
		session_start();
		$trx=date("d")."/REQ/".$_SESSION['id']."/".date("m/y");

			$query=$root->con->query("insert into permintaan set kode_kasir='$_SESSION[id]',total_bayar='$_POST[total_bayar]',no_invoice='$trx',nama_pembeli='$_POST[nama_pembeli]'");

		$trx2=date("d")."/REQ/".$_SESSION['id']."/".date("y");
		$get1=$root->con->query("select *  from permintaan where no_invoice='$trx'");
		$datatrx=$get1->fetch_assoc();
		$id_transaksi2=$datatrx['id_transaksi'];

		$query2=$root->con->query("select * from tempo where trx='$trx2'");
		while ($f=$query2->fetch_assoc()) {
			$root->con->query("insert into sub_permintaan set id_barang='$f[id_barang]',
			id_transaksi='$id_transaksi2',
			jumlah_beli='$f[jumlah_beli]',
			total_harga='$f[total_harga]',
			ket='$f[ket]',
			no_invoice='$trx'");
		}
		$root->con->query("delete from tempo where trx='$trx2'");
		$root->alert("Transaksi berhasil");
		$root->redirect("permintaan.php");


	}
	if ($action=="delete_transaksi") {
		$q1=$root->con->query("delete from transaksi where id_transaksi='$_GET[id]'");
		$q2=$root->con->query("delete from sub_transaksi where id_transaksi='$_GET[id]'");
		if ($q1===TRUE && $q2 === TRUE) {
			$root->alert("Transaksi No $_GET[id] Berhasil Dihapus");
			$root->redirect("laporan.php");
		}
	}


}else{
	echo "no direct script are allowed";
}
?>
