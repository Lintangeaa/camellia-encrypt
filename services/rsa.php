<?php

// Encrypt using RSA public key
function rsaEncrypt($data, $publicKey)
{
  // Encrypt data with the public key
  openssl_public_encrypt($data, $encryptedData, $publicKey);
  return base64_encode($encryptedData); // Return base64 encoded for safe display/storage
}

// Decrypt using RSA private key
function rsaDecrypt($encryptedData, $privateKey)
{
  // Decode the base64 encoded encrypted data
  $encryptedData = base64_decode($encryptedData);

  // Decrypt data with the private key
  openssl_private_decrypt($encryptedData, $decryptedData, $privateKey);
  return $decryptedData;
}

// Load the public and private keys from storage
$publicKey = file_get_contents('storage/public.key');
$privateKey = file_get_contents('storage/private.key');
