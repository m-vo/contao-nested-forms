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
use Symfony\Component\Security\Core\Security;

class FormField
{
    /** @var Security */
    private $security;

    /** @var Connection */
    private $database;

    public function __construct(Security $security, Connection $database)
    {
        $this->security = $security;
        $this->database = $database;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function onGetForms(DataContainer $dc): array
    {
        if (null === ($user = $this->getUser()) || (!$user->isAdmin && !\is_array($user->forms))) {
            return [];
        }

        $formCandidates = $this->database->fetchAllKeyValue(
            'SELECT id, title FROM tl_form WHERE id != ? ORDER BY title',
            [$dc->activeRecord->pid]
        );

        $forms = [];

        /* @var BackendUser $user */
        foreach ($formCandidates as $id => $title) {
            if ($user->hasAccess($id, 'forms')) {
                $forms[$id] = $title.' (ID '.$id.')';
            }
        }

        return $forms;
    }

    private function getUser(): ?BackendUser
    {
        if (($user = $this->security->getUser()) instanceof BackendUser) {
            return $user;
        }

        return null;
    }
}
