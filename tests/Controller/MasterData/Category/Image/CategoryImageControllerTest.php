<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Silecust\WebShop\Tests\Controller\MasterData\Category\Image;

use Silecust\WebShop\Factory\CategoryFactory;
use Silecust\WebShop\Factory\CategoryImageFactory;
use Silecust\WebShop\Service\MasterData\Category\Image\Provider\CategoryDirectoryImagePathProvider;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use function Symfony\Component\String\u;

class CategoryImageControllerTest extends WebTestCase
{
    use HasBrowser, EmployeeFixture, Factories;

    public function testCreateEditAndList()
    {

        self::bootKernel();
        /** @var CategoryDirectoryImagePathProvider $provider */
        $provider = self::getContainer()->get(CategoryDirectoryImagePathProvider::class);

        $category = CategoryFactory::createOne();


        $id = $category->getId();

        $uri = "/admin/category/$id/image/create";

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
        $name = $form->get('category_image_create_form[fileDTO][name]')->getValue();

        $visit
            ->fillField('category_image_create_form[fileDTO][yourFileName]', 'MyFile')
            ->fillField(
                'category_image_create_form[fileDTO][uploadedFile]', $uploadedFileCreate)
            ->click('Save')
            ->assertSuccessful();

        self::assertFileExists(
            $provider->getFullPhysicalPathForFileByName($category->object(), $name . '.jpg')
        );

        $uploadedToServerFilePathAfterCreate = $provider->getFullPhysicalPathForFileByName(
            $category->object(), $name . '.jpg');

        self::assertEquals(md5_file($filePathCreate), md5_file($uploadedToServerFilePathAfterCreate));

        /** @var \Silecust\WebShop\Entity\CategoryImage $categoryImage */
        $categoryImage = CategoryImageFactory::find(['category' => $category]);

        // ************
        // Edit Testing

        $uri = "/admin/category/image/{$categoryImage->getId()}/edit";

        $fileNameEdit = 'test_image_jpeg.jpg';
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
        $name = $form->get('category_image_edit_form[fileDTO][name]')->getValue();

        $visit
            ->fillField('category_image_edit_form[fileDTO][yourFileName]', 'MyFile')
            ->fillField(
                'category_image_edit_form[fileDTO][uploadedFile]', $uploadedFileEdit)
            ->click('Save')
            ->assertSuccessful();

        self::assertFileExists(
            $provider->getFullPhysicalPathForFileByName($category->object(), $name)
        );

        $uploadedToServerFilePathAfterEdit = $provider->getFullPhysicalPathForFileByName(
            $category->object(), $name);

        self::assertEquals(md5_file($filePathEdit), md5_file($uploadedToServerFilePathAfterEdit));


        // ************
        // List Testing
        $uri = "/admin/category/{$category->getId()}/image/list";


        $this
            ->browser()
            ->visit($uri)
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful()
            ->assertSee($categoryImage->getFile()->getYourFileName())
            ->assertSee($categoryImage->getFile()->getName());

        // ************
        // file type change test


        $uri = "/admin/category/image/{$categoryImage->getId()}/edit";

        $fileNameEditPNG = 'test_image_png.png';
        $filePathEditPNG = __DIR__ . '/' . $fileNameEditPNG;
        $uploadedFileEditPNG = new UploadedFile(
            $filePathEditPNG, $fileNameEditPNG
        );

        $fileToBeReplacedPathJPG = $provider->getFullPhysicalPathForFileByName($category->object(), $fileNameEdit);

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
        $name = $form->get('category_image_edit_form[fileDTO][name]')->getValue();

        $visit
            ->fillField('category_image_edit_form[fileDTO][yourFileName]', 'MyFile')
            ->fillField(
                'category_image_edit_form[fileDTO][uploadedFile]', $uploadedFileEditPNG)
            ->click('Save')
            ->assertSuccessful();

        // check if CategoryImage File is updated

        $categoryImage = CategoryImageFactory::find($categoryImage);

        self::assertTrue(u($categoryImage->getFile()->getName())->endsWith('.png'));
        $uploadedToServerFilePathAfterEditPNG = $provider->getFullPhysicalPathForFileByName(
            $category->object(), $categoryImage->getFile()->getName());

        self::assertFileExists($uploadedToServerFilePathAfterEditPNG);

        // check if extension changed in file
        self::assertEquals('png', (new SplFileInfo($uploadedToServerFilePathAfterEditPNG))->getExtension());

        // test: file is correct?
        self::assertEquals(md5_file($filePathEditPNG), md5_file($uploadedToServerFilePathAfterEditPNG));

        // test: check if old file deleted?

        self::assertFileDoesNotExist($fileToBeReplacedPathJPG);

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

}
