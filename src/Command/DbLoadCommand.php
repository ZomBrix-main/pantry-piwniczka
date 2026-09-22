<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:db-load',
    description: 'Pobiera zmiany z Gita i nadpisuje lokalną bazę danych ze zrzutu.',
)]
class DbLoadCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $projectDir = dirname(__DIR__, 2);

        $io->note('Pobieranie najnowszych plików z GitHuba...');

        $gitProcess = new Process(['git', 'pull', 'origin', 'main'], $projectDir);
        $gitProcess->run();

        // Jeśli plik dump.sql nie istnieje po pobraniu, to coś poszło nie tak
        if (!file_exists($projectDir . '/dump.sql')) {
            $io->error('Nie znaleziono pliku dump.sql w projekcie!');
            return Command::FAILURE;
        }

        $io->note('Odtwarzanie bazy danych...');

        // Najbezpieczniejsza technika: usuwamy starą bazę i wpisujemy zawartość od nowa
        $dbFile = $projectDir . '/var/data_dev.db';
        if (file_exists($dbFile)) {
            unlink($dbFile);
        }

        // Różne komendy do pipe'owania plików na Windows i na Linuksie/Telefonie
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cmd = 'type dump.sql | sqlite3 var/data_dev.db';
        } else {
            $cmd = 'sqlite3 var/data_dev.db < dump.sql';
        }

        $process = Process::fromShellCommandline($cmd, $projectDir);
        $process->run();

        if (!$process->isSuccessful()) {
            $io->error('Błąd: Odtwarzanie bazy nie powiodło się. Czy masz zainstalowane sqlite3?');
            return Command::FAILURE;
        }

        $io->success('Pełen sukces! Baza danych została pomyślnie zsynchronizowana i na komputerze widać dokładnie to samo co na telefonie.');
        return Command::SUCCESS;
    }
}
