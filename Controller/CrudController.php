<?php

namespace Httpi\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Httpi\Bundle\CoreBundle\Library\Info\Info as InfoLib;
use Httpi\Bundle\CoreBundle\Entity\Info;

class CrudController extends Controller
{
    protected $crudConfiguration = array(
        // default values to be used for entity instantiation
        'defaults' => array(
        ),

        // whether to work with the Info Class from HttpiCoreBundle or not, defaults to true
        'hasInfo' => true,

        // the name of the entity, e.g.: User
        'entityName' => null,

        // form type name, full namespace, e.g.: MyBundle/Full/Namespace/UserType
        'entityFormtypeName' => null,

        // the fully qualified namespace for the entity, e.g. MyBundle/Full/Namespace/User
        'entityFqn' => null,

        // the entity repository, full symfony namespace, e.g.: MyBundleFullNamespace:User
        'entityRepository' => null,

        // the prefix to use to fetch templates
        'templateNamePrefix' => 'HttpiCoreBundle:Crud:',

        // edit route path
        'editPath' => null,

        // path to redirect after deleting a term
        'afterDeleteRedirectPath' => null,

        // the string to be used for a single unit, defaults to 'item'
        'unitName' => 'item',

        // the string to be used for collections, defaults to 'collection'
        'collectionName' => 'collection',

        // the column to be used as id column
        'idColumn' => 'id',

        // the column to be used to fetch the title of the item; if this is null, idColumn will be used instead
        'titleColumn' => null,

        // array of messages to be used for notifications and errors
        'messages' => array(
            "success" => "Success!",
            "fail" => "Failed!",
            "please_correct_errors" => 'Please correct the error(s)!'
        )
    );

    protected $overwriteCrudConfiguration;

    public function __construct()
    {
        if (!is_null($this->overwriteCrudConfiguration) && is_array($this->overwriteCrudConfiguration) && !empty($this->overwriteCrudConfiguration)) {
            $this->crudConfiguration = array_merge($this->crudConfiguration, $this->overwriteCrudConfiguration);
        }
    }

    public function addAction(Request $request)
    {
        $entity = new $this->crudConfiguration['entityFqn'];

        // set defaults
        $entity->setData($this->crudConfiguration['defaults']);

        // create form
        $form = $this->createForm(new $this->crudConfiguration['entityFormtypeName'], $entity);

        // check if request was posted
        if ($request->getMethod() == 'POST') {
            // handle the post
            $form->handleRequest($request);

            // check if form is valid
            if ($form->isValid()) {
                // get entity from form data
                $entity = $form->getData();

                // check if it hasInfo, if so stamp it
                if ($this->crudConfiguration['hasInfo'] === true) {
                    $entity->setInfo(InfoLib::stamp(new Info()));
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('flash', array(
                    "title" => $this->crudConfiguration['messages']['success'],
                    "content" => 'You have successfully added a new ' . $this->crudConfiguration['unitName'] . ' to the ' . $this->crudConfiguration['collectionName'] . '!',
                    "type" => "info",
                    "success" => true
                  ));

                return $this->redirect($this->generateUrl($request->get('_route')));
            } else {
                $this->get('session')->getFlashBag()->add('flash', array(
                    "title" => $this->crudConfiguration['messages']['fail'],
                    "content" => $this->crudConfiguration['messages']['please_correct_errors'],
                    "type" => "error",
                    "success" => true
                ));
            }
        }

        return $this->render($this->crudConfiguration['templateNamePrefix'] . 'add.html.twig', array(
            'form' => $form->createView()
        ));

    }

    public function editAction(Request $request)
    {
        $id = $request->get('id');

        //@TODO: verify id is int here

        $entity = $this->getDoctrine()
            ->getRepository($this->crudConfiguration['entityRepository'])
            ->find($id);

        $form = $this->createForm(new $this->crudConfiguration['entityFormtypeName'], $entity);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $entity = $form->getData();
                if ($this->crudConfiguration['hasInfo'] === true) {
                    $entity->setInfo(InfoLib::stamp($entity->getInfo()));
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->persist($entity->getInfo());
                $em->flush();

                $this->get('session')->getFlashBag()->add('flash', array(
                        "title" => $this->crudConfiguration['messages']['success'],
                        "content" => 'You have successfully updated a ' . $this->crudConfiguration['unitName'] . ' in the ' . $this->crudConfiguration['collectionName'] . '!',
                        "type" => "info",
                        "success" => true
                    ));

                return $this->redirect($this->generateUrl($request->get('_route'), array("id" => $id)));
            } else {
                $this->get('session')->getFlashBag()->add('flash', array(
                        "title" => $this->crudConfiguration['mesages']['fail'],
                        "content" => $this->crudConfiguration['messages']['please_correct_errors'],
                        "type" => "error",
                        "success" => true
                    ));
            }
        }

        return $this->render($this->crudConfiguration['templateNamePrefix'] . 'edit.html.twig', array(
                'form' => $form->createView(),
                'id' => $id
            ));

    }

    public function deleteAction(Request $request)
    {
        // get id/entity
        $id = $request->get('id');
        $entity = $this->getDoctrine()
            ->getRepository($this->crudConfiguration['entityRepository'])
            ->find($id);

        $titleColumnFunctionName = 'get' . ucfirst($this->crudConfiguration['titleColumn']);

        // check for confirmation
        if ($request->get('confirmed') == '1') {
            // delete the entity
            $title = $entity->$titleColumnFunctionName();
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            // set deleted flash message
            $this->get('session')->getFlashBag()->add('flash', array(
                    'title' => 'Success!',
                    'content' => ucfirst($this->crudConfiguration['unitName']) . ' "' . $title . '" deleted.',
                    'type' => 'info',
                    'success' => 'true'
                ));

            return $this->redirect($this->generateUrl($this->crudConfiguration['afterDeleteRedirectPath']));
        }

        // set confirmation message
        $this->get('session')->getFlashBag()->add('flash', array(
                'title' => 'Are you sure?',
                'content' => 'This will permanently delete the term "' . $entity->$titleColumnFunctionName() . '"!',
                'type' => 'confirm',
                'success' => 'function (result) {
				if (result == "Yes") {
					window.location.href =  "' . $this->generateUrl($request->get('_route'), array("id" => $id, "confirmed" => 1)) . '";
				} else {
					window.location.href =  "' . $this->generateUrl($this->crudConfiguration['editPath'], array("id" => $id)) . '";
				}
			}'
            ));

        return $this->render($this->crudConfiguration['templateNamePrefix'] . 'delete.html.twig', array(
            'id' => $id,
            'entity' => $entity
        ));
    }
}