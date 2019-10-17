<?php

use Caligrafy\Controller;
use Caligrafy\Watson;

class WatsonController extends Controller {
    
    /* Connect to Watson
     * @appId: Watson App Id needs to be provided in the URL
     * @output: Successful connection will be stored in the session
     */
    public function connect()
    {
        $appId = $this->request->fetch->appId;
        $myWatson = new Watson($appId);
        session('watson', serialize($myWatson));
        return api(true);
    }
    
    
    /* Send a Message to Watson
     * @text: The input text that the user sends to the bot
     * @variables: array of variables to store in the context of the conversation
     * @output: Watson response and context are returned
     */
    public function communicate()
    {
        $result = array('action_success' => false, 'error' => 'Communication could not be established');
        $myWatson = session('watson');
        if ($myWatson) {
            $myWatson = unserialize($myWatson);
        }
        
        $appId = $myWatson? $myWatson->getSessionId() : null;
        if ($appId) {
            $parameters = $this->request->parameters;
            $textMessage = isset($parameters['text'])? $parameters['text'] : '';
            $variables = !empty($parameters['variables'])? $parameters['variables'] : null;
            if($textMessage != '') {
                $result = array('action_success' => true);
                $result['response'] = $myWatson->communicate($textMessage, $variables)->getResponse();
                $result['context'] = $myWatson->getSkillVariables();
            }
            
        }
        return api($result);
    }
    
    /* Disconnect from Watson
     * @output: The active Watson session is destroyed
     */
    public function end() {
        $myWatson = session('watson')?? null;
        if ($myWatson) {
            $myWatson = unserialize($myWatson);
        }
        $appId = $myWatson? $myWatson->getSessionId() : null;
        if ($appId) {
            $myWatson->closeSession();
            clearFromSession('watson');
        }
    }
    

}