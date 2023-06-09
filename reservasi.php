<?php
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['id'])){
        $query = "UPDATE reservasi
        SET tanggal = '".$_POST['tanggal']."', jam_penyewaan=".$_POST['jam_penyewaan']."
        WHERE id=".$_POST['id'].";";
        $result = $conn->query($query);
        echo json_encode(["message"=>"berhasil"]);
    }else{
        echo json_encode(reservasi($conn));
    }
    if(isset($_GET['riwayat'])){
        echo json_encode(riwayat($conn));
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET['tanggal'])) {
        echo json_encode(cekJadwalKosong($_GET['tanggal'], $conn));
    }else if(isset($_GET['riwayat'])){
        if(isset($_GET['id'])){
            riwayatConfirm($_GET['id'],$conn);
        }
        echo json_encode(riwayat($conn));
    }else{
        echo json_encode(cekJadwalKosong(null, $conn));
    }
} else {
    echo json_encode(riwayat($conn));
}

function cekJadwalKosong($tanggal = null, $conn)
{
    if ($tanggal == null) {
        $tanggal = date("Y-m-d");
    }
    $query = "SELECT * FROM `reservasi` WHERE `tanggal` = '$tanggal';";
    $result = $conn->query($query);
    $rows = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $response = $rows;
    if ($rows == null) {
        $response = [];
    }
    return $response;
}
function reservasi($conn)
{
    $query = "INSERT INTO `reservasi` (nama,lapangan,tanggal,jam_penyewaan,durasi) VALUES ('$_POST[nama]','$_POST[lapangan]', '$_POST[tanggal]', '$_POST[penyewaan]', '1');";
    if (mysqli_query($conn, $query)) {
        http_response_code(200);
        return "Berhasil Reservasi";
    } else {
        http_response_code(400);
        return "Reservasi gagal, cek data anda kembali atau lapangan tidak tersedia!";
    };
};

function riwayat($conn)
{
    $query = "SELECT * FROM `reservasi`;";
    $result = $conn->query($query);
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $response = $rows;
    return $response;
};
function riwayatConfirm($id,$conn)
{
    $query = "UPDATE reservasi SET confirm = 1 WHERE id = $id;";
    $conn->query($query);
    $response = [
        "success"=>1
    ];
    return $response;
};
