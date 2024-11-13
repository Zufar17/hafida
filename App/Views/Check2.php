<?php

use Config\Database;

$db = Database::getInstance();

// Ambil transaksi yang statusnya masih Pending dan sudah lebih dari 5 menit
$stmt = $db->prepare("SELECT id, user_id, username, unique_code, signature, amount, status FROM bayarlangganan WHERE status = 'Pending' AND created_at > NOW() - INTERVAL 5 MINUTE");
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

            $updateStmt = $db->prepare("UPDATE bayarlangganan SET status = 'Completed' WHERE id = ?");
            $updateStmt->execute([$transaction['id']]);

            $memberId = $transaction['user_id']; // ID pengguna dari transaksi
            $updatePremiumStmt = $db->prepare("UPDATE users SET premium = 'Y', premium_expiry = DATE_ADD(NOW(), INTERVAL 1 MONTH) WHERE user_id = ?");
            $updatePremiumStmt->execute([$memberId]);

        }
    } else {
        // Update status transaksi menjadi Failed jika gagal
        $updateStmt = $db->prepare("UPDATE bayarlangganan SET status = 'Failed' WHERE id = ?");
        $updateStmt->execute([$transaction['id']]);
    }
}
?>
