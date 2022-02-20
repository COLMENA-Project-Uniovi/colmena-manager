<?php

namespace App\Encryption;

/**
 * Encryption trait to provide methods to clases to encrypt and decrypt data
 */
trait EncryptTrait
{
    /**
     * Encrypt the data with the encryption_method and encryption_key configured in the class
     *
     * @param  string $data the data to encrypt
     *
     * @return mixed  the encrypted data
     */
    public function encrypt($data)
    {
        $data = json_encode($data);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->encryption_method));
        $encrypted = openssl_encrypt(
            $data,
            $this->encryption_method,
            $this->encryption_key,
            OPENSSL_RAW_DATA,
            $iv
        );
        $encrypted_data = base64_encode($iv . $encrypted);

        return $encrypted_data;
    }

    /**
     * Decrypt the data with the encryption_method and encryption_key configured in the class
     *
     * @param  string $data the encrypted data
     *
     * @return mixed  the decrypted data
     */
    public function decrypt($data)
    {
        $iv_size = openssl_cipher_iv_length($this->encryption_method);
        $data = base64_decode($data);

        $iv = substr($data, 0, $iv_size);
        $encrypted = substr($data, $iv_size);

        $decrypted_data = openssl_decrypt(
            $encrypted,
            $this->encryption_method,
            $this->encryption_key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if (!$decrypted_data) {
            return false;
        }

        return json_decode($decrypted_data, true);
    }
}
