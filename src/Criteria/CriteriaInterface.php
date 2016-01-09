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


interface CriteriaInterface
{
    /**
     * Check if the message match the criteria
     *
     * @param \Swift_Message $message
     * @return boolean
     */
    public function match(\Swift_Message $message);
}
