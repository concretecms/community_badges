/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

import {alert, Stack, defaultModules} from '@pnotify/core';
import * as PNotifyBootstrap4 from '@pnotify/bootstrap4';

defaultModules.set(PNotifyBootstrap4, {});

const stackBottomModal = new Stack({
    dir1: 'up',
    dir2: 'left',
    firstpos1: 25,
    firstpos2: 25,
    push: 'top',
    maxOpen: 5,
    modal: false,
    overlayClose: false,
    context: $('body').get(0)
});

$(".community-award-modal").each((index, element) => {
    let $modal = $(element);

    $modal.find(".btn-primary").click(() => {
        let grantedAwardId = $modal.find("[name=grantedAwardId]").val();
        let user = $modal.find("[name=user]").val();

        $.ajax({
            url: CCM_DISPATCHER_FILENAME + "/api/v1/community_badges/give_award",
            method: "POST",
            data: {
                grantedAwardId: grantedAwardId,
                user: user
            },
            success: (data) => {
                if (data.error) {
                    for (let i = 0; i < data.errors.length; i++) {
                        let errorMessage = data.errors[i];

                        alert({
                            text: errorMessage,
                            stack: stackBottomModal,
                            type: 'error'
                        });
                    }
                } else {
                    alert({
                        text: data.message,
                        stack: stackBottomModal
                    });

                    $modal.modal('hide');

                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                }
            }
        });

    });
});