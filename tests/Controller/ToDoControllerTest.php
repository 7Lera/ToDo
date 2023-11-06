<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ToDoControllerTest extends WebTestCase
{
    public function testIndexPageLoadsSuccessfully()
    {
        $client = static::createClient();
        $client->request('GET', '/todo');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Lista de Sarcini');
    }

    public function testViewPageLoadsSuccessfully()
    {
        $client = static::createClient();
        $client->request('GET', '/todo/2');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Detalii Sarcină');
    }

    public function testCreateTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/create');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Salvează')->form();

        $form['todo[title]'] = 'Test Task';
        $form['todo[description]'] = 'This is a test task description';
        $form['todo[dueDate]'] = '2023-12-31';
        $form['todo[category]'] = 'Test Category';

        $client->submit($form);

        $this->assertResponseRedirects('/todo');
        $client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Lista de Sarcini');
    }

    public function testUpdateTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/todo/update/5'); 

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Salvează')->form();

        $form['todo[title]'] = 'Updated Test Task';

        $client->submit($form);

        $this->assertResponseRedirects('/todo');
        $client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Lista de Sarcini');
    }

    public function testDeleteTask()
    {
        $client = static::createClient();
        $client->request('GET', '/todo/delete/3'); 

        $this->assertResponseRedirects('/todo');
        $client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Lista de Sarcini');
    }
}
