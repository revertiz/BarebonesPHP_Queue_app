<?php


namespace Code\Statistics;


use Code\Model\Statistics;

class StatisticsController
{
    public function statistics(Statistics $statistics)
    {
        if (!empty($_GET['specialist'])) {
            $statistics = $statistics->specialist($_GET['specialist']);
        }

        if (!empty($_GET['weekday'])) {
            $statistics = $statistics->weekday($_GET['weekday']);
        }

        return $statistics;
    }

}
