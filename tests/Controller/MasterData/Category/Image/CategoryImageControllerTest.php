<?php

namespace Silecust\WebShop\Tests\Controller\MasterData\Category\Image;

use Silecust\WebShop\Factory\CategoryFactory;
use Silecust\WebShop\Service\MasterData\Category\Image\Provider\CategoryDirectoryImagePathProvider;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class CategoryImageControllerTest extends WebTestCase
{
    use HasBrowser, selectElement, EmployeeFixture, Factories;

    protected function setUp(): void
    { $this->browser()->visit('/logout');
        $this->createEmployeeFixtures();
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
