<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\Argument;
use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Container;
use Dagger\Directory;

use function Dagger\dag;

#[DaggerObject]
readonly class SymfonyCli
{
    #[DaggerFunction]
    public function __construct(
        #[DefaultPath('.')] private Directory $source,
        #[Argument('PHP version')] private string $phpVersion = '8.4',
        #[Argument('Symfony CLI version')] private string $cliVersion = 'v5.10',
    ) {
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 cache-clear --env=dev --no-warmup --no-optional-warmers
    // dagger call cache-clear --env=dev --no-warmup --no-optional-warmers
    #[DaggerFunction('Calls the cache:clear command')]
    public function cacheClear(
        #[Argument('Environment')] ?string $env = null,
        #[Argument('No warmup')] bool $noWarmup = false,
        #[Argument('No optional warmers (faster)')] bool $noOptionalWarmers = false,
    ): string {
        return $this
            ->console('cache:clear'
                . ($env ? ' --env=' . $env : '')
                . ($noWarmup ? ' --no-warmup' : '')
                . ($noOptionalWarmers ? ' --no-optional-warmers' : '')
            )
        ;
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 lint-container
    // dagger call lint-container
    #[DaggerFunction('Calls the lint:container command')]
    public function lintContainer(): string {
        return $this->console('lint:container');
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 lint-twig
    // dagger call lint-twig
    #[DaggerFunction('Calls the lint:twig command')]
    public function lintTwig(
        #[Argument('Path')] string $path = '',
    ): string {
        return $this->console(trim('lint:twig ' . $path));
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 lint-yaml
    // dagger call lint-yaml
    #[DaggerFunction('Calls the lint:yaml command')]
    public function lintYaml(
        #[Argument('Path')] string $path = './config',
    ): string {
        return $this->console('lint:yaml ' . $path);
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 lint-xliff
    // dagger call lint-xliff
    #[DaggerFunction('Calls the lint:xliff command')]
    public function lintXliff(
        #[Argument('Path')] string $path = './translations',
    ): string {
        return $this->console('lint:xliff ' . $path);
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 importmap-install
    // dagger call importmap-install
    #[DaggerFunction('Calls the importmap:install command')]
    public function importmapInstall(): string {
        return $this->console('importmap:install');
    }

    // Those functions need to know about the Database of the app

//    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 doctrine-migrations-migrate
//    // dagger call doctrine-migrations-migrate
//    #[DaggerFunction('Calls the doctrine:migrations:migrate command')]
//    public function doctrineMigrationsMigrate(
//        #[Argument('No interaction')] bool $noInteraction = false,
//    ): string {
//        return $this
//            ->console('doctrine:migrations:migrate'
//                . ($noInteraction ? ' --no-interaction' : '')
//            )
//        ;
//    }
//
//    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 doctrine-schema-validate
//    // dagger call doctrine-schema-validate
//    #[DaggerFunction('Calls the doctrine:schema:validate command')]
//    public function doctrineSchemaValidate(): string {
//        return $this->console('doctrine:schema:validate');
//    }
//
//    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 doctrine-fixtures-load
//    // dagger call doctrine-fixtures-load
//    #[DaggerFunction('Calls the doctrine:fixtures:load command')]
//    public function doctrineFixturesLoad(
//        #[Argument('No interaction')] bool $noInteraction = false,
//    ): string {
//        return $this
//            ->console('doctrine:fixtures:load'
//                . ($noInteraction ? ' --no-interaction' : '')
//            )
//        ;
//    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 translation-pull
    // dagger call translation-pull
    #[DaggerFunction('Calls the translation:pull command')]
    public function translationPull(
        #[Argument('Provider')] ?string $provider = null,
        #[Argument('Format')] ?string $format = null,
        #[Argument('Force')] bool $force = false,
        #[Argument('Intl-icu')] bool $intlIcu = false,
        // domains
        // locales
    ): string {
        return $this->console('translation:pull '
            . ($provider ? : '')
            . ($format ? ' --format=' . $format : '')
            . ($force ? ' --force' : '')
            . ($intlIcu ? ' --intl-icu' : '')
        );
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 tailwind-build
    // dagger call tailwind-build
    #[DaggerFunction('Calls the tailwind:build command')]
    public function tailwindBuild(
        #[Argument('Watch')] bool $watch = false,
    ): string {
        return $this
            ->console('tailwind:build'
                . ($watch ? ' --watch' : '')
            )
        ;
    }

    // symfony security:check
    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 symfony-security-check
    // dagger call symfony-security-check
    #[DaggerFunction('Calls the Symfony security:check command')]
    public function symfonySecurityCheck(): string {
        return $this->symfony('security:check');
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 composer-audit
    // dagger call composer-audit
    #[DaggerFunction('Calls the Composer audit command')]
    public function composerAudit(): string {
        return $this->composer('audit');
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 composer-validate
    // dagger call composer-validate
    #[DaggerFunction('Calls the Composer validate command')]
    public function composerValidate(): string {
        return $this->composer('validate');
    }

    ##################
    # Base functions #
    ##################

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 php --cmd='-v'
    // dagger call php --cmd='-v'
    #[DaggerFunction('Calls PHP via Symfony binary')]
    public function php(
        #[Argument('Composer command')] string $cmd = '-v',
    ): string {
        return $this
            ->_php($cmd)
            ->stdout()
        ;
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 composer --cmd=list
    // dagger call composer --cmd=list
    #[DaggerFunction('Calls Composer via Symfony binary')]
    public function composer(
        #[Argument('Composer command')] string $cmd = 'list',
    ): string {
        return $this
            ->_composer($cmd)
            ->stdout()
        ;
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 console --cmd=list
    // dagger call console --cmd=list
    #[DaggerFunction('Calls the console of Symfony application')]
    public function console(
        #[Argument('Console command')] string $cmd = 'list',
    ): string {
        return $this
            ->_console($cmd)
            ->stdout()
        ;
    }

    // dagger call --source=. --phpVersion=8.4 --cliVersion=v5.10 symfony --cmd=help
    // dagger call symfony --cmd=help
    #[DaggerFunction('Calls the Symfony binary')]
    public function symfony(
        #[Argument('Symfony command')] string $cmd = 'help',
    ): string {
        return $this
            ->_symfony($cmd)
            ->stdout()
        ;
    }

    private function _composer(string $cmd): Container {
        return $this
            ->container()
            ->withExec(['symfony', 'composer', ...explode(' ', $cmd)])
        ;
    }

    ###########
    # Helpers #
    ###########

    private function _console(string $cmd): Container {
        return $this
            ->container()
            ->withExec(['symfony', 'console', ...explode(' ', $cmd)])
        ;
    }

    private function _php(string $cmd): Container {
        return $this
            ->container()
            ->withExec(['symfony', 'php', ...explode(' ', $cmd)])
        ;
    }

    private function _symfony(string $cmd): Container {
        return $this
            ->container()
            ->withExec(['symfony', ...explode(' ', $cmd)])
        ;
    }

    private function container(): Container
    {
        $symfonyCliContainer = dag()
            ->container()
            ->from('ghcr.io/symfony-cli/symfony-cli:' . $this->cliVersion)
        ;

        $composerContainer = dag()
            ->container()
            ->from('composer:2')
        ;

        return dag()
            ->container()
            ->from('php:' . $this->phpVersion)

            ->withExec(['apt-get', 'update'])
            ->withExec([
                'apt-get', 'install', '-y', '--no-install-recommends',
                'libxslt-dev',
            ])
            ->withExec(['docker-php-ext-install', 'xsl'])
            ->withExec(['rm', '-rf', '/var/lib/apt/lists/*'])

            ->withFile('/usr/local/bin/symfony', $symfonyCliContainer->file('/usr/local/bin/symfony'))
            ->withFile('/usr/bin/composer', $composerContainer->file('/usr/bin/composer'))

            ->withMountedDirectory('/app', $this->source)
            ->withWorkdir('/app')
        ;
    }
}
