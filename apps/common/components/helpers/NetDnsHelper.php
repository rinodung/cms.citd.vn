<?php declare(strict_types=1);
if (!defined('MW_PATH')) {
    exit('No direct script access allowed');
}

/**
 * NetDnsHelper
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.3.1
 */

class NetDnsHelper
{
    /**
     * @param string $ipAddress
     * @param array $dnsBls
     *
     * @return bool|mixed
     */
    public static function isIpBlacklistedAtDnsbls(string $ipAddress, array $dnsBls = [])
    {
        static $checkdnsrrExists;
        static $execExists;
        static $digLookupExists;
        static $checked = [];

        if (isset($checked[$ipAddress])) {
            return $checked[$ipAddress];
        }

        $blackListed = false;

        if (!FilterVarHelper::ip($ipAddress)) {
            if (($_ipAddress = self::getHostByName($ipAddress)) === $ipAddress) {
                return $checked[$ipAddress] = $blackListed;
            }
            $ipAddress = $_ipAddress;
        }

        if ($checkdnsrrExists === null) {
            $checkdnsrrExists = CommonHelper::functionExists('checkdnsrr');
        }

        if ($execExists === null) {
            $execExists = CommonHelper::functionExists('exec');
        }

        if (!$checkdnsrrExists && !$execExists) {
            return $checked[$ipAddress] = $blackListed;
        }

        if (empty($dnsBls)) {
            return $checked[$ipAddress] = $blackListed;
        }

        shuffle($dnsBls);

        $ipBlocks  = explode('.', $ipAddress);
        $reverseIp = implode('.', array_reverse($ipBlocks));

        if ($execExists) {
            if ($digLookupExists !== false) {
                if ($digLookupExists === null) {
                    exec('command -v dig >/dev/null 2>&1', $lines, $status);
                    $digLookupExists = $status == 0;
                }
                if ($digLookupExists) {
                    foreach ($dnsBls as $host) {
                        exec(sprintf('dig %s +nocomments +noquestion +noauthority +noadditional +nostats', escapeshellarg($reverseIp . '.' . $host . '.')), $lines, $status);
                        foreach ($lines as $line) {
                            if (strpos($line, $reverseIp . '.' . $host) === 0) {
                                return $checked[$ipAddress] = $host;
                            }
                        }
                    }
                }
            }
        }

        if ($checkdnsrrExists && !$digLookupExists) {
            foreach ($dnsBls as $host) {
                if (@checkdnsrr($reverseIp . '.' . $host . '.', 'A')) {
                    return $checked[$ipAddress] = $host;
                }
            }
        }

        return $checked[$ipAddress] = $blackListed;
    }

    /**
     * @param string $hostname
     *
     * @return string
     */
    public static function getHostByName(string $hostname): string
    {
        static $gethostbynameExists;
        static $execExists;
        static $digExists;
        static $checked = [];

        if (isset($checked[$hostname])) {
            return $checked[$hostname];
        }

        if ($gethostbynameExists === null) {
            $gethostbynameExists = CommonHelper::functionExists('gethostbyname');
        }

        if ($execExists === null) {
            $execExists = CommonHelper::functionExists('exec');
        }

        if (!$gethostbynameExists && !$execExists) {
            return $checked[$hostname] = $hostname;
        }

        if ($execExists) {
            if ($digExists !== false) {
                if ($digExists === null) {
                    exec('command -v dig >/dev/null 2>&1', $lines, $status);
                    $digExists = $status == 0;
                }
                if ($digExists) {
                    exec(sprintf('dig +short %s', escapeshellarg($hostname)), $lines, $status);
                    foreach ($lines as $line) {
                        if (FilterVarHelper::ip($line)) {
                            return $checked[$hostname] = $line;
                        }
                    }
                }
            }
        }

        if ($gethostbynameExists && !$digExists) {
            if (($ip = @gethostbyname($hostname)) != $hostname) {
                return $checked[$hostname] = $ip;
            }
        }

        return $checked[$hostname] = $hostname;
    }

    /**
     * @param string $hostname
     *
     * @return array
     */
    public static function getHostMxRecords(string $hostname): array
    {
        if (empty($hostname)) {
            return [];
        }

        static $hosts = [];
        if (isset($hosts[$hostname])) {
            return $hosts[$hostname];
        }
        $hosts[$hostname] = [];

        static $getmxrr;
        if ($getmxrr === null) {
            $getmxrr = CommonHelper::functionExists('getmxrr');
        }

        if (!$getmxrr) {
            return $hosts[$hostname];
        }

        $_hosts = [];
        if (!getmxrr($hostname, $_hosts)) {
            return $hosts[$hostname];
        }

        $hosts[$hostname] = (array)$_hosts;
        unset($_hosts);

        foreach ($hosts[$hostname] as $index => $host) {
            if (empty($host)) {
                unset($hosts[$hostname][$index]);
                continue;
            }
            if ($host == '0.0.0.0') {
                unset($hosts[$hostname][$index]);
                continue;
            }
        }

        return $hosts[$hostname];
    }
}
