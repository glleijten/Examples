<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\ApodService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function index()
    {
        try {
            $response = $this->apodService->getData();
            $apod = $this->apodService->create($response);
            $this->entityManager->persist($apod);
            $this->entityManager->flush();
        } catch (InvalidArgumentException $e) {
            $this->logger->info('Apod for date already exists in database. Skipping.');
        }

        return $this->render(
            'index/index.html.twig',
            [
                'response' => $response,
            ]
        );
    }
}
