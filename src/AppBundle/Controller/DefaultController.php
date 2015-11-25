<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Entity\PersonDetail;
use AppBundle\Form\PersonDetailType;
use AppBundle\Form\PersonType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
        $person = new Person();
        $form = $this->createPersonForm($person);

        return $this->render('default/index.html.twig',
            [
                'entity' => $person,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * Creates a new Person entity.
     *
     * @Route("/step/1", name="person_create")
     * @Method("POST")
     *
     */
    public function createPersonAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $entity = new Person();
        $form = $this->createPersonForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return new JsonResponse([
                'message' => 'Success!',
                'id' => $entity->getId()
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
     * Creates a new Person entity.
     *
     * @Route("/step/2", name="person_detail")
     *
     */
    public function personDetailAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $entity = new PersonDetail();
        $entity->setPersonId($request->get('id'));
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
                    ], 200);
            }
        } else {
            return $response = new JsonResponse(
                [
                    'form' => $this->renderView(':form:personDetail.html.twig',
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
                'action' => $this->generateUrl('person_detail'),
                'method' => 'POST',
            ));

        return $form;
    }
}
