<?php

    /**
     * Foursquare callback
     */

    namespace IdnoPlugins\Foursquare\Pages {

        /**
         * Default class to serve the Facebook callback
         */
        class Callback extends \Idno\Common\Page
        {

            function get()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($foursquare = \Idno\Core\site()->plugins()->get('Foursquare')) {
                    $fsObj = $foursquare->connect();
                    $token = $fsObj->getAccessToken($this->getInput('code'), \Idno\Core\site()->config()->url . 'foursquare/callback');
                    $fsObj->setAccessToken($token->access_token);
                    $user = \Idno\Core\site()->session()->currentUser();
                    $user->foursquare = array('access_token' => $token->access_token);
                    $user->save();
                    \Idno\Core\site()->session()->addMessage('Your Foursquare account was connected.');
                }
                if (!empty($_SESSION['onboarding_passthrough'])) {
                    unset($_SESSION['onboarding_passthrough']);
                    $this->forward(\Idno\Core\site()->config()->getURL() . 'begin/connect-forwarder');
                }
                $this->forward(\Idno\Core\site()->config()->getURL() . 'account/foursquare/');
            }

        }

    }