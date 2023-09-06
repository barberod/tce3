<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$username, $orgID, $displayName, $email, $category, $status, $frozen, $roles]) {
            $user = new User();
            $user->setUsername($username);
            $user->setOrgID($orgID);
            $user->setDisplayName($displayName);
            $user->setEmail($email);
            $user->setCategory($category);
            $user->setStatus($status);
            $user->setFrozen($frozen);
            $user->setRoles($roles);
            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    /**
     * @return array<array{string, int, string, string, string, int, int, array<string>}>
     */
    private function getUserData(): array
    {
        return [
            // $userData = [$username, $orgID, $displayName, $email, $category, $status, $frozen, $roles];
            ['burnhamm','100000001','Michael Burnham','michael.burnham@example.edu','staff',1,1,[User::ROLE_ADMIN]],
            ['janewayk','100000002','Kathryn Janeway','kathryn.janeway@example.edu','faculty',1,1,[User::ROLE_FACULTY]],
            ['uhuran','100000003','Nyota Uhura','nyota.uhura@example.edu','staff',1,1,[User::ROLE_STAFF]],
            ['crusherb','100000004','Beverly Crusher','beverly.crusher@example.edu','student',1,1,[User::ROLE_STUDENT]],
            ['daxj','100000005','Jadzia Dax','jadzia.dax@example.edu','applicant',1,1,[User::ROLE_GSAPP]],
            ['satoh','100000006','Hoshi Sato','hoshi.sato@example.edu','applicant',1,1,[User::ROLE_UGAPP]],
            ['hansena','100000007','Annika Hansen','annika.hansen@example.edu','member',1,1,[User::ROLE_USER]],
            ['marinerb','100000008','Beckett Mariner','beckett.mariner@example.edu','faculty',1,1,[User::ROLE_FACULTY,User::ROLE_STAFF]],
            ['ortegase','100000009','Erica Ortegas','eria.ortegas@example.edu','student',1,1,[User::ROLE_STUDENT,User::ROLE_GSAPP]],
            ['kes','100000010','Kes','kes@example.edu','member',0,1,[User::ROLE_STAFF]]
        ];
    }
}
