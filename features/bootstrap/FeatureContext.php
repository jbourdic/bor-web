<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends Behat\MinkExtension\Context\MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given /^I am logged in as "([^"]*)"$/
     */
    public function iAmLoggedInAs($username)
    {
        $testUsers = [
            'user'   =>['user@user.com','user'],
            'expert' =>['expert@expert.com','expert'],
            'admin'  =>['admin@blabla.business','admin'],
        ];

        $this->visit('/login');
        $this->fillField('_username', $testUsers[$username][0]);
        $this->fillField('_password', $testUsers[$username][1]);
        $this->pressButton('Connexion');

        return $this->assertPageAddress('/mon-profil') && $this->assertPageNotContainsText('Connexion');
    }

    /**
     * @When I fill in :field with a random email
     */
    public function iFillInWithARandomEmail($field)
    {
        $this->fillField($field, uniqid().'@test.com');
    }
}
