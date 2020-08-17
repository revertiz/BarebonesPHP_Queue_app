<?php


namespace Code\Specialist;

use Code\Model\Specialist;

class SpecialistView
{

    public function output(Specialist $model): string
    {
        $output = '';

        $output .= '
<div class="container">
<div class="cold-md-4">
<ul class="list-group">

     ';

        foreach ($model->getSpecialists() as $specialist) {

            $output .= '<li style="max-width:25%" class="list-group-item"><a href="?route=specialist&specialist-id=' . $specialist['id'] . '">' . $specialist['name'] . '</a></li>';
        }
        $output .= '

</ul>
</div>
     </div>
     ';

        if (isset($_GET['specialist-id'])) {
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
                        <th colspan="2"></th>
                      </tr>
                    </thead>
                    <tbody>';

            $position =1;
            foreach ($model->getClients($_GET['specialist-id']) as $person) {
                $output .= '

                                    <tr>
                                        <td>' . $position . '</td>
                                        <td>' . $person['name'] . '</td>
                                        <td>' . $person['surname'] . '</td>
                                        <td style="text-align: right">
                                            <form action="index.php?route=specialist&amp;action=service&amp;specialist-id=' . $_GET['specialist-id'] . '" method="POST">
                                                <input  type="hidden" name="client_id" value="' . $person['id'] . '" />
                                                <input  type="hidden" name="specialist_id" value="' . $_GET['specialist-id'] . '" /> 
                                                <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i>Aptarnauti</button>
                                            </form>
                                        </td>
                                    </tr>';
                $position += 1;
            }
        }

        $output .= '
                </tbody>
              </table>
            </div>
         </div>';

        return $output;
    }

}
