<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Department;
use App\Entity\Region;
use App\Entity\Sales;
use App\Entity\SalesAverage;
use App\Entity\Salon;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        // Création du pays
        $country = new Country();
        $country->setName("France");
        $manager->persist($country);

        // Création des régions
        $listRegion = [];
       $regions = [
            "84" => "Auvergne-Rhône-Alpes", "27" => "Bourgogne-Franche-Comté", "53" => "Bretagne", "24" => "Centre-Val de Loire",
            "94" => "Corse", "44" => "Grand Est", "32" => "Hauts-de-France", "11" => "Île-de-France", "28" => "Normandie",
            "75" => "Nouvelle-Aquitaine", "76" => "Occitanie", "52" => "Pays de la Loire", "93" => "Provence-Alpes-Côte d'Azur",
            "01" => "Guadeloupe", "02" => "Martinique", "03" => "Guyane", "04" => "La Réunion", "06" => "Mayotte"
        ];


        foreach ($regions as $number => $regionsName) {
            $region = new Region();
            $region->setCountryId($country);
            $region->setName($regionsName);
            $region->setNumber($number);
            $manager->persist($region);
            $listRegion[$number] = $region;
        }

        // Création des départements
        $listDepartment = [];
        $listDepartment = [];
        $departments = [
            "01" => "Ain", "02" => "Aisne", "03" => "Allier", "04" => "Alpes-de-Haute-Provence", "05" => "Hautes-Alpes",
            "06" => "Alpes-Maritimes", "07" => "Ardèche", "08" => "Ardennes", "09" => "Ariège", "10" => "Aube",
            "11" => "Aude", "12" => "Aveyron", "13" => "Bouches-du-Rhône", "14" => "Calvados", "15" => "Cantal",
            "16" => "Charente", "17" => "Charente-Maritime", "18" => "Cher", "19" => "Corrèze", "2A" => "Corse-du-Sud",
            "2B" => "Haute-Corse", "21" => "Côte-d'Or", "22" => "Côtes-d'Armor", "23" => "Creuse", "24" => "Dordogne",
            "25" => "Doubs", "26" => "Drôme", "27" => "Eure", "28" => "Eure-et-Loir", "29" => "Finistère",
            "30" => "Gard", "31" => "Haute-Garonne", "32" => "Gers", "33" => "Gironde", "34" => "Hérault",
            "35" => "Ille-et-Vilaine", "36" => "Indre", "37" => "Indre-et-Loire", "38" => "Isère", "39" => "Jura",
            "40" => "Landes", "41" => "Loir-et-Cher", "42" => "Loire", "43" => "Haute-Loire", "44" => "Loire-Atlantique",
            "45" => "Loiret", "46" => "Lot", "47" => "Lot-et-Garonne", "48" => "Lozère", "49" => "Maine-et-Loire",
            "50" => "Manche", "51" => "Marne", "52" => "Haute-Marne", "53" => "Mayenne", "54" => "Meurthe-et-Moselle",
            "55" => "Meuse", "56" => "Morbihan", "57" => "Moselle", "58" => "Nièvre", "59" => "Nord",
            "60" => "Oise", "61" => "Orne", "62" => "Pas-de-Calais", "63" => "Puy-de-Dôme", "64" => "Pyrénées-Atlantiques",
            "65" => "Hautes-Pyrénées", "66" => "Pyrénées-Orientales", "67" => "Bas-Rhin", "68" => "Haut-Rhin", "69" => "Rhône",
            "70" => "Haute-Saône", "71" => "Saône-et-Loire", "72" => "Sarthe", "73" => "Savoie", "74" => "Haute-Savoie",
            "75" => "Paris", "76" => "Seine-Maritime", "77" => "Seine-et-Marne", "78" => "Yvelines", "79" => "Deux-Sèvres",
            "80" => "Somme", "81" => "Tarn", "82" => "Tarn-et-Garonne", "83" => "Var", "84" => "Vaucluse",
            "85" => "Vendée", "86" => "Vienne", "87" => "Haute-Vienne", "88" => "Vosges", "89" => "Yonne",
            "90" => "Territoire de Belfort", "91" => "Essonne", "92" => "Hauts-de-Seine", "93" => "Seine-Saint-Denis",
            "94" => "Val-de-Marne", "95" => "Val-d'Oise", "971" => "Guadeloupe", "972" => "Martinique",
            "973" => "Guyane", "974" => "La Réunion", "976" => "Mayotte"
        ];

        foreach ($departments as $number => $departmentsName) {
            $department = new Department();
            $department->setRegionId($listRegion[$number] ?? $listRegion[array_rand($listRegion)]);
            $department->setName($departmentsName);
            $department->setNumber($number);
            $manager->persist($department);
            $listDepartment[] = $department;
        }

        // Création des utilisateurs
        $listUsers = [];
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setName($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $faker->password));
            $user->setRoles(["ROLE_USER"]);
            $manager->persist($user);
            $listUsers[] = $user;
        }

        // Création des salons
        $listSalons = [];
        for ($i = 0; $i < 20; $i++) {
            $salon = new Salon();
            $salon->setDepartmentId($listDepartment[array_rand($listDepartment)]);
            $salon->addUserId($listUsers[array_rand($listUsers)]);
            $salon->setName($faker->company);
            $salon->setAddress($faker->streetAddress);
            $salon->setStaffNumber(random_int(1, 10));
            $date = DateTimeImmutable::createFromFormat('Y-m-d', sprintf("%04d-%02d-%02d", $faker->year, $faker->month, $faker->dayOfMonth));
            $salon->setOpeningDate($date);
            $manager->persist($salon);
            $listSalons[] = $salon;
        }

        // Création des ventes
        foreach ($listSalons as $salon) {
            for ($month = 1; $month <= 12; $month++) {
                $sales = new Sales();
                $sales->setSalonId($salon);
                $sales->setAmount(random_int(1000, 100000));
                $sales->setCreatedAt(new DateTimeImmutable("2025-$month-01"));
                $manager->persist($sales);
            }
        }

        // Moyennes des ventes
        $averageFrance = new SalesAverage();
        $averageFrance->setName("Average France");
        $averageFrance->setAmountAverage(75000);
        $month = random_int(1, 12);
        $averageFrance->setCreatedAt(new DateTimeImmutable(sprintf("2025-%02d-01", $month)));
        $manager->persist($averageFrance);

        foreach ($listRegion as $region) {
            $averageRegion = new SalesAverage();
            $averageRegion->setName("Average " . $region->getName());
            $averageRegion->setAmountAverage(random_int(25000, 125000));
            $month = random_int(1, 12);
            $averageRegion->setCreatedAt(new DateTimeImmutable(sprintf("2025-%02d-01", $month)));
            $manager->persist($averageRegion);
        }

        foreach ($listDepartment as $department) {
            $averageDepartment = new SalesAverage();
            $averageDepartment->setName("Average " . $department->getName());
            $averageDepartment->setAmountAverage(random_int(25000, 125000));
            $month = random_int(1, 12);
            $averageDepartment->setCreatedAt(new DateTimeImmutable(sprintf("2025-%02d-01", $month)));
            $manager->persist($averageDepartment);
        }

        // Enregistrement en base
        $manager->flush();
    }
}