<?php
/**
 * Created by Ant-WEB S.L.
 * User: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 9/10/13
 * Time: 10:25
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Guzzle\Plugin\Token;


class MacToken implements  AcessToken
{
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function __toString()
    {
        $macString = sprintf('%s ', $this->getFormat());
        foreach ($this->config as $key => $value) {
            $macString .= sprintf('%s="%s",'.PHP_EOL, $key, $value);
        }
        return trim($macString, PHP_EOL.",");
    }
    public function getFormat()
    {
        return 'MAC';
    }
    public function setFormat($format)
    {
        $this->format = $format;
    }
}