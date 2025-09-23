<?php

declare(strict_types=1);

namespace AqWiki\Items\Infrastructure\Commands;

use AqWiki\Items\Domain\ValueObjects\{ItemInfo, ItemTags, Description, Name};
use Symfony\Component\Console\Question\{Question, ChoiceQuestion};
use AqWiki\Items\Domain\Repositories\WeaponRepository;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;
use AqWiki\Items\Domain\Enums\WeaponType;
use AqWiki\Shared\Domain\Enums\TagType;
use RuntimeException;

class AddItemCommand extends Command
{
    public function __construct(
        private readonly WeaponRepository $weaponRepository
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('item:add')
            ->setDescription('Persist a new item in database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();

        $itemTypeQuestion = new ChoiceQuestion(
            'Choice the item type (default: Weapon)',
            ['Weapon'],
            0
        );

        $itemType = $helper->ask($input, $output, $itemTypeQuestion);
        $itemInfo = $this->buildItemInfo($helper, $input, $output);
        
        $output->writeln('You selected: <info>' . $itemType . '</info>');
        $output->writeln('<comment>-== Item info ==-</comment>');
        $output->writeln('Name: <info>' . $itemInfo->getName() . '</info>');
        $output->writeln('Description: <info>' . $itemInfo->getDescription() . '</info>');
        $output->writeln('Tags: <info>' . implode(', ', $itemInfo->getTags()->toArray()) . '</info>');
        $output->writeln('<comment>-== Item info ==-</comment>');
        
        if ($itemType === 'Weapon') {
            $weaponTypeChoices = [];
            foreach (WeaponType::cases() as $weaponType) {
                $weaponTypeChoices[$weaponType->toString()] = $weaponType->toString();
            }
            $weaponTypeQuestion = new ChoiceQuestion(
                'Choice the weapon type (default: Axe)',
                $weaponTypeChoices,
                'Axe'
            );
            $weaponTypeQuestion->setValidator(function (string $answer) {
                $result = WeaponType::fromString($answer);
                if ($result->isError()) {
                    throw new RuntimeException($result->getMessage());
                }

                return $result->getData();
            });

            $weaponType = $helper->ask($input, $output, $weaponTypeQuestion);

            $this->weaponRepository->persist($itemInfo, $weaponType);
        }

        return Command::SUCCESS;
    }

    private function buildItemInfo(QuestionHelper $helper, InputInterface $input, OutputInterface $output): ItemInfo
    {
        $question = new Question('Please enter the name of the item:' . PHP_EOL, '');
        $question->setValidator(function (string $answer) {
            $result = Name::create($answer);
            if ($result->isError()) {
                throw new RuntimeException($result->getMessage());
            }

            return $result->getData();
        });

        /** @var Name $itemName */
        $itemName = $helper->ask($input, $output, $question);

        $question = new Question('Please enter the description of the item:' . PHP_EOL, '');
        $question->setValidator(function (string $answer) {
            $result = Description::create($answer);
            if ($result->isError()) {
                throw new RuntimeException($result->getMessage());
            }

            return $result->getData();
        });

        /** @var Description $itemDescription */
        $itemDescription = $helper->ask($input, $output, $question);

        $tagChoices = array_map(fn(TagType $tag) => $tag->toString(), TagType::cases());

        $question = new ChoiceQuestion(
            'Please select one or more tags (comma separated):',
            $tagChoices
        );
        $question->setMultiselect(true);

        /** @var string[] $selectedTags */
        $selectedTags = $helper->ask($input, $output, $question);

        $itemTags = new ItemTags();
        foreach ($selectedTags as $tagName) {
            $result = TagType::fromString($tagName);
            if ($result->isError()) {
                $output->writeln('<error>' . $result->getMessage() . '</error>');
                continue;
            }

            $itemTags->add($result->getData());
        }

        $itemInfo = ItemInfo::create($itemName, $itemDescription, $itemTags)->getData();

        return $itemInfo;
    }
}
