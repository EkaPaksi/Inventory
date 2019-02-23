<?php
require('assets/lib/fpdf.php');
class PDF extends FPDF
{
function Header()
{
    $this->SetFont('Arial','B',30);
    $this->Cell(30,10,'Laporan Barang Keluar');

    $this->Ln(10);
    $this->SetFont('Arial','i',10);
    $this->cell(30,10,'');
 

    $this->Ln(5);
    $this->SetFont('Arial','i',10);
    $this->cell(30,10,'Tanggal :'.date("d-m-Y").'');
    $this->Line(10,40,200,40);
	
}
	function data_barang(){
		mysql_connect("localhost","root","");
		mysql_select_db("gudangedp");
		$data=mysql_query("select sub_transaksi.no_invoice, 
						sub_transaksi.jumlah_beli,
						barang.nama_barang,
						barang.harga_jual,
						sub_transaksi.total_harga,
						transaksi.tgl_transaksi
						from 
						sub_transaksi 

						inner join 
						barang on barang.id_barang=sub_transaksi.id_barang 

						inner join 
						transaksi on transaksi.id_transaksi=sub_transaksi.id_transaksi 

						where 
						sub_transaksi.no_invoice");
		while ($r=  mysql_fetch_array($data))
		        {
		            $hasil[]=$r;
		        }
		        return $hasil;
		        
	}
	function set_table($header,$data){
		$this->SetFont('Arial','B',9);
        $this->Cell(10,7,"No",1);
        $this->Cell(60,7,$header[0],1);
        $this->Cell(40,7,$header[1],1);
        $this->Cell(24,7,$header[2],1);
        $this->Cell(40,7,$header[3],1);
    	$this->Ln();

    	$this->SetFont('Arial','',9);
    	$no=1;
	    foreach($data as $row)
	    {
	        $this->Cell(10,7,$no++,1);
	        $this->Cell(60,7,$row['no_invoice'],1);
	        $this->Cell(40,7,$row['nama_barang'],1);
	        $this->Cell(24,7,$row['jumlah_beli'],1);
	        $this->Cell(40,7,$row['tgl_transaksi'],1);
	        $this->Ln();
	    }
	}
}

$pdf = new PDF();
$pdf->SetTitle('Cetak Data Barang');

$header = array('Nomor', 'Nama Barang','Jumlah' ,'Tanggal');
$data = $pdf->data_barang();

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->set_table($header,$data);
$pdf->Output('','OUT/'.date("d-m-Y").'.pdf');
?>