<?php

/*
 * This file is part of the SgomezDebugSwiftMailerBundle.
 *
 * (c) Sergio Gómez <decano@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sgomez\DebugSwiftMailerBundle\Behat\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer as BaseContextInitializer;
use Sgomez\DebugSwiftMailerBundle\Behat\SwiftMailerContext;
use Sgomez\DebugSwiftMailerBundle\Utils\SwiftMailerManager;

class ContextInitializer implements BaseContextInitializer
{
    /**
     * @var SwiftMailerManager
     */
    private $manager;

    /**
     * ContextInitializer constructor.
     */
    public function __construct(SwiftMailerManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Initializes provided context.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof SwiftMailerContext) {
            return;
        }

        $context->setConfiguration($this->manager);
    }
}
