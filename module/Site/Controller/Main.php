<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Site\Controller;

final class Main extends AbstractController
{
    /**
     * Invokes home page's controller
     * 
     * @return string
     */
    public function homeAction()
    {
        $controller = 'Pages:Page@homeAction';
        //$controller = 'Blog:Home@indexAction';
        return $this->forward($controller);
    }

    /**
     * Changes a language
     * 
     * @param string $code Language's code
     * @return void
     */
    public function changeLanguageAction($code)
    {
        $languageManager = $this->getService('Cms', 'languageManager');

        // @TODO fetchIdByCode() should be here instead
        $language = $languageManager->fetchByCode($code);

        // If $language isn't false, then $code was a valid one
        if ($language !== false) {
            // Set content language id
            $languageManager->setCurrentId($language->getId())
                            ->setInterfaceLangCode($code);

            // And finally redirect to a home page
            $this->response->redirect('/');

        } else {

            return false;
        }
    }

    /**
     * Renders CAPTCHA
     * 
     * @return void
     */
    public function captchaAction()
    {
        $this->captcha->render();
    }

    /**
     * This action is executed when user requested not-existing page
     * 
     * @return string
     */
    public function notFoundAction()
    {
        // If triggering 404 from administration area
        if (strpos($this->request->getUri(), '/admin') !== false) {
            return $this->translator->translate('Invalid parameter supplied');
        } else {
            $controller = 'Pages:Page@notFoundAction';

            // Passing null will trigger 404's action
            return $this->forward($controller, array(null));
        }
    }

    /**
     * This action exists for testing purposes only
     * Sometimes we need to test stuff on live system without breaking it
     * 
     * @return string
     */
    public function testAction()
    {
        return false;
    }

    /**
     * Default action
     * 
     * @param string $slug
     * @param integer $pageNumber
     * @param string $code Optional language code
     * @return string
     */
    public function slugAction($slug, $pageNumber = 1, $code = null)
    {
        $slug = urldecode($slug);

        // Grab a service
        $webPageManager = $this->getService('Cms', 'webPageManager');
        $webPage = $webPageManager->fetchBySlug($slug);

        // Not empty means that existing slug is supplied
        if (!empty($webPage)) {

            // Data to be passed to a controller
            $args = array($webPage['target_id'], $pageNumber, $code, $slug);

            // Now we have a controller, action, params and page params
            return $this->forward($webPage['controller'], $args);

        } else {

            // Trigger 404 if not found
            return false;
        }
    }

    /**
     * This invoked instead of slugAction() when we have more than one language
     * 
     * @param string $code Language code
     * @param string $slug Web page slug
     * @param integer $page Optional web page
     */
    public function slugLanguageAwareAction($code, $slug, $page = 1)
    {
        return $this->slugAction($slug, $page, $code);
    }
}
