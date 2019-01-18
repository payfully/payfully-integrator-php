<?php 

class AES {
   
    protected $key;
    protected $data;
    protected $method;
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
    function __construct($data = null, $key = null, $mode = 'CBC') {
        $this->setData($data);
        $this->setKey($key);
        $this->setMethode($mode);
    }
    /**
     * 
     * @param type $data
     */
    public function setData($data) {
        $this->data = $data;
    }
    /**
     * 
     * @param type $key
     */
    public function setKey($key) {
        $this->key = $key;
    }
    
    public function setMethode($mode = 'CBC') {
        $this->method = 'AES-256-' . $mode;
    }
    /**
     * 
     * @return boolean
     */
    public function validateParams() {
        if ($this->data != null &&
                $this->method != null ) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * @return type
     * @throws Exception
     */
    public function encrypt() {
        if ($this->validateParams()) { 

            echo 'Original: ' . $this->encrypt2("Liz la dejaron",'Ot9NTFP2ylGLnipUhkXSlEPpddlEY9HrBDF6veE7dqhgkqZ40UaLDxRo2S/F9+R5tBFFsso9RENCMrh11Dbl7g=='). '<br>';




            $readableString = 'asdf-ghjk-qwer-tyui';
            $encryptedString = 'eyJjaXBoZXJ0ZXh0IjoiNkRuSzRueVR5aERIQTVCdkF6SU9Mc0E0S1llUW5tZndvS0hIbERRMlE1VT0iLCJpdiI6IjNlNGU0YjFlNTBjNGRmODc2ZWExZTg3NjY3MDc4ZjBkIiwic2FsdCI6IjY0OWUxZDQ0NGNiZDc1YjBhODk2NmY2YTRjZTNjYzUzMmIyYTA4ZDQzZjlmYTQzNDRiOGU2MDFmNWIxODlkNzFjZGE3ZDc1YzU1YTBjMzNhMmM1ZWRlMjc5MTMxZTM5ZjNhYjgzY2JjNGQ5ZjIwYmY5YWE3YjdjN2MwNmVlMTZmNjJmYWEzMWU1MjFiMWZjNWFmZDcxMmRlNDQ3MWEyOTg3MDM0MzliODk0N2E0NGViOTMyMWFlMzI0ZWM2Zjg1ZjkwYmQzYzRmNjk5YzdmN2ViMTVhOGE0ZWExYjU1OGJmNWFiYjg5MzFjMjA5YTkzMWEwY2Q1NWM1NTgxMTRkNTY5NTIzZTk5OWMwZDA4Y2FiYmY4MzAzMTA0MzJkNzE2NmJlMDZlYzk3NjQzNzY1MzQ2NDI4YTM0ODM3MWUyOWRkNDU2ZTVmOGQ0NDgxZGVmZjY4M2FlOGYwOTJjODk3NjdhMzRhN2I0MWNlM2VlMDVlOWQ2ZDg4ZDI5MzVmZGM5MDUxY2VlZDhiYjllZDM5MzNjNjg2ODczZGNiOTJhZWI2MzBkMjNjODNhMjIyNTRjZDkxMDg4OTc4OWQ1MTI1MTc2MjQ2ZGYwOTQyODE5MTZlMmY4Y2RjYTU2MDEwMzEzZTM2NmE2ZDMyOTA4OGM3NzI5MWY3NDE3ODRiNTdmNTc1IiwiaXRlcmF0aW9ucyI6OTk5fQ==';
            
            echo 'readable string: ' . $readableString . '<br>';
        $encrypted = $this->encrypt2($readableString, 'Ot9NTFP2ylGLnipUhkXSlEPpddlEY9HrBDF6veE7dqhgkqZ40UaLDxRo2S/F9+R5tBFFsso9RENCMrh11Dbl7g==');
        echo 'encrypted: ' . $encrypted . '<br>';
        
        echo "\n\n\n";
        echo '<hr>';
        echo "\n\n\n";
        
        $decrypted = $this->decrypt2($encrypted, 'Ot9NTFP2ylGLnipUhkXSlEPpddlEY9HrBDF6veE7dqhgkqZ40UaLDxRo2S/F9+R5tBFFsso9RENCMrh11Dbl7g==');
        echo 'decrypted: ' . $decrypted . '<br>';
        $decrypted = $this->decrypt2($encryptedString, 'Ot9NTFP2ylGLnipUhkXSlEPpddlEY9HrBDF6veE7dqhgkqZ40UaLDxRo2S/F9+R5tBFFsso9RENCMrh11Dbl7g==');
        echo 'decrypted from old encrypted string: <strong>' . $decrypted . '</strong><br>';
            // $iv = mb_substr($this->key, 0, 16);
            // $encrypted_data = openssl_encrypt("hola", 'aes-256-cbc', $this->key, OPENSSL_RAW_DATA, $iv);

            // $data = base64_encode($encrypted_data);
            // return $data;
            // return base64_encode(openssl_encrypt($this->data,$this->method, $this->key, $this->options,$this->getIV()));
            // return openssl_encrypt($this->data,$this->method, $this->key, $this->options,$this->getIV());
            // openssl_encrypt(json_encode("data"),'aes-256-cbc', $this->key,0);
            // echo $iv = substr($this->key, 0, 16);
            // return openssl_encrypt("hola",$this->method, $this->key,0,$iv);
            // return openssl_encrypt($this->data,$this->method, $this->key);
            // return base64_encode(trim(openssl_encrypt($this->data,$this->method, $this->key)));
        } else {
            throw new Exception('Invlid params!');
        }
    }
    /**
     * 
     * @return type
     * @throws Exception
     */
    public function decrypt() {
        if ($this->validateParams()) {
           $ret=openssl_decrypt($this->data, $this->method, $this->key, $this->options,$this->getIV());
          
           return   trim($ret); 
        } else {
            throw new Exception('Invlid params!');
        }
    }

        /**
     * @link http://php.net/manual/en/function.openssl-get-cipher-methods.php Available methods.
     * @var string Cipher method. Recommended AES-128-CBC, AES-192-CBC, AES-256-CBC
     */
    protected $encryptMethod = 'AES-256-CBC';
    /**
     * Decrypt string.
     * 
     * @link https://stackoverflow.com/questions/41222162/encrypt-in-php-openssl-and-decrypt-in-javascript-cryptojs Reference.
     * @param string $encryptedString The encrypted string that is base64 encode.
     * @param string $key The key.
     * @return mixed Return original string value.
     */
    public function decrypt2($encryptedString, $key)
    {
        $json = json_decode(base64_decode($encryptedString), true);
        try {
            $salt = hex2bin($json["salt"]);
            $iv = hex2bin($json["iv"]);
        } catch (Exception $e) {
            return null;
        }
        $cipherText = base64_decode($json['ciphertext']);
        $iterations = intval(abs($json['iterations']));
        if ($iterations <= 0) {
            $iterations = 999;
        }
        $hashKey = hash_pbkdf2('sha512', $key, $salt, $iterations, ($this->encryptMethodLength() / 4));
        unset($iterations, $json, $salt);
        $decrypted= openssl_decrypt($cipherText , $this->encryptMethod, hex2bin($hashKey), OPENSSL_RAW_DATA, $iv);
        unset($cipherText, $hashKey, $iv);
        return $decrypted;
    }// decrypt
    /**
     * Encrypt string.
     * 
     * @link https://stackoverflow.com/questions/41222162/encrypt-in-php-openssl-and-decrypt-in-javascript-cryptojs Reference.
     * @param string $string The original string to be encrypt.
     * @param string $key The key.
     * @return string Return encrypted string.
     */
    public function encrypt2($string, $key)
    {
        $ivLength = openssl_cipher_iv_length($this->encryptMethod);
        $iv = openssl_random_pseudo_bytes($ivLength);
 
        $salt = openssl_random_pseudo_bytes(256);
        $iterations = 999;
        $hashKey = hash_pbkdf2('sha512', $key, $salt, $iterations, ($this->encryptMethodLength() / 4));
        $encryptedString = openssl_encrypt($string, $this->encryptMethod, hex2bin($hashKey), OPENSSL_RAW_DATA, $iv);
        $encryptedString = base64_encode($encryptedString);
        unset($hashKey);
        $output = ['ciphertext' => $encryptedString, 'iv' => bin2hex($iv), 'salt' => bin2hex($salt), 'iterations' => $iterations];
        unset($encryptedString, $iterations, $iv, $ivLength, $salt);
        return base64_encode(json_encode($output));
    }// encrypt
    /**
     * Get encrypt method length number (128, 192, 256).
     * 
     * @return integer.
     */
    protected function encryptMethodLength() {
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