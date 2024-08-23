<?php

namespace App\Tests\Controller\MasterData\Category\Image;

use App\Factory\CategoryFactory;
use App\Service\MasterData\Category\Image\Provider\CategoryDirectoryImagePathProvider;
use App\Tests\Fixtures\EmployeeFixture;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class CategoryImageControllerTest extends WebTestCase
{
    use HasBrowser, selectElement, EmployeeFixture;

    protected function setUp(): void
    {
        $this->createEmployee();
    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

        $root = self::getContainer()->getParameter('kernel.project_dir');
        $path = $root . self::getContainer()->getParameter('file_storage_path');

        shell_exec("rm -rf " . $path);

    }

    public function testCreate()
    {

        self::bootKernel();
        $provider = self::getContainer()->get(CategoryDirectoryImagePathProvider::class);

        $category = CategoryFactory::createOne();

        $id = $category->getId();
        $uri = "/admin/category/$id/image/create";

        $fileName = 'grocery_1920.jpg';
        $uploadedFile = new UploadedFile(
            __DIR__ . '/' . $fileName, $fileName
        );

        $name = '';
        $visit = $this->browser()
            ->visit($uri)
            // test: not authenticated
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) use ($name) {
                /** @var Crawler $nodes */
                $nodes = $browser->crawler()->filter('input[name="category_image_create_form[fileDTO][name]"]');
                $name =    $nodes->getNode(0);


            });

        $a = $visit->crawler()->filter('category_image_create_form[fileDTO][name]');

        $form = $visit->crawler()->selectButton('Save')->form();

        $name = $form->get('category_image_create_form[fileDTO][name]')->getValue();


        $visit->fillField('category_image_create_form[fileDTO][yourFileName]', 'MyFile.jpg')
            ->fillField(
                'category_image_create_form[fileDTO][uploadedFile]', $uploadedFile
            )->click('Save')->assertSuccessful();

        self::assertFileExists(
            $provider->getFullPhysicalPathForFileByName
            (
                $category->object(), $name . '.jpg'
            )
        );


    }


}
