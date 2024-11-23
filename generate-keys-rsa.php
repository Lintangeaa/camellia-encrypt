<?php

$config = array(
  "private_key_bits" => 2048,
  "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

// Generate the key pair
$res = openssl_pkey_new($config);

if (!$res) {
  die('Failed to generate RSA key pair');
}

openssl_pkey_export($res, $privateKey);

$publicKeyDetails = openssl_pkey_get_details($res);
$publicKey = $publicKeyDetails['key'];

file_put_contents('storage/private.key', $privateKey);

file_put_contents('storage/public.key', $publicKey);

echo "RSA Key Pair Generated Successfully.\n";
echo "Private Key saved to 'storage/private.key'\n";
echo "Public Key saved to 'storage/public.key'\n";
?>