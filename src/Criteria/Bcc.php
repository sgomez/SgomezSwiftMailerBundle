<?php

/*
 * This file is part of the SgomezDebugSwiftMailerBundle.
 *
 * (c) Sergio Gómez <decano@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sgomez\DebugSwiftMailerBundle\Criteria;


class Bcc extends AbstractCriteria
{
    /**
     * {@inheritdoc}
     */
    public function match(\Swift_Message $message)
    {
        $recipients = $message->getBcc();

        return in_array($this->subject, $recipients);
    }
}
