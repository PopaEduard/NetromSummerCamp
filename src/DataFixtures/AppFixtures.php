<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\UserDetails;
use App\Entity\Purchase;
use App\Entity\Festival;
use App\Entity\Ticket;
use App\Entity\Stage;
use App\Entity\Artist;
use App\Entity\FestivalArtist;
use App\Entity\Editions;
use App\Entity\Schedule;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $festivals = [
            ["name" => "UNTOLD Festival", "location" => "Cluj-Napoca, Romania"],
            ["name" => "Electric Castle", "location" => "Bánffy Castle, Romania"],
            ["name" => "Summer Well", "location" => "Buftea (near Bucharest), Romania"],
            ["name" => "Neversea", "location" => "Constanța, Romania"],
            ["name" => "Jazz in the Park", "location" => "Cluj-Napoca, Romania"],
            ["name" => "Gărâna Jazz Festival", "location" => "Gărâna, Caraș-Severin, Romania"],
            ["name" => "George Enescu Festival", "location" => "Bucharest, Romania"],
            ["name" => "Artmania Festival", "location" => "Sibiu, Romania"],
            ["name" => "Mioritmic", "location" => "Cluj-Napoca, Romania"],
            ["name" => "Dakini Festival", "location" => "Plaja Tuzla, Romania"],

            ["name" => "Tomorrowland", "location" => "Boom, Belgium"],
            ["name" => "Sziget Festival", "location" => "Budapest, Hungary"],
            ["name" => "Glastonbury Festival", "location" => "Pilton, England"],
            ["name" => "Primavera Sound", "location" => "Barcelona, Spain"],
            ["name" => "Roskilde Festival", "location" => "Roskilde, Denmark"],
            ["name" => "Lollapalooza", "location" => "Chicago, USA"],
            ["name" => "Exit Festival", "location" => "Novi Sad, Serbia"],
            ["name" => "Rock am Ring", "location" => "Nürburg, Germany"],
            ["name" => "Coachella", "location" => "Indio, California, USA"],
            ["name" => "Open'er Festival", "location" => "Gdynia, Poland"]
        ];



        $users = [
            ["firstName" => "Andrei", "lastName" => "Popescu", "email" => "andrei.popescu@example.com"],
            ["firstName" => "Ioana", "lastName" => "Marin", "email" => "ioana.marin@example.com"],
            ["firstName" => "Mihai", "lastName" => "Ionescu", "email" => "mihai.ionescu@example.com"],
            ["firstName" => "Elena", "lastName" => "Dumitrescu", "email" => "elena.dumitrescu@example.com"],
            ["firstName" => "Alexandru", "lastName" => "Stan", "email" => "alexandru.stan@example.com"],
            ["firstName" => "Gabriela", "lastName" => "Petrescu", "email" => "gabriela.petrescu@example.com"],
            ["firstName" => "Radu", "lastName" => "Georgescu", "email" => "radu.georgescu@example.com"],
            ["firstName" => "Corina", "lastName" => "Tudor", "email" => "corina.tudor@example.com"],
            ["firstName" => "Vlad", "lastName" => "Iliescu", "email" => "vlad.iliescu@example.com"],
            ["firstName" => "Alina", "lastName" => "Radu", "email" => "alina.radu@example.com"],
            ["firstName" => "Cristian", "lastName" => "Matei", "email" => "cristian.matei@example.com"],
            ["firstName" => "Diana", "lastName" => "Barbu", "email" => "diana.barbu@example.com"],
            ["firstName" => "Florin", "lastName" => "Voicu", "email" => "florin.voicu@example.com"],
            ["firstName" => "Simona", "lastName" => "Sava", "email" => "simona.sava@example.com"],
            ["firstName" => "Cătălin", "lastName" => "Neagu", "email" => "catalin.neagu@example.com"],
            ["firstName" => "Anca", "lastName" => "Lungu", "email" => "anca.lungu@example.com"],
            ["firstName" => "Lucian", "lastName" => "Drăghici", "email" => "lucian.draghici@example.com"],
            ["firstName" => "Laura", "lastName" => "Baciu", "email" => "laura.baciu@example.com"],
            ["firstName" => "Sorin", "lastName" => "Enache", "email" => "sorin.enache@example.com"],
            ["firstName" => "Bianca", "lastName" => "Preda", "email" => "bianca.preda@example.com"],
            ["firstName" => "Daniel", "lastName" => "Albu", "email" => "daniel.albu@example.com"],
            ["firstName" => "Oana", "lastName" => "Dobre", "email" => "oana.dobre@example.com"],
            ["firstName" => "Ionuț", "lastName" => "Cristea", "email" => "ionut.cristea@example.com"],
            ["firstName" => "Natalia", "lastName" => "Filip", "email" => "natalia.filip@example.com"],
            ["firstName" => "Tudor", "lastName" => "Marinescu", "email" => "tudor.marinescu@example.com"],
            ["firstName" => "Irina", "lastName" => "Cojocaru", "email" => "irina.cojocaru@example.com"],
            ["firstName" => "Bogdan", "lastName" => "Șerban", "email" => "bogdan.serban@example.com"],
            ["firstName" => "Monica", "lastName" => "Tănase", "email" => "monica.tanase@example.com"],
            ["firstName" => "Dragoș", "lastName" => "Andronache", "email" => "dragos.andronache@example.com"],
            ["firstName" => "Camelia", "lastName" => "Nistor", "email" => "camelia.nistor@example.com"]
        ];

        $tickets = [
            ["type" => "General Admission", "price" => 300],
            ["type" => "VIP", "price" => 500],
            ["type" => "Backstage Pass", "price" => 400],
            ["type" => "One-Day Pass", "price" => 100],
            ["type" => "Camping Pass",  "price" => 200],
        ];

        $artists = [
            ["name" => "Inna", "genre" => "Pop / Dance"],
            ["name" => "Gheorghe Zamfir", "genre" => "Folk / Instrumental"],
            ["name" => "Alexandra Stan", "genre" => "Pop / Dance"],
            ["name" => "Subcarpați", "genre" => "Hip-Hop / Folk Fusion"],
            ["name" => "Feli", "genre" => "Pop / Soul"],
            ["name" => "Delia", "genre" => "Pop / Dance"],
            ["name" => "The Motans", "genre" => "Pop / Indie"],
            ["name" => "Cargo", "genre" => "Rock"],
            ["name" => "Smiley", "genre" => "Pop / Dance"],
            ["name" => "Byron", "genre" => "Progressive Rock"],
            ["name" => "Loredana", "genre" => "Pop / Rock"],
            ["name" => "Voltaj", "genre" => "Pop Rock"],
            ["name" => "B.U.G. Mafia", "genre" => "Hip-Hop / Rap"],
            ["name" => "Mahmut Orhan", "genre" => "Electronic / Deep House"],
            ["name" => "Deliric", "genre" => "Hip-Hop"],
            ["name" => "Vama", "genre" => "Alternative Rock"],
            ["name" => "Andra", "genre" => "Pop / R&B"],
            ["name" => "Subcarpați", "genre" => "Hip-Hop / Folk Fusion"],
            ["name" => "Rise of Artificial", "genre" => "Electronic / House"],
            ["name" => "Grimus", "genre" => "Alternative Rock"]
        ];

        $editions = [
            ["festivalName" => "UNTOLD Festival", "startDate" => "2023-08-03", "endDate" => "2023-08-07"],
            ["festivalName" => "UNTOLD Festival", "startDate" => "2024-08-08", "endDate" => "2024-08-12"],
            ["festivalName" => "UNTOLD Festival", "startDate" => "2025-08-14", "endDate" => "2025-08-18"],

            ["festivalName" => "Electric Castle", "startDate" => "2023-07-13", "endDate" => "2023-07-17"],
            ["festivalName" => "Electric Castle", "startDate" => "2024-07-17", "endDate" => "2024-07-21"],

            ["festivalName" => "Summer Well", "startDate" => "2023-08-05", "endDate" => "2023-08-07"],
            ["festivalName" => "Summer Well", "startDate" => "2024-08-04", "endDate" => "2024-08-06"],

            ["festivalName" => "Neversea", "startDate" => "2023-07-06", "endDate" => "2023-07-10"],
            ["festivalName" => "Neversea", "startDate" => "2024-07-12", "endDate" => "2024-07-16"],
            ["festivalName" => "Neversea", "startDate" => "2025-07-10", "endDate" => "2025-07-14"],

            ["festivalName" => "Jazz in the Park", "startDate" => "2023-06-02", "endDate" => "2023-06-08"],
            ["festivalName" => "Jazz in the Park", "startDate" => "2024-06-01", "endDate" => "2024-06-07"],

            ["festivalName" => "Gărâna Jazz Festival", "startDate" => "2023-07-22", "endDate" => "2023-07-25"],
            ["festivalName" => "Gărâna Jazz Festival", "startDate" => "2024-07-21", "endDate" => "2024-07-24"],

            ["festivalName" => "George Enescu Festival", "startDate" => "2023-09-01", "endDate" => "2023-09-21"],
            ["festivalName" => "George Enescu Festival", "startDate" => "2025-09-01", "endDate" => "2025-09-21"],

            ["festivalName" => "Artmania Festival", "startDate" => "2023-08-02", "endDate" => "2023-08-04"],
            ["festivalName" => "Artmania Festival", "startDate" => "2024-08-01", "endDate" => "2024-08-03"],

            ["festivalName" => "Mioritmic", "startDate" => "2023-05-14", "endDate" => "2023-05-16"],
            ["festivalName" => "Mioritmic", "startDate" => "2024-05-15", "endDate" => "2024-05-17"],

            ["festivalName" => "Bucharest International Jazz Competition", "startDate" => "2023-11-11", "endDate" => "2023-11-15"],
            ["festivalName" => "Bucharest International Jazz Competition", "startDate" => "2024-11-10", "endDate" => "2024-11-14"],

            ["festivalName" => "Transilvania International Film Festival (TIFF)", "startDate" => "2023-06-10", "endDate" => "2023-06-19"],
            ["festivalName" => "Transilvania International Film Festival (TIFF)", "startDate" => "2024-06-12", "endDate" => "2024-06-21"],

            ["festivalName" => "Astra Film Festival", "startDate" => "2023-10-09", "endDate" => "2023-10-16"],
            ["festivalName" => "Astra Film Festival", "startDate" => "2024-10-10", "endDate" => "2024-10-17"],

            ["festivalName" => "Animest Festival", "startDate" => "2023-04-19", "endDate" => "2023-04-26"],
            ["festivalName" => "Animest Festival", "startDate" => "2024-04-20", "endDate" => "2024-04-27"],

            ["festivalName" => "Comedy Cluj", "startDate" => "2023-11-04", "endDate" => "2023-11-08"],
            ["festivalName" => "Comedy Cluj", "startDate" => "2024-11-05", "endDate" => "2024-11-09"],

            ["festivalName" => "Sibiu International Theatre Festival", "startDate" => "2023-06-13", "endDate" => "2023-06-27"],
            ["festivalName" => "Sibiu International Theatre Festival", "startDate" => "2024-06-14", "endDate" => "2024-06-28"],

            ["festivalName" => "Hora de la Prislop", "startDate" => "2023-06-20", "endDate" => "2023-06-22"],
            ["festivalName" => "Hora de la Prislop", "startDate" => "2024-06-21", "endDate" => "2024-06-23"],

            ["festivalName" => "Golden Stag Festival", "startDate" => "2023-08-19", "endDate" => "2023-08-24"],
            ["festivalName" => "Golden Stag Festival", "startDate" => "2024-08-20", "endDate" => "2024-08-25"],

            ["festivalName" => "Sighișoara Medieval Festival", "startDate" => "2023-07-29", "endDate" => "2023-08-02"],
            ["festivalName" => "Sighișoara Medieval Festival", "startDate" => "2024-07-30", "endDate" => "2024-08-03"],

            ["festivalName" => "Dracula Film Festival", "startDate" => "2023-10-14", "endDate" => "2023-10-19"],
            ["festivalName" => "Dracula Film Festival", "startDate" => "2024-10-15", "endDate" => "2024-10-20"],

            ["festivalName" => "Festivalul Călușului", "startDate" => "2023-05-09", "endDate" => "2023-05-11"],
            ["festivalName" => "Festivalul Călușului", "startDate" => "2024-05-10", "endDate" => "2024-05-12"],
        ];


        $stages = [];
        $i = 0;

        foreach($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setPassword("password" . $i);
            $user->setToken("token" . $i);
            $roles = ['ROLE_USER', 'ROLE_ADMIN'];
            $user->setRole($roles[array_rand($roles)]);

            $userObj[] = $user;

            $manager->persist($user);

            $userDetails = new UserDetails();
            $userDetails->setUserId($user);
            $userDetails->setFirstName($userData['firstName']);
            $userDetails->setLastName($userData['lastName']);
            $userDetails->setAge(random_int(16, 60));
            $userDetails->setRegisterDate(new \DateTime());
            $userDetails->setLastLogin(new \DateTime());

            $manager->persist($userDetails);
            $i++;
        }

        foreach($festivals as $festivalData) {
            $festival = new Festival();
            $festival->setName($festivalData['name']);
            $festival->setLocation($festivalData['location']);

            $festivalObj[] = $festival;

            $manager->persist($festival);
        }

        foreach($artists as $artistData) {
            $artist = new Artist();
            $artist->setName($artistData['name']);
            $artist->setMusicGenre($artistData['genre']);

            $artistObj[] = $artist;

            $manager->persist($artist);
        }

        foreach($tickets as $ticketData) {
            $ticket = new Ticket();
            $ticket->setPrice($ticketData['price']);
            $ticket->setType($ticketData['type']);

            $ticketObj[] = $ticket;

            $manager->persist($ticket);
        }

        for($i = 0; $i < 10; $i++){
            $stage = new Stage();
            $stage->setName('Stage' . $i);
            $stage->setDescription("Description" . $i);

            $stages[] = $stage;

            $manager->persist($stage);
        }

        for($i = 1; $i <= 60; $i++) {
            $purchase = new Purchase();
            $purchase->setUserId($userObj[array_rand($userObj)]);
            $purchase->setUsed(false);
            $purchase->setFestivalId($festivalObj[array_rand($festivalObj)]);
            $purchase->setTypeId($ticketObj[array_rand($ticketObj)]);

            $manager->persist($purchase);
        }

        foreach($editions as $editionData) {
            $edition = new Editions();
            $edition->setFestivalId($festivalObj[array_rand($festivalObj)]);
            $edition->setStartDate(new \DateTime($editionData['startDate']));
            $edition->setEndDate(new \DateTime($editionData['endDate']));

            $editionObj[] = $edition;

            $manager->persist($edition);

            $schedule = new Schedule();
            $startDateTime = new \DateTime();
            $schedule->setStartDatetime($startDateTime);

            $endDateTime = (clone $startDateTime)->add(new \DateInterval('PT1H'));
            $schedule->setEndDatetime($endDateTime);

            $schedule->setEditionId($editionObj[array_rand($editionObj)]);
            $schedule->setStageId($stages[array_rand($stages)]);
            $schedule->setArtistId($artistObj[array_rand($artistObj)]);

            $manager->persist($schedule);
        }

        for($i = 1; $i <= 50; $i++) {
            $festivalArtist = new FestivalArtist();
            $festivalArtist->setArtistId($artistObj[array_rand($artistObj)]);
            $festivalArtist->setEditionId($editionObj[array_rand($editionObj)]);

            $manager->persist($festivalArtist);
        }

        $manager->flush();
    }
}
