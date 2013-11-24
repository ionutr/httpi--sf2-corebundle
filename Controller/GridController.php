<?php

namespace Httpi\Maritime\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\QueryBuilder;

use Httpi\Bundle\CoreBundle\Library\Info\Info as InfoLib;
use Httpi\Bundle\CoreBundle\Entity\Info;
use Httpi\Bundle\CoreBundle\Entity\Import;
use Httpi\Bundle\CoreBundle\Form\ImportType;

use Httpi\Maritime\Bundle\CoreBundle\Entity\Lexicon;
use Httpi\Maritime\Bundle\AdminBundle\Form\Type\LexiconType;

use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\DateTimeColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Export\CSVExport;

class CrudController extends Controller
{
    private $entityName = 'lexicon';

    private $entityFqn = 'HttpiMaritimeCoreBundle:Lexicon';

    public function indexAction(Request $request)
    {
        // get repository
        $repository = $this->getDoctrine()
            ->getRepository('HttpiMaritimeCoreBundle:Lexicon');

        // init querybuilder
        $queryBuilder = $repository->createQueryBuilder('l')
            ->leftJoin('l.info', 'i')
            ->leftJoin('l.import', 'imp')
            ->addSelect('i.created_at')
            ->addSelect('imp.title')
            ->orderBy('i.created_at', 'DESC');

        // Creates simple grid based on your entity (ORM)
        $source = new Entity('HttpiMaritimeCoreBundle:Lexicon');
        $source->initQueryBuilder($queryBuilder);

        // Get a grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);

        // add export
        $grid->addExport(new CSVExport("Full Export", 'lexicon-' . date("Y-M-d_H-i-s"), array(), 'UTF-8'));

        // Configuration of the grid
        $columns = $grid->getColumns();
        $columns->setColumnsOrder(array(
                "id",
                "abbr",
                "value",
                "isVerified",
                "isPublished"
            ));

        // add created at column
        $createdAtColumn = new DateTimeColumn(array(
            'field' => 'created_at',
            'id' => 'created_at',
            'title' => 'Created at',
            'size' => 110
        ));
        $columns->addColumn($createdAtColumn, 4);

        // import column
        $importColumn = new TextColumn(array(
            'field' => 'title',
            'id' => 'title',
            'title' => 'Import',
            'size' => 50,
            'filterable' => false
        ));
        $columns->addColumn($importColumn);

        // fine-tunes
        $columns->getColumnById('id')->setSize(30)->setTitle('Id')->setFilterable(false);
        $columns->getColumnById('abbr')->setSize(150)->setTitle('Abbreviation');
        $columns->getColumnById('value')->setSize(400)->setTitle('Content');
        $columns->getColumnById('isVerified')->setSize(20)->setTitle('V');
        $columns->getColumnById('isPublished')->setSize(20)->setTitle('P');
        $columns->getColumnById('isImported')->setSize(20)->setTitle('I');

        $grid->setColumns($columns);
        $grid->hideColumns(array(
                //"value"
            ));

        $grid->setDefaultOrder("created_at", "desc");

        // Create an Actions Column
        $actionsColumn = new ActionsColumn('actions_column', 'Actions');
        $actionsColumn->setSize(35);
        $actionsColumn->setSeparator("<br />");
        $grid->addColumn($actionsColumn, 9);

        //$grid->setId('lexicon-datagrid');

        // Attach a rowAction to the Actions Column
        $rowActionEdit = new RowAction('Edit', 'httpi_maritime_admin_lexicon_edit');
        $rowActionEdit->setColumn('actions_column');
        /*$rowActionEdit->addRouteParameters(array(
                "grid_id" => $grid->getId(),
                "page" => $grid->getPage()
        ));*/
        $grid->addRowAction($rowActionEdit);

        // Manage the grid redirection, exports and the response of the controller
        return $grid->getGridResponse('HttpiMaritimeAdminBundle:Default:index.html.twig', array(
            'sectionTitle' => "Lexicon"
        ));
    }

    public function addAction(Request $request)
    {
        $lexicon = new Lexicon;
        $lexicon->setIsImported(false);
        $lexicon->setIsVerified(false);
        $lexicon->setIsPublished(false);

        $form = $this->createForm(new LexiconType(), $lexicon);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $lexicon = $form->getData();
                $lexicon->setInfo(InfoLib::stamp(new Info()));

                $em = $this->getDoctrine()->getManager();
                $em->persist($lexicon);
                $em->flush();

                $this->get('session')->getFlashBag()->add('flash', array(
                        "title" => "Success!",
                        "content" => 'You have successfully added a new term to the lexicon!',
                        "type" => "info",
                        "success" => true
                    ));

                return $this->redirect($this->generateUrl('httpi_maritime_admin_lexicon_add'));
            } else {
                $this->get('session')->getFlashBag()->add('flash', array(
                        "title" => "Failed!",
                        "content" => 'Please correct the error(s)!',
                        "type" => "error",
                        "success" => true
                    ));
            }
        }

        return $this->render('HttpiMaritimeAdminBundle:Lexicon:add.html.twig', array(
                'form' => $form->createView()
            ));

    }

    public function editAction(Request $request)
    {
        $id = $request->get('id');

        //@TODO: verify id is int here

        $lexicon = $this->getDoctrine()
            ->getRepository('HttpiMaritimeCoreBundle:Lexicon')
            ->find($id);

        $form = $this->createForm(new LexiconType(), $lexicon);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $lexicon = $form->getData();
                $lexicon->setInfo(InfoLib::stamp($lexicon->getInfo()));

                $em = $this->getDoctrine()->getManager();
                $em->persist($lexicon);
                $em->persist($lexicon->getInfo());
                $em->flush();

                $this->get('session')->getFlashBag()->add('flash', array(
                        "title" => "Success!",
                        "content" => 'You have successfully updated a term in the lexicon!',
                        "type" => "info",
                        "success" => true
                    ));

                return $this->redirect($this->generateUrl('httpi_maritime_admin_lexicon_edit', array("id" => $id)));
            } else {
                $this->get('session')->getFlashBag()->add('flash', array(
                        "title" => "Failed!",
                        "content" => 'Please correct the error(s)!',
                        "type" => "error",
                        "success" => true
                    ));
            }
        }

        return $this->render('HttpiMaritimeAdminBundle:Lexicon:edit.html.twig', array(
                'form' => $form->createView(),
                'id' => $id
            ));

    }

    public function deleteAction(Request $request)
    {
        // get id/entity
        $id = $request->get('id');
        $lexicon = $this->getDoctrine()
            ->getRepository('HttpiMaritimeCoreBundle:Lexicon')
            ->find($id);

        // check for confirmation
        if ($request->get('confirmed') == '1') {
            // delete the entity
            $title = $lexicon->getAbbr();
            $em = $this->getDoctrine()->getManager();
            $em->remove($lexicon);
            $em->flush();

            // set deleted flash message
            $this->get('session')->getFlashBag()->add('flash', array(
                    'title' => 'Success!',
                    'content' => 'Term "' . $title . '" deleted.',
                    'type' => 'info',
                    'success' => 'true'
                ));

            return $this->redirect($this->generateUrl('httpi_maritime_admin_lexicon_index'));
        }

        // set confirmation message
        $this->get('session')->getFlashBag()->add('flash', array(
                'title' => 'Are you sure?',
                'content' => 'This will permanently delete the term "' . $lexicon->getAbbr() . '"!',
                'type' => 'confirm',
                'success' => 'function (result) {
				if (result == "Yes") {
					window.location.href =  "' . $this->generateUrl('httpi_maritime_admin_lexicon_delete', array("id" => $id, "confirmed" => 1)) . '";
				} else {
					window.location.href =  "' . $this->generateUrl('httpi_maritime_admin_lexicon_edit', array("id" => $id)) . '";
				}
			}'
            ));

        return $this->render('HttpiMaritimeAdminBundle:Lexicon:delete.html.twig', array(
                'id' => $id,
                'lexicon' => $lexicon
            ));
    }

    public function importAction(Request $request)
    {
        $import = new Import;
        $import->setTitle(date("Y-m-d H:i:s"));
        $import->setDescription("Lexicon Import Process");
        //$import->setInfo(InfoLib::stamp(new Info()));
        $import->setObjectFqn('Httpi\Maritime\Bundle\CoreBundle\Entity\Lexicon');

        $form = $this->createForm(new ImportType(), $import)->createView();

        return $this->render('HttpiMaritimeAdminBundle:Lexicon:import.html.twig', array(
                'form' => $form
            ));
    }
}