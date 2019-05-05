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

		\Stripe\Stripe::setApiKey($this->_private_key);
        return $this;

	}

	/**
	 * Creates a new payment transaction
	 * @param Integer $amount defines the amount of the transaction in cents
	 * @param string $currency defines the currecy of the transaction
	 * @param Object $card takes the card object submitted
	 * @param string $metadata defines the metadata information that can be passed on to the transaction
	 * @param string $description defines the description that can be passed on to the transaction
	 * @param boolean $save defines whether the transaction should be saved or not for a particular customer
	 * @return $result to report on the result of the creation
	 * @author Dory A.Azar
	 * @version 1.0
	 */

	public function createTransaction($amount, $currency, $card, $metadata = null, $description = '', $save = false) {
        
        $result = array('action_success' => false, 'error' => 'Transaction could not be completed');
        // Create a charge: this will charge the user's card
		try {
          
          if ($card) {
              $token = $this->createStripeToken($card);
              if ($token && $token->id) {
                $outcome = \Stripe\Charge::create(array(
                "amount" => $amount, // Amount in cents
                "currency" => $currency,
                "source" => $token,
                "metadata" => $metadata,
                "description" => $description
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
