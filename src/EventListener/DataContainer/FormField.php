<?php

declare(strict_types=1);

/*
 * Nested Forms Bundle for Contao Open Source CMS
 *
 * @copyright  Moritz Vondano
 * @license    MIT
 * @link       https://github.com/m-vo/contao-nested-forms
 *
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
        $this->token    = $tokenStorage->getToken();
        $this->database = $database;
    }

    /**
     * @param DataContainer $dc
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
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
                $forms[$id] = $title . ' (ID ' . $id . ')';
            }
        }

        return $forms;
    }
}