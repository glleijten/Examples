<?php declare(strict_types=1);

namespace App\Controller;

use App\Form\ApodType;
use App\Service\ApodService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ApodService */
    private $apodService;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ApodService $apodService
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApodService $apodService,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->apodService = $apodService;
        $this->logger = $logger;
    }

    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ApodType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $apod = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($apod);
            $entityManager->flush();

            return $this->redirectToRoute('task_success');
        }

        return $this->render('apod/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /*
     * Method to create API call and store APOD data
     */
    public function newApod($response): Response
    {
        try {
            $response = $this->apodService->getData();
            $apod = $this->apodService->create($response);
            $this->entityManager->persist($apod);
            $this->entityManager->flush();
        } catch (InvalidArgumentException $e) {
            $this->logger->info(sprintf('Apod for date %d already exists in database. Skipping.', $response->date));
        }

        return $this->render(
            'index/index.html.twig',
            [
                'response' => $response,
            ]
        );
    }
}
