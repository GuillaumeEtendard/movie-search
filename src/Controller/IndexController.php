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
        $director = $_POST['director'];
        $directorGender = $_POST['directorGender'];

        if (isset($title)) {
            $sqlTitle = " SELECT * FROM film_director
                          INNER JOIN artist AS a
                          ON artist_id = a.id
                          INNER JOIN film AS f
                          ON film_director.film_id = f.id
                          WHERE f.title
                          LIKE '%$title%' ";
            //envoyer la requête à la BDD
        }

        if (isset($duration)) {
            if ($duration == "allDuration") {
                $sqlDuration = "";

            } else if ($duration == "lessThan1h") {
                $sqlDuration = " AND f.duration < 3600 ";

            } else if ($duration == "between1hAnd1h30") {
                $sqlDuration = " AND f.duration BETWEEN 3600 AND 5400 ";

            } else if ($duration == "between1h30And2h30") {
                $sqlDuration = " AND f.duration BETWEEN 5400 AND 9000 ";

            } else if ($duration == "moreThan2h30") {
                $sqlDuration = " AND f.duration > 9000 ";
            }
        }

        if (isset($yearStart)) {
            $sqlYearStart = " AND f.year >= '$yearStart' ";
        }
        if (empty($yearStart)) {
            $sqlYearStart = "";
        }

        if (isset($yearEnd)) {
            $sqlYearEnd = " AND f.year <= '$yearEnd' ";
        }
        if (empty($yearEnd)) {
            $sqlYearEnd = "";
        }

        if (isset($director)) {
            $sqlDirector = " AND ( a.first_name LIKE '%$director%' OR a.last_name LIKE '%$director%')  ";
        }

        if (empty($director)) {
            $sqlDirector = "";
        }

        if (isset($directorGender)) {
            if ($directorGender == "allGender") {
                $sqlDirectorGender = "";

            } else if ($directorGender == "M") {
                $sqlDirectorGender = " AND ( gender = 'M' ) ";

            } else if ($directorGender == "F") {
                $sqlDirectorGender = " AND ( gender = 'F') ";
            }
        }

        $stmt = $conn->prepare($sqlTitle . $sqlDuration . $sqlYearStart . $sqlYearEnd . $sqlDirector . $sqlDirectorGender . 'GROUP BY f.title');
        $stmt->execute();
        $films = $stmt->fetchAll();
        return json_encode(["films" => $films]);
    }
}
