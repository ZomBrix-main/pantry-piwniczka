<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:db-dump',
    description: 'Eksportuje strukturę bazy danych SQLite i commituje ją do Gita.',
)]
class DbDumpCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $projectDir = dirname(__DIR__, 2);

        $io->note('Zrzucanie bazy danych do pliku dump.sql...');

        // Zrzut bazy. Używamy wbudowanego polecenia sqlite3.
        $process = Process::fromShellCommandline('sqlite3 var/data_dev.db .dump > dump.sql', $projectDir);
        $process->run();

        if (!$process->isSuccessful()) {
            $io->error('Błąd: Nie udało się wykonać zrzutu. Upewnij się, że masz zainstalowany program sqlite3 (sqlite-tools) i dodany do zmiennych systemowych PATH na Windowsie!');
            return Command::FAILURE;
        }

        // Pchanie zmian na githa
        $io->note('Wysyłanie pliku dump.sql na repozytorium...');
        $commands = [
            ['git', 'add', 'dump.sql'],
            ['git', 'commit', '-m', 'Aktualizacja stanu piwnicy: ' . date('Y-m-d H:i:s')],
            ['git', 'push', 'origin', 'main'], // Zmień na 'master', jeśli używasz starszego nazewnictwa
        ];

        foreach ($commands as $cmd) {
            $proc = new Process($cmd, $projectDir);
            $proc->run();
        }

        $io->success('Gotowe! Stan spiżarni został wyeksportowany i wysłany na serwer.');
        return Command::SUCCESS;
    }
}
