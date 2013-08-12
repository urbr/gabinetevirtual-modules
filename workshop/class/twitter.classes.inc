<?php

/**
 * @file
 * Twitter Class
 * 
 * @package          Gabinete Virtual
 * @copyright        Copyright (c) 2013, Urucum Brasil
 * 
 * @author           Fellipe
 * @description
 *   Classe responsável por acessar a API do Twitter com
 *   base no protocolo OAuth.
 *
 *  Se ocorrer algum erro na função http_build_query, adicionar 
 *    a constante PHP_QUERY_RFC3986 como quarto parâmetro.
 *
 */

class Twitter
{
    const TWITTER_API_URL = "https://api.twitter.com/oauth/";
    const TWITTER_API_URL_UPDATE = "https://api.twitter.com/1/statuses/update.json";
    const OAUTH_SIGNATURE_METHOD = "HMAC-SHA1";
    const OAUTH_VERSION = "1.0";
    
    private $params;
    private $tokens;

    function __construct($consumerKey = null, $consumerSecret = null)
    {
        if($consumerKey && $consumerSecret)
        {
            $this->params['consumer_key']    = $consumerKey;
            $this->params['consumer_secret'] = $consumerSecret;
            $this->params['nonce']           = md5(microtime() . mt_rand());
            $this->params['timestamp']       = mktime();
            $this->params['callback']        = $_SERVER['SERVER_NAME'] . "/socialnetworks/twitter";
            $this->tokens                    = new stdClass;
        } 
        else 
        {
            throw new Exception("Informe o Consumer Key e Consumer Secret do seu aplicativo!");
        }
    }

    private function createBaseString($args = array(), $urlSignature = "request_token")
    {
        $signature = "POST&". rawurlencode(self::TWITTER_API_URL. $urlSignature).'&';

        $signature .= $this->mountArgs($args, 'signature');

        $signature .= rawurlencode("oauth_callback=")
                     .rawurlencode(rawurlencode($this->params['callback']))
                     .rawurlencode("&oauth_consumer_key=" . ($this->params['consumer_key']))
                     .rawurlencode("&oauth_nonce=" . rawurlencode($this->params['nonce']))
                     .rawurlencode("&oauth_signature_method=" . self::OAUTH_SIGNATURE_METHOD)
                     .rawurlencode("&oauth_timestamp=" . $this->params['timestamp'])
                     .rawurlencode("&oauth_version=" . self::OAUTH_VERSION);

        return $signature;
    }

    private function createHeader($args = array(), $publishMessage = FALSE)
    {
        $header = "Authorization: OAuth ";

        $header .= ($publishMessage) ? 'realm="/statuses/update.json",' 
            : 'oauth_callback="'.rawurlencode($this->params['callback']);

        $header .= $this->mountArgs($args);

        $header .= '", oauth_nonce="' . rawurlencode($this->params['nonce'])
		          .'", oauth_consumer_key="' . rawurlencode($this->params['consumer_key'])  
                  .'", oauth_signature_method="' . self::OAUTH_SIGNATURE_METHOD  
                  .'", oauth_timestamp="' . $this->params['timestamp']  
                  .'", oauth_version="' . self::OAUTH_VERSION  
                  .'", oauth_signature="' . rawurlencode($args['oauth_signature']).'"';

        if(isset($args['status']))
            return array($header, "Expect:", "Content-Length: " . strlen($args['status']));

        return array($header, "Expect:");
    }

    private function createAuthToken()
    {
        $oauth_signature = base64_encode(
            hash_hmac(
                'sha1', 
                $this->createBaseString(), 
                $this->params['consumer_secret'].'&', 
                true
            )
        );
        
	    $args['oauth_signature'] = $oauth_signature;

        $header = $this->createHeader($args);

        $response = $this->curlPost(self::TWITTER_API_URL . "request_token", $header);
        
	if(!empty($response[1]))
        {
            $this->tokens->oauth_token        = $response[0];
            $this->tokens->oauth_token_secret = $response[1];
        } 
        else 
        {
            throw new Exception($response[0]);
        }

        return $this->tokens;
    }

    private function curlPost($url = '', $header = '', $postFields = '')
    {
        $options = array(
            CURLOPT_HTTPHEADER => $header,  
            CURLOPT_HEADER => FALSE,
            CURLOPT_URL => $url,  
            CURLOPT_POST => TRUE,  
            CURLOPT_POSTFIELDS => $postFields,  
            CURLOPT_RETURNTRANSFER => TRUE,  
            CURLOPT_SSL_VERIFYPEER => FALSE
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        
        return explode('&', curl_exec($ch));
    }

    private function mountArgs($args = array(), $type = 'header')
    {
        $params = '';
        switch ($type)
        {
            case 'header':
                foreach ($args as $key => $value)
                {
                    $params .= ($key != 'status') ? '", '.$key.'="'. rawurlencode($value) : '';
                }
                break;

            case 'signature':
                foreach ($args as $key => $value)
                {
                    $params .= rawurlencode($key."=");
                    $params .= ($key != 'status') ? rawurlencode($value) : rawurldecode(urlencode($value));
                }
                break;

            default:
                break;
        }
        return $params;
    }

    private function oauthcmp($a, $b)
    {
        return strcmp($a[0], $b[0])
                ? strcmp(rawurlencode($a[0]), rawurlencode($b[0]))
                : strcmp(rawurlencode($a[1]), rawurlencode($b[1]));
    }

    private function oauthsort($a)
    {
        $b = array_map(null, array_keys($a), $a);
        usort($b, array($this,'oauthcmp'));
        $c = array();
        foreach ($b as $v) {
            $c[$v[0]] = $v[1];
        }
        return $c;
    }

    public function getUrlAuthTwitter()
    {
	   return self::TWITTER_API_URL . "authenticate?" . $this->createAuthToken()->oauth_token;
    }

    public function publishMessageTwitter($message = "")
    {
        $parameters = array('status' => $message);

        $oauth_parameters = array(
            'oauth_version' => '1.0',
            'oauth_nonce' => $this->params['nonce'],
            'oauth_timestamp' => $this->params['timestamp'],
            'oauth_consumer_key' => $this->params['consumer_key'],
            'oauth_token' => variable_get('workshop_twitter_oauth_token'),
            'oauth_signature_method' => self::OAUTH_SIGNATURE_METHOD,
        );
         
        $base_string = implode(
            '&', 
            array(
                rawurlencode('POST'),
                rawurlencode(self::TWITTER_API_URL_UPDATE),
                rawurlencode(
                    http_build_query($this->oauthsort(
                            array_merge($oauth_parameters, $parameters)
                    ), '', '&')
                )
            )
        );

        $key = implode(
            '&', 
            array(
                rawurlencode($this->params['consumer_secret']), 
                rawurlencode(variable_get('workshop_twitter_oauth_token_secret'))
            )
        );

        $oauth_parameters['oauth_signature'] = base64_encode(
            hash_hmac('sha1', $base_string, $key, true)
        );
        
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => array(
                    'Authorization: OAuth ' . http_build_query($oauth_parameters, '', ','),
                ),
                'content' => http_build_query($parameters)
            )
        );

        return @file_get_contents(
            self::TWITTER_API_URL_UPDATE, 
            FALSE, 
            stream_context_create($options)
        );
    }

    public function oauthVerifier($oauth_token, $verifier)
    {
        $args['oauth_token']    = $oauth_token;
        $args['oauth_verifier'] = $verifier;

        $oauth_signature = base64_encode(
            hash_hmac('sha1', 
                $this->createBaseString(
                    $args, 
                    "access_token"
                ), 
                rawurlencode($this->params['consumer_secret']).'&', 
                true
            )
        );

        $args['oauth_signature'] = $oauth_signature;

        $header = $this->createHeader($args);

        $response = $this->curlPost(self::TWITTER_API_URL . "access_token", $header);

        if(!empty($response[1]))
        {
            variable_set('workshop_twitter_oauth_token', 
                str_replace('oauth_token=', '', $response[0])
            );
            variable_set('workshop_twitter_oauth_token_secret', 
                str_replace('oauth_token_secret=', '', $response[1])
            );
        } 
        else
        {
            throw new Exception($response[0]);
        }

        return TRUE;
    }
}
?>
