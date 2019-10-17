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
	* @var array the Conversation context
	* @property the Conversation context
	*/
	private $_watson_context;
    
    /**
	* @var array the Conversation
	* @property the Conversation
	*/
	private $_watson_conversation_log;
    
    

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
        $this->_watson_context = array();
        $this->_watson_conversation_log = array();
        
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
     * @param string $messageText to send to Watson
     * @param array $skillVariables
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function communicate($messageText, $skillVariables = null)
    {
        $input = array('input' => array('text' => $messageText,
                                        'options' => array('return_context' => true)
                        ));
        if (!empty($skillVariables)) {
            $input['context']['skills']['main skill']['user_defined'] = $skillVariables;
        }
        
        $url = $this->_watson_url.$this->_watson_assistant_id."/sessions/".$this->_watson_session."/message".$this->_watson_api_version;
        $result = httpRequest($url, 'POST', $input, array('Content-Type:application/json'), 'apikey', $this->_api_key);
        
        // If there is an output then log the conversation
        if (!empty($result) && isset($result['output']['generic'][0])) {    
            $this->_watson_conversation_log[sizeof($this->_watson_conversation_log) + 1] =  array('turn_count' => sizeof($this->_watson_conversation_log) + 1, 'user' => $messageText, 'response' => $result['output']['generic']);
        }
        
        // If there is a context then update the object context
        if (!empty($result) && isset($result['context'])) {
            $this->_watson_context = $result['context'];
        } 
        return $this;
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
	 * Get Context
	 * @return the current Context
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function getContext()
    {
        return $this->_watson_context;
    }
    
    
    
    /**
	 * Get Context Variable
	 * @return the Context variable
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function getContextVariable($variable)
    {
        return isset($this->_watson_context['skills']['main skill']['user_defined'][$variable])? $this->_watson_context['skills']['main skill']['user_defined'][$variable] : null;
    }
                                                    
                                                    
                                                    
    /**
	 * Get Context
	 * @return the current Conversation
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function getConversation()
    {
        return $this->_watson_conversation_log;
    }
    
    
    /**
	 * Get Context
	 * @return the last exchange
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function getLastExchange()
    {
        return array_pop($this->_watson_conversation_log);
    }
    
    
    /**
	 * Get Last Response
	 * @return the last response
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function getResponse()
    {
        $response = array_pop($this->_watson_conversation_log);
        return isset($response['response'])? $response['response'] : null;
    }
    
    
    /**
	 * Get conversation exchange at turn count
     * @param int $turnCount defines the turn count of the conversation
	 * @return array the conversation exchange at turn count
	 * @author Dory A.Azar
	 * @version 1.0
	 */
    
    public function getExchangeAt($turnCount)
    {
        return isset($this->_watson_conversation_log[$turnCount])? $this->_watson_conversation_log[$turnCount] : array();
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