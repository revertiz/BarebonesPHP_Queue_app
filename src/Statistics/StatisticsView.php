<?php


namespace Code\Statistics;


use Code\Model\Statistics;

class StatisticsView
{
    public function output(Statistics $model): string
    {

        $statistics = $model->getStats();
        $weekdays = $model->getWeekdays();
        $specialists = $model->getSpecialists();

//        print_r($statistics);
        $output = '<div class="container">
		<form action="index.php" method="get">
		<input type="hidden" value="statistics" name="route">
		
		<div class="form-check">
                <input type="checkbox" class="form-check-input" value="1" name="specialist[]" '. (in_array(1,$specialists) ? 'checked' : '') .'>
                    <label class="form-check-label" for="exampleCheck1">Linas</label>
                <input type="checkbox" class="form-check-input" value="2" name="specialist[]" '. (in_array(2,$specialists) ? 'checked' : '') .'>
                    <label class="form-check-label" for="exampleCheck1">Petras</label>
		</div>
			  <div class="form-check">
                <input type="checkbox" class="form-check-input" value="0" name="weekday[]" '. (in_array(0,$weekdays) ? 'checked' : '') .'>
                    <label class="form-check-label" for="exampleCheck1">Pirmadienis</label>
                <input type="checkbox" class="form-check-input" value="1" name="weekday[]" '. (in_array(1,$weekdays) ? 'checked' : '') .'>
                    <label class="form-check-label" for="exampleCheck1">Antradienis</label>
                <input type="checkbox" class="form-check-input" value="2" name="weekday[]" '. (in_array(2,$weekdays) ? 'checked' : '') .'>
                    <label class="form-check-label" for="exampleCheck1">Treciadienis</label>
                <input type="checkbox" class="form-check-input" value="3" name="weekday[]" '. (in_array(3,$weekdays) ? 'checked' : '') .'>
                    <label class="form-check-label" for="exampleCheck1">Ketvirtadienis</label>
                <input type="checkbox" class="form-check-input" value="4" name="weekday[]" '. (in_array(4,$weekdays) ? 'checked' : '') .'>
                    <label class="form-check-label" for="exampleCheck1">Penktadienis</label>
                <input type="checkbox" class="form-check-input" value="5" name="weekday[]" '. (in_array(5,$weekdays) ? 'checked' : '') .'>
                    <label class="form-check-label" for="exampleCheck1">Sestadienis</label>
                <input type="checkbox" class="form-check-input" value="6" name="weekday[]" '. (in_array(6,$weekdays) ? 'checked' : '') .'>
                    <label class="form-check-label" for="exampleCheck1">Sekmadienis</label>
</div>

				<input class="btn btn-default" type="submit" value="Ieskoti" />
			</form>
            </div>';

        $monday =[];
        $tuesday =[];
        $wednesday =[];
        $thursday = [];
        $friday = [];
        $saturday =[];
        $sunday = [];


foreach ($statistics as $key => $value){


switch($value['weekday']){
    case 0:
        $monday[] = [$value['hour'] => $value['hour_counts']];
        break;
    case 1:
        $tuesday[] = [$value['hour'] => $value['hour_counts']];
        break;
    case 2:
        $wednesday[] = [$value['hour'] => $value['hour_counts']];
        break;
    case 3:
        $thursday[] = [$value['hour'] => $value['hour_counts']];
        break;
    case 4:
        $friday[] = [$value['hour'] => $value['hour_counts']];
        break;
    case 5:
        $saturday[] = [$value['hour'] => $value['hour_counts']];
        break;
    case 6:
        $sunday[] = [$value['hour'] => $value['hour_counts']];
        break;
}

}

$allDays = [];
array_push($allDays,$monday,$tuesday,$wednesday, $thursday, $friday,$saturday,$sunday);

        $output .= '
        <div class="container">
            <div class="row">
                <div class="col-12">
                  <table class="table table-bordered">
                    <thead>
                      
                    </thead>
                    <tbody>';

        $output .= '<tr><th scope="col" colspan="2">Pirmadienis</th>';
        foreach($monday as $key => $value){
            foreach($value as $hour => $amount) {
                $output .= '<td>' . $hour . ':00 registracijos: <b>'. $amount .'</b></td>';
            }
        }
        $output .='</tr>';

        $output .= '<tr><th scope="col" colspan="2">Antradienis</th>';
        foreach($tuesday as $key => $value){
            foreach($value as $hour => $amount) {
                $output .= '<td>' . $hour . ':00 registracijos: <b>'. $amount .'</b></td>';
            }
        }
        $output .='</tr>';

        $output .= '<tr><th scope="col" colspan="2">Treciadienis</th>';
        foreach($wednesday as $key => $value){
            foreach($value as $hour => $amount) {
                $output .= '<td>' . $hour . ':00 registracijos: <b>'. $amount .'</b></td>';
            }
        }
        $output .='</tr>';

        $output .= '<tr><th scope="col" colspan="2">Ketvirtadienis</th>';
        foreach($thursday as $key => $value){
            foreach($value as $hour => $amount) {
                $output .= '<td>' . $hour . ':00 registracijos: <b>'. $amount .'</b></td>';
            }
        }
        $output .='</tr>';

        $output .= '<tr><th scope="col" colspan="2">Penktadienis</th>';
        foreach($friday as $key => $value){
            foreach($value as $hour => $amount) {
                $output .= '<td>' . $hour . ':00 registracijos: <b>'. $amount .'</b></td>';
            }
        }
        $output .='</tr>';

        $output .= '<tr><th scope="col" colspan="2">Sestadienis</th>';
        foreach($saturday as $key => $value){
            foreach($value as $hour => $amount) {
                $output .= '<td>' . $hour . ':00 registracijos: <b>'. $amount .'</b></td>';
            }
        }
        $output .='</tr>';

        $output .= '<tr><th scope="col" colspan="2">Sekmadienis</th>';
        foreach($sunday as $key => $value){
            foreach($value as $hour => $amount) {
                $output .= '<td>' . $hour . ':00 registracijos: <b>'. $amount .'</b></td>';
            }
        }
        $output .='</tr>';

        $output .= '
                </tbody>
              </table>
            </div>
         </div>';


        return $output;

    }

}
