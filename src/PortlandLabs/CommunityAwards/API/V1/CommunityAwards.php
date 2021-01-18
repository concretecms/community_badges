<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityAwards\API\V1;

use Concrete\Core\Application\EditResponse;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\Request;
use Concrete\Core\User\User;
use PortlandLabs\CommunityAwards\AwardService;
use PortlandLabs\CommunityAwards\Exceptions\GrantAwardNotFound;
use PortlandLabs\CommunityAwards\Exceptions\MailTransportError;
use PortlandLabs\CommunityAwards\Exceptions\NoAuthorization;
use PortlandLabs\CommunityAwards\Exceptions\NoUserSelected;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommunityAwards
{
    protected $awardService;
    protected $request;

    public function __construct(
        AwardService $awardService,
        Request $request
    )
    {
        $this->awardService = $awardService;
        $this->request = $request;
    }

    public function giveAward()
    {
        $editResponse = new EditResponse();
        $errorList = new ErrorList();

        if (!$this->request->request->has("grantedAwardId") || $this->request->request->getInt("grantedAwardId") === 0) {
            $errorList->add(t("Missing granted award id."));
        } else if (!$this->request->request->has("user") || $this->request->request->getInt("user") === 0) {
            $errorList->add(t("You need to select a valid user."));
        } else {
            $grantAwardId = $this->request->request->getInt("grantedAwardId", 0);
            $userId = $this->request->request->getInt("user", 0);

            $user = User::getByUserID($userId);

            try {
                $grantedAward = $this->awardService->getGrantAwardById((int)$grantAwardId);
                $this->awardService->giveGrantedAward($grantedAward, $user);
                $editResponse->setMessage(t("Award successfully given to the user %s.", $user->getUserName()));
            } catch (GrantAwardNotFound $e) {
                $errorList->add(t("You need to select a valid grant award."));
            } catch (MailTransportError $e) {
                $errorList->add(t("There was an error while sending the mail notification."));
            } catch (NoUserSelected $e) {
                $errorList->add(t("You need to select a user."));
            } catch (NoAuthorization $e) {
                $errorList->add(t("You can't give away an granted award that you don't own by yourself."));
            }
        }

        $editResponse->setError($errorList);

        return new JsonResponse($editResponse);
    }

}