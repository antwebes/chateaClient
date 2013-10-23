<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xabier
 * Date: 10/10/13
 * Time: 23:31
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\ChateaClient\Service\Client;

use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;

class ChateaOAuth2Client extends Client
{
    public static function factory($config = array())
    {
        // Provide a hash of default client configuration options
        $default = array(
            'base_url'=>'{scheme}://{subdomain}.chateagratis.local',
            'Accept'=>'application/json',
            'environment'=>'prod',
            'scheme' => 'https',
            'version'=>'',
            'subdomain'=>'api',
            'service-description-name' => Client::NAME_SERVICE_AUTH
        );
        $required = array(
            'base_url',
            'scheme',
            'subdomain',
            'Accept',
            'environment'
        );

        // Merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);


        if($config['environment'] == 'dev' ){

            $config['base_url'] = $config['base_url'] . '/app_dev.php';
            $config['scheme'] = 'http';
            $config['ssl.certificate_authority'] = 'system';
            $config['curl.options'] = array(CURLOPT_SSL_VERIFYHOST=>false,CURLOPT_SSL_VERIFYPEER=>false);
        }


        // Create a new ChateaOAuth2 client
        $client = new self($config->get('base_url'),
            $config->get('scheme'),
            $config->get('subdomain'),
            $config
        );

        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));

        return $client;
    }
}