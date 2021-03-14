<?php


namespace App\Model;


class Crypto
{



    /**
     * AES/CBC/PKCS5Padding Encrypter
     *
     * @param $str
     * @param $key
     * @return string
     */
    public static function encrypt($str, $key)
    {
        $zeroPack = pack('i*', 0);
        $iv = str_repeat($zeroPack, 4);
        return bin2hex(openssl_encrypt($str, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }

    /**
     * AES/CBC/PKCS5Padding Decrypter
     *
     * @param $encryptedStr
     * @param $key
     * @return string
     */
    public static function decrypt($encryptedStr, $key)
    {
        $zeroPack = pack('i*', 0);
        $iv = str_repeat($zeroPack, 4);
        return openssl_decrypt(base64_decode($encryptedStr), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

}
