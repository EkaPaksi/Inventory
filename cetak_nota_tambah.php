<?php
require('assets/lib/fpdf.php');
class PDF extends FPDF
{
function Header()
{
    $this->SetFont('Arial','B',30);
    $this->Cell(30,10,'Detail Stok Masuk');

    $this->Ln(10);
    $this->SetFont('Arial','i',10);
    $this->cell(30,10,'');
 

    $this->Ln(5);
    $this->SetFont('Arial','i',10);
    $this->cell(30,10,'Tanggal :'.base64_decode($_GET['uuid']).'');
    $this->Line(10,40,200,40);
	
	$this->Ln(5);
    $this->SetFont('Arial','i',10);
    $this->cell(30,10,'No Permintaan : '.base64_decode($_GET['inf']).'');
    $this->Line(10,40,200,40);
}
function LoadData(){
	mysql_connect("localhost","root","");
	mysql_select_db("gudangedp");
	$id=base64_decode($_GET['oid']);
	$data=mysql_query("select sub_transaksi_tambah.jumlah_beli,
	barang.nama_barang,
	barang.harga_jual,
	sub_transaksi_tambah.total_harga
	from sub_transaksi_tambah inner join barang on barang.id_barang=sub_transaksi_tambah.id_barang where sub_transaksi_tambah.id_transaksi='$id'");
	
	while ($r=  mysql_fetch_array($data))
		        {
		            $hasil[]=$r;
		        }
		        return $hasil;
}
function BasicTable($header, $data)
{
    
    $this->SetFont('Arial','B',12);
		$this->Cell(90,7,$header[1],1);
        $this->Cell(15,7,$header[0],1);
    $this->Ln();
    
    $this->SetFont('Arial','',12);
    foreach($data as $row)
    {
		$this->Cell(90,7,$row['nama_barang'],1);
        $this->Cell(15,7,$row['jumlah_beli'],1);
        $this->Ln();
    }

    mysql_connect("localhost","root","");
	mysql_select_db("gudangedp");
	$id=base64_decode($_GET['oid']);

    $getsum=mysql_query("select sum(total_harga) as grand_total,sum(jumlah_beli) as jumlah_beli from sub_transaksi_tambah where id_transaksi='$id'");
	$getsum1=mysql_fetch_array($getsum);
	$this->cell(15);
	$this->cell(90);
	$this->Ln(30);
    $this->SetFont('Arial','',15);
    session_start();
}
}

$pdf = new PDF();
$pdf->SetTitle('Invoice : '.base64_decode($_GET['inf']).'');
$pdf->AliasNbPages();
$header = array('Qty', 'Nama Barang');
$data = $pdf->LoadData();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->BasicTable($header,$data);
$filename=base64_decode($_GET['inf']);
$pdf->Output('','Masuk/'.$filename.'.pdf');
?>
