<?php


namespace Code\Model;


class Token
{
    public function generateId($clientId)
    {
        return uniqid($clientId);
    }
}
