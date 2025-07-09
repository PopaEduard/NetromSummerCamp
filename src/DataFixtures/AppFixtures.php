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
        $users = [];
        $festivals = [];
        $tickets = [];
        $artists = [];
        $stages = [];
        $editions = [];

        for($i = 1; $i <= 20; $i++){
            $user = new User();
            //$user->setId($i);
            $user->setEmail("user".$i."@mail.com");
            $user->setPassword("password".$i);
            $user->setToken("token".$i);
            $roles = ['ROLE_USER', 'ROLE_ADMIN'];
            $user->setRole($roles[array_rand($roles)]);

            $users[] = $user;

            $manager->persist($user);

            $userDetails = new UserDetails();
            $userDetails->setUserId($users[$i - 1]);
            $userDetails->setFirstName("firstName" . $i);
            $userDetails->setLastName("lastName" . $i);
            $userDetails->setAge(random_int(18, 60));
            $userDetails->setRegisterDate(new \DateTime());
            $userDetails->setLastLogin(new \DateTime());

            $manager->persist($userDetails);

            $festival = new Festival();
            //$festival->setId($i);
            $festival->setName('Festival' . $i);
            $festival->setLocation("location" . $i);
            $festivals[] = $festival;

            $manager->persist($festival);

            $artist = new Artist();
            //$artist->setId($i);
            $artist->setName('Artist' . $i);
            $artist->setMusicGenre("MusicGenre" . ($i % 5));
            $artists[] = $artist;

            $manager->persist($artist);
        }

        for($i = 1; $i <= 5; $i++){
            $ticket = new Ticket();
            //$ticket->setId($i);
            $ticket->setPrice($i * 100);
            $ticket->setType("type".$i);

            $tickets[] = $ticket;

            $manager->persist($ticket);

            $stage = new Stage();
            //$stage->setId($i);
            $stage->setName('Stage' . $i);
            $stage->setDescription("Description" . $i);
            $stages[] = $stage;

            $manager->persist($stage);
        }

        for($i = 1; $i <= 35; $i++) {
            $purchase = new Purchase();
            $purchase->setUserId($users[array_rand($users)]);
            //$purchase->setId($i);
            $purchase->setUsed((bool)(rand(0, 1)));
            $purchase->setFestivalId($festivals[array_rand($festivals)]);
            $purchase->setTypeId($tickets[array_rand($tickets)]);

            $manager->persist($purchase);
        }

        for($i = 1; $i <= 60; $i++) {
            $edition = new Editions();
            //$edition->setId($i);
            $edition->setFestivalId($festivals[array_rand($festivals)]);
            $edition->setStartDate(new \DateTime());
            $edition->setEndDate(new \DateTime());
            $editions[] = $edition;

            $manager->persist($edition);

            $schedule = new Schedule();

            $startDateTime = new \DateTime();
            $schedule->setStartDatetime($startDateTime);

            $endDateTime = (clone $startDateTime)->add(new \DateInterval('PT1H'));
            $schedule->setEndDatetime($endDateTime);

            //$schedule->setId($i);
            $schedule->setEditionId($editions[array_rand($editions)]);
            $schedule->setStageId($stages[array_rand($stages)]);
            $schedule->setArtistId($artists[array_rand($artists)]);

            $manager->persist($schedule);
        }

        for($i = 1; $i <= 50; $i++) {
            $festivalArtist = new FestivalArtist();
            //$festivalArtist->setId($i);
            $festivalArtist->setArtistId($artists[array_rand($artists)]);
            //$festivalArtist->setStageId($stages[array_rand($stages)]);
            $festivalArtist->setEditionId($editions[array_rand($editions)]);

            $manager->persist($festivalArtist);
        }

        $manager->flush();
    }
}
