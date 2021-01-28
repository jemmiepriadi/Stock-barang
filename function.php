<?php
session_start();
$conn = mysqli_connect("localhost","root","","stockbarang");

//add new barang
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskirpsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "insert into stock(namabarang ,deskirpsi, stock) values('$namabarang','$deskripsi','$stock')");
    if($addtotable){
        header('location:index.php');
    } else{
        echo "gagal";
        header('location:masuk.php');
    }

    
}
//menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

    $addtobarangmasuk = mysqli_query($conn, "insert into masuk(idbarang,keterangan,qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock = '$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtobarangmasuk&&$updatestockmasuk){
        header('location:masuk.php');
    } else{
        echo "gagal";
        header('location:masuk.php');
    }
}

if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

    $addtobarangkeluar = mysqli_query($conn, "insert into keluar(idbarang,penerima,qty) values('$barangnya','$penerima','$qty')");
    $updatestockkeluar = mysqli_query($conn, "update stock set stock = '$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtobarangkeluar&&$updatestockkeluar){
        header('location:keluar.php');
    } else{
        echo "gagal";
        header('location:keluar.php');
    }
}


//update info barang
if(isset($_POST['editdata'])){
    $idbarang = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskirpsi'];

    $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskirpsi='$deskripsi' where idbarang='$idbarang'");
    if($update){

    }else{
        echo '<script language="javascript">';
        echo 'alert("tidak bisa update")';
        echo '</script>';
    }
}

if(isset($_POST['hapusdata'])){
    $idbarang = $_POST['idb'];
    $delete = mysqli_query($conn, "delete from stock where idbarang='$idbarang'");
}

if(isset($_POST['editdatamasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $namabarang = $_POST['namabarang'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $stocksekarang = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $fetch_stock_sekarang = mysqli_fetch_array($stocksekarang);
    $jumlah_stock_sekarang = $fetch_stock_sekarang['stock'];

    $datasekarang = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $fetch_masuk = mysqli_fetch_array($datasekarang);
    $qty_sekarang_masuk = $fetch_masuk['qty'];
    $keterangan_sekarang = $fetch_masuk['keterangan'];

    if($qty>$qty_sekarang){
        $qty_sekarang = $qty-$qty_sekarang_masuk;
        $stocktotal = $jumlah_stock_sekarang+$qty_sekarang;
        $updatestock = mysqli_query($conn, "update stock set stock='$stocktotal' where idbarang='$idb'");
        $update_masuk = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$keterangan' where idmasuk='$idm'");
    }else{
        $qty_sekarang = $qty_sekarang_masuk-$qty;
        $stocktotal = $jumlah_stock_sekarang-$qty_sekarang;
        $updatestock = mysqli_query($conn, "update stock set stock='$stocktotal' where idbarang='$idb'");
        $update_masuk = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$keterangan where idmasuk='$idm'");
    }

}

if(isset($_POST['hapusdatamasuk'])){
    $idmasuk = $_POST['idm'];
    $idbarang = $_POST['idb'];

    $datasekarang = mysqli_query($conn, "select * from stock where idbarang='$idbarang'");
    $fetch_data = mysqli_fetch_array($datasekarang);
    $stock = $fetch_data['stock'];

    $data_masuk = mysqli_query($conn, "select * from masuk where idmasuk='$idmasuk'");
    $fetch_masuk = mysqli_fetch_array($data_masuk);
    $qty_skrg = $fetch_masuk['qty'];

    $stock_sekarang = $stock-$qty_skrg;

    $update_stock = mysqli_query($conn, "update stock set stock='$stock_sekarang' where idbarang='$idbarang'");

    $delete = mysqli_query($conn, "delete from masuk where idmasuk='$idmasuk'");
}

if(isset($_POST['editdatakeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $namabarang = $_POST['namabarang'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $stocksekarang = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $fetch_stock_sekarang = mysqli_fetch_array($stocksekarang);
    $jumlah_stock_sekarang = $fetch_stock_sekarang['stock'];

    $datasekarang = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $fetch_masuk = mysqli_fetch_array($datasekarang);
    $qty_sekarang_keluar = $fetch_masuk['qty'];

    if($qty>$qty_sekarang_keluar){
        $qty_sekarang = $qty-$qty_sekarang_keluar;
        $stocktotal = $jumlah_stock_sekarang-$qty_sekarang;
        $updatestock = mysqli_query($conn, "update stock set stock='$stocktotal' where idbarang='$idb'");
        $update_masuk = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
    }else{
        $qty_sekarang = $qty_sekarang_keluar - $qty;
        $stocktotal = $jumlah_stock_sekarang+$qty_sekarang;
        $updatestock = mysqli_query($conn, "update stock set stock='$stocktotal' where idbarang='$idb'");
        $update_masuk = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
    }

}

if(isset($_POST['hapusdatakeluar'])){
    $idkeluar = $_POST['idk'];
    $idbarang = $_POST['idb'];

    $datasekarang = mysqli_query($conn, "select * from stock where idbarang='$idbarang'");
    $fetch_data = mysqli_fetch_array($datasekarang);
    $stock = $fetch_data['stock'];

    $data_keluar = mysqli_query($conn, "select * from keluar where idkeluar='$idkeluar'");
    $fetch_masuk = mysqli_fetch_array($data_keluar);
    $qty_skrg = $fetch_masuk['qty'];

    $stock_sekarang = $stock+$qty_skrg;

    $update_stock = mysqli_query($conn, "update stock set stock='$stock_sekarang' where idbarang='$idbarang'");

    $delete = mysqli_query($conn, "delete from keluar where idkeluar='$idkeluar'");
}



?>