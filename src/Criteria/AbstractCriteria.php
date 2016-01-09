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


abstract class AbstractCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    protected $subject;

    /**
     * AbstractCriteria constructor.
     */
    public function __construct($subject)
    {
        $this->subject = $subject;
    }
}
