<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Entity\PersonDetail;
use AppBundle\Form\PersonDetailType;
use AppBundle\Form\PersonType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use Symfony\Component\DomCrawler\Crawler;
use Guzzle\Http\Client;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', [] );
    }

    /**
     * Creates a new Person entity.
     *
     * @Route("/step/1", name="person_create")
     *
     */
    public function createPersonAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $entity = new Person();
        $entity->setIpAddress(ip2long($request->getClientIp()));
        $form = $this->createPersonForm($entity);
        $form->handleRequest($request);

        if ( $request->isMethod('POST') ) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return new JsonResponse(
                    [
                        'message' => 'Success!',
                        'id' => $entity->getId(),
                        'step' => 2
                    ], 200);
            }
        } else {
            return $response = new JsonResponse(
                [
                    'form' => $this->renderView(':form:personInfo.html.twig',
                        [
                            'entity' => $entity,
                            'form' => $form->createView(),
                        ])
                ], 200);
        }

        $response = new JsonResponse(
            [
                'form' => $this->renderView(':form:formBody.html.twig',
                    [
                        'entity' => $entity,
                        'form' => $form->createView(),
                    ])
            ], 400);

        return $response;

        //return $this->processForm($form, $entity, $request);
    }

    /**
     * Creates a new PersonDetail entity.
     *
     * @Route("/step/2/id/{id}", name="person_detail")
     * @ParamConverter("person", class="AppBundle:Person")
     *
     */
    public function personDetailAction(Request $request, Person $person)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $entity = new PersonDetail();
        $entity->setPerson($person);
        $form = $this->createPersonDetailForm($entity);
        $form->handleRequest($request);

        if ( $request->isMethod('POST') ) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return new JsonResponse(
                    [
                        'message' => 'Success!',
                        'step' => 3
                    ], 200);
            }
        } else {
            return $response = new JsonResponse(
                [
                    'form' => $this->renderView(':form:personDetail.html.twig',
                        [
                            'entity' => $person,
                            'form' => $form->createView(),
                        ])
                ], 200);
        }

        $response = new JsonResponse(
            [
                'form' => $this->renderView(':form:formBody.html.twig',
                    [
                        'entity' => $entity,
                        'form' => $form->createView(),
                    ])
            ], 400);

        return $response;

    }

    /**
     * Show result
     *
     * @Route("/step/3", name="show_result")
     *
     */
    public function resultAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $client = new Client('http://gifbin.com/');
        $request = $client->get('random');
        $response = $request->send();
        $crawler = new Crawler($response->getBody(true));
        $res = $crawler->filter('form#share-form > fieldset')->eq('2')->filter('input')->attr('value');
        $response = new JsonResponse(
            [
                'form' => $this->renderView('default/result.html.twig', ['img' => $res]),
                //'step' => '4'
            ], 200);

        return $response;
    }

    /**
     * Creates a form to create a Person entity.
     *
     * @param Person $entity
     *
     * @return Form
     */
    private function createPersonForm(Person $entity)
    {
        $form = $this->createForm(new PersonType(), $entity,
            array(
                'action' => $this->generateUrl('person_create'),
                'method' => 'POST',
            ));

        return $form;
    }

    /**
     * Creates a form to create a Person entity.
     *
     * @param PersonDetail $entity
     *
     * @return Form
     */
    private function createPersonDetailForm(PersonDetail $entity)
    {
        $form = $this->createForm(new PersonDetailType(), $entity,
            array(
                'action' => $this->generateUrl('person_detail', ['id' => $entity->getPerson()->getId()]),
                'method' => 'POST',
            ));

        return $form;
    }

}