<?php

declare(strict_types=1);

/*
 * Nested forms bundle for Contao Open Source CMS
 *
 * @copyright  Copyright (c) $date, Moritz Vondano
 * @license MIT
 */

namespace Mvo\ContaoNestedForms\EventListener\DataContainer;

use Contao\BackendUser;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class FormField
{
    /** @var TokenInterface */
    private $token;

    /** @var Connection */
    private $database;

    /**
     * FormField constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param Connection            $database
     */
    public function __construct(TokenStorageInterface $tokenStorage, Connection $database)
    {
        $this->token = $tokenStorage->getToken();
        $this->database = $database;
    }

    /**
     * @param DataContainer $dc
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return array
     */
    public function onGetForms(DataContainer $dc): array
    {
        /** @var BackendUser $user */
        $user = $this->token->getUser();

        if (!$user || (!$user->isAdmin && !\is_array($user->forms))) {
            return [];
        }

        $formCandidates = $this->database
            ->executeQuery('SELECT id, title FROM tl_form WHERE id != ? ORDER BY title', [$dc->activeRecord->pid])
            ->fetchAll(\PDO::FETCH_KEY_PAIR);

        $forms = [];
        foreach ($formCandidates as $id => $title) {
            if ($user->hasAccess($id, 'forms')) {
                $forms[$id] = $title.' (ID '.$id.')';
            }
        }

        return $forms;
    }
}
