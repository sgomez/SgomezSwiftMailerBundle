<?php

/*
 * This file is part of the SgomezDebugSwiftMailerBundle.
 *
 * (c) Sergio Gómez <decano@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sgomez\DebugSwiftMailerBundle\Utils;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class SwiftMailerManager
{
    private $spoolDir;

    /**
     * Util constructor.
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->spoolDir = $kernel->getContainer()->getParameter('swiftmailer.spool.default.file.path');
    }

    /**
     * Purge spool
     */
    public function purge()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->spoolDir);
    }

    /**
     * @return int
     */
    public function getMessageCount()
    {
        return $this->getSpool()->count();
    }

    public function searchOne(array $criterias)
    {
        $results = $this->search($criterias, 1);

        if (count($results) !== 1) {
            return null;
        }

        return $results[0];
    }

    public function search(array $criterias, $limit = -1)
    {
        $foundMessages = [];

        foreach($this->getSpool() as $file) {
            /** @var \Swift_Message $message */
            $message = unserialize(file_get_contents($file));

            if ($limit !== -1 && count($foundMessages) >= $limit) {
                break;
            }

            foreach($criterias as $criteria) {
                if ($criteria->match($message)) {
                    $foundMessages[] = $message;
                    break;
                }
            }
        }

        return $foundMessages;
    }

    /**
     * @return Finder
     */
    protected function getSpool()
    {
        $filesystem = new Filesystem();
        $finder = new Finder();

        if ($filesystem->exists($this->spoolDir)) {
            $spool = $finder
                ->ignoreDotFiles(true)
                ->in($this->spoolDir)
                ->files();

            return $spool;
        }

        throw new \RuntimeException("Spool not found or empty: " . $this->spoolDir);
    }
}
