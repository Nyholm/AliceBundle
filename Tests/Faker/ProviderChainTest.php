<?php

namespace Hautelook\AliceBundle\Tests\Faker;

use Faker\Generator;
use Hautelook\AliceBundle\Faker\Provider\ProviderChain;
use Hautelook\AliceBundle\Tests\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * @coversDefaultClass Hautelook\AliceBundle\Faker\Provider\ProviderChain
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class ProviderChainTest extends KernelTestCase
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var ProviderChain
     */
    private $providerChain;

    protected function setUp()
    {
        self::bootKernel();
        $this->application = new Application(self::$kernel);
        $this->providerChain = $this->application->getKernel()->getContainer()->get('hautelook_alice.faker.provider_chain');
    }

    /**
     * @cover ::__construct
     * @cover ::getProviders
     */
    public function testConstructor()
    {
        $providerChain = new ProviderChain([]);
        $this->assertEquals([], $providerChain->getProviders());

        $providers = ['foo', 'bar'];
        $providerChain = new ProviderChain($providers);
        $this->assertEquals($providers, $providerChain->getProviders());
    }

    /**
     * @coversNothing
     */
    public function testGenerator()
    {
        $this->assertEquals(count((new Generator())->getProviders()) + 1, count($this->providerChain->getProviders()));
        $this->assertEquals(
            'Hautelook\AliceBundle\Tests\SymfonyApp\TestBundle\DataFixtures\Faker\Provider\FooProvider',
            get_class($this->providerChain->getProviders()[0]),
            'Expected custom Faker provider to be registered.'
        );
    }
}
