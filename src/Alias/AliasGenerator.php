<?php

declare(strict_types=1);

namespace App\Alias;

use Contao\CoreBundle\Slug\Slug;
use Contao\CoreBundle\Translation\Translator;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

class AliasGenerator
{
    /** @var Connection */
    protected $connection;

    /** @var Slug */
    protected $slugGenerator;

    /** @var Translator */
    protected $translator;

    public function __construct(Connection $connection, Slug $slugGenerator, Translator $translator)
    {
        $this->connection = $connection;
        $this->slugGenerator = $slugGenerator;
        $this->translator = $translator;
    }

    public function generate(string $value, DataContainer $dc): string
    {
        $aliasExists = function (string $alias) use ($dc) {
            $statement = $this->connection->prepare('SELECT id FROM ' . $dc->table . ' WHERE alias = ? AND id <> ?');
            $statement->execute([$alias, $dc->id]);

            return \count($statement->fetchAll()) > 0;
        };

        if ($aliasExists($value)) {
            throw new \DomainException(sprintf($this->translator->trans('ERR.aliasExists'), $value));
        }

        if ('' === $value) {
            $value = $this->slugGenerator->generate($dc->activeRecord->title, $dc->activeRecord->pid, $aliasExists);
        }

        return $value;
    }
}
