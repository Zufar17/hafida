<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Asumsikan $username sudah diambil dari database atau proses login
$username = $_SESSION['username'];
$role = $_SESSION['role'];

$db = \Config\Database::getInstance();

if($role == 'psikolog') {
    $baseAuth = true;
} else {
    $baseAuth = false;
}

if (isset($_GET['sesi_id']) && !empty($_GET['sesi_id'])) {
    $sesi_id = $_GET['sesi_id'];

    // Validasi meet_id
    if (!preg_match('/^[a-zA-Z0-9]+$/', $sesi_id)) {
        echo "Error: sesi_id tidak valid.";
        exit;
    }

    // Simpan meet_id dalam sesi
    $_SESSION['sesi_id'] = $sesi_id;

    // Redirect ke URL tanpa meet_id
    header("Location: /room");
    exit;
}

// Cek apakah meet_id tersedia di sesi
if (!isset($_SESSION['sesi_id'])) {
    echo "Error: sesi_id tidak ditemukan.";
    exit;
}

$sesi_id = $_SESSION['sesi_id'];

$stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

require 'vendor\autoload.php';

use Firebase\JWT\JWT;

/**
 * Change the variables below.
 */
$API_KEY="vpaas-magic-cookie-5bdac6c04abd42b5b852bd7a66574374/3d7505";
$APP_ID="vpaas-magic-cookie-5bdac6c04abd42b5b852bd7a66574374";
$USER_EMAIL= $userData['email'];
$USER_NAME=$username;
$USER_IS_MODERATOR=false;
$USER_AVATAR_URL="";
$USER_ID=uniqid($username);
$LIVESTREAMING_IS_ENABLED=true;
$RECORDING_IS_ENABLED=true;
$OUTBOUND_IS_ENABLED=false;
$TRANSCRIPTION_IS_ENABLED=false;
$EXP_DELAY_SEC=3600;
$NBF_DELAY_SEC=0;
///

// Read your private key from file see https://jaas.8x8.vc/#/apikeys
$private_key = file_get_contents('App/Views/rsa-private.pk');

// Gunakan meet_id dalam roomName
$room_name = "vpaas-magic-cookie-5bdac6c04abd42b5b852bd7a66574374/" . $sesi_id;

// Use the following function to generate your JaaS JWT.
function create_jaas_token(
    $api_key,
    $app_id,
    $user_email,
    $user_name,
    $user_is_moderator,
    $user_avatar_url,
    $user_id,
    $live_streaming_enabled,
    $recording_enabled,
    $outbound_enabled,
    $transcription_enabled,
    $exp_delay,
    $nbf_delay,
    $private_key) {

  $payload = array(
    'iss' => 'chat',
    'aud' => 'jitsi',
    'exp' => time() + $exp_delay,
    'nbf' => time() - $nbf_delay,
    'room'=> '*',
    'sub' => $app_id,
    'context' => [
        'user' => [
            'moderator' => $user_is_moderator ? "true" : "false",
            'email' => $user_email,
            'name' => $user_name,
            'avatar' => $user_avatar_url,
            'id' => $user_id
        ],
        'features' => [
            'recording' => $recording_enabled ? "true" : "false",
            'livestreaming' => $live_streaming_enabled ? "true" : "false",
            'transcription' => $transcription_enabled ? "true" : "false",
            'outbound-call' => $outbound_enabled ? "true" : "false"
        ]
    ]
  );
  return JWT::encode($payload, $private_key, "RS256", $api_key);
}

// 
$token = create_jaas_token($API_KEY,
                            $APP_ID,
                            $USER_EMAIL,
                            $USER_NAME,
                            $USER_IS_MODERATOR,
                            $USER_AVATAR_URL,
                            $USER_ID,
                            $LIVESTREAMING_IS_ENABLED,
                            $RECORDING_IS_ENABLED,
                            $OUTBOUND_IS_ENABLED,
                            $TRANSCRIPTION_IS_ENABLED,
                            $EXP_DELAY_SEC,
                            $NBF_DELAY_SEC,
                            $private_key);

?>

<!DOCTYPE html>
<html>
    <head>
    <script src='https://8x8.vc/vpaas-magic-cookie-5bdac6c04abd42b5b852bd7a66574374/external_api.js' async></script>
    <style>html, body, #jaas-container { height: 100%; }</style>
    <script type="text/javascript">
        window.onload = () => {
        const api = new JitsiMeetExternalAPI("8x8.vc", {
            roomName: "<?php echo $room_name; ?>",
            parentNode: document.querySelector('#jaas-container'),
                        // Make sure to include a JWT if you intend to record,
                        // make outbound calls or use any other premium features!
                        jwt: "<?php echo $token; ?>"
        });
        }
    </script>
    </head>
    <body><div id="jaas-container"></body>
</html>
