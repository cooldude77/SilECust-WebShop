<?php

namespace Silecust\WebShop\Tests\Controller\Common\File;

use Silecust\WebShop\Service\Testing\AbstractDoctrineWithMigrationTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileControllerTest extends WebTestCase
{

    /*

    public function testCreate()
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
        $crawler = $client->request('GET', '/file/create');

        $this->assertResponseIsSuccessful();
        $buttonCrawlerNode = $crawler->selectButton('Save');

        $form = $buttonCrawlerNode->form();
        $form['file_create_form[yourFileName]'] = 'Random';
        $form['file_create_form[type]'] = 1;

        $file =dirname(__FILE__).'/random.jpg';
        $form['file_create_form[uploadedFile]']->upload($file);

        $fileName =$form->get('file_create_form[name]')->getValue();

        // submit the Form object
        $client->submit($form);
        $this->assertResponseIsSuccessful();

    }
*/
}