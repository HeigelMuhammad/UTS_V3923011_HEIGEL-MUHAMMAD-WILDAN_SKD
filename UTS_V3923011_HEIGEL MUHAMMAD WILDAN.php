<?php
// Fungsi untuk Caesar Cipher: menggeser karakter berdasarkan kunci
function caesar_cipher($char, $key) {
    if (ctype_alpha($char)) {
        $base = ctype_upper($char) ? 'A' : 'a'; // Tentukan huruf dasar (A/a)
        return chr((ord($char) - ord($base) + $key) % 26 + ord($base)); // Geser dan kembalikan karakter baru
    }
    return $char; // Jika bukan huruf, kembalikan karakter asli
}

// Fungsi enkripsi Caesar
function caesar_encrypt($text, $key) {
    return implode('', array_map(fn($char) => caesar_cipher($char, $key), str_split($text))); // Enkripsi setiap karakter
}

// Fungsi dekripsi Caesar
function caesar_decrypt($text, $key) {
    return caesar_encrypt($text, 26 - $key); // Dekripsi dengan kunci terbalik
}

// Fungsi untuk Vigenère Cipher: enkripsi dengan kunci berulang
function vigenere_encrypt($text, $key) {
    $key = strtoupper($key); // Kunci dalam huruf besar
    $keyLen = strlen($key);
    return implode('', array_map(function($char, $i) use ($key, $keyLen) {
        if (ctype_alpha($char)) {
            $shift = ord($key[$i % $keyLen]) - ord('A'); // Hitung pergeseran
            return caesar_cipher($char, $shift); // Geser karakter
        }
        return $char; // Kembalikan karakter asli jika bukan huruf
    }, str_split($text), array_keys(str_split($text))));
}

// Fungsi dekripsi Vigenère
function vigenere_decrypt($text, $key) {
    $key = strtoupper($key);
    return implode('', array_map(function($char, $i) use ($key) {
        if (ctype_alpha($char)) {
            $shift = ord($key[$i % strlen($key)]) - ord('A');
            return caesar_cipher($char, 26 - $shift); // Dekripsi dengan shift terbalik
        }
        return $char;
    }, str_split($text), array_keys(str_split($text))));
}

// Fungsi enkripsi gabungan Caesar dan Vigenère
function encrypt_combination($text, $caesar_key, $vigenere_key) {
    return vigenere_encrypt(caesar_encrypt($text, $caesar_key), $vigenere_key); // Enkripsi Caesar, lalu Vigenère
}

// Fungsi dekripsi gabungan Caesar dan Vigenère
function decrypt_combination($text, $caesar_key, $vigenere_key) {
    return caesar_decrypt(vigenere_decrypt($text, $vigenere_key), $caesar_key); // Dekripsi Vigenère, lalu Caesar
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enkripsi & Dekripsi</title>
    <style>
        .container { max-width: 400px; margin: 50px auto; text-align: center; }
        input, button, textarea { display: block; width: 100%; margin-bottom: 10px; padding: 10px; }
        button { cursor: pointer; }
        textarea { height: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enkripsi & Dekripsi</h1>
        <form method="post">
            <input type="text" name="plain" placeholder="Masukkan teks" required />
            <input type="number" name="caesar_key" placeholder="Kunci Caesar (0-25)" min="0" max="25" required />
            <input type="text" name="vigenere_key" placeholder="Kunci Vigenère" required />
            <button type="submit" name="enkripsi">Enkripsi</button>
            <button type="submit" name="dekripsi">Dekripsi</button>
            <textarea readonly><?php  
                if (isset($_POST["enkripsi"])) { 
                    echo encrypt_combination($_POST["plain"], $_POST["caesar_key"], $_POST["vigenere_key"]);
                } else if (isset($_POST["dekripsi"])) {
                    echo decrypt_combination($_POST["plain"], $_POST["caesar_key"], $_POST["vigenere_key"]);
                }
            ?></textarea>
        </form>
    </div>
</body>
</html>
