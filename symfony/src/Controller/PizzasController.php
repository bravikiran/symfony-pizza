<?php

namespace App\Controller;

use App\Entity\Pizzas;
use App\Entity\Properties;
use App\Repository\PropertiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;


class PizzasController extends AbstractController
{
    /**
     * @Route("/api/pizzas", methods={"GET"})
     *
     * @OA\Get (
     *     path="/pizzas/",
     *     summary="List of all pizzas",
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns all Pizzas",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Pizzas::class, groups={"full"})),
     *        @OA\Items(ref=@Model(type=Properties::class, groups={"full"}))
     *     )
     * )
     *
     * @OA\Tag(name="Pizzas")
     * @Security(name="Bearer")
     */
    public function fetchPizzas(PropertiesRepository $propertiesRepository): Response
    {
        $pizzas = $this->getDoctrine()->getRepository(Pizzas::class)->findAll();

        $data = [];
        foreach ($pizzas as $pizza) {
            $pizzaProperties = $propertiesRepository->findBy(['pizza' => $pizza]);
            $pizzaProperty = [];
            foreach ($pizzaProperties as $property) {
                $pizzaProperty[] = $property->getProperty();
            }
            $data[] = [
                'id' => $pizza->getId(),
                'name' => $pizza->getName(),
                'price' => $pizza->getPrice(),
                'properties' => $pizzaProperty,
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/pizza/{id}", methods={"GET"})
     *
     * @OA\Get (
     *     path="/pizza/{id}",
     *     summary="Gets one pizza details",
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the Pizza",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Pizzas::class, groups={"full"}))
     *     )
     * )
     *
     * @OA\Tag(name="Pizzas")
     * @Security(name="Pizzas")
     */
    public function fetchPizza(int $id, PropertiesRepository $propertiesRepository): JsonResponse
    {
        $pizza = $this->getDoctrine()
            ->getRepository(Pizzas::class)
            ->find($id);

        if (!$pizza) {
            throw $this->createNotFoundException(
                'No pizza found for id '. $id
            );
        }

        $pizzaProperties = $propertiesRepository->findBy(['pizza' => $pizza]);

        $pizzaProperty = [];
        foreach ($pizzaProperties as $property) {
            $pizzaProperty[] = $property->getProperty();
        }

        $data = [
            'id' => $pizza->getId(),
            'name' => $pizza->getName(),
            'price' => $pizza->getPrice(),
            'properties' => $pizzaProperty
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/pizzas/create", methods={"POST"})
     *
     * @OA\Post(
     *     path="/api/pizza",
     *     summary="Adds new pizza",
     *     tags={"pizza"},
     *     summary="stored Pizza",
     *     operationId="Pizza saved",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Pizzas"),
     *         @OA\XmlContent(ref="#/components/schemas/Pizzas")
     *     ),
     *     @OA\RequestBody(
     *         description="Creatin New Pizza Optipn",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Pizzas")
     *     )
     * )
     * @OA\Parameter(
     *     name="name",
     *     in="query",
     *     required=true,
     *     description="The field used to order rewards",
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *     name="price",
     *     in="query",
     *     required=true,
     *     description="Price in decimal",
     *     @OA\Schema(type="number")
     * )
     *
     *
     * * @OA\Parameter(
     *     name="propertyname",
     *     in="query",
     *     description="Add Property",
     *     @OA\Items(type="string")
     * )
     *
     * @OA\Tag(name="Pizzas")
     * @Security(name="Pizzas")
     */
    public function createPizzas(Request $request): JsonResponse
    {
        $name = $request->get("name");
        $price = $request->get("price");
        $property = $request->get("propertyname");

        // Pizza details
        $pizza = new Pizzas();
        $pizza->setName($name);
        $pizza->setPrice($price);

        // Pizza Property Details
        $pizzaProperty = new Properties();
        $pizzaProperty->setProperty($property);
        $pizzaProperty->setPizza($pizza);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pizza);
        $entityManager->persist($pizzaProperty);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse('Pizza added', Response::HTTP_OK);
    }


    /**
     * @Route("/api/pizza/{id}", methods={"PUT"})
     *
     * @OA\Put(
     *     path="/api/pizza/{id}",
     *     summary="Updates the pizza",
     *     @OA\Parameter(
     *         description="Parameter with Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Pizza Id not found"
     *     )
     * )
     * @OA\Parameter(
     *     name="name",
     *     in="query",
     *     description="The field used to order rewards",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="price",
     *     in="query",
     *     description="Price in decimal",
     *     @OA\Schema(type="number")
     * )
     *
     * @OA\Parameter(
     *     name="property",
     *     in="query",
     *     description="Add Property",
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Tag(name="Pizzas")
     * @Security(name="Pizzas")
     */
    public function updatePizzas($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $pizza = $entityManager->getRepository(Pizzas::class)
            ->findOneBy(['id' => $id]);

        $properties = $entityManager->getRepository(Properties::class)
            ->findOneBy(['pizza' => $pizza]);

        $property = $request->get("property");
        $properties->setProperty($property);

        if (!$pizza) {
            throw $this->createNotFoundException(
                'No pizza found' . $id
            );
        }

        $name = $request->get("name");
        $pizza->setName($name);

        $price = $request->get("price");
        $pizza->setPrice($price);


        $pizza->addProperty($properties);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($pizza);
        $entityManager->persist($properties);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new JsonResponse(['status' => 'Pizza Updated'], Response::HTTP_OK);

    }

    /**
     * @Route("/api/pizza/{id}", methods={"DELETE"})
     *
     * @OA\Delete(
     *     path="/api/pizza/{ID}",
     *     summary="Delete the pizza",
     *     tags={"pizza"},
     *     summary="Delete purchase order by ID",
     *     description="For valid response try integer IDs with positive integer value. Negative or non-integer values will generate API errors",
     *     operationId="deleteOrder",
     *     @OA\Parameter(
     *         name="ID",
     *         in="path",
     *         required=true,
     *         description="ID of the order that needs to be deleted",
     *         @OA\Schema(
     *             type="array",
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * ),
     *
     * @OA\Response(
     *     response=200,
     *     description="Deleted Pizza",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Pizzas::class, groups={"full"}))
     *     )
     * )
     *
     * @OA\Tag(name="Pizzas")
     * @Security(name="Pizzas")
     */
    public function deletePizza($id): JsonResponse
    {
        // get EntityManager
        $entityManager = $this->getDoctrine()
            ->getManager();
        $pizza = $entityManager->getRepository(Pizzas::class)
            ->findBy(['id'=> $id]);

        $properties = $entityManager->getRepository(Properties::class)
            ->findOneBy(['pizza' => $pizza]);

        // Remove it and flush
        $entityManager->remove($pizza[0]);
        $entityManager->remove($properties);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Pizza deleted'], Response::HTTP_NO_CONTENT);
    }
}
