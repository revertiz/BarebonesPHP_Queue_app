<?php


namespace Code\Specialist;


use Code\Model\Specialist;

class SpecialistController
{
    public function service(Specialist $specialist) : Specialist
    {
        return $specialist->serviceClient($_POST['client_id'], $_POST['specialist_id']);
    }
}
