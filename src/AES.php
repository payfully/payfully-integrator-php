<?php

namespace Payfully\Integrator;

class AES
{
    /**
     * @link http://php.net/manual/en/function.openssl-get-cipher-methods.php Available methods.
     * @var string Cipher method. Recommended AES-128-CBC, AES-192-CBC, AES-256-CBC
     */
    protected $encryptMethod = 'AES-256-CBC';
    protected $key;
    protected $data;
    /**
     * Available OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
     *
     * @var type $options
     */
    protected $options = 0;
    /**
     *
     * @param type $data
     * @param type $key
     * @param type $mode
     */
    public function __construct($data = null, $key = null)
    {
        $this->data = $data;
        $this->key = $key;
    }
    /**
     *
     * @return boolean
     */
    public function validateParams()
    {
        if ($this->data != null) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Encrypt string.
     *
     * @link https://stackoverflow.com/questions/41222162/encrypt-in-php-openssl-and-decrypt-in-javascript-cryptojs Reference.
     * @param string $string The original string to be encrypt.
     * @param string $key The key.
     * @return string Return encrypted string.
     */
    public function encrypt()
    {
        if ($this->validateParams()) {
            $iv = mb_substr($this->key, 0, 16);
            $salt = openssl_random_pseudo_bytes(256);
            $iterations = 999;
            $hashKey = hash_pbkdf2('sha512', $this->key, $salt, $iterations, ($this->encryptMethodLength() / 4));
            $encryptedData = openssl_encrypt($this->data, $this->encryptMethod, hex2bin($hashKey), OPENSSL_RAW_DATA, $iv);
            $encryptedData = base64_encode($encryptedData);
            unset($hashKey);
            $output = ['ciphertext' => $encryptedData, 'salt' => bin2hex($salt)];
            unset($encryptedString, $iv, $salt);
            return base64_encode(json_encode($output));
        } else {
            throw new Exception('Invlid params!');
        }
    }
    // encrypt
    /**
     * Decrypt string.
     *
     * @link https://stackoverflow.com/questions/41222162/encrypt-in-php-openssl-and-decrypt-in-javascript-cryptojs Reference.
     * @param string $encryptedString The encrypted string that is base64 encode.
     * @param string $key The key.
     * @return mixed Return original string value.
     */
    public function decrypt($encryptedString, $key)
    {
        $json = json_decode(base64_decode($encryptedString), true);
        try {
            $salt = hex2bin($json["salt"]);
            $iv = hex2bin($json["iv"]);
        } catch (Exception $e) {
            return null;
        }
        $cipherText = base64_decode($json['ciphertext']);
        $iterations = 999;
        $hashKey = hash_pbkdf2('sha512', $key, $salt, $iterations, ($this->encryptMethodLength() / 4));
        unset($iterations, $json, $salt);
        $decrypted= openssl_decrypt($cipherText, $this->encryptMethod, hex2bin($hashKey), OPENSSL_RAW_DATA, $iv);
        unset($cipherText, $hashKey, $iv);
        return $decrypted;
    }
    // decrypt
    /**
     * Get encrypt method length number (128, 192, 256).
     *
     * @return integer.
     */
    protected function encryptMethodLength()
    {
        $number = filter_var($this->encryptMethod, FILTER_SANITIZE_NUMBER_INT);
        return intval(abs($number));
    }// encryptMethodLength
    /**
     * Set encryption method.
     *
     * @link http://php.net/manual/en/function.openssl-get-cipher-methods.php Available methods.
     * @param string $cipherMethod
     */
    public function setCipherMethod($cipherMethod)
    {
        $this->encryptMethod = $cipherMethod;
    }// setCipherMethod
}
