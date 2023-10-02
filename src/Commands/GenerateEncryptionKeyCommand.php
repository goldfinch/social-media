<?php

namespace Goldfinch\SocialKit\Commands;

use LeKoala\Encrypt\EncryptHelper;
use Goldfinch\Taz\Services\InputOutput;
use Goldfinch\Taz\Console\GeneratorCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'generate:encryption-key')]
class GenerateEncryptionKeyCommand extends GeneratorCommand
{
    protected static $defaultName = 'generate:encryption-key';

    protected $description = 'Generate Encryption Key (lekoala/silverstripe-encrypt)';

    protected function execute($input, $output): int
    {
        // parent::execute($input, $output);

        $io = new InputOutput($input, $output);
        $io->text(EncryptHelper::generateKey());

        return Command::SUCCESS;
    }
}
