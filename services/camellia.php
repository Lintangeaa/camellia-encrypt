<?php
// Encrypt function (XOR with the key, for demonstration purposes)
function camelliaEncrypt($plainText, $key)
{
  $blockSize = 16; // Camellia uses 128-bit blocks
  if (strlen($key) !== 32) {
    throw new Exception('Key must be 256 bits (32 bytes)');
  }

  $textBuffer = mb_convert_encoding($plainText, 'UTF-8', 'auto');

  // Pad the text to be a multiple of the block size
  $textBuffer = str_pad($textBuffer, ceil(strlen($textBuffer) / $blockSize) * $blockSize, "\0");

  // Split into blocks of the given block size
  $blocks = str_split($textBuffer, $blockSize);
  $encryptedBlocks = [];

  foreach ($blocks as $block) {
    $encryptedBlocks[] = camelliaEncryptBlock($block, $key);
  }

  return bin2hex(implode('', $encryptedBlocks));
}

// Encrypt a single block (XOR with the key)
function camelliaEncryptBlock($block, $key)
{
  $result = '';
  for ($i = 0; $i < strlen($block); $i++) {
    $result .= chr(ord($block[$i]) ^ ord($key[$i % strlen($key)]));
  }
  return $result;
}

// Decrypt function (XOR with the key, for demonstration purposes)
function camelliaDecrypt($cipherText, $key)
{
  $blockSize = 16; // Camellia uses 128-bit blocks
  if (strlen($key) !== 32) {
    throw new Exception('Key must be 256 bits (32 bytes)');
  }

  // Convert the hex cipher text back to binary data
  $cipherBuffer = hex2bin($cipherText);
  $blocks = str_split($cipherBuffer, $blockSize);
  $decryptedBlocks = [];

  foreach ($blocks as $block) {
    $decryptedBlocks[] = camelliaDecryptBlock($block, $key);
  }

  $decryptedBuffer = implode('', $decryptedBlocks);
  return rtrim($decryptedBuffer, "\0"); // Remove padding
}

// Decrypt a single block (XOR with the key)
function camelliaDecryptBlock($block, $key)
{
  return camelliaEncryptBlock($block, $key); // Decryption is identical to encryption for XOR
}

?>