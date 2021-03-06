<?php

/*
 * This file is part of Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\ContactBundle\DependencyInjection;

use Sulu\Bundle\ContactBundle\Entity\Account;
use Sulu\Bundle\PersistenceBundle\DependencyInjection\PersistenceExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SuluContactExtension extends Extension implements PrependExtensionInterface
{
    use PersistenceExtensionTrait;

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        if ($container->hasExtension('sulu_search')) {
            $container->prependExtensionConfig(
                'sulu_search',
                [
                    'indexes' => [
                        'contact' => [
                            'security_context' => 'sulu.contact.people',
                        ],
                        'account' => [
                            'security_context' => 'sulu.contact.organizations',
                        ],
                    ],
                ]
            );
        }

        if ($container->hasExtension('sulu_media')) {
            $container->prependExtensionConfig(
                'sulu_media',
                [
                    'system_collections' => [
                        'sulu_contact' => [
                            'meta_title' => ['en' => 'Sulu contacts', 'de' => 'Sulu Kontakte'],
                            'collections' => [
                                'contact' => [
                                    'meta_title' => ['en' => 'People', 'de' => 'Personen'],
                                ],
                                'account' => [
                                    'meta_title' => ['en' => 'Organizations', 'de' => 'Organisationen'],
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }

        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig(
                'sulu_admin',
                [
                    'resources' => [
                        'contacts' => [
                            'form' => ['@SuluContactBundle/Resources/config/forms/Contact.xml'],
                            'datagrid' => '%sulu.model.contact.class%',
                            'endpoint' => 'get_contacts',
                        ],
                        'accounts' => [
                            'form' => ['@SuluContactBundle/Resources/config/forms/Account.xml'],
                            'datagrid' => Account::class,
                            'endpoint' => 'get_accounts',
                        ],
                    ],
                    'field_type_options' => [
                        'single_selection' => [
                            'single_account_selection' => [
                                'default_type' => 'auto_complete',
                                'resource_key' => 'accounts',
                                'types' => [
                                    'auto_complete' => [
                                        'display_property' => 'name',
                                        'search_properties' => ['number', 'name'],
                                    ],
                                    'datagrid_overlay' => [
                                        'adapter' => 'table',
                                        'display_properties' => ['name'],
                                        'empty_text' => 'sulu_contact.no_account_selected',
                                        'icon' => 'su-house',
                                        'overlay_title' => 'sulu_contact.single_account_selection_overlay_title',
                                    ],
                                ],
                            ],
                            'single_contact_selection' => [
                                'default_type' => 'datagrid_overlay',
                                'resource_key' => 'contacts',
                                'types' => [
                                    'datagrid_overlay' => [
                                        'adapter' => 'table',
                                        'display_properties' => ['fullName'],
                                        'empty_text' => 'sulu_contact.no_contact_selected',
                                        'icon' => 'su-user',
                                        'overlay_title' => 'sulu_contact.single_contact_selection_overlay_title',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('content.xml');
        $loader->load('command.xml');

        $container->setParameter(
            'sulu_contact.defaults',
            $config['defaults']
        );

        $this->setDefaultForFormOfAddress($config);
        $container->setParameter(
            'sulu_contact.form_of_address',
            $config['form_of_address']
        );
        $container->setParameter(
            'sulu_contact.contact_form.category_root',
            $config['form']['contact']['category_root']
        );
        $container->setParameter(
            'sulu_contact.account_form.category_root',
            $config['form']['account']['category_root']
        );

        $this->configurePersistence($config['objects'], $container);
    }

    /**
     * Sets default values for form of address if not defined in config.
     *
     * @param $config
     */
    private function setDefaultForFormOfAddress($config)
    {
        if (!array_key_exists('form_of_address', $config) || 0 == count($config['form_of_address'])) {
            $config['form_of_address'] = [
                'male' => [
                    'id' => 0,
                    'name' => 'male',
                    'translation' => 'contact.contacts.formOfAddress.male',
                ],
                'female' => [
                    'id' => 1,
                    'name' => 'female',
                    'translation' => 'contact.contacts.formOfAddress.female',
                ],
            ];
        }
    }
}
