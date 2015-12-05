<?php

namespace Electro2\MysqlPostfixAdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Electro2\MysqlPostfixAdminBundle\Entity\Alias;
use Electro2\MysqlPostfixAdminBundle\Entity\Domain;
use ElEctro2\MysqlPostfixAdminBundle\Entity\Mailbox;
use Electro2\MysqlPostfixAdminBundle\Repository\AliasRepository;

class MysqlPostfixAdminService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Domain $domain
     */
    public function saveDomain(Domain $domain)
    {
        $this->em->persist($domain);
        $this->em->flush($domain);
    }

    /**
     * @param Alias $alias
     */
    public function saveAlias(Alias $alias)
    {
        $this->em->persist($alias);
        $this->em->flush($alias);
    }

    /**
     * @param Alias $alias
     */
    public function deleteAlias(Alias $alias)
    {
        $this->em->remove($alias);
        $this->em->flush($alias);
    }

    /**
     * @param Mailbox $mailbox
     */
    public function saveMailbox(Mailbox $mailbox)
    {
        $this->em->persist($mailbox);
        $this->em->flush($mailbox);
    }

    /**
     * @param Mailbox $mailbox
     */
    public function deleteMailbox(Mailbox $mailbox)
    {
        $this->em->remove($mailbox);
        $this->em->flush($mailbox);
    }

    /**
     * @param string $domain
     * @return \Electro2\MysqlPostfixAdminBundle\Entity\Mailbox[]
     */
    public function getMailboxesByDomain($domain)
    {
        return $this->getRepositoryFor('Mailbox')->findBy(
            ['domain' => $domain],
            ['username' => 'ASC']
        );
    }

    /**
     * @param string $domain
     * @param bool $ignoreMailboxes set to true to ignore aliases with same address as goto
     * @return \Electro2\MysqlPostfixAdminBundle\Entity\Alias[]
     */
    public function getAliasesByDomain($domain, $ignoreMailboxes = false)
    {
        /** @var AliasRepository $aliasRepo */
        $aliasRepo = $this->getRepositoryFor('Alias');

        return $aliasRepo->findByDomain(
            $domain,
            $ignoreMailboxes
        );
    }

    /**
     * @param string $entity
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepositoryFor($entity)
    {
        return $this->em->getRepository('\\Electro2\\MysqlPostfixAdminBundle\\Entity\\' . $entity);
    }
}
