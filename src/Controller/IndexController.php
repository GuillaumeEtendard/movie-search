<?php
namespace Controller;

use Doctrine\DBAL\Query\QueryBuilder;

class IndexController
{
    public function indexAction()
    {
        include("search.php");
    }

    public function searchAction()
    {
        //se connecter à la bdd
        header('Content-Type: application/json');

        $conn = \MovieSearch\Connexion::getInstance();
        //creer la requete adéquate


        $title = $_POST['title'];
        $duration = $_POST['duration'];
        $yearStart = $_POST['year_start'];
        $yearEnd = $_POST['year_end'];

        if (isset($title)) {
            $sqlTitle = " SELECT * FROM `film` WHERE title LIKE '%$title%' ";
            //envoyer la requête à la BDD
        }

        if (isset($duration)) {
            if ($duration == "allDuration") {
                $sqlDuration = "";

            } else if ($duration == "lessThan1h") {
                $sqlDuration = " AND duration < 3600 ";

            } else if ($duration == "between1hAnd1h30") {
                $sqlDuration = " AND duration BETWEEN 3600 AND 5400 ";

            } else if ($duration == "between1h30And2h30") {
                $sqlDuration = " AND duration BETWEEN 5400 AND 9000 ";

            } else if ($duration == "moreThan2h30") {
                $sqlDuration = " AND duration > 9000 ";
            }
        }

        if (isset($yearStart)) {
            $sqlYearStart = " AND year >= '$yearStart' ";
        }

        if (empty($yearStart)) {
            $sqlYearStart = "";
        }

        if (isset($yearEnd)) {
            $sqlYearEnd = " AND year <= '$yearEnd' ";
        }

        if (empty($yearEnd)) {
            $sqlYearEnd = "";
        }

        $stmt = $conn->prepare($sqlTitle . $sqlDuration . $sqlYearStart . $sqlYearEnd.'GROUP BY title');
        $stmt->execute();
        $films = $stmt->fetchAll();
        return json_encode(["films" => $films]);

    }
}
