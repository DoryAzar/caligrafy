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
	 * @param string $receipt_email defines the email address where the receipt should be sent
	 * @param string $description defines a description of the product beind sold
	 * @param array $transfer defines the transfer data to vendors if they  exist. keys possible: 'destination' for connected stripe account. 'amount' to define the amount the vendor will receive
	 * @return $result to report on the result of the creation
	 * @author Dory A.Azar
	 * @version 1.0
	 */
	public function createPaymentIntent($amount, $currency, $metadata = array(), $receipt_email = null, $description = '', $transfer = array() ) 
	{
		$result = array('action_success' => false, 'error' => 'Transaction could not be completed');
		try {
			
			$input = array(
				'amount' => $amount,
		  		'currency' => $currency,
		  		// Verify your integration in this guide by including this parameter
				'metadata' => strtolower(APP_ENV) == 'production'? $metadata : array_merge($metadata, ['integration_check' => 'accept_a_payment']),
				'description' => $description,
                'receipt_email' => $receipt_email
			);
			$input = !empty($transfer)? array_merge($input, $transfer) : $input;
			$intent = \Stripe\PaymentIntent::create($input);
			
            $result = $intent?  array('action_success' => true, 'data' => $intent) : $result;
			
		} catch(Exception $e) {
			$result['error'] = $e->getMessage();
		}
		return $result;
	}
	
	/**
	 * Retrieve Payment Intent
	 * @param string $intentId 
	 * @return $result to report on the result of the creation
	 * @author Dory A.Azar
	 * @version 1.0
	 */
	public function retrieveIntent($intentId)
	{
		$result = array('action_success' => false, 'error' => 'Transaction could not be completed');
		try {
			$intent = null;
			if (isset($intentId)) {
				$intent = \Stripe\PaymentIntent::retrieve(
					$intentId,
					[]
				);	
				$result = $intent?  array('action_success' => true, 'data' => $intent) : $result;
			}
	
		} catch(Exception $e) {
		 	$result['error'] = $e->getMessage();
		}
		return $result;

	}
	
	/**
	 * Creates a new integrated checkout session
	 * @param Integer $amount defines the amount of the transaction in cents
	 * @param string $currency defines the currency of the transaction
	 * @param string $quantity defines the quantity of products
	 * @param string $productData defines the product information that can be passed on to the transaction
	 * @param string $successUrl defines the url that it will redirect to when successful payment
	 * @param string $cancelUrl defines the url that it will redirect to when the payment fails
	 * @param array $paymentType defines the different payment methods to be supported by checkout
	 * @param array $payment_intent_data. Potential attributes: 'application_fee_amount' for charging vendors
	 * @param array $vendor. Potential array keys: 'stripe_account' for the vendor account
	 * @return $result to report on the result of the creation
	 * @author Dory A.Azar
	 * @version 1.0
	 */
	public function createCheckout($amount = 1000, $currency = 'usd', $quantity = 1, $productData = array(), $successUrl = '', $cancelUrl = '', $customerEmail = null, $locale = null, $paymentType = ['card'], $payment_intent_data = array(), $vendor = array())
	{
		$result = array('action_success' => false, 'error' => 'Transaction could not be completed');
		
		try {
			
			// Initial parameters area
			$parameters = [
  				'payment_method_types' => $paymentType,
  				'line_items' => [[
    				'price_data' => [
      					'currency' => $currency,
      					'product_data' => $productData,
      					'unit_amount' => $amount,
    				],
    				'quantity' => $quantity,
  				]],
				'customer_email' => $customerEmail,
				'locale' => $locale,
  				'mode' => 'payment'
			]; 
			
			// If success URL specified, then add to parameters
			if (isset($successUrl) && trim($successUrl) != '') { $parameters = array_merge($parameters, ['success_url' => $successUrl]); }
			
			// If cancel URL specified then add to parameters
			if (isset($cancelUrl) && trim($cancelUrl) != '') { $parameters = array_merge($parameters, ['cancel_url' => $cancelUrl]); }
			
			$parameters = array_merge($parameters, $payment_intent_data);
			
			$checkout = \Stripe\Checkout\Session::create($parameters, $vendor);
			
			$result = $checkout? array('action_success' => true, 'data' => $checkout) : $result;
			
		} catch(Exception $e) {
			$result['error'] = $e->getMessage();
		}
		return $result;
		
	}
	
	/* Create Vendor Account in Stripe
	 * This allows vendors to receive direct payouts
	 * An account needs to be created in order to onboard the vendor
	 * @param string $email. Default is 'null'
	 * @param string $type. Default is 'express'
	 * @param string $country. Country code. Default 'US'
	 * @param array $capabilities. Defines the capabilities requested for the account. Defaut is card_payment: false and transfer: true
	 * @param array $accountInformation. Default is empty. Stripe Account additional fields
	 */
	
	public function createAccount($email = null, $type = 'express', $country = 'US', $capabilities = array(), $accountInformation = array())
	{
		$result = array('action_success' => false, 'error' => 'Account could not be created');
		
		$cardRequired = strtolower($country) != 'us';
		$email = $email? ['email' => $email] : [];
		
		try {
			$capabilities = !empty($capabilities)? $capabilities : array('card_payments' => [ 'requested' => $cardRequired,],
 								'transfers' => ['requested' => true,]);
			$input = array_merge($email, array('type' => $type, 'country' => $country, 'capabilities' => $capabilities), $accountInformation);
			$account = \Stripe\Account::create($input);
			$result = $account? array('action_success' => true, 'data' => $account) : $result;
		} catch(Exception $e) {
			$result['error'] = $e->getMessage();
		}
		
		return $result;

	}
	
	
	/* Create Account Link for onboarding vendor
	 * An account link needs to be created to onboard a vendor
	 * @param string $accountId. Required Id of the Stripe Account
	 * @param string $refreshUrl. Required. The URL that will regenerate the onboarding in case it expired
	 * @param string $returnUrl. Required. The URL that will be redirected when process completed (even if not finalized)
	 * @param string $type. Default is 'account_onboarding'. Other possible values is 'account_update'
	 */
	
	public function createAccountLink($accountId, $refreshUrl, $returnUrl, $type = 'account_onboarding')
	{
		$result = array('action_success' => false, 'error' => 'Account Link could not be created');
		try {
			$input = array('account' => $accountId, 'refresh_url' => $refreshUrl, 'return_url' => $returnUrl, 'type' => $type);
			$account_links = \Stripe\AccountLink::create($input);
			$result = $account_links? array('action_success' => true, 'data' => $account_links) : $result;
		} catch(Exception $e) {
			$result['error'] = $e->getMessage();
		}
		return $result;
		
	}
	
	/* Update a vendor account
	 * @param string $accountId. Required Id of the Stripe Account
	 * @param array $updates
	 */
	public function updateAccount($accountId, $updates)
	{
		$result = array('action_success' => false, 'error' => 'Account could not be updated');
		try {
			$account = \Stripe\Account::update($accountId, $updates);
			$result = $account? array('action_success' => true, 'data' => $account) : $result;
		} catch(Exception $e) {
			$result['error'] = $e->getMessage();
		}
		return $result;
	}
	
	/* Retrieve Vendor Account
	 * @param string $accountId. Required Id of the Stripe Account
	 */
	public function retrieve($accountId)
	{
		$result = array('action_success' => false, 'error' => 'Account could not be found');
		try {
			$account = \Stripe\Account::retrieve($accountId, []);
			$result = $account && !isset($account['error'])? array('action_success' => true, 'data' => $account) : $result;
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
