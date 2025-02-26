<?php

namespace Silecust\WebShop\Tests\Controller\MasterData\Product\Image;

use Silecust\WebShop\Factory\ProductFactory;
use Silecust\WebShop\Service\MasterData\Product\Image\Provider\ProductDirectoryImagePathProvider;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class ProductImageControllerTest extends WebTestCase
{
    use HasBrowser,EmployeeFixture,Factories;
    protected function setUp(): void
    {
        $this->browser()->visit('/logout');
        $this->createEmployeeFixtures();
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
        $provider = self::getContainer()->get(ProductDirectoryImagePathProvider::class);

        $product = ProductFactory::createOne();


        $id = $product->getId();

        $uri = "/admin/product/$id/image/create";

        $fileName = 'grocery_1920.jpg';
        $uploadedFile = new UploadedFile(
            __DIR__ . '/' . $fileName, $fileName
        );
        $visit = $this->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri);

        $form = $visit->crawler()->selectButton('Save')->form();

        $name = $form->get('product_image_create_form[fileDTO][name]')->getValue();


        $visit->fillField('product_image_create_form[fileDTO][yourFileName]', 'MyFile.jpg')
            ->fillField(
                'product_image_create_form[fileDTO][uploadedFile]', $uploadedFile
            )->click('Save')->assertSuccessful();

        self::assertFileExists(
            $provider->getFullPhysicalPathForFileByName
            (
                $product->object(), $name.'.jpg'
            )
        );


    }

    // Todo: Create List test case
    // todo: create edit test case
}
