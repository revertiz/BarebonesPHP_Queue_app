<?php


namespace Code\Registration;


use Code\Model\Registration;

class RegistrationController
{

    public function register(Registration $registration) : Registration
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $registration = $this->submit($registration);
            return $registration;
        }
        else {
            return $registration;
        }
    }
    public function submit(Registration $registration)
    {
        return $registration->save($_POST['register']);
    }
}
