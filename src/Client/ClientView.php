<?php


namespace Code\Client;

use Code\Model\Client;

class ClientView
{

    public function output(Client $model) : string
    {

if (!is_null($model->getPosition())) {
    $client = $model;
    $client->isLast();

    $isServiced = floatval($client->getServiced());
    if($isServiced == 1){
        $visit = 'Vizito Laikas';
        $time = $client->getVisitLength();
        $cancel = '';
    } else {
        $visit = 'Liko Laukti';
        $time = $client->getTimeLeft();

        //cia tikriausiai nereikia to $client->getId() nes yra id paciam objekte
        $cancel = '<form action="index.php?route=client&action=delete" method="POST">
                                <input type="hidden" name="id" value="' . $client->getId() . '" />     
                                <button type="submit" class="btn btn-danger">Atsaukti</button>
                            </form>';
        if(!$client->isLast()){
            $delay = '<form action="index.php?route=client&action=delay" method="POST">
                        <input type="hidden" name="specialist_id" value="' . $client->getSpecialistId() . '" /> 
                        <input type="hidden" name="client_id" value="' . $client->getId() . '" />
                        <button type="submit" class="btn btn-danger">Velinti</button>
                       </form>';
        } else {
            $delay = '';
        }
    }
    $output = '';
//    $output = '<meta http-equiv="refresh" content="5">';
    $output .= '
        <div class="container">
            <div class="row">
                <div class="col-12">
                  <table class="table table-borderless">
                    <thead>
                      <tr>
                      <th scope="col">Pozicija</th>
                        <th scope="col">Vardas</th>
                        <th scope="col">Pavarde</th>
                        <th scope="col">Aptarnauta</th>
                        <th scope="col">'. $visit . '</th>

                      </tr>
                    </thead>
                    <tbody>';

    $output .= '';
    $output .= '

                                    <tr>
                                    <td>' . $client->getPosition() . '</td>
                                        <td>' . $client->getName() . '</td>
                                        <td>' . $client->getSurname() . '</td>
                                        <td>' . $client->getServiced() . '</td>   
                                         <td>' . $time . '</td>
                                         <td>' . $cancel . '</td>
                                           <td>' . $delay . '</td>
                                    </tr>';
    $output .= '
                </tbody>
              </table>
            </div>
         </div>';

} else {
    $output = '
<div class="container">
<form action="" method="get">
              <div class="form-group">
                <label >Iveskite savo ID:</label>
                <input type="hidden" value="client" name="route" />
                <input class="form-control" placeholder="id" name="id">
              </div>
              <div><button type="submit" class="btn btn-primary">Registruotis</button></div>
              </form>
              </div>';

}

        return $output;
    }

}
