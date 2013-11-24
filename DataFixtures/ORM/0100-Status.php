<?php

namespace Httpi\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Httpi\Bundle\CoreBundle\Entity\Status;

class LoadStatusData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $statusActive = new Status();
        $statusActive->setName('Active');
        $manager->persist($statusActive);

        $statusSuspended = new Status();
        $statusSuspended->setName('Suspended');
        $manager->persist($statusSuspended);

        $statusArchived = new Status();
        $statusArchived->setName('Archived');
        $manager->persist($statusArchived);

        $statusDeleted = new Status();
        $statusDeleted->setName('Deleted');
        $manager->persist($statusDeleted);

        $manager->flush();
    }

}