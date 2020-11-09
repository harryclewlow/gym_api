<?php

namespace App\Controller;

use App\Entity\Gym;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;

class GymController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/gym")
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $repo = $this->getDoctrine()->getRepository(Gym::class);

        $serializer = SerializerBuilder::create()->build();

        $gyms = $serializer->toArray(
            $repo->findAll(),
            SerializationContext::create()->setGroups('list_gyms')
        );

        return $this->json($gyms);
    }
}
