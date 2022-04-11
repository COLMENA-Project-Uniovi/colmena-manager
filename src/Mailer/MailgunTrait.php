<?php
namespace App\Mailer;

use Cake\Core\App;
use Cake\Mailer\Exception\MissingMailerException;
use Mailgun\Mailer\MailgunEmail;

/**
 * Provides functionality for loading mailer classes
 * onto properties of the host object with MailgunEmail email class.
 *
 * Example users of this trait are Cake\Controller\Controller and
 * Cake\Console\Shell.
 */
trait MailgunTrait
{

    /**
     * Returns a mailer instance.
     *
     * @param string $name Mailer's name.
     * @return \Cake\Mailer\Mailer
     * @throws \Cake\Mailer\Exception\MissingMailerException if undefined mailer class.
     */
    protected function getMailer($name)
    {
        $email = new MailgunEmail();

        $className = App::className($name, 'Mailer', 'Mailer');

        if (empty($className)) {
            throw new MissingMailerException(compact('name'));
        }

        return new $className((array) $email);
    }
}
