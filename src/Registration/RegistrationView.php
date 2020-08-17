<?php


namespace Code\Registration;


use Code\Model\Registration;

class RegistrationView
{
    public function output(Registration $model)
    {
        $output = '<div class="container">';
        $errors = $model->getErrors();
        $record = $model->getRecord();
        if (!empty($errors)) {
            $output .= '<b><p class="text-muted text-danger">Registracija nesekminga:</p></b>';
            $output .= '<ul class="list-group">';
            foreach ($errors as $error) {
                $output .= '<li class=" list-group-item text-danger">' . $error . '</li>';
            }
            $output .= '</ul> </div>';
        }
        $output .= '<div class="container">';

        if ($model->isSubmitted()) {
            $output .= '<p class="text-success">Registracija sekminga</p>';
            $output .= '<div>Nuoroda i jusu laukimo puslapi: <a href="/index.php?route=client&token=' . $record['token'] . '">' . $record['name'] . ' </a></div>';
            $output .= '<div>Jusu kliento id: <b>' . $record['id'] . '</b></div>';
        }


//        if ($model->isSubmitted() && empty($errors)) {
//            header('location: index.php');
//        }


        if (!$model->isSubmitted()) {
            $output .= '
            <form action="" method="post">

               <div class="form-group">
                <label >Vardas:</label>
                <input class="form-control" placeholder="Vardas" name="register[name]" value="' . ($record['name'] ?? '') . '">
              </div>
              
                 <div class="form-group">
                <label >Pavarde:</label>
                <input class="form-control" placeholder="Vardas" name="register[surname]" value="' . ($record['surname'] ?? '') . '">
              </div>
              
              <div class="form-group">
              <label >Specialistas:</label>

              <select name="register[specialist_id]" class="form-control" >';


//TODO cia galima buvo susikurti klaseje specialist ir pasiduoti kaip dependency ir praloopinti specialistus
            if ($record['specialist_id'] == '1') {
                $output .= '<option selected value="1">Linas</option>
        <option value="2">Petras</option>
        ';
            } elseif ($record['specialist_id'] == '2') {
                $output .= '<option selected value="2">Petras</option>
        <option value="1">Linas</option>';
            } else {
                $output .= '<option selected>Pasirinkite specialista</option>
        <option value="1">Linas</option>
        <option value="2">Petras</option>';

            }

            $output .= '</select>
              </div>
              
                <div><button type="submit" class="btn btn-primary">Registruotis</button></div>
			</form>
			</div>';
        }
        return $output;
    }
}
