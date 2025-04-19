<?php

namespace Wertelko\DoctrineEasyJoin\Trait;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Exception;

trait JoinTrait
{
    const JOIN_TYPES = [Join::INNER_JOIN, Join::LEFT_JOIN];

    private static function getJoinAlias(string $field, ?string $alias = null): array
    {
        $parts = explode('.', $field);
        if ($alias) {
            $parts = [$alias, ...$parts];
        }
        $lastKey = array_key_last($parts);
        $target = $parts[$lastKey];
        unset($parts[$lastKey]);
        $parent = implode('_', $parts);
        return [$parent . '.' . $target, $parent . '_' . $target];
    }

    /**
     * @throws Exception
     */
    public function join(array $fields, string $joinType = Join::LEFT_JOIN, ?QueryBuilder $qb = null): QueryBuilder
    {
        $joinType = strtoupper($joinType);

        if (!($this instanceof EntityRepository)) {
            throw new Exception(JoinTrait::class . ' can only be used for ' . EntityRepository::class);
        }

        if (!in_array($joinType, self::JOIN_TYPES)) {
            throw new Exception(sprintf(
                '"%s" is not available, please use only: %s', $joinType, implode(', ', self::JOIN_TYPES)
            ));
        }

        $rootAlias = $qb?->getRootAliases()[0] ?? 'entity';

        $qb ??= $this->createQueryBuilder($rootAlias);

        foreach ($fields as $field) {
            [$join, $parentAlias] = self::getJoinAlias($field, $rootAlias);

            $qb->{strtolower($joinType) . 'Join'}($join, $parentAlias)
                ->addSelect($parentAlias);
        }

        return $qb;
    }
}