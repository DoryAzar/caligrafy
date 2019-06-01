<?php

/**
 * CryptoPayment.php is the file that controls all the cryptocurrency payments
 * @copyright 2019
 * @author Dory A.Azar
 * @version 1.0
 */

/**
 * The Payment handles all payment transactions
 * @author Dory A.Azar
 * @version 1.0
 */

namespace Caligrafy;

use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;

class CryptoPayment {

    
	/**
	* @var string the Stripe public_key
	* @property string Stripe public_key
	*/
	private $_public_key;


	/**
	 * Constructs the Payment Controller to initiate the Stripe api
	 * @param $key_args containing the public and private keys for the appropriate environment
	 * @author Dory A.Azar
	 * @version 1.0
	 */

	public function __construct()
	{
		$this->_public_key = CRYPTO_PAY_KEY;
        ApiClient::init($this->_public_key);
        return $this;

	}
    
 	/**
	 * Creates a new payment transaction
	 * @author Dory A.Azar
	 * @version 1.0
	 */

	public function createTransaction($amount, $currency, $charge, $metadata = array(), $redirectUrl = '', $cancelUrl = '')
    {   
        $currency = $currency?? 'USD';
        $chargeObj = new Charge();

        $chargeObj->name = $charge && isset($charge['name'])? $charge['name'] : '';
        $chargeObj->description = $charge && isset($charge['description'])? $charge['description'] : '';
        $chargeObj->logo_url = $charge && isset($charge['logo_url'])? $charge['logo_url'] : session('imagesUrl').'resources/logo.png';
        $chargeObj->local_price = [
            'amount' => $amount,
            'currency' => $currency
        ];
        $chargeObj->pricing_type = 'fixed_price';
        $chargeObj->redirect_url = $redirectUrl;
        $chargeObj->cancel_url = $cancelUrl;
        $chargeObj->metadata = $metadata;
        $chargeObj->save();
        return $chargeObj;
    
    }
    
    
    /**
	 * Cancel a transaction
     * @param $id string defines the id
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    public function cancelTransaction($id)
    {
        $chargeObj = Charge::retrieve($id);
        if ($chargeObj) {
            $chargeObj->cancel();
        }   
    }
    
    /**
	 * Get All the charges
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    public function getCharges()
    {
        return Charge::getAll();
    }
    
    /**
	 * Retrieve a charge
     * @param chargeId defines the id of the charge
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function getCharge($chargeId)
    {
        return Charge::retrieve($chargeId)?? null;
    }
    
    
    /**
	 * Get Charge Status
     * @param chargeId defines the id of the charge
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    public function getChargeStatus($chargeId)
    {
        $charge = Charge::retrieve($chargeId)?? null;
        $status = 'unknown';
        if ($charge) {
            $statuses = $charge->timeline?? array();
            $status = !empty($statuses)? $statuses[count($statuses)-1]['status'] : $status; 
            
        }
        return $status;
        
    }
    

}

?>
