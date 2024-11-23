<?php
// Generate RSA keys (2048 bits)
$config = array(
  "private_key_bits" => 2048,
  "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

// Generate the private and public keys
$res = openssl_pkey_new($config);

// Extract the private key from the key pair
openssl_pkey_export($res, $privateKey);

// Extract the public key from the key pair
$publicKeyDetails = openssl_pkey_get_details($res);
$publicKey = $publicKeyDetails["key"];

// Save the private and public keys to files
file_put_contents('storage/private.key', $privateKey);
file_put_contents('storage/public.key', $publicKey);

// Optionally, you can echo the keys here to verify
// echo "Public Key: $publicKey";
// echo "Private Key: $privateKey";
