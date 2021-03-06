<?php
/**
 * This file is part of the PrestaCMSSandboxBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSSandboxBundle\DataFixtures\PHPCR;

use Doctrine\Common\Persistence\ObjectManager;
use PHPCR\Util\NodeHelper;
use Symfony\Component\Yaml\Parser;
use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseRouteFixture;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadRoute extends BaseRouteFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $home   = $manager->find(null, '/website/symfony-prestacms/route/en');
        $homeFr = $manager->find(null, '/website/symfony-prestacms/route/fr');

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/page.yml'));
        foreach ($datas['pages'] as $pageConfiguration) {
            if ($pageConfiguration['name'] == 'home') {
                continue;
            }
            $pageConfiguration['content_path'] = '/website/symfony-prestacms/page' . '/' .  $pageConfiguration['name'];
            $pageConfiguration['parent'] = $home;
            $pageConfiguration['locale'] = 'en';
            $this->getFactory()->create($pageConfiguration);

            $pageConfiguration['parent'] = $homeFr;
            $pageConfiguration['locale'] = 'fr';
            $this->getFactory()->create($pageConfiguration);
        }

        $this->manager->flush();
    }
}
