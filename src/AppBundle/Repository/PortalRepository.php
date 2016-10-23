<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Portal;
use AppBundle\Entity\Page;
use AppBundle\Entity\Widget;
use AppBundle\Entity\Source;
use AppBundle\Entity\Storage;

/**
 * PortalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PortalRepository extends \Doctrine\ORM\EntityRepository
{
    public function savePortals($portals) {

        $em = $this->getEntityManager();
        foreach ($portals as $data) {
            $this->handlePortal($em, $data);
        }
    }

    public function clonePages($source, $target) {

        $em = $this->getEntityManager();

        $pages = $em
            ->getRepository('AppBundle:Page')
            ->findBy([ 'portal' => $source ], [])
        ;

        foreach ($pages as $ps) {

            $pt = (new Page())
                ->setName($ps->getName())
                ->setPortal($target)
            ;

            $rs = $ps->getRoot();
            $rt = (new Widget())
                ->setName($rs->getName())
                ->setPage($pt)
                ->setParent(null)
                ->setIndex($rs->getIndex())
                ->setParams($rs->getParams())
            ;

            $pt->setRoot($rt);

            $em->persist($pt);
            $em->flush();

            $this->cloneWidgets($em, $rs, $rt);
            $this->cloneSources($em, $ps, $pt);
            $this->cloneStorages($em, $ps, $pt);

            $em->flush();
        }

        $em->flush();
    }

    private function cloneWidgets($em, $source, $target) {

        foreach($source->getWidgets() as $ws) {

            $wt = (new Widget())
                ->setName($ws->getName())
                ->setPage(null)
                ->setParent($target)
                ->setIndex($ws->getIndex())
                ->setParams($ws->getParams())
            ;

            $em->persist($wt);
            $em->flush();

            $this->cloneWidgets($em, $ws, $wt);
        }
    }

    private function cloneSources($em, $source, $target) {
    }

    private function cloneStorages($em, $source, $target) {
    }

    private function handlePortal($em, $data) {

        if (isset($data['_action'])) {

            switch ($data['_action']) {
                case 'remove':
                    $this->handlePortalRemove($em, $data);
                    break;
                case 'update':
                    $portal = $this->handlePortalUpdate($em, $data);
                    $this->handlePortalTree($em, $portal, $data);
                    break;
                case 'create':
                    $portal = $this->handlePortalCreate($em, $data);
                    $this->handlePortalTree($em, $portal, $data);
                    break;
            }

        } else {
            $portal = $this->findOneById($data['id']);
            $this->handlePortalTree($em, $portal, $data);
        }
    }

    private function handlePortalCreate($em, $data) {

        $portal = (new Portal())
            ->setName($data['name'])
            ->setTitle($data['title'])
        ;

        $em->persist($portal);
        $em->flush();

        return $portal;
    }

    private function handlePortalUpdate($em, $data) {

        $portal = $this->findOneById($data['id'])
            ->setName($data['name'])
            ->setTitle($data['title'])
        ;

        $em->persist($portal);
        $em->flush();

        return $portal;
    }

    private function handlePortalRemove($em, $data) {

        $portal = $this->findOneById($data['id']);

        $em->remove($portal);
        $em->flush();
    }

    private function handlePortalTree($em, $portal, $data) {
    }

    private function clearParams($data) {

        $array = [];

        foreach ($data as $param) {
            if (!isset($param['_action']) || $param['_action'] != 'remove') {
                $p = $param;
                unset($p['_action']);
                $array[] = $p;
            }
        }

        return $array;
    }
}
