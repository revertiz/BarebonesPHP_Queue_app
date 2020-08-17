<?php


namespace Code\Queue;


use Code\Model\Queue;

class QueueView
{
    public function output(Queue $model) : string
    {
        $output = '';
        $output .= '
        <div class="container">
            <div class="row">
                <div class="col-12">
                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Vieta</th>
                        <th scope="col">Vardas</th>
                        <th scope="col">Pavarde</th>
                        <th scope="col">Specialistas</th>
                        <th scope="col">Laukti:</th>
                        <th scope="col">Laukimo laikas:</th>
                        <th scope="col">ID:</th>
                        <th colspan="2"></th>
                      </tr>
                    </thead>
                    <tbody>';
                    $clients = $model->getClients();
                    $position= 1;

                    $avgWaitTime = $clients[0]['timediffavg'];
                        foreach ($clients as $person) {
                            //TODO tai $person['wait_time'] yra SEKUNDEM
                            // service_time yra data
                            // gmdate yra irgi data
                            // perkelti expectedServiceTime i modely
                            $currentTime = strtotime(date("Y:m:d H:i:s"));
                            $serviceTime = strtotime($person['service_start']) + $person['wait_time'];
//                            $expectedServiceTime =  gmdate("H:i:s",strtotime($person['service_start']) + $person['wait_time'] - strtotime(date("Y:m:d H:i:s")));
                            if ($serviceTime-$currentTime <= 0)
                            {
                                $expectedServiceTime = 'Aptarnaujama';
                            } else {
                                $expectedServiceTime = gmdate("H:i:s", $serviceTime - $currentTime);
                            }
                            $output .= '
                              
                                    <tr>
                                        <td>' . $position . '</td>
                                        <td>' . $person['name'] . '</td>
                                        <td>' . $person['surname'] . '</td>
                                        <td>' . $person['specname'] . '</td>
                                        <td>' . $expectedServiceTime . '</td>
                                        <td>' . gmdate("H:i:s",$person['wait_time']) . '</td>
                                        <td>' . $person['id'] . '</td>
                                    </tr>';
                            $position += 1;

                        }
                   $output .= '
                </tbody>
              </table>
            </div>
         </div>';

        return $output;
    }
}
