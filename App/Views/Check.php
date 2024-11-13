<?php

use Config\Database;

set_time_limit(0); // Mengatur waktu maksimum eksekusi skrip menjadi tidak terbatas

$db = Database::getInstance();

while (true) {
    // Ambil transaksi yang statusnya masih Pending dan sudah lebih dari 5 menit
    $stmt = $db->prepare("SELECT histori_id, member_id, username, psikolog_id, unique_code, signature, tanggal, waktu_mulai, waktu_akhir FROM historibayar WHERE status = 'Pending' AND created_at > NOW() - INTERVAL 5 MINUTE");
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($transactions as $transaction) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.paydisini.co.id/v1/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $post = array(
            'key' => '06a10122fa5db79f7c4d813954cc33ef',
            'request' => 'status',
            'unique_code' => $transaction['unique_code'],
            'signature' => $transaction['signature']
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            continue;
        }
        curl_close($ch);
        echo $result;
        $response = json_decode($result);
        
        if ($response->success == 1) {
            if ($response->data->status == 'Success') {
                $updateStmt = $db->prepare("UPDATE historibayar SET status = 'Completed' WHERE histori_id = ?");
                $updateStmt->execute([$transaction['histori_id']]);

                $psikologStmt = $db->prepare("SELECT * FROM psikolog WHERE psikolog_id = ?");
                $psikologStmt->execute([$transaction['psikolog_id']]);
                $psikolog = $psikologStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($psikolog) {
                    $harga = $psikolog['harga'];

                    // Tambahkan log untuk melihat nilai harga dan psikolog_id
                    echo "Harga: $harga, Psikolog ID: {$transaction['psikolog_id']} <br>";

                    // Tambahkan saldo pada tabel psikolog
                    $updateSaldoStmt = $db->prepare("UPDATE psikolog SET saldo = saldo + ? WHERE psikolog_id = ?");
                    if ($updateSaldoStmt->execute([$harga, $transaction['psikolog_id']])) {
                        echo "Saldo berhasil ditambahkan untuk Psikolog ID: {$transaction['psikolog_id']}<br>";
                    } else {
                        $errorInfo = $updateSaldoStmt->errorInfo();
                        echo "Gagal menambah saldo: " . $errorInfo[2] . "<br>";
                    }

                    // Tambahkan sesi untuk psikolog dan member
                    $addsesi = $db->prepare("INSERT INTO sesi (psikolog_id, member_id, tanggal, waktu_mulai, waktu_akhir) VALUES (?, ?, ?, ?, ?)");
                    $addsesi->bindParam(1, $transaction['psikolog_id']);
                    $addsesi->bindParam(2, $transaction['member_id']);
                    $addsesi->bindParam(3, $transaction['tanggal']);
                    $addsesi->bindParam(4, $transaction['waktu_mulai']);
                    $addsesi->bindParam(5, $transaction['waktu_akhir']);
                    $addsesi->execute();

                    echo $transaction['username'];
                    echo json_encode(['status' => $response->data->status]);
                } else {
                    echo "Psikolog tidak ditemukan.";
                }
            }
        } else {
            // Update status transaksi menjadi Failed jika gagal
            $updateStmt = $db->prepare("UPDATE historibayar SET status = 'Failed' WHERE histori_id = ?");
            $updateStmt->execute([$transaction['histori_id']]);
        }
    }

    // Tunggu selama 5 detik sebelum menjalankan loop lagi
    sleep(5);
}
?>
