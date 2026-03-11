<?php

declare(strict_types=1);

namespace Respinar\WhatsappBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(category: 'miscellaneous')]
class WhatsappController extends AbstractFrontendModuleController
{
    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        // Check if the current page has WhatsApp fields set
        $page = $this->getPageModel();

        if (!$this->isWhatsappVisible($page, $model)) {
            return new Response();
        }

        $whatsappData = [
            'title' => null,
            'number' => null,
            'message' => null,
        ];

        while (null !== $page) {
            // Set only if not already set and the page value is non-empty
            if (empty($whatsappData['title']) && !\in_array(trim((string) $page->whatsappTitle), ['', '0'], true)) {
                $whatsappData['title'] = $page->whatsappTitle;
            }

            if (empty($whatsappData['number']) && !\in_array(trim((string) $page->whatsappNumber), ['', '0'], true)) {
                $whatsappData['number'] = $page->whatsappNumber;
            }

            if (empty($whatsappData['message']) && !\in_array(trim((string) $page->whatsappMessage), ['', '0'], true)) {
                $whatsappData['message'] = $page->whatsappMessage;
            }

            // If all are filled, break
            if (!empty($whatsappData['title']) && !empty($whatsappData['number']) && !empty($whatsappData['message'])) {
                break;
            }

            // If all values are found, break
            if ($whatsappData['title'] && $whatsappData['number'] && $whatsappData['message']) {
                break;
            }

            // Move to parent
            $page = PageModel::findById($page->pid);
        }

        // Assign data to the template
        $template->set('whatsappTitle', $whatsappData['title'] ?? $model->whatsappTitle);
        $template->set('whatsappNumber', $whatsappData['number'] ?? $model->whatsappNumber);
        $template->set('whatsappMessage', $whatsappData['message'] ?? $model->whatsappMessage);
        $template->set('searchable', false);

        // Add JavaScript file to the page
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/respinarwhatsapp/js/whatsapp.js|static';

        return $template->getResponse();
    }

    private function isWhatsappVisible(PageModel $page, ModuleModel $model): bool
    {
        while (null !== $page) {
            $visibility = $page->whatsappVisibility;

            if ('show' === $visibility) {
                return true;
            }

            if ('hide' === $visibility) {
                return false;
            }

            $page = PageModel::findById($page->pid);
        }

        return $model->whatsappIsVisible;
    }
}
