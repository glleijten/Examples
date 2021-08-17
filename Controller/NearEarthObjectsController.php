<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\NearEarthObjectsService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NearEarthObjectsController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LoggerInterface */
    private $logger;

    /** @var NearEarthObjectsService */
    private $nearEarthObjectsService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param NearEarthObjectsService $nearEarthObjectsService
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        NearEarthObjectsService $nearEarthObjectsService,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->nearEarthObjectsService = $nearEarthObjectsService;
    }

    /**
     * @Route("/neow", name="neow")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAction()
    {
        try {
            //TODO: create method to search by keys
            $neowRequest = $this->nearEarthObjectsService->request();
            $nearEarthObjects = [];

            foreach ($neowRequest->near_earth_objects as $object) {
                $nearEarthObjects[] = $this->nearEarthObjectsService->normalize($object);
            }
        } catch (UniqueConstraintViolationException $exception) {
        }

        return $this->render(
            'neow/neow.html.twig',
            [
                'objects' => $nearEarthObjects,
            ]
        );
    }
}
