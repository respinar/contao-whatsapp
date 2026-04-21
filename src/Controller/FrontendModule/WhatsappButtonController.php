<?php

declare(strict_types=1);

/*
 * This file is part of Contao Whatsapp Button Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\WhatsappButtonBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(type: 'whatsapp_button', category: 'miscellaneous')]
class WhatsappButtonController extends AbstractFrontendModuleController
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

            // If all values are found, break
            if (!empty($whatsappData['title']) && !empty($whatsappData['number']) && !empty($whatsappData['message'])) {
                break;
            }

            // Move to parent
            $page = PageModel::findById($page->pid);
        }

        $whatsappNumber = $whatsappData['number'] ?? $model->whatsappNumber;
        $whatsappMessage = $whatsappData['message'] ?? $model->whatsappMessage;

        $baseUrl = $this->isMobile($request)
            ? 'whatsapp://send'
            : 'https://web.whatsapp.com/send';

        $whatsappUrl = $baseUrl.'?phone='.rawurlencode((string) $whatsappNumber).'&text='.rawurlencode((string) $whatsappMessage);

        // Assign data to the template
        $template->set('whatsappTitle', $whatsappData['title'] ?? $model->whatsappTitle);
        $template->set('whatsappUrl', $whatsappUrl);
        $template->set('searchable', false);

        return $template->getResponse();
    }

    private function isMobile(Request $request): bool
    {
        $userAgent = $request->headers->get('User-Agent', '');

        return (bool) preg_match('/Mobi|Android|iPhone/i', $userAgent);
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
