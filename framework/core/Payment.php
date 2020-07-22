<?php

/**
 * Payment.php is the file that controls all the payment transactions
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
use \Exception as Exception;

class Payment {


	/**
	* @var string the Stripe public_key
	* @property string Stripe public_key
	*/
	private $_public_key;

	/**
	* @var string the Stripe private_key
	* @property string Stripe private_key
	*/
	private $_private_key;
    
    
    /**
	* @var string the Plaid client_id
	* @property string the Plaid client_id
	*/
	private $_ach_client_id;
    
    /**
	* @var string the Plaid public key
	* @property string the Plaid public key
	*/
	private $_ach_public_key;
    
    /**
	* @var string the Plaid secret 
	* @property string the Plaid secret
	*/
	private $_ach_secret;
    
    /**
	* @var string the Plaid url 
	* @property string the Plaid sandbox url
	*/
	private $_ach_url;
    

	/**
	 * Constructs the Payment Controller to initiate the Stripe api
	 * @param $key_args containing the public and private keys for the appropriate environment
	 * @author Dory A.Azar
	 * @version 1.0
	 */

	public function __construct()
	{
		if (strtolower(APP_ENV) == 'production') {
            $this->_public_key = PAY_PUBLIC_KEY_PRODUCTION;
            $this->_private_key = PAY_PRIVATE_KEY_PRODUCTION;
        } else {
            $this->_public_key = PAY_PUBLIC_KEY_TEST;
            $this->_private_key = PAY_PRIVATE_KEY_TEST;
        }
        
        if (strtolower(ACH_ACTIVATE) == 'true') {
            $this->_ach_client_id = ACH_CLIENT_ID;
            $this->_ach_public_key = ACH_PUBLIC_KEY;
            if (strtolower(APP_ENV) == 'production') {
                $this->_ach_url = "https://google.plaid.com";
                $this->_ach_secret = ACH_PRODUCTION_SECRET;
            } else {
                $this->_ach_url = "https://sandbox.plaid.com";
                $this->_ach_secret = ACH_SANDBOX_SECRET;
            }
        }

		\Stripe\Stripe::setApiKey($this->_private_key);
        return $this;

	}

	/**
	 * Creates a new payment transaction
	 * @param Integer $amount defines the amount of the transaction in cents
	 * @param string $currency defines the currecy of the transaction
	 * @param Object $card takes the card object submitted
	 * @param string $receipt_email takes the customer email
	 * @param string $metadata defines the metadata information that can be passed on to the transaction
	 * @param string $description defines the description that can be passed on to the transaction
	 * @param boolean $save defines whether the transaction should be saved or not for a particular customer
	 * @return $result to report on the result of the creation
	 * @author Dory A.Azar
	 * @version 1.0
	 */

	public function createTransaction($amount, $currency, $card, $receipt_email = null, $metadata = null, $description = '', $save = false) {
        
        $result = array('action_success' => false, 'error' => 'Transaction could not be completed');
        // Create a charge: this will charge the user's card
		try {
          
          if ($card) {
              $token = is_array($card)? $this->createStripeToken($card) : $card;
              
              if (isset($token)) {
                $outcome = \Stripe\Charge::create(array(
                "amount" => $amount, // Amount in cents
                "currency" => $currency,
                "source" => $token,
                "metadata" => $metadata,
                "description" => $description,
                "receipt_email" => $receipt_email
                ));
                $result = $outcome && $outcome->id?  array('action_success' => true, 'data' => array('confirmation' => $outcome->id)) : $result;
              }
          }
            
		} catch(Exception $e) {
		  // The card has been declined
		  $result['error'] = $e->getMessage();
		}
		return $result;
	}
    
    
	/**
	 * Creates an ACH bank link
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    public function linkBankInformation($publicToken, $account)
    {
        $result = array('action_success' => false, 'error' => 'Transaction could not be completed');
        
        if (isset($publicToken) && isset($account)) {
            
           $data = array('client_id' => $this->_ach_client_id,
                         'secret' => $this->_ach_secret,
                         'public_token' => $publicToken);
            $headers = array("Content-Type: application/json");
            $response = httpRequest($this->_ach_url.'/item/public_token/exchange', 'POST', $data, $headers);
            
            if (isset($response['access_token'])) {
                $data = array('client_id' => $this->_ach_client_id,
                         'secret' => $this->_ach_secret,
                         'access_token' => $response['access_token'],
                         'account_id' => $account);
                
                $response = httpRequest($this->_ach_url.'/processor/stripe/bank_account_token/create', 'POST', $data, $headers);
                
            }
            
            $response = isset($response['stripe_bank_account_token'])? $response['stripe_bank_account_token'] : null;
            if ($response) {
                $result = array('action_success' => true, 'token' => $response);
            }
        }
        return $result;
    }
    
	
	/**
	 * Creates a new payment transaction that might require a customer authentication flow (3DSecure for example)
	 * @param Integer $amount defines the amount of the transaction in cents
	 * @param string $currency defines the currecy of the transaction
	 * @param string $metadata defines the metadata information that can be passed on to the transaction
	 * @return $result to report on the result of the creation
	 * @author Dory A.Azar
	 * @version 1.0
	 */
	public function createPaymentIntent($amount, $currency, $metadata = array(), $receipt_email = null, $description = '') 
	{
		$result = array('action_success' => false, 'error' => 'Transaction could not be completed');
		try {
			
			$intent = \Stripe\PaymentIntent::create([
		  		'amount' => $amount,
		  		'currency' => $currency,
		  		// Verify your integration in this guide by including this parameter
				'metadata' => array_merge($metadata, ['integration_check' => 'accept_a_payment']),
				'description' => $description,
                'receipt_email' => $receipt_email
			]);
			
            $result = $intent?  array('action_success' => true, 'data' => $intent) : $result;
			
		} catch(Exception $e) {
			$result['error'] = $e->getMessage();
		}
		return $result;
	}
    
    private function createStripeToken($card)
    {
        return \Stripe\Token::create($card);
    }

	/**
	 * Accessor that returns the public key
	 */

	public function getPublicKey() {
		return $this->_public_key;

	}

}

?>
