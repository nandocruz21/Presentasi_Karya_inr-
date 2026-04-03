<?php
/**
 * File khusus untuk mengirim pesan WhatsApp via API Fonnte
 */

function kirimWhatsApp($nomor_tujuan, $pesan) {
    // ⚠️ GANTI TEKS DI BAWAH INI DENGAN TOKEN DARI FONNTE ANDA ⚠️
    $token = 'FHhH1kvPmhpYuRcy8Esb'; 

    // Pastikan nomor tujuan dimulai dengan kode negara (mengubah 08 jadi 628)
    if (substr($nomor_tujuan, 0, 1) == '0') {
        $nomor_tujuan = '62' . substr($nomor_tujuan, 1);
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.fonnte.com/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array(
        'target' => $nomor_tujuan,
        'message' => $pesan, 
        'countryCode' => '62', 
      ),
      CURLOPT_HTTPHEADER => array(
        "Authorization: $token" 
      ),
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);
    
    return $response;
}
?>