<?php

declare(strict_types=1);

namespace Respinar\WhatsappBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Contao\StringUtil;

#[AsFrontendModule(category: "miscellaneous", type: "whatsapp", template: "mod_whatsapp")]
class WhatsappController extends AbstractFrontendModuleController
{
    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        // Check if the current page has WhatsApp fields set
        $page = $this->getPageModel();

        if ($page->whatsappDisabled) {
            return new Response();
        }

        // Assign data to the template
        $template->whatsappTitle = $page->whatsappTitle ?: $model->whatsappTitle;
        $template->whatsappNumber = $page->whatsappNumber ?: $model->whatsappNumber;
        $template->whatsappMessage = $page->whatsappMessage ?: $model->whatsappMessage;
        $template->cssClass = StringUtil::deserialize($model->cssID)[1] ?? '';

        // Add JavaScript file to the page
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/respinarwhatsapp/js/whatsapp.js|static';

        return $template->getResponse();
    }
}