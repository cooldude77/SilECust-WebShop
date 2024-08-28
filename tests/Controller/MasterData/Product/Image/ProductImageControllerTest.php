<?php

namespace App\Tests\Controller\MasterData\Product\Image;

use App\Factory\ProductFactory;
use App\Service\MasterData\Product\Image\Provider\ProductDirectoryImagePathProvider;
use App\Tests\Fixtures\EmployeeFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class ProductImageControllerTest extends WebTestCase
{
    use HasBrowser,EmployeeFixture;
    protected function setUp(): void
    {
        $this->createEmployee();
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
