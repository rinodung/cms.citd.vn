<?php declare(strict_types=1);
if (!defined('MW_PATH')) {
    exit('No direct script access allowed');
}

/**
 * UpdateWorkerFor_1_3_7_3
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.7.3
 */

class UpdateWorkerFor_1_3_7_3 extends UpdateWorkerAbstract
{
    public function run()
    {
        // run the sql from file
        $this->runQueriesFromSqlFile('1.3.7.3');

        // populate the blacklist regexes
        $criteria = new CDbCriteria();
        $criteria->compare('email', '*', true);
        $criteria->select = 'email';
        $emails  = EmailBlacklist::model()->findAll($criteria);
        $regexes = [];

        foreach ($emails as $email) {
            $regexes[] = sprintf('/%s$/i', preg_quote(str_replace('*@', '', $email->email), '/'));
        }

        $regexes  = implode("\n", $regexes);
        $_regexes = options()->get('system.email_blacklist.regular_expressions');
        if (!empty($_regexes)) {
            $regexes = $_regexes . "\n" . $regexes;
        }
        if (!empty($regexes)) {
            options()->set('system.email_blacklist.regular_expressions', $regexes);
        }
    }
}
