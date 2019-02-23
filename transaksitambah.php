<?php include "head.php" ?>
<?php
	if (isset($_GET['action']) && $_GET['action']=="transaksi_baru_tambah") {
		include "transaksi_baru_tambah.php";
	}
	else if (isset($_GET['action']) && $_GET['action']=="detail_transaksi_tambah") {
		include "detail_transaksi_tambah.php";
	}

	else{
?>
 <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .align-middle {
            vertical-align: middle !important;
        }
    </style>
<script type="text/javascript">
	document.title="Transaksi";
	document.getElementById('transaksitambah').classList.add('active');
</script>
<div class="content">
	<div class="padding">
		<div class="bgwhite">
			<div class="padding">
			<div class="contenttop">
				<div class="left">
					<a href="?action=transaksi_baru_tambah" class="btnblue">Transaksi Baru</a>
				</div>
				<div class="both"></div>
			</div>
			<span class="label">Jumlah Transaksi : <?= $root->show_jumlah_trans() ?></span>
			<table class="datatable">
				<thead>
				<tr>
					<th width="35px">NO</th>
					<th>No Masuk</th>
					<th>Tanggal</th>
					<th>Total</th>
					
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
                // Include / load file koneksi.php
                include "koneksi.php";

                // Cek apakah terdapat data pada page URL
                $page = (isset($_GET['page'])) ? $_GET['page'] : 1;

                $limit = 5; // Jumlah data per halamanya

                // Buat query untuk menampilkan daa ke berapa yang akan ditampilkan pada tabel yang ada di database
                $limit_start = ($page - 1) * $limit;

                // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
                $sql = $pdo->prepare("SELECT * FROM transaksi_tambah LIMIT ".$limit_start.",".$limit);
                $sql->execute(); // Eksekusi querynya

                $no = $limit_start + 1; // Untuk penomoran tabel
                while ($data = $sql->fetch()) { // Ambil semua data dari hasil eksekusi $sql
                ?>
                    <tr>
                        <td class="align-middle text-center"><?php echo $no; ?></td>
                        <td class="align-middle"><?php echo $data['no_invoice']; ?></td>
                        <td class="align-middle"><?php echo $data['tgl_transaksi']; ?></td>
                        <td class="align-middle"><?php echo $data['total_bayar']; ?></td>
						
						<td>
							<a href="?action=detail_transaksi_tambah&id_transaksi=<?= $data['id_transaksi'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Detail</span><i class="fa fa-eye"></i></a>
							<a href="cetak_nota_tambah.php?oid=<?= base64_encode($data['id_transaksi']) ?>&id-uid=<?= base64_encode($data['nama_pembeli']) ?>&inf=<?= base64_encode($data['no_invoice']) ?>&tb=<?= base64_encode($data['total_bayar']) ?>&uuid=<?= base64_encode(date("d-m-Y",strtotime($data['tgl_transaksi']))) ?>" target="_blank" class="btn bluetbl"><span class="btn-hapus-tooltip">Cetak</span><i class="fa fa-print"></i></a>
						</td>
                    </tr>
                <?php
                $no++; // Tambah 1 setiap kali looping
                }
                ?>
            </table>
        <div>

        <!--
        Buat paginationnya
        Dengan bootstrap, kita jadi dimudahkan untuk membuat tombol-tombol pagination dengan design yang
        bagus tentunya -->
        <ul class="pagination">
            <!-- LINK FIRST AND PREV -->
            <?php
            if ($page == 1) { // Jika page adalah pake ke 1, maka disable link PREV
            ?>
                <li class="disabled"><a href="#">First</a></li>
                <li class="disabled"><a href="#">&laquo;</a></li>
            <?php
            } else { // Jika buka page ke 1
                $link_prev = ($page > 1) ? $page - 1 : 1;
            ?>
                <li><a href="transaksitambah.php?page=1">First</a></li>
                <li><a href="transaksitambah.php?page=<?php echo $link_prev; ?>">&laquo;</a></li>
            <?php
            }
            ?>

            <!-- LINK NUMBER -->
            <?php
            // Buat query untuk menghitung semua jumlah data
            $sql2 = $pdo->prepare("SELECT COUNT(*) AS jumlah FROM transaksi_tambah");
            $sql2->execute(); // Eksekusi querynya
            $get_jumlah = $sql2->fetch();

            $jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah halamanya
            $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
            $start_number = ($page > $jumlah_number) ? $page - $jumlah_number : 1; // Untuk awal link member
            $end_number = ($page < ($jumlah_page - $jumlah_number)) ? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number

            for ($i = $start_number; $i <= $end_number; $i++) {
                $link_active = ($page == $i) ? 'class="active"' : '';
            ?>
                <li <?php echo $link_active; ?>><a href="transaksitambah.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php
            }
            ?>

            <!-- LINK NEXT AND LAST -->
            <?php
            // Jika page sama dengan jumlah page, maka disable link NEXT nya
            // Artinya page tersebut adalah page terakhir
            if ($page == $jumlah_page) { // Jika page terakhir
            ?>
                <li class="disabled"><a href="#">&raquo;</a></li>
                <li class="disabled"><a href="#">Last</a></li>
            <?php
            } else { // Jika bukan page terakhir
                $link_next = ($page < $jumlah_page) ? $page + 1 : $jumlah_page;
            ?>
                <li><a href="transaksitambah.php?page=<?php echo $link_next; ?>">&raquo;</a></li>
                <li><a href="transaksitambah.php?page=<?php echo $jumlah_page; ?>">Last</a></li>
            <?php
            }
            ?>
        </ul>
    </div>
			</tbody>
			</table>
			</div>
		</div>
	</div>
</div>

<?php 
}
include "foot.php" ?>
