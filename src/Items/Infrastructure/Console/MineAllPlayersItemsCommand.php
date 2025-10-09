<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Console;

use AqHub\Items\Application\UseCases\Armor\AddArmor;
use AqHub\Items\Application\UseCases\Cape\AddCape;
use AqHub\Items\Application\UseCases\Helmet\AddHelmet;
use AqHub\Items\Application\UseCases\Weapon\AddWeapon;
use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Items\Infrastructure\Http\Scrappers\AqWikiScrapper;
use AqHub\Player\Application\UseCases\{FindAllPlayers, MarkAsMined};
use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Player\Infrastructure\Http\Scrappers\CharpageScrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MineAllPlayersItemsCommand extends Command
{
    public function __construct(
        private readonly FindAllPlayers $findAllPlayers,
        private readonly AddWeapon $addWeapon,
        private readonly AddArmor $addArmor,
        private readonly AddHelmet $addHelmet,
        private readonly AddCape $addCape,
        private readonly MarkAsMined $markAsMined
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('item:mine-all-players')
            ->setDescription('Mine items from all players in the repository and persist in database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = microtime(true);

        $players = $this->findAllPlayers->execute(new PlayerFilter(mined: false));

        if ($players->isError()) {
            $output->writeln('<fg=red;options=bold>✘ Failed to retrieve players:</> <fg=yellow>' . $players->getMessage() . '</>');
            return Command::FAILURE;
        }

        $players    = $players->getData();
        $totalMined = 0;

        foreach ($players as $player) {
            $output->writeln("<fg=magenta;options=bold>▶ Mining items for player:</> <fg=cyan>{$player->name->value}</>");

            $itemsByType = CharpageScrapper::findPlayerItemsNameByType($player->identifier);

            if ($itemsByType->isError()) {
                $output->writeln("<fg=red;options=bold>✘ Failed to retrieve items for player {$player->name->value}:</> <fg=yellow>" . $itemsByType->getMessage() . '</>');
                continue;
            }

            $itemsByType = $itemsByType->getData();

            foreach ($itemsByType as $type => $items) {
                $output->writeln('<fg=blue;options=bold>ℹ Found ' . count($items) . " item(s) of type <fg=yellow>{$type}</> for player <fg=cyan>{$player->name->value}</>:</>");

                $allowedTypes = array_merge(['Armor', 'Helm', 'Cape'], array_map(fn ($type) => $type->toString(), WeaponType::cases()));

                if (!in_array($type, $allowedTypes, true)) {
                    $output->writeln("<fg=yellow>✘ Skiping: {$type}</>");
                    continue;
                }

                foreach ($items as $itemName) {
                    $output->writeln("<fg=cyan>🔎 Mining item:</> <fg=yellow>{$itemName->value}</> (<fg=magenta>{$type}</>)");

                    $itemData = AqWikiScrapper::findItemData($itemName);

                    if ($itemData->isError()) {
                        $output->writeln("<fg=red>✘ Failed to retrieve Wiki data for item {$itemName->value}:</> <fg=yellow>" . $itemData->getMessage() . '</>');
                        continue;
                    }

                    $itemData = $itemData->getData();

                    $itemInfo = ItemInfo::create($itemData->name, $itemData->description, $itemData->tags, $itemData->rarity)->getData();

                    if ($type === 'Armor') {
                        $persistResult = $this->addArmor->execute($itemInfo);
                    } elseif ($type === 'Helm') {
                        $persistResult = $this->addHelmet->execute($itemInfo);
                    } elseif ($type === 'Cape') {
                        $persistResult = $this->addCape->execute($itemInfo, $itemData->canAccessBank ?? false);
                    } else {
                        $weaponTypeResult = WeaponType::fromString($type);
                        if ($weaponTypeResult->isError()) {
                            $output->writeln("<fg=red>✘ Unknown weapon type: {$type}</>");
                            continue;
                        }
                        $persistResult = $this->addWeapon->execute($itemInfo, $weaponTypeResult->getData());
                    }

                    if ($persistResult->isError()) {
                        $output->writeln("<fg=red>✘ Failed to persist item {$itemName->value}:</> <fg=yellow>" . $persistResult->getMessage() . '</>');
                        continue;
                    }

                    $output->writeln("<fg=green>✔ Persisted item:</> <fg=yellow>{$itemName->value}</> (<fg=magenta>{$type}</>)");
                    $totalMined++;
                }
            }

            $this->markAsMined->execute($player->name);
            $output->writeln("<fg=green;options=bold>✔ {$player->name->value} marked as mined.</>");
            $output->writeln('');
        }

        $output->writeln('<fg=green;options=bold>✔ Finished mining all players.</>');
        $output->writeln("<fg=blue>Total items mined: <fg=yellow>{$totalMined}</></>");
        $output->writeln('<fg=blue;options=bold>⏱ Process took</> <fg=green>' . round(microtime(true) - $start, 2) . 's</>.');

        return Command::SUCCESS;
    }
}
