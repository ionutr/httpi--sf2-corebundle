<?php

namespace Httpi\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Httpi\Bundle\CoreBundle\Entity\Status;
use Httpi\Bundle\CoreBundle\Form\StatusType;

/**
 * Status controller.
 *
 */
class StatusController extends Controller
{
    
    protected $extendsTemplate = false;

    private function readAndSetExtendsTemplate()
    {
        $this->extendsTemplate = $this->container->getParameter('extends_template');
    }

    /**
     * Lists all Status entities.
     *
     */
    public function indexAction()
    {
        $this->readAndSetExtendsTemplate();

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HttpiCoreBundle:Status')->findAll();

        return $this->render('HttpiCoreBundle:Status:index.html.twig', array(
            'entities' => $entities,
            'extendsTemplate' => $this->extendsTemplate
        ));
    }

    /**
     * Creates a new Status entity.
     *
     */
    public function createAction(Request $request)
    {
        $this->readAndSetExtendsTemplate();
        $entity  = new Status();
        $form = $this->createForm(new StatusType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_status__edit', array('id' => $entity->getId())));
        }

        return $this->render('HttpiCoreBundle:Status:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'extendsTemplate' => $this->extendsTemplate
        ));
    }

    /**
     * Displays a form to create a new Status entity.
     *
     */
    public function newAction()
    {
        $this->readAndSetExtendsTemplate();
        $entity = new Status();
        $form   = $this->createForm(new StatusType(), $entity);

        return $this->render('HttpiCoreBundle:Status:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'extendsTemplate' => $this->extendsTemplate
        ));
    }

    /**
     * Finds and displays a Status entity.
     *
     */
    public function showAction($id)
    {
        $this->readAndSetExtendsTemplate();
        $this->readAndSetExtendsTemplate();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HttpiCoreBundle:Status')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Status entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('HttpiCoreBundle:Status:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'extendsTemplate' => $this->extendsTemplate
        ));
    }

    /**
     * Displays a form to edit an existing Status entity.
     *
     */
    public function editAction($id)
    {
        $this->readAndSetExtendsTemplate();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HttpiCoreBundle:Status')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Status entity.');
        }

        $editForm = $this->createForm(new StatusType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('HttpiCoreBundle:Status:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'extendsTemplate' => $this->extendsTemplate
        ));
    }

    /**
     * Edits an existing Status entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $this->readAndSetExtendsTemplate();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HttpiCoreBundle:Status')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Status entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new StatusType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_status__edit', array('id' => $id)));
        }

        return $this->render('HttpiCoreBundle:Status:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'extendsTemplate' => $this->extendsTemplate
        ));
    }
    /**
     * Deletes a Status entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HttpiCoreBundle:Status')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Status entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_status_'));
    }

    /**
     * Creates a form to delete a Status entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
