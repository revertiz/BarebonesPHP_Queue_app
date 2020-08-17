<?php


namespace Code\Client;

use Code\Model\Client;

class ClientController
{
    public function client(Client $client)
    {
        if(isset($_GET['token']))
        {
            $client = $client->getClientByToken($_GET['token']);
        }

        if(isset($_GET['id']))
        {
            $client = $client->getClientById($_GET['id']);
        }
        return $client;
    }

    public function delete(Client $client)
    {
        return $client->deleteClient($_POST['id']);
    }

    public function delay(Client $client)
    {
        $client->delay($_POST['specialist_id'], $_POST['client_id']);

        return header('Location: index.php?route=client&id=' . $_POST['client_id']);
    }
}
