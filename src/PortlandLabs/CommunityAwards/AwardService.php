<?php /** @noinspection DuplicatedCode */
/** @noinspection PhpUnused */

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityAwards;

use Concrete\Core\Mail\Service;
use Concrete\Core\User\User;
use Doctrine\ORM\EntityManagerInterface;
use PortlandLabs\CommunityAwards\Entity\Award;
use PortlandLabs\CommunityAwards\Entity\AwardedAward;
use PortlandLabs\CommunityAwards\Entity\AwardGrant;
use PortlandLabs\CommunityAwards\Events\AfterGiveAward;
use PortlandLabs\CommunityAwards\Events\AfterGrantAward;
use PortlandLabs\CommunityAwards\Events\BeforeGiveAward;
use PortlandLabs\CommunityAwards\Events\BeforeGrantAward;
use PortlandLabs\CommunityAwards\Exceptions\AwardedAwardNotFound;
use PortlandLabs\CommunityAwards\Exceptions\AwardNotFound;
use PortlandLabs\CommunityAwards\Exceptions\GrantAwardNotFound;
use PortlandLabs\CommunityAwards\Exceptions\MailTransportError;
use PortlandLabs\CommunityAwards\Exceptions\NoAuthorization;
use PortlandLabs\CommunityAwards\Exceptions\NoUserSelected;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Exception;
use DateTime;

class AwardService
{
    protected $mailService;
    protected $eventDispatcher;
    protected $entityManager;
    protected $awardRepository;
    protected $awardedAwardRepository;
    protected $grantAwardRepository;
    protected $user;

    public function __construct(
        Service $mailService,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager
    )
    {
        $this->mailService = $mailService;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->awardRepository = $this->entityManager->getRepository(Award::class);
        $this->awardedAwardRepository = $this->entityManager->getRepository(AwardedAward::class);
        $this->grantAwardRepository = $this->entityManager->getRepository(AwardGrant::class);
        $this->user = new User();
    }

    /**
     * @return Award[]
     */
    public function getAllAwards(): iterable
    {
        return $this->awardRepository->findAll();
    }

    public function getAwardList(): array
    {
        $listItems = [];

        foreach ($this->getAllAwards() as $award) {
            $listItems[$award->getId()] = $award->getName();
        }

        return $listItems;
    }

    /**
     * @param int $id
     * @return Award
     * @throws AwardNotFound
     */
    public function getAwardById(
        int $id
    ): Award
    {
        $entry = $this->awardRepository->findOneBy([
            "id" => $id
        ]);

        if ($entry instanceof Award) {
            return $entry;
        } else {
            throw new AwardNotFound();
        }
    }

    /**
     * @param int $id
     * @return AwardGrant
     * @throws GrantAwardNotFound
     */
    public function getGrantAwardById(
        int $id
    ): AwardGrant
    {
        $entry = $this->grantAwardRepository->findOneBy([
            "id" => $id,
            "redeemedAt" => null
        ]);

        if ($entry instanceof AwardGrant) {
            return $entry;
        } else {
            throw new GrantAwardNotFound();
        }
    }

    /**
     * @param int $id
     * @return AwardedAward
     * @throws AwardedAwardNotFound
     */
    public function getAwardedAwardById(
        int $id
    ): AwardedAward
    {
        $entry = $this->awardedAwardRepository->findOneBy([
            "id" => $id
        ]);

        if ($entry instanceof AwardedAward) {
            return $entry;
        } else {
            throw new AwardedAwardNotFound();
        }
    }

    public function saveAward(
        Award $award
    ): void
    {
        if ($award->getCreatedAt() === null) {
            $award->setCreatedAt(new DateTime());
        }

        $this->entityManager->persist($award);
        $this->entityManager->flush();
    }

    public function removeAward(
        Award $award
    ): void
    {
        foreach($this->grantAwardRepository->findBy(["award" => $award]) as $grantAward) {
            $this->entityManager->remove($grantAward);
        }

        foreach($this->awardedAwardRepository->findBy(["award" => $award]) as $awardedAward) {
            $this->entityManager->remove($awardedAward);
        }

        $this->entityManager->remove($award);
        $this->entityManager->flush();
    }

    /**
     * @param Award $award
     * @param null|User $user
     * @return AwardGrant
     * @throws NoUserSelected
     * @throws MailTransportError
     */
    public function grantAward(
        Award $award,
        ?User $user
    ): AwardGrant
    {
        if (!$user instanceof User || !$user->isRegistered()) {
            throw new NoUserSelected();
        } else {
            $grantedAward = new AwardGrant();

            $userInfo = $user->getUserInfoObject();

            $grantedAward->setUser($userInfo->getEntityObject());
            $grantedAward->setAward($award);
            $grantedAward->setCreatedAt(new DateTime());

            $event = new BeforeGrantAward();
            $event->setGrantedAward($grantedAward);
            $this->eventDispatcher->dispatch("on_before_grant_award", $event);

            $this->entityManager->persist($grantedAward);
            $this->entityManager->flush();

            $this->mailService->addParameter('grantedAward', $grantedAward);
            $this->mailService->load("award_granted", "community_awards");
            $this->mailService->to($userInfo->getUserEmail());

            try {
                $this->mailService->sendMail();
            } catch (Exception $e) {
                throw new MailTransportError();
            }

            $event = new AfterGrantAward();
            $event->setGrantedAward($grantedAward);
            $this->eventDispatcher->dispatch("on_after_grant_award", $event);

            return $grantedAward;
        }
    }

    /**
     * @param Award $award
     * @param User|null $user
     * @return AwardedAward
     * @throws MailTransportError
     * @throws NoUserSelected
     */
    public function giveAward(
        Award $award,
        ?User $user
    ): AwardedAward
    {
        if (!$user instanceof User || !$user->isRegistered()) {
            throw new NoUserSelected();
        } else {
            $awardedAward = new AwardedAward();
            $userInfo = $user->getUserInfoObject();

            $awardedAward->setAward($award);
            $awardedAward->setUser($userInfo->getEntityObject());
            $awardedAward->setCreatedAt(new DateTime());

            $event = new BeforeGiveAward();
            $event->setAwardedAward($awardedAward);
            $this->eventDispatcher->dispatch("on_before_give_award", $event);

            $this->entityManager->persist($awardedAward);
            $this->entityManager->flush();

            $this->mailService->addParameter('awardedAward', $awardedAward);
            $this->mailService->load("award_given", "community_awards");
            $this->mailService->to($userInfo->getUserEmail());

            try {
                $this->mailService->sendMail();
            } catch (Exception $e) {
                throw new MailTransportError();
            }

            $event = new AfterGiveAward();
            $event->setAwardedAward($awardedAward);
            $this->eventDispatcher->dispatch("on_after_give_award", $event);

            return $awardedAward;
        }
    }

    /**
     * @param AwardGrant $grantedAward
     * @param User $user
     * @return AwardedAward
     * @throws MailTransportError
     * @throws NoUserSelected
     * @throws NoAuthorization
     */
    public function giveGrantedAward(
        AwardGrant $grantedAward,
        User $user
    ): AwardedAward
    {
        if (!$user->isRegistered()) {
            throw new NoUserSelected();
        } else {
            $awardedAward = new AwardedAward();
            $userInfo = $user->getUserInfoObject();

            if ($grantedAward->getUser()->getUserID() != $this->user->getUserID()) {
                throw new NoAuthorization();
            }

            $awardedAward->setAward($grantedAward->getAward());
            $awardedAward->setUser($userInfo->getEntityObject());
            $awardedAward->setAwardGrant($grantedAward);
            $awardedAward->setCreatedAt(new DateTime());

            $event = new BeforeGiveAward();
            $event->setAwardedAward($awardedAward);
            $this->eventDispatcher->dispatch("on_before_give_award", $event);

            $grantedAward->setRedeemedAt(new DateTime());

            $this->entityManager->persist($grantedAward);
            $this->entityManager->persist($awardedAward);
            $this->entityManager->flush();

            $this->mailService->addParameter('awardedAward', $awardedAward);
            $this->mailService->load("award_given", "community_awards");
            $this->mailService->to($userInfo->getUserEmail());

            try {
                $this->mailService->sendMail();
            } catch (Exception $e) {
                throw new MailTransportError();
            }

            $event = new AfterGiveAward();
            $event->setAwardedAward($awardedAward);
            $this->eventDispatcher->dispatch("on_after_give_award", $event);

            return $awardedAward;
        }
    }

    /**
     * @param User|null $user
     * @return AwardGrant[]
     */
    public function getAllGrantedAwardsByUser(
        ?User $user = null
    ): iterable
    {
        if ($user === null) {
            // if no user is given use the current user
            $user = $this->user;
        }

        if ($user->isRegistered()) {
            return $this->grantAwardRepository->findBy([
                "user" => $user,
                "redeemedAt" => null
            ]);
        } else {
            return [];
        }
    }

    /**
     * @param User|null $user
     * @return AwardedAward[]
     */
    public function getAllAwardedAwardsByUser(
        ?User $user = null
    ): iterable
    {
        if ($user === null) {
            // if no user is given use the current user
            $user = $this->user;
        }

        if ($user->isRegistered()) {
            return $this->awardedAwardRepository->findBy([
                "user" => $user
            ]);
        } else {
            return [];
        }
    }
}
