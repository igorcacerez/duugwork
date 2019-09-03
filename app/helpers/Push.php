<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 06/05/2019
 * Time: 16:42
 */

namespace Helper;


class Push
{
    // Variaveis
    private $pusher;

    public function __construct()
    {
        // Get config variables
        $app_id     = pusher_app_id;
        $app_key    = pusher_app_key;
        $app_secret = pusher_app_secret;
        $options    = $this->options();

        // Create Pusher object only if we don't already have one
        if (!isset($this->pusher))
        {
            // Create new Pusher object
            $this->pusher = new \Helper\Pusher\Pusher($app_key, $app_secret, $app_id, $options);
        }

    } // END >> Fun::__construct()


    /**
     * Get Pusher object
     *
     * @return  Object
     */
    public function get_pusher()
    {
        return $this->pusher;
    } // END >> get_pusher()


    /**
     * Build optional options array
     *
     * @return  array
     */
    private function options()
    {
        $options['pusher_scheme']    = pusher_scheme;
        $options['pusher_host']      = pusher_host;
        $options['pusher_port']      = pusher_port;
        $options['pusher_timeout']   = pusher_timeout;
        $options['pusher_encrypted'] = pusher_encrypted;

        $options = array_filter($options);

        return $options;

    } // END >> Fun::options()



} // END >> Class::Push