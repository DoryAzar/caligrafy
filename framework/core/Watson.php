<?php

/**
 * Watson.php is the file that controls all the communication with the Watson Assistant
 * @copyright 2019
 * @author Dory A.Azar
 * @version 1.0
 */

/**
 * The Watson class handles all exchanges with the Watson Assistant
 * @author Dory A.Azar
 * @version 1.0
 */

namespace Caligrafy;
use \Exception as Exception;


class Watson {

    
    /**
	* @var string the Watson API URI
	* @property Watson API URI
	*/
	private $_watson_url = 'https://gateway.watsonplatform.net/assistant/api/v2/assistants/';
    
    
    /**
	* @var string the Watson api_key
	* @property string the Watson api_key
	*/
	private $_api_key;
    
    /**
	* @var string the Watson assistant id
	* @property string the Watson assistant id
	*/
	private $_watson_assistant_id;    
    
    
    /**
	* @var string the Watson active session
	* @property Watson active session
	*/
	private $_watson_session;
    
    /**
	* @var string the Watson api version
	* @property the Watson api version
	*/
	private $_watson_api_version;
    
    


	/**
	 * Constructs the Watson Controller to initiate the Watson exchange
	 * @param string $watson_assistant_id takes the Watson ID
	 * @author Dory A.Azar
	 * @version 1.0
	 */

	public function __construct($watson_assistant_id)
	{
		$this->_api_key = WATSON_API_KEY;
        $this->_watson_assistant_id = $watson_assistant_id;
        $this->_watson_api_version = "?version=2019-02-28";
        
        $url = $this->_watson_url.$this->_watson_assistant_id."/sessions".$this->_watson_api_version;
        
        $result = httpRequest($url, 'POST', array(), array(), 'apikey', $this->_api_key);
        
        if (isset($result['session_id'])) {
            $this->_watson_session = $result['session_id'];
            return $this;
        } else {
            throw new Exception('The session could not be initialized');
        }
	}
    
	/**
	 * Send Input to Watson
	 * @param $watson_assistant_id takes the Watson ID
     * @param array $input to send to Watson
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function communicate($input)
    {
        $url = $this->_watson_url.$this->_watson_assistant_id."/sessions/".$this->_watson_session."/message".$this->_watson_api_version;
        $result = httpRequest($url, 'POST', $input, array('Content-Type:application/json'), 'apikey', $this->_api_key);
        return $result;
    }
    
    
    
  	/**
	 * Get Session ID
	 * @return the current active session ID
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function getSessionId()
    {
        return $this->_watson_session;
    }
    
    /**
	 * Close the Watson Session
	 * @return boolean true if session closed successfully
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function closeSession()
    {
        $url = $this->_watson_url.$this->_watson_assistant_id."/sessions/".$this->_watson_session.$this->_watson_api_version;
        $result = httpRequest($url, 'DELETE', array(), array(), 'apikey', $this->_api_key);
        return $result;
        
    }

}