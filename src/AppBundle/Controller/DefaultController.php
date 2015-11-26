<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ErrorLog;
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
                        'step' => 2 //next step
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
                'message' => 'Error',
                'form' => $this->renderView(':form:formBody.html.twig',
                    [
                        'entity' => $entity,
                        'form' => $form->createView(),
                    ])
            ], 400);

        return $response;

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
            } else {
                $em = $this->getDoctrine()->getManager();
                $errors = $form->getErrors(true, false);

                foreach($errors as $error) {
                    $errorLog = new ErrorLog();
                    $errorLog->setPerson($person);
                    $errorLog->setTitle($error->current()->getOrigin()->getName().': '.$error->current()->getMessage());
                    $em->persist($errorLog);
                }
                $em->flush();

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
                'message' => 'Error',
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

        $res = $this->container->get('result.image.manager')->getRandomImage();

        $response = new JsonResponse(
            [
                'form' => $this->renderView('default/result.html.twig', ['img' => $res]),
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
