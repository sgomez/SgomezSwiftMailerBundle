<?php

/*
 * This file is part of the SgomezDebugSwiftMailerBundle.
 *
 * (c) Sergio Gómez <decano@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sgomez\DebugSwiftMailerBundle\Behat;


use Behat\Behat\Context\TranslatableContext;
use Behat\Gherkin\Node\PyStringNode;
use Sgomez\DebugSwiftMailerBundle\Criteria;
use Sgomez\DebugSwiftMailerBundle\Criteria\CriteriaInterface;
use Sgomez\DebugSwiftMailerBundle\Utils\SwiftMailerManager;

class SwiftMailerContext implements TranslatableContext
{
    /**
     * @var SwiftMailerManager
     */
    protected $manager;

    /**
     * @var \Swift_Message
     */
    private $currentMessage;

    /**
     * @param SwiftMailerManager $manager
     */
    public function setConfiguration(SwiftMailerManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        $this->manager->purge();
    }

    /**
     * @When /^I open mail from "([^"]+)"$/
     */
    public function openMailFrom($value)
    {
        $message = $this->findMail(new Criteria\From($value));

        $this->currentMessage = $message;
    }

    /**
     * @When /^I open mail with subject "([^"]+)"$/
     */
    public function openMailSubject($value)
    {
        $message = $this->findMail(new Criteria\Subject($value));

        $this->currentMessage = $message;
    }

    /**
     * @When /^I open mail to "([^"]+)"$/
     */
    public function openMailTo($value)
    {
        $message = $this->findMail(new Criteria\To($value));

        $this->currentMessage = $message;
    }

    /**
     * @When /^I open mail with blind carbon copy to "([^"]+)"$/
     */
    public function openMailBcc($value)
    {
        $message = $this->findMail(new Criteria\Bcc($value));

        $this->currentMessage = $message;
    }

    /**
     * @When /^I open mail containing "([^"]+)"$/
     */
    public function openMailContaining($value)
    {
        $message = $this->findMail(new Criteria\Contains($value));

        $this->currentMessage = $message;
    }

    /**
     * @Then /^I should see mail from "([^"]+)"$/
     */
    public function seeMailFrom($value)
    {
        $message = $this->findMail(new Criteria\From($value));
    }

    /**
     * @Then /^I should see mail with subject "([^"]+)"$/
     */
    public function seeMailSubject($value)
    {
        $message = $this->findMail(new Criteria\Subject($value));
    }

    /**
     * @Then /^I should see mail to "([^"]+)"$/
     */
    public function seeMailTo($value)
    {
        $message = $this->findMail(new Criteria\To($value));
    }

    /**
     * @Then /^I should see mail with blind carbon copy to "([^"]+)"$/
     */
    public function seeMailBcc($value)
    {
        $message = $this->findMail(new Criteria\Bcc($value));
    }

    /**
     * @Then /^I should see mail containing "([^"]+)"$/
     */
    public function seeMailContaining($value)
    {
        $message = $this->findMail(new Criteria\Contains($value));
    }

    /**
     * @Then /^I should see "([^"]+)" in mail$/
     */
    public function seeInMail($text)
    {
        $message = $this->getCurrentMessage();

        $body = trim(strip_tags($message->getBody()));

        \PHPUnit_Framework_Assert::assertContains($text, $body);
    }

    /**
     * @Then /^the mail should contain:$/
     */
    public function theMailShouldContain(PyStringNode $text)
    {
        $message = $this->getCurrentMessage();

        $body = trim(strip_tags($message->getBody()));

        \PHPUnit_Framework_Assert::assertEquals($text->getRaw(), $body);
    }

    /**
     * @Then /^(?P<count>\d+) mails? should be sent$/
     */
    public function verifyMailsSent($count)
    {
        $actual = $this->manager->getMessageCount();

        \PHPUnit_Framework_Assert::assertEquals($count, $actual);
    }


    /**
     * @return \Swift_Message
     */
    private function findMail(CriteriaInterface $criteria)
    {
        $message = $this->manager->searchOne([$criteria]);

        if (null === $message) {
            throw new \InvalidArgumentException(sprintf('Unable to find a message with criteria "%s".', json_encode($criteria)));
        }

        return $message;
    }

    /**
     * Returns list of definition translation resources paths.
     *
     * @return array
     */
    public static function getTranslationResources()
    {
        return glob(__DIR__.'/../i18n/*.xliff');
    }

    /**
     * @return \Swift_Message
     */
    private function getCurrentMessage()
    {
        if (null === $this->currentMessage) {
            throw new \RuntimeException('No message selected');
        }

        return $this->currentMessage;
    }
}
