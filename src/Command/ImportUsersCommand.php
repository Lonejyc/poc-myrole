<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:import-users',
    description: 'Import des utilisateurs depuis un fichier CSV',
)]
class ImportUsersCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('csv-file', InputArgument::REQUIRED, 'Chemin du fichier CSV');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvPath = $input->getArgument('csv-file');

        if (!file_exists($csvPath)) {
            $io->error('Fichier CSV introuvable');
            return Command::FAILURE;
        }

        $file = fopen($csvPath, 'r');
        // Skip header
        fgetcsv($file, 0, ',');

        while (($data = fgetcsv($file, 0, ',')) !== false) {
            $user = new User();
            $user->setLastname($data[0])
                ->setFirstname($data[1])
                ->setAge((int)$data[2])
                ->setSex($data[3] === 'F')
                ->setEmail($data[4])
                ->setAddress($data[5])
                ->setNss(preg_replace('/\s+/', '', $data[6]))
                ->setPhoneNumber(preg_replace('/\s+/', '', $data[7]))
                ->setLicence($data[8] === 'oui')
                ->setIntermittent($data[9] === 'oui')
                ->setRoles(["ROLE_EMPLOYEE"])
                ->setVerified(true);

            // Set a default password (you should change this in production)
            $hashedPassword = $this->hasher->hashPassword($user, '!ChangeMe!');
            $user->setPassword($hashedPassword);

            $this->em->persist($user);
        }

        fclose($file);
        $this->em->flush();

        $io->success('Utilisateurs importés avec succès');
        return Command::SUCCESS;
    }
}
