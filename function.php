<?php

$conn = mysqli_connect("localhost","root","","db_riset");

function query($query){
    global $conn;
    $result = mysqli_query($conn,$query); //kotaknya
    $rows = []; //siapkan wadah kosong
    while($row = mysqli_fetch_assoc($result)){
        $rows [] = $row;
    }
    return $rows; //mengembalikan kotaknya yg dipilih
}

function tambah($post){
    global $conn;
    $pengawas = serialize([
        [
            "lokasiPembebasan" => $post["lokasiPembebasan"]
        ]
    ]); 
    
    $manuverBebas = serialize([
        [
            "lokasiManuverBebas" => $post["lokasiManuverBebas"],
            "installManuverBebas" => $post["installManuverBebas"]
        ]
    ]);

    $manuverNormal = serialize([
        [
            "lokasiManuverNormal" => $post["lokasiManuverNormal"],
            "installManuverNormal" => $post["installManuverNormal"]
        ]
    ]);

    $query = "INSERT INTO `data` (pengawas,pembebasan,penormalan,`status`)
             VALUE ('$pengawas','$manuverBebas','$manuverNormal','lanjut')";
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}