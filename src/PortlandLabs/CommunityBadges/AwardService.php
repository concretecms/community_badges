<?php /** @noinspection DuplicatedCode */
/** @noinspection PhpUnused */

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges;

use Concrete\Core\Events\EventDispatcher;
use Concrete\Core\Mail\Service;
use Concrete\Core\User\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PortlandLabs\CommunityBadges\Entity\Achievement;
use PortlandLabs\CommunityBadges\Entity\Award;
use PortlandLabs\CommunityBadges\Entity\UserBadge;
use PortlandLabs\CommunityBadges\Entity\AwardGrant;
use PortlandLabs\CommunityBadges\Entity\Badge;
use PortlandLabs\CommunityBadges\Enumerations\BadgeTypes;
use PortlandLabs\CommunityBadges\Events\AfterGiveAchievement;
use PortlandLabs\CommunityBadges\Events\AfterGiveAward;
use PortlandLabs\CommunityBadges\Events\AfterGrantAward;
use PortlandLabs\CommunityBadges\Events\BeforeGiveAchievement;
use PortlandLabs\CommunityBadges\Events\BeforeGiveAward;
use PortlandLabs\CommunityBadges\Events\BeforeGrantAward;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\AwardedBadgeNotFound;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;
use PortlandLabs\CommunityBadges\Exceptions\GrantBadgeNotFound;
use PortlandLabs\CommunityBadges\Exceptions\InvalidBadgeType;
use PortlandLabs\CommunityBadges\Exceptions\InvalidSelfAssignment;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoAuthorization;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;
use Exception;
use DateTime;

class AwardService
{
    protected $mailService;
    protected $eventDispatcher;
    protected $entityManager;
    protected $awardRepository;
    protected $userBadgeRepository;
    protected $grantAwardRepository;
    protected $achievementRepository;
    protected $badgeRepository;
    protected $user;
    protected $connection;

    public function __construct(
        Service $mailService,
        EventDispatcher $eventDispatcher,
        EntityManagerInterface $entityManager,
        Connection $connection
    )
    {
        $this->mailService = $mailService;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->badgeRepository = $this->entityManager->getRepository(Badge::class);
        $this->awardRepository = $this->entityManager->getRepository(Award::class);
        $this->achievementRepository = $this->entityManager->getRepository(Achievement::class);
        $this->userBadgeRepository = $this->entityManager->getRepository(UserBadge::class);
        $this->grantAwardRepository = $this->entityManager->getRepository(AwardGrant::class);
        $this->user = new User();
        $this->connection = $connection;
    }

    /**
     * @return Award[]
     */
    public function getAllAwards(): iterable
    {
        return $this->awardRepository->findAll();
    }

    /**
     * @return Badge[]
     */
    public function getAllBadges(): iterable
    {
        return $this->badgeRepository->findAll();
    }

    /**
     * @return Achievement[]
     */
    public function getAllAchievements(): iterable
    {
        $args = func_get_args();
        if (count($args) > 0) {
            return $this->achievementRepository->findBy(...$args);
        } else {
            return $this->achievementRepository->findBy([], ['name' => 'asc']);
        }
    }

    public function getAwardList(): array
    {
        $listItems = [];

        foreach ($this->getAllAwards() as $award) {
            $listItems[$award->getId()] = $award->getName();
        }

        return $listItems;
    }

    public function getAchievementList(): array
    {
        $listItems = [];

        foreach ($this->getAllAchievements() as $award) {
            $listItems[$award->getId()] = $award->getName();
        }

        return $listItems;
    }

    public function getBadgeList(): array
    {
        $listItems = [];

        foreach ($this->getAllBadges() as $award) {
            $listItems[$award->getId()] = $award->getName();
        }

        return $listItems;
    }

    public function getBadgeTypeList()
    {
        return [
            BadgeTypes::ACHIEVEMENT => t("Achievement"),
            BadgeTypes::AWARD => t("Award")
        ];
    }

    /**
     * @param int $id
     * @return Award
     * @throws BadgeNotFound
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
            throw new BadgeNotFound();
        }
    }

    /**
     * @param string $handle
     * @return Badge
     * @throws BadgeNotFound
     */
    public function getBadgeByHandle(
        string $handle
    ): Badge
    {
        $entry = $this->badgeRepository->findOneBy([
            "handle" => $handle
        ]);

        if ($entry instanceof Badge) {
            return $entry;
        } else {
            throw new BadgeNotFound();
        }
    }

    /**
     * @param int $id
     * @return Badge
     * @throws BadgeNotFound
     */
    public function getBadgeById(
        int $id
    ): Badge
    {
        $entry = $this->badgeRepository->findOneBy([
            "id" => $id
        ]);

        if ($entry instanceof Badge) {
            return $entry;
        } else {
            throw new BadgeNotFound();
        }
    }

    /**
     * @param string $name
     * @return Badge
     * @throws BadgeNotFound
     */
    public function getBadgeByName(
        string $name
    )
    {
        $entry = $this->badgeRepository->findOneBy([
            "name" => $name
        ]);

        if ($entry instanceof Badge) {
            return $entry;
        } else {
            throw new BadgeNotFound();
        }
    }

    /**
     * @param int $id
     * @return AwardGrant
     * @throws GrantBadgeNotFound
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
            throw new GrantBadgeNotFound();
        }
    }

    /**
     * @param int $id
     * @return UserBadge
     * @throws AwardedBadgeNotFound
     */
    public function getUserBadgeById(
        int $id
    ): UserBadge
    {
        $entry = $this->userBadgeRepository->findOneBy([
            "id" => $id
        ]);

        if ($entry instanceof UserBadge) {
            return $entry;
        } else {
            throw new AwardedBadgeNotFound();
        }
    }

    public function saveBadge(
        $badge
    ): void
    {
        if ($badge instanceof Award) {
            $this->saveAward($badge);
        } else {
            $this->saveAchievement($badge);
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

    public function saveAchievement(
        Achievement $achievement
    ): void
    {
        if ($achievement->getCreatedAt() === null) {
            $achievement->setCreatedAt(new DateTime());
        }

        $this->entityManager->persist($achievement);
        $this->entityManager->flush();
    }

    public function removeBadge(
        Badge $badge
    ): void
    {
        if ($badge instanceof Award) {
            foreach ($this->grantAwardRepository->findBy(["award" => $badge]) as $grantAward) {
                $this->entityManager->remove($grantAward);
            }
        }

        foreach ($this->userBadgeRepository->findBy(["badge" => $badge]) as $userBadge) {
            $this->entityManager->remove($userBadge);
        }

        $this->entityManager->remove($badge);
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
            $this->mailService->load("award_granted", "community_badges");
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
     * @param Badge $badge
     * @param User|null $user
     * @return UserBadge
     * @throws MailTransportError
     * @throws NoUserSelected
     * @throws InvalidBadgeType
     * @throws AchievementAlreadyExists
     */
    public function giveBadge(
        Badge $badge,
        ?User $user
    ): UserBadge
    {
        if ($badge instanceof Achievement) {
            return $this->giveAchievement($badge, $user);
        } else if ($badge instanceof Award) {
            return $this->giveAward($badge, $user);
        } else {
            throw new InvalidBadgeType();
        }
    }

    /**
     * @param Achievement $achievement
     * @param User|null $user
     * @return UserBadge
     * @throws MailTransportError
     * @throws NoUserSelected
     * @throws AchievementAlreadyExists
     */
    public function giveAchievement(
        Achievement $achievement,
        ?User $user
    ): UserBadge
    {
        if (!$user instanceof User || !$user->isRegistered()) {
            throw new NoUserSelected();
        } else {
            $userBadge = new UserBadge();
            $userInfo = $user->getUserInfoObject();

            $queryBuilder = $this->connection->createQueryBuilder();

            try {
                $countOfBadges = (int)$queryBuilder
                    ->select("COUNT(*)")
                    ->from("UserBadge", "ub")
                    ->where("ub.uID = :uID")
                    ->andWhere("ub.badgeId = :badgeId")
                    ->setParameter(":uID", $userInfo->getUserID())
                    ->setParameter(":badgeId", $achievement->getId())
                    ->execute()
                    ->fetchColumn();
            } catch (Exception $e) {
                $countOfBadges = 0;
            }

            if ($countOfBadges > 0) {
                throw new AchievementAlreadyExists();
            }

            $userBadge->setBadge($achievement);
            $userBadge->setUser($userInfo->getEntityObject());
            $userBadge->setCreatedAt(new DateTime());

            $event = new BeforeGiveAchievement();
            $event->setUserBadge($userBadge);
            $this->eventDispatcher->dispatch("on_before_give_achievement", $event);

            $this->entityManager->persist($userBadge);
            $this->entityManager->flush();

            $this->mailService->addParameter('userBadge', $userBadge);
            $this->mailService->load("achievement_given", "community_badges");
            $this->mailService->to($userInfo->getUserEmail());

            try {
                $this->mailService->sendMail();
            } catch (Exception $e) {
                throw new MailTransportError();
            }

            $event = new AfterGiveAchievement();
            $event->setUserBadge($userBadge);
            $this->eventDispatcher->dispatch("on_after_give_achievement", $event);

            return $userBadge;
        }
    }

    /**
     * @param Award $award
     * @param User|null $user
     * @return UserBadge
     * @throws MailTransportError
     * @throws NoUserSelected
     */
    public function giveAward(
        Award $award,
        ?User $user
    ): UserBadge
    {
        if (!$user instanceof User || !$user->isRegistered()) {
            throw new NoUserSelected();
        } else {
            $userBadge = new UserBadge();
            $userInfo = $user->getUserInfoObject();

            $userBadge->setBadge($award);
            $userBadge->setUser($userInfo->getEntityObject());
            $userBadge->setCreatedAt(new DateTime());

            $event = new BeforeGiveAward();
            $event->setUserBadge($userBadge);
            $this->eventDispatcher->dispatch("on_before_give_award", $event);

            $this->entityManager->persist($userBadge);
            $this->entityManager->flush();

            $this->mailService->addParameter('userBadge', $userBadge);
            $this->mailService->load("award_given", "community_badges");
            $this->mailService->to($userInfo->getUserEmail());

            try {
                $this->mailService->sendMail();
            } catch (Exception $e) {
                throw new MailTransportError();
            }

            $event = new AfterGiveAward();
            $event->setUserBadge($userBadge);
            $this->eventDispatcher->dispatch("on_after_give_award", $event);

            return $userBadge;
        }
    }

    /**
     * @param AwardGrant $grantedAward
     */
    public function dismissGrantedAward(
        AwardGrant $grantedAward
    ): void
    {
        $grantedAward->setDismissed(true);
        $this->entityManager->persist($grantedAward);
        $this->entityManager->flush();
    }

    /**
     * @param AwardGrant $grantedAward
     * @param User $user
     * @return UserBadge
     * @throws MailTransportError
     * @throws NoUserSelected
     * @throws NoAuthorization
     * @throws InvalidSelfAssignment
     */
    public function giveGrantedAward(
        AwardGrant $grantedAward,
        User $user
    ): UserBadge
    {
        if (!$user->isRegistered()) {
            throw new NoUserSelected();
        } else {
            $userBadge = new UserBadge();
            $userInfo = $user->getUserInfoObject();

            if ($grantedAward->getUser()->getUserID() != $this->user->getUserID()) {
                throw new NoAuthorization();
            }

            if ($this->user->getUserID() == $user->getUserID()) {
                throw new InvalidSelfAssignment();
            }

            $userBadge->setBadge($grantedAward->getAward());
            $userBadge->setUser($userInfo->getEntityObject());
            $userBadge->setAwardGrant($grantedAward);
            $userBadge->setCreatedAt(new DateTime());

            $event = new BeforeGiveAward();
            $event->setUserBadge($userBadge);
            $this->eventDispatcher->dispatch("on_before_give_award", $event);

            $grantedAward->setRedeemedAt(new DateTime());

            $this->entityManager->persist($grantedAward);
            $this->entityManager->persist($userBadge);
            $this->entityManager->flush();

            $this->mailService->addParameter('userBadge', $userBadge);
            $this->mailService->load("award_given", "community_badges");
            $this->mailService->to($userInfo->getUserEmail());

            try {
                $this->mailService->sendMail();
            } catch (Exception $e) {
                throw new MailTransportError();
            }

            $event = new AfterGiveAward();
            $event->setUserBadge($userBadge);
            $this->eventDispatcher->dispatch("on_after_give_award", $event);

            return $userBadge;
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
                "user" => $user->getUserInfoObject()->getEntityObject(),
                "redeemedAt" => null
            ]);
        } else {
            return [];
        }
    }

    /**
     * @param User|null $user
     * @return UserBadge[]
     */
    public function getAllUserBadgesByUser(
        ?User $user = null
    ): iterable
    {
        if ($user === null) {
            // if no user is given use the current user
            $user = $this->user;
        }

        if ($user->isRegistered()) {
            return $this->userBadgeRepository->findBy([
                "user" => $user->getUserInfoObject()->getEntityObject()
            ]);
        } else {
            return [];
        }
    }

    /**
     * @param User|null $user
     * @return UserBadge[]
     */
    public function getAllAchievementsByUser(
        ?User $user = null
    ): iterable
    {
        $userBadges = [];

        foreach ($this->getAllUserBadgesByUser($user) as $userBadge) {
            if ($userBadge->getBadge() instanceof Achievement) {
                $userBadges[] = $userBadge;
            }
        }

        return $userBadges;
    }

    /**
     * @param User|null $user
     * @return UserBadge[]
     */
    public function getAllAwardsByUser(
        ?User $user = null
    ): iterable
    {
        $userBadges = [];

        foreach ($this->getAllUserBadgesByUser($user) as $userBadge) {
            if ($userBadge->getBadge() instanceof Award) {
                $userBadges[] = $userBadge;
            }
        }

        return $userBadges;
    }

    /**
     * @param User|null $user
     * @return UserBadge[]
     */
    public function getAllAwardsGroupedByUser(
        ?User $user = null
    ): iterable
    {
        $userBadges = [];

        foreach ($this->getAllUserBadgesByUser($user) as $userBadge) {
            if ($userBadge->getBadge() instanceof Award) {
                $badgeAdded = false;

                foreach ($userBadges as $index => $addedBadge) {
                    if ($addedBadge["userBadge"]->getBadge()->getId() === $userBadge->getBadge()->getId()) {
                        $userBadges[$index]["count"]++;
                        $badgeAdded = true;
                        break;
                    }
                }

                if (!$badgeAdded) {
                    $userBadges[] = [
                        "userBadge" => $userBadge,
                        "count" => 1
                    ];
                }
            }
        }

        return $userBadges;
    }

    /**
     * @param User|null $user
     * @return UserBadge[]
     */
    public function getAllBadgesGroupedByUser(
        ?User $user = null
    ): iterable
    {
        $userBadges = [];

        foreach ($this->getAllUserBadgesByUser($user) as $userBadge) {
            $badgeAdded = false;

            foreach ($userBadges as $index => $addedBadge) {
                if ($addedBadge["userBadge"]->getBadge()->getId() === $userBadge->getBadge()->getId()) {
                    $userBadges[$index]["count"]++;
                    $badgeAdded = true;
                    break;
                }
            }

            if (!$badgeAdded) {
                $userBadges[] = [
                    "userBadge" => $userBadge,
                    "count" => 1
                ];
            }
        }

        return $userBadges;
    }

    /**
     * @param User|null $user
     * @return AwardGrant[]
     */
    public function getAllGrantedAwardsGroupedByUser(
        ?User $user = null
    ): iterable
    {
        $userGrantAwards = [];

        foreach ($this->getAllGrantedAwardsByUser($user) as $grantedAward) {
            /** @var AwardGrant $grantedAward */

            if ($grantedAward->getAward() instanceof Award) {
                $grantAwardAdded = false;

                foreach ($userGrantAwards as $index => $addedGrantAward) {
                    if ($addedGrantAward["grantedAward"]->getAward()->getId() === $grantedAward->getAward()->getId()) {
                        $userGrantAwards[$index]["count"]++;
                        $grantAwardAdded = true;
                        break;
                    }
                }

                if (!$grantAwardAdded) {
                    $userGrantAwards[] = [
                        "grantedAward" => $grantedAward,
                        "count" => 1
                    ];
                }
            }
        }

        return $userGrantAwards;
    }
}
