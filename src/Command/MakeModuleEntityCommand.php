<?php
// src/Command/MakeModuleEntityCommand.php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:module-entity',
    description: 'Creates a new entity in a module with repository',
)]
class MakeModuleEntityCommand extends Command
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        parent::__construct();
        $this->projectDir = $projectDir;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('module', InputArgument::REQUIRED, 'Module name (e.g., Permission, User)')
            ->addArgument('entity', InputArgument::REQUIRED, 'Entity name (e.g., Permission, Role)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $moduleName = $input->getArgument('module');
        $entityName = $input->getArgument('entity');

        // Ask for fields
        $fields = [];
        $io->section('Add entity fields');

        while (true) {
            $fieldName = $io->ask('New property name (press <return> to stop adding fields)', null);

            if (!$fieldName) {
                break;
            }

            $fieldType = $io->choice(
                'Field type',
                ['string', 'integer', 'boolean', 'datetime', 'text', 'float', 'array', 'json'],
                'string'
            );

            $nullable = $io->confirm('Can this field be null?', false);
            $length = null;

            if ($fieldType === 'string') {
                $length = $io->ask('Field length', '255');
            }

            $fields[] = [
                'name' => $fieldName,
                'type' => $fieldType,
                'nullable' => $nullable,
                'length' => $length,
            ];
        }

        // Generate entity
        $this->generateEntity($moduleName, $entityName, $fields);

        // Generate repository
        $this->generateRepository($moduleName, $entityName);

        $io->success(sprintf(
            'Entity "%s" created in module "%s" with repository!',
            $entityName,
            $moduleName
        ));

        $io->note('Next: Run "php bin/console make:migration" to generate the migration');

        return Command::SUCCESS;
    }

    private function generateEntity(string $moduleName, string $entityName, array $fields): void
    {
        $namespace = "App\\Module\\{$moduleName}\\Entity";
        $repositoryNamespace = "App\\Module\\{$moduleName}\\Repository";
        $tableName = $this->camelToSnake($entityName);

        $fieldsCode = '';
        $gettersSetters = '';

        foreach ($fields as $field) {
            $fieldName = $field['name'];
            $fieldType = $field['type'];
            $nullable = $field['nullable'];
            $length = $field['length'];

            $phpType = $this->getPhpType($fieldType);
            $nullableMark = $nullable ? '?' : '';
            $defaultValue = $nullable ? ' = null' : '';

            // Field definition
            $columnAttrs = ["type: '{$fieldType}'"];
            if ($length && $fieldType === 'string') {
                $columnAttrs[] = "length: {$length}";
            }
            if ($nullable) {
                $columnAttrs[] = 'nullable: true';
            }

            $fieldsCode .= "\n    #[ORM\\Column(" . implode(', ', $columnAttrs) . ")]";
            $fieldsCode .= "\n    private {$nullableMark}{$phpType} \${$fieldName}{$defaultValue};\n";

            // Getter
            $methodName = 'get' . ucfirst($fieldName);
            if ($fieldType === 'boolean') {
                $methodName = 'is' . ucfirst($fieldName);
            }

            $gettersSetters .= "\n    public function {$methodName}(): {$nullableMark}{$phpType}";
            $gettersSetters .= "\n    {";
            $gettersSetters .= "\n        return \$this->{$fieldName};";
            $gettersSetters .= "\n    }\n";

            // Setter
            $setterParam = $nullable ? "?{$phpType}" : $phpType;
            $gettersSetters .= "\n    public function set" . ucfirst($fieldName) . "({$setterParam} \${$fieldName}): self";
            $gettersSetters .= "\n    {";
            $gettersSetters .= "\n        \$this->{$fieldName} = \${$fieldName};";
            $gettersSetters .= "\n        return \$this;";
            $gettersSetters .= "\n    }\n";
        }

        $content = '<?php' . "\n\n";
        $content .= "namespace {$namespace};\n\n";
        $content .= "use App\\Module\\Core\\Entity\\EntityInterface;\n";
        $content .= "use {$repositoryNamespace}\\{$entityName}Repository;\n";
        $content .= "use Doctrine\\ORM\\Mapping as ORM;\n";
        $content .= "use App\\Module\\Core\\Entity\\Timestampable;\n\n";
        $content .= "use App\\Module\\Core\\Entity\\UserTrackingTrait;\n\n";
        $content .= "#[ORM\\Entity(repositoryClass: {$entityName}Repository::class)]\n";
        $content .= "#[ORM\\Table(name: '{$tableName}')]\n";
        $content .= "#[ORM\HasLifecycleCallbacks] // Notify Doctrine that this Entity has callback methods (PrePersist, PreUpdate) to be automatically invoked.";
        $content .= "class {$entityName} implements EntityInterface\n";
        $content .= "{\n";
        $content .= "    use Timestampable;\n";
        $content .= "    use UserTrackingTrait;\n";
        $content .= "    #[ORM\\Id]\n";
        $content .= "    #[ORM\\GeneratedValue]\n";
        $content .= "    #[ORM\\Column(type: 'integer')]\n";
        $content .= "    private ?int \$id = null;\n";
        $content .= $fieldsCode;
        $content .= "\n    public function getId(): ?int\n";
        $content .= "    {\n";
        $content .= "        return \$this->id;\n";
        $content .= "    }\n";
        $content .= $gettersSetters;
        $content .= "}\n";

        $dir = $this->projectDir . "/src/Module/{$moduleName}/Entity";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents("{$dir}/{$entityName}.php", $content);
    }

    private function generateRepository(string $moduleName, string $entityName): void
    {
        $namespace = "App\\Module\\{$moduleName}\\Repository";
        $entityNamespace = "App\\Module\\{$moduleName}\\Entity";

        $content = '<?php' . "\n\n";
        $content .= "namespace {$namespace};\n\n";
        $content .= "use {$entityNamespace}\\{$entityName};\n";
        $content .= "use Doctrine\\Bundle\\DoctrineBundle\\Repository\\ServiceEntityRepository;\n";
        $content .= "use Doctrine\\Persistence\\ManagerRegistry;\n\n";
        $content .= "/**\n";
        $content .= " * @extends ServiceEntityRepository<{$entityName}>\n";
        $content .= " */\n";
        $content .= "class {$entityName}Repository extends ServiceEntityRepository\n";
        $content .= "{\n";
        $content .= "    public function __construct(ManagerRegistry \$registry)\n";
        $content .= "    {\n";
        $content .= "        parent::__construct(\$registry, {$entityName}::class);\n";
        $content .= "    }\n\n";
        $content .= "    //    /**\n";
        $content .= "    //     * @return {$entityName}[] Returns an array of {$entityName} objects\n";
        $content .= "    //     */\n";
        $content .= "    //    public function findByExampleField(\$value): array\n";
        $content .= "    //    {\n";
        $content .= "    //        return \$this->createQueryBuilder('e')\n";
        $content .= "    //            ->andWhere('e.exampleField = :val')\n";
        $content .= "    //            ->setParameter('val', \$value)\n";
        $content .= "    //            ->orderBy('e.id', 'ASC')\n";
        $content .= "    //            ->setMaxResults(10)\n";
        $content .= "    //            ->getQuery()\n";
        $content .= "    //            ->getResult()\n";
        $content .= "    //        ;\n";
        $content .= "    //    }\n\n";
        $content .= "    //    public function findOneBySomeField(\$value): ?{$entityName}\n";
        $content .= "    //    {\n";
        $content .= "    //        return \$this->createQueryBuilder('e')\n";
        $content .= "    //            ->andWhere('e.exampleField = :val')\n";
        $content .= "    //            ->setParameter('val', \$value)\n";
        $content .= "    //            ->getQuery()\n";
        $content .= "    //            ->getOneOrNullResult()\n";
        $content .= "    //        ;\n";
        $content .= "    //    }\n";
        $content .= "}\n";

        $dir = $this->projectDir . "/src/Module/{$moduleName}/Repository";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents("{$dir}/{$entityName}Repository.php", $content);
    }

    private function camelToSnake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    private function getPhpType(string $doctrineType): string
    {
        return match ($doctrineType) {
            'integer' => 'int',
            'boolean' => 'bool',
            'datetime' => '\DateTimeInterface',
            'float' => 'float',
            'array', 'json' => 'array',
            default => 'string',
        };
    }
}
