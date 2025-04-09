<?php

namespace Silecust\WebShop\Tests\Controller\MasterData\Customer\Image;

use Silecust\WebShop\Service\MasterData\Customer\Image\Provider\CustomerDirectoryImagePathProvider;
use Silecust\WebShop\Tests\Fixtures\CustomerFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class CustomerImageControllerTest extends WebTestCase
{
    use HasBrowser,CustomerFixture,Factories;
    protected function setUp(): void
    {
        $this->browser()->visit('/logout');
        $this->createCustomerFixtures();
    }
    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

        $root = self::getContainer()->getParameter('kernel.project_dir');
        $path =  $root.self::getContainer()->getParameter('file_storage_path');

        shell_exec("rm -rf ".$path);
    }
  public function testCreate()
    {

        self::bootKernel();
        $provider = self::getContainer()->get(CustomerDirectoryImagePathProvider::class);

        $id = $this->customer->getId();

        $uri = "/admin/customer/$id/image/create";

        $fileName = 'grocery_1920.jpg';
        $uploadedFile = new UploadedFile(
            __DIR__ . '/' . $fileName, $fileName
        );
        $visit = $this->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
            })
            ->visit($uri);

        $form = $visit->crawler()->selectButton('Save')->form();

        $name = $form->get('customer_image_create_form[fileDTO][name]')->getValue();


        $visit->fillField('customer_image_create_form[fileDTO][yourFileName]', 'MyFile.jpg')
            ->fillField(
                'customer_image_create_form[fileDTO][uploadedFile]', $uploadedFile
            )->click('Save')->assertSuccessful();

        self::assertFileExists(
            $provider->getFullPhysicalPathForFileByName
            (
                $this->customer->object(), $name.'.jpg'
            )
        );


    }

    // Todo: Create List test case
    // todo: create edit test case
}
