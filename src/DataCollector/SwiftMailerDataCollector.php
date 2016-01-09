<?php
/*
 * This file is part of the SgomezDebugSwiftMailerBundle and forked from
 * Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector
 *
 * (c) Sergio Gómez <decano@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sgomez\DebugSwiftMailerBundle\DataCollector;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class SwiftMailerDataCollector extends DataCollector
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * SwiftMailerDataCollector constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     * @param \Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'default_mailer' => null,
            'delivery_enabled' => true,
            'mailer' => [],
            'messageCount' => 0,
        ];

        // only collect when Swiftmailer has already been initialized
        if (class_exists(\Swift_Mailer::class)) {

            $this->data['delivery_enabled'] = $this->container->getParameter('swiftmailer.delivery.enabled');

            $mailers = $this->container->getParameter('swiftmailer.mailers');
            foreach ($mailers as $name => $mailer) {

                if ($this->container->getParameter(sprintf('swiftmailer.mailer.%s.spool.enabled', $name))) {
                    if  ($this->container->hasParameter(sprintf('swiftmailer.spool.%s.file.path', $name))) {
                        if ($this->container->getParameter('swiftmailer.default_mailer') == $name) {
                            $this->data['default_mailer'] = $name;
                        }

                        $path = $this->container->getParameter(sprintf('swiftmailer.spool.%s.file.path', $name));
                        $messages = $this->getSpooledMessages($path);

                        $this->data['mailer'][$name] = [
                            'path' => $path,
                            'messages' => $messages,
                            'messageCount' => count($messages),
                        ];

                        $this->data['messageCount'] += count($messages);
                    }
                }
            }
        }
    }

    public function getDeliveryEnabled()
    {
        return $this->data['delivery_enabled'];
    }


    /**
     * Returns the mailer names.
     *
     * @return array The mailer names.
     */
    public function getMailers()
    {
        return array_keys($this->data['mailer']);
    }

    /**
     * Returns the data collected of a mailer.
     *
     * @return array The data of the mailer.
     */

    public function getMailerData($name)
    {
        if (!isset($this->data['mailer'][$name])) {
            throw new \LogicException(sprintf("Missing %s data in %s", $name, get_class()));
        }

        return $this->data['mailer'][$name];
    }

    /**
     * Returns the message count of a mailer or the total.
     *
     * @return int The number of messages.
     */
    public function getMessageCount($name = null)
    {
        if (is_null($name)) {
            return $this->data['messageCount'];
        } elseif ($data = $this->getMailerData($name)) {
            return $data['messageCount'];
        }

        return null;
    }

    /**
     * Returns the messages of a mailer.
     *
     * @return array The messages.
     */
    public function getMessages($name = 'default')
    {
        if ($data = $this->getMailerData($name)) {
            return $data['messages'];
        }

        return array();
    }

    /**
     * Returns if the mailer is the default mailer.
     *
     * @return boolean
     */
    public function isDefaultMailer($name)
    {
        return $this->data['default_mailer'] == $name;
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'sgomez_swiftmailer';
    }

    /**
     * Returns the spooled messages inside a path
     *
     * @param $path
     * @return Finder
     */
    private function getSpooledMessages($path)
    {
        $messages = [];

        try {
            $finder = new Finder();
            $files = $finder
                ->in($path)
                ->name("*.message")
                ->files()
            ;
        } catch(\InvalidArgumentException $e) {
            $files = [];
        }

        foreach ($files as $file) {
            $messages[] = unserialize(file_get_contents($file));
        }

        return $messages;
    }
}