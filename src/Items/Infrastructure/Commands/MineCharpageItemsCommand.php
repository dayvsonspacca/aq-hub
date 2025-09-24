<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Commands;

use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, Name, ItemTags};
use Symfony\Component\Console\Input\{InputInterface, InputArgument};
use AqHub\Player\Domain\ValueObjects\Name as PlayerName;
use AqHub\Items\Application\UseCases\Weapon\AddWeapon;
use Symfony\Component\Console\Output\OutputInterface;
use AqHub\Player\Application\UseCases\AddPlayer;
use AqHub\Shared\Domain\ValueObjects\Identifier;
use Symfony\Component\Console\Command\Command;
use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Shared\Domain\Enums\TagType;
use GuzzleHttp\Client;

class MineCharpageItemsCommand extends Command
{
    private Client $client;

    public function __construct(
        private readonly AddWeapon $addWeapon,
        private readonly AddPlayer $addPlayer
    ) {
        parent::__construct();
        $this->client = new Client();
    }

    protected function configure()
    {
        $this->setName('item:mine-charpage')
            ->setDescription('Use a user charpage to mine items and persist in database')
            ->addArgument(
                'charpage',
                InputArgument::REQUIRED,
                'The AQW user nickname'
            );
    }

    private function fetchHtml(string $url): ?string
    {
        try {
            $response = $this->client->get($url);
            return (string) $response->getBody();
        } catch (\Throwable) {
            return null;
        }
    }

    private function mineDescription(string $url): ?string
    {
        $html = $this->fetchHtml($url);
        if (!$html) {
            return null;
        }

        preg_match('/<strong>\s*Description:\s*<\/strong>\s*(.*?)(?:<br\s*\/?>|<\/|\z)/is', $html, $matches);

        return isset($matches[1]) ? trim($matches[1]) : null;
    }


    private function generatePossibleUrls(string $baseName, string $itemType, bool $isAc, bool $isLegend): array
    {
        $urls   = [];
        $suffix = strtolower($itemType);
        $urls[] = $baseName;
        $urls[] = $baseName . '-' . $suffix;

        if ($isAc) {
            $urls[] = $baseName . '-ac';
            $urls[] = $baseName . '-' . $suffix . '-ac';
            $urls[] = $baseName . '-0-ac';
            $urls[] = $baseName . '-' . $suffix . '-0-ac';
        }

        if ($isLegend) {
            $urls[] = $baseName . '-legend';
        }

        $urls[] = $baseName . '-non-legend';
        $urls[] = $baseName . '-non-ac';
        $urls[] = $baseName . '-1';
        $urls[] = $baseName . '-2';
        return array_map(fn ($name) => 'http://aqwwiki.wikidot.com/' . $name, $urls);
    }

    private function slugify(string $string): string
    {
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9]+/i', '-', $string);
        $string = trim($string, '-');
        return $string;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start    = microtime(true);
        $charpage = $input->getArgument('charpage');

        $playerName = PlayerName::create($charpage);
        if ($playerName->isError()) {
            $output->writeln($playerName->getMessage());
            return Command::INVALID;
        }

        $charpage = $playerName->getData()->value;

        $html = $this->fetchHtml('https://account.aq.com/CharPage?id=' . $charpage);
        preg_match("/var\s+ccid\s*=\s*(\d+);/", $html ?? '', $matches);
        if (!isset($matches[1])) {
            $output->writeln('<fg=red;options=bold>✘ Could not found the ccid on page from charpage:</> <fg=yellow>' . $charpage . '</>');
            return Command::INVALID;
        }
        $ccid = $matches[1];
        $output->writeln('<fg=green;options=bold>✔ Found AQW user ID (ccid):</> <fg=cyan>' . $ccid . '</>');

        $output->writeln('<fg=magenta;options=bold>⚔ Saving player...</>');
        $result = $this->addPlayer->execute(Identifier::create((int) $ccid)->getData(), $playerName->getData());
        if ($result->isError()) {
            $output->writeln('<fg=red;options=bold>✘ Failed to persist player:</> <fg=yellow>' . $playerName->getData()->value . '</>');
            $output->writeln('<fg=red>↳ Reason:</> ' . $result->getMessage());
        }

        $response = $this->client->get('https://account.aq.com/CharPage/Inventory?ccid=' . $ccid);
        $jsonData = json_decode($response->getBody()->getContents(), true);

        $output->writeln('<fg=blue;options=bold>ℹ Found</> <fg=yellow>' . count($jsonData) . '</> <fg=blue;options=bold>items in</> <fg=cyan>' . $charpage . '</>.');

        $weapons = array_filter($jsonData, fn (array $object) => WeaponType::fromString($object['strType'])->isSuccess());

        $output->writeln('<fg=magenta;options=bold>⚔ Mining only weapons for now.</>');
        $output->writeln('<fg=blue;options=bold>ℹ Found</> <fg=yellow>' . count($weapons) . '</> <fg=blue;options=bold>weapons in</> <fg=cyan>' . $charpage . '</>.');
        $output->writeln('<fg=green;options=bold>▶ Starting to mine items info in AqWiki...</>');

        $totalMined = 0;
        foreach ($weapons as $object) {
            $weaponTypeResult = WeaponType::fromString($object['strType']);
            if ($weaponTypeResult->isError()) {
                $output->writeln('<fg=red>✘ ' . $weaponTypeResult->getMessage() . '</>');
                continue;
            }
            $weaponType = $weaponTypeResult->getData();

            $nameResult = Name::create($object['strName']);
            if ($nameResult->isError()) {
                $output->writeln('<fg=red>✘ ' . $nameResult->getMessage() . '</>');
                continue;
            }
            $itemName    = $nameResult->getData();
            $urlItemName = $this->slugify(html_entity_decode($itemName->value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            $urls = $this->generatePossibleUrls(
                $urlItemName,
                $weaponType->toString(),
                (bool) $object['bCoins'],
                (bool) $object['bUpgrade']
            );

            $description = null;
            foreach ($urls as $url) {
                $output->writeln('<fg=cyan>🔎 Trying</> <fg=yellow>' . $itemName->value . '</> <fg=cyan>at</> <fg=blue;options=underscore>' . $url . '</>');
                $description = $this->mineDescription($url);
                if ($description) {
                    $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    break;
                }
            }

            if (!$description) {
                $output->writeln('<fg=red;options=bold>✘ Description not found for:</> <fg=yellow>' . $itemName->value . '</>');
                $output->writeln('<fg=magenta>↳ Check base URL:</> <fg=blue;options=underscore>' . $urls[0] . '</>');
                continue;
            }

            $descResult = Description::create($description);
            if ($descResult->isError()) {
                $output->writeln('<fg=red;options=bold>✘ ' . $descResult->getMessage() . '</>');
                continue;
            }
            $description = $descResult->getData();

            $output->writeln('<fg=green;options=bold>✔ Description found:</> <fg=yellow>"' . $description->value . '"</>');

            $itemTags = new ItemTags();
            if ($object['bUpgrade']) {
                $itemTags->add(TagType::AdventureCoins);
            }

            $itemInfo = ItemInfo::create($itemName, $description, $itemTags)->getData();

            $result = $this->addWeapon->execute($itemInfo, $weaponType);
            if ($result->isError()) {
                $output->writeln('<fg=red;options=bold>✘ Failed to persist item:</> <fg=yellow>' . $itemName->value . '</>');
                $output->writeln('<fg=red>↳ Reason:</> ' . $result->getMessage());
                continue;
            }

            $output->writeln('<fg=green;options=bold>✔ Persisted:</> <fg=yellow>' . $itemName->value . '</> (<fg=magenta>' . $weaponType->toString() . '</>)');
            $totalMined++;
        }

        $output->writeln('');
        $output->writeln('<fg=green;options=bold>✔✔✔ Finished mining.</>');
        $output->writeln('<fg=blue;options=bold>ℹ Total mined successfully:</> <fg=yellow>' . $totalMined . '</>');
        $output->writeln('<fg=blue;options=bold>⏱ Process took</> <fg=green>' . round(microtime(true) - $start, 2) . 's</>.');

        return Command::SUCCESS;
    }
}
