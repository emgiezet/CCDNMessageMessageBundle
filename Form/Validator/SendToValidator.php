<?php

/*
 * This file is part of the CCDNMessage MessageBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNMessage\MessageBundle\Form\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.1
 *
 * @see http://symfony.com/doc/current/cookbook/validation/custom_constraint.html
 */
class SendToValidator extends ConstraintValidator
{

    /**
     *
     * @access protected
     */
    protected $doctrine;

    /**
     *
     * @access protected
     */
    protected $container;

    /**
     *
     * @access public
     * @param $doctrine, $container
     */
    public function __construct($doctrine, $container)
    {

        $this->doctrine = $doctrine;
        $this->container = $container;
    }

    /**
     *
     * @access public
     * @param string $value, Constraint $constraint
     * @return bool
     */
    public function isValid($value, Constraint $constraint)
    {

        if (strlen($value) < 1) {
            $constraint->addNotFoundUsernames(array());
        }

        // convert either one user or mulitple users who
        // the mail will be sent to into user entities.
        if ($recipients = preg_split('/((,)|(\s))/', $value, PREG_OFFSET_CAPTURE)) {
            foreach ($recipients as $key => $recipient) {
                // Sanitize the input by removing non-alpha-numeric chars.
                $recipients[$key] = preg_replace("/[^a-zA-Z0-9_]/", "", $recipients[$key]);

                if (! $recipient) {
                    unset($recipients[$key]);
                }
            }

            if (count($recipients) > 0) {
                $sendToUsers = $this->container->get('ccdn_user_user.repository.user')->findTheseUsersByUsername($recipients);
            } else {
                $constraint->addNotFoundUsernames(array());
            }
        } else {
            $recipients = array($value);

            $sendToUsers = $this->container->get('ccdn_user_user.repository.user')->findByUsername($recipients);
        }

        $notFound = array();
        foreach ($recipients as $recipientKey => $recipient) {
            $recipientsFound = 0;

            foreach ($sendToUsers as $sendToUserkey => $sendToUser) {
                if ($sendToUser->getUsername() == $recipient) {
                    $recipientsFound++;
                }
            }

            if ($recipientsFound == 0) {
                $notFound[] = $recipient;
            }
        }

        if (count($notFound) > 0) {
            $constraint->addNotFoundUsernames($notFound);

            $this->setMessage($constraint->message);

            return false;
        } else {
            return true;
        }
    }

}
