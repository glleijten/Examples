<?= "<?php\n"; ?>

namespace App\Controller;

use <?= $entity_fqcn; ?>;
use App\Form\<?= $form_class_name; ?>;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("<?= $route_path; ?>", name="<?= $route_name; ?>_")
 */
class <?= $controller_class_name; ?> extends Controller
{
    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index()
    {
        $<?= $entity_var_plural; ?> = $this->getDoctrine()
            ->getRepository(<?= $entity_class_name; ?>::class)
            ->findAll();

        return $this->render('<?= $route_name; ?>/index.html.twig', ['<?= $entity_var_plural; ?>' => $<?= $entity_var_plural; ?>]);
    }

    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $<?= $entity_var_singular; ?> = new <?= $entity_class_name; ?>();
        $form = $this->createForm(<?= $form_class_name; ?>::class, $<?= $entity_var_singular; ?>);
        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($<?= $entity_var_singular; ?>);
            $em->flush();

            return $this->redirectToRoute('<?= $route_name; ?>_index');
        }

        return $this->render('<?= $route_name; ?>/new.html.twig', [
            '<?= $entity_var_singular; ?>' => $<?= $entity_var_singular; ?>,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{<?= $entity_identifier; ?>}", name="show")
     * @Method("GET")
     */
    public function show(<?= $entity_class_name; ?> $<?= $entity_var_singular; ?>)
    {
        $deleteForm = $this->createDeleteForm($<?= $entity_var_singular; ?>);
        return $this->render('<?= $route_name; ?>/show.html.twig', [
            '<?= $entity_var_singular; ?>' => $<?= $entity_var_singular; ?>,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{<?= $entity_identifier; ?>}/edit", name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, <?= $entity_class_name; ?> $<?= $entity_var_singular; ?>)
    {
        $deleteForm = $this->createDeleteForm($<?= $entity_var_singular; ?>);
        $editForm = $this->createForm(<?= $form_class_name; ?>::class, $<?= $entity_var_singular; ?>);
        $editForm->add('submit', SubmitType::class, array('label' => 'Update'));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('<?= $route_name; ?>_edit', ['<?= $entity_identifier; ?>' => $<?= $entity_var_singular; ?>->get<?= ucfirst($entity_identifier); ?>()]);
        }

        return $this->render('<?= $route_name; ?>/edit.html.twig', [
            '<?= $entity_var_singular; ?>' => $<?= $entity_var_singular; ?>,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{<?= $entity_identifier; ?>}", name="delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, <?= $entity_class_name; ?> $<?= $entity_var_singular; ?>)
    {
        $form = $this->createDeleteForm($<?= $entity_var_singular; ?>);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($<?= $entity_var_singular; ?>);
            $entityManager->flush();
        }
        return $this->redirectToRoute('<?= $route_name; ?>_index');
    }

    /**
     * @return FormInterface
     */
    private function createDeleteForm( <?= $entity_class_name; ?> $<?= $entity_var_singular; ?>)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('<?= $route_name; ?>_delete', ['<?= $entity_identifier; ?>' => $<?= $entity_var_singular; ?>->get<?= ucfirst($entity_identifier); ?>()]))
        ->setMethod('DELETE')
        ->add('submit', SubmitType::class, array('label' => 'Delete'))
        ->getForm();
    }
}