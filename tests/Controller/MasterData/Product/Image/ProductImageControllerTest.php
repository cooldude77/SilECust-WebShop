<?php

namespace Silecust\WebShop\Tests\Controller\MasterData\Product\Image;

use Silecust\WebShop\Factory\ProductFactory;
use Silecust\WebShop\Factory\ProductImageFactory;
use Silecust\WebShop\Service\MasterData\Product\Image\Provider\ProductDirectoryImagePathProvider;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class ProductImageControllerTest extends WebTestCase
{
    use HasBrowser, EmployeeFixture, Factories;

    public function testCreateEditAndList()
    {

        self::bootKernel();
        /** @var ProductDirectoryImagePathProvider $provider */
        $provider = self::getContainer()->get(ProductDirectoryImagePathProvider::class);

        $product = ProductFactory::createOne();


        $id = $product->getId();

        $uri = "/admin/product/$id/image/create";

        $fileNameCreate = 'grocery_1920.jpg';
        $filePathCreate = __DIR__ . '/' . $fileNameCreate;
        $uploadedFileCreate = new UploadedFile(
            $filePathCreate, $fileNameCreate
        );

        $visit = $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri);

        $form = $visit->crawler()->selectButton('Save')->form();
        $name = $form->get('product_image_create_form[fileDTO][name]')->getValue();

        $visit
            ->fillField('product_image_create_form[fileDTO][yourFileName]', 'MyFile')
            ->fillField(
                'product_image_create_form[fileDTO][uploadedFile]', $uploadedFileCreate)
            ->click('Save')
            ->assertSuccessful();

        self::assertFileExists(
            $provider->getFullPhysicalPathForFileByName($product->object(), $name . '.jpg')
        );

        $uploadedToServerFilePathAfterCreate = $provider->getFullPhysicalPathForFileByName(
            $product->object(), $name . '.jpg');

        self::assertEquals(md5_file($filePathCreate), md5_file($uploadedToServerFilePathAfterCreate));

        /** @var \Silecust\WebShop\Entity\ProductImage $productImage */
        $productImage = ProductImageFactory::find(['product' => $product]);

        // ************
        // Edit Testing

        $uri = "/admin/product/image/{$productImage->getId()}/edit";

        $fileNameEdit = 'test_2.jpg';
        $filePathEdit = __DIR__ . '/' . $fileNameEdit;
        $uploadedFileEdit = new UploadedFile(
            $filePathEdit, $fileNameEdit
        );

        $visit = $this
            ->browser()
            ->visit($uri)
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $response = $browser->client()->getResponse();
            });

        $form = $visit->crawler()->selectButton('Save')->form();
        $name = $form->get('product_image_edit_form[fileDTO][name]')->getValue();

        $visit
            ->fillField('product_image_edit_form[fileDTO][yourFileName]', 'MyFile')
            ->fillField(
                'product_image_edit_form[fileDTO][uploadedFile]', $uploadedFileEdit)
            ->click('Save')
            ->assertSuccessful();

        self::assertFileExists(
            $provider->getFullPhysicalPathForFileByName($product->object(), $name)
        );

        $uploadedToServerFilePathAfterEdit = $provider->getFullPhysicalPathForFileByName(
            $product->object(), $name);

        self::assertEquals(md5_file($filePathEdit), md5_file($uploadedToServerFilePathAfterEdit));


        // ************
        // List Testing
        $uri = "/admin/product/{$product->getId()}/image/list";


        $this
            ->browser()
            ->visit($uri)
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful()
            ->assertSee($productImage->getFile()->getYourFileName())
            ->assertSee($productImage->getFile()->getName());


    }

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');
        $this->createEmployeeFixtures();
    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

        $root = self::getContainer()->getParameter('kernel.project_dir');
        $path = $root . self::getContainer()->getParameter('file_storage_path');

        shell_exec("rm -rf " . $path);
    }

    // Todo: Create List test case
    // todo: create edit test case
}
