<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2018, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

require_once APPPATH . 'third_party/community_auth/core/Auth_Controller.php';

class MY_Controller extends Auth_Controller
{
	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$global = array();
        $global['calendar_url'] = $this->calendar_path();


        $this->load->vars($global);
	}

	public function calendar_path()
    {
        $this->load->library('encryption');

        $base64Key = '3WXW1JcFHVcAVgyyA3RzBbbFU3jEmKf719b2LmYVqgg=';

        // Decode the Base64-encoded key
        $key = base64_decode($base64Key);
        $cipher = 'AES-256-CBC';

        // Encrypt the user ID
        $user_id = $this->session->userdata('user_id');

        // Generate a random IV
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

        // Encrypt the user ID with the specified cipher and key
        $encrypted_user_id = openssl_encrypt($user_id, $cipher, $key, 0, $iv);

        // Combine IV and encrypted data, then encode to base64
        $encrypted_user_id_with_iv = base64_encode($iv . $encrypted_user_id);

        $utcNow = time();
        $istOffset = 5 * 3600 + 30 * 60; // 5 hours 30 minutes in seconds
        $istNow = $utcNow + $istOffset;

        // Set expiry time to 1 minute from now in IST
        $expiryTime = $istNow + 7200; // 60 seconds (1 minute) from the current IST time

        // Generate the URL with the encrypted user ID and expiry time
        $url = 'https://bookings.ontimegroup.com/api/receive-data?user_id=' . urlencode($encrypted_user_id_with_iv) . '&expiry=' . $expiryTime;

        return $url; 
    }
}

/* End of file MY_Controller.php */
/* Location: /community_auth/core/MY_Controller.php */