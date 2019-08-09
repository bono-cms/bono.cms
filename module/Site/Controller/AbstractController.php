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

use Krystal\Application\Controller\AbstractController as BaseController;
use Krystal\Paginate\Paginator;
use Krystal\Validate\Renderer;
use RuntimeException;

abstract class AbstractController extends BaseController
{
    /**
     * Let CSRF validation to be enabled by default
     * 
     * @var boolean
     */
    protected $enableCsrf = true;

    /**
     * Bootstrap site services
     * 
     * @return array
     */
    private function bootstrapSiteServices()
    {
        foreach ($this->moduleManager->getLoadedModuleNames() as $module) {
            // Build PSR-0 compliant class name
            $class = sprintf('\%s\Service\SiteBootstrapper', $module);
            if (class_exists($class)) {
                $bootstrapper = new $class($this->moduleManager, $this->view);
                $bootstrapper->bootstrap();
            }
        }
    }

    /**
     * Prepares paginator instance (sets generated URL)
     * 
     * @param \Krystal\Paginate\Paginator $paginator
     * @param string $code Language code
     * @param string $slug Target slug
     * @param integer $pageNumber Current page number
     * @return void
     */
    final protected function preparePaginator(Paginator $paginator, $code, $slug, $pageNumber)
    {
        // If $code isn't empty, then we have more than one language
        if (!empty($code)) {
            $url = sprintf('/%s/%s/page/(:var)', $code, $slug);
        } else {
            // Otherwise we have only one language
            $url = sprintf('/%s/page/(:var)', $slug);
        }

        $paginator->setUrl($url);
    }

    /**
     * Returns theme configuration
     * 
     * @return array
     */
    private function getThemeConfig()
    {
        static $cache = null;

        if (is_null($cache)) {
            // Build a path to the configuration file
            $file = $this->view->getWithThemePath('theme.config.php');

            // Initial state
            $config = array();

            // Do include only in case, if config file exists
            if (is_file($file)) {
                $config = include($file);
            }

            $cache = $config;
        }

        return $cache;
    }

    /**
     * Loads registered site plugins
     * 
     * @return void
     */
    final protected function loadSitePlugins()
    {
        $this->loadSiteTheme();

        $config = $this->getThemeConfig();

        // Plugins must have higher priority
        if (isset($config['plugins']) && is_array($config['plugins'])) {
            foreach ($config['plugins'] as $plugin) {
                $this->view->getPluginBag()
                           ->load($plugin);
            }
        }

        // If we have theme a section for theme configuration
        if (isset($config['theme'])) {
            // Append script paths to the stack
            if (isset($config['theme']['scripts']) && is_array($config['theme']['scripts'])) {
                foreach ($config['theme']['scripts'] as $script) {
                    $this->view->getPluginBag()
                               ->appendScript($this->request->isUrlLike($script) ? $script : $this->view->createThemeUrl('Site').$script);
                }
            }

            // Append stylesheet paths to stack
            if (isset($config['theme']['stylesheets']) && is_array($config['theme']['stylesheets'])) {
                foreach ($config['theme']['stylesheets'] as $stylesheet) {
                    $this->view->getPluginBag()
                               ->appendStylesheet($this->request->isUrlLike($stylesheet) ? $stylesheet : $this->view->createThemeUrl('Site').$stylesheet);
                }
            }

            // Optional validation for modules
            if (isset($config['modules']) && is_array($config['modules'])) {
                $unloaded = $this->moduleManager->getUnloadedModules($config['modules']);

                if (!empty($unloaded)) {
                    throw new RuntimeException(sprintf('The theme requires the following modules: %s', implode(', ', $unloaded)));
                }
            }
        }

        $this->view->addVariables(array(
            'locale' => $this->appConfig->getLanguage(),
            'currentUrl' => $this->request->getCurrentUrl(),
            'baseUrl' => $this->request->getBaseUrl(),
            // Inject parameter bag service
            'paramBag' => $this->paramBag
        ));

        $this->bootstrapSiteServices();
        $this->loadThemeTranslations();

        // And lastly, do load global site plugin
        $this->view->getPluginBag()->load('site');
    }

    /**
     * Returns current theme path on the file system
     * 
     * @return string
     */
    private function getCurrentThemePath()
    {
        return $this->view->createThemePath('Site', $this->appConfig->getTheme());
    }

    /**
     * Load theme translations if available
     * 
     * @throws \RuntimeException If translation file doesn't return an array
     * @return void
     */
    private function loadThemeTranslations()
    {
        // Create a path to the translation path
        $file = sprintf('%s/translations/%s.php', $this->getCurrentThemePath(), $this->appConfig->getLanguage());

        // Do load only if present
        if (is_file($file)) {
            $data = include($file);

            if (is_array($data)) {
                $this->translator->extend($data);
            } else {
                throw new RuntimeException(sprintf(
                    'Translation file must return an array of translations. Got "%s" in %s', gettype($data), $file
                ));
            }
        }
    }

    /**
     * Loads site theme
     * 
     * @return void
     */
    private function loadSiteTheme()
    {
        $theme = $this->config->get('Cms', 'theme');

        if ($theme !== false) {
            $this->appConfig->setTheme($theme);
            $this->view->setTheme($theme);
        } else {
            throw new RuntimeException('Can not load site theme. Probably a default theme was not selected in administration panel');
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function bootstrap($action)
    {
        $this->loadSiteTheme();
        $this->validateRequest();
        $this->validateInstallationState();

        $this->validatorFactory->setRenderer(new Renderer\StandardJson());

        // Load translations
        $language = $this->getService('Cms', 'languageManager')->getInterfaceLangCode();
        $this->loadTranslations($language);

        // Configure view
        $this->view->setLayout('__layout__')
                   ->setModule('Site');

        // Append blocks
        $this->view->getPartialBag()
                   ->addPartialDirs(array(
                                    $this->getCurrentThemePath().'/partials/',
                                    $this->view->createThemePath('Site', 'shared')
                                 ));

        // Tweak breadcrumbs
        $this->view->getBreadcrumbBag()
                   ->removeFirst()
                   ->add(array(array(
                        'link' => '/',
                        'name' => $this->translator->translate('Home page')
                    ))
        );

        // Get core configuration entity of the system itself
        $config = $this->getService('Cms', 'configManager')->getEntity();

        // If site isn't enabled, then its down for maintenance
        if ($config->getSiteEnabled()) {
            $response = $this->view->renderRaw('Cms', 'down', 'main', array(
                'reason' => $config->getSiteDownReason()
            ));

            die($response);
        }
    }

    /**
     * Validates installation status
     * 
     * @return void
     */
    private function validateInstallationState()
    {
        $configManager = $this->getService('Cms', 'configManager');
        $installed = $configManager->get('installed');

        if (!$installed) {
            $this->redirectToRoute('Cms:Install:Install@indexAction');
        }
    }

    /**
     * Validates the request
     * 
     * @return void
     */
    private function validateRequest()
    {
        // Must support only POST and GET requests
        if (!$this->request->isIntended()) {
            $this->response->setStatusCode(400);
            die('Invalid request');
        }

        // Do validate only for POST requests for now
        if ($this->enableCsrf === true && $this->request->isPost()) {
            // Current token
            $token = $this->request->isAjax() ? $this->request->getMetaCsrfToken() : $this->request->getPost('csrf-token');

            // This is general for all forms
            if (!$this->csrfProtector->isValid($token)) {
                $this->response->setStatusCode(400);
                die('Invalid CSRF token');
            }
        }
    }
}
