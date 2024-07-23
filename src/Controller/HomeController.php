<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\DetailRepository;
use App\Repository\HeaderProcessRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

//#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController{
    #[Route('', name:'home')]
    public function index(): Response{
        return $this->render(view:'home/index.html.twig');
    }
    #[Route('/header-processes', name: 'header_processes')]
    public function getHeaderProcesses(HeaderProcessRepository $headerProcessRepository, Request $request): JsonResponse
    {
        $startDate = new \DateTime($request->query->get('startDate'));
        $endDate = new \DateTime($request->query->get('endDate'));
        $headerProcesses = $headerProcessRepository->findByDateRange($startDate, $endDate);
        $data = [];

        foreach ($headerProcesses as $process) {
            $data[] = [
                'id' => $process->getId(),
                'executionDate' => $process->getExecutionDate()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/details/{headerProcessId}', name: 'details', methods: ['GET'])]
    public function getDetails(int $headerProcessId, DetailRepository $detailRepository): JsonResponse
    {
        $details = $detailRepository->findBy(['headerProcess' => $headerProcessId]);

        $data = array_map(function ($detail) {
            $detailData = json_decode($detail->getData(), true);

            return [
                'id' => $detailData['id'],
                'firstName' => $detailData['firstName'],
                'lastName' => $detailData['lastName'],
                'maidenName' => $detailData['maidenName'],
                'age' => $detailData['age'],
                'gender' => $detailData['gender'],
                'email' => $detailData['email'],
                'phone' => $detailData['phone'],
                'username' => $detailData['username'],
                'birthDate' => $detailData['birthDate'],
                'image' => $detailData['image'],
                'bloodGroup' => $detailData['bloodGroup'],
                'height' => $detailData['height'],
                'weight' => $detailData['weight'],
                'eyeColor' => $detailData['eyeColor'],
                'hairColor' => $detailData['hair']['color'],
                'hairType' => $detailData['hair']['type'],
                'ip' => $detailData['ip'],
                'address' => $detailData['address']['address'],
                'city' => $detailData['address']['city'],
                'state' => $detailData['address']['state'],
                'stateCode' => $detailData['address']['stateCode'],
                'postalCode' => $detailData['address']['postalCode'],
                'country' => $detailData['address']['country'],
                'macAddress' => $detailData['macAddress'],
                'university' => $detailData['university'],
                'bankDetails' => [
                    'cardNumber' => $detailData['bank']['cardNumber'],
                    'cardExpire' => $detailData['bank']['cardExpire'],
                    'cardType' => $detailData['bank']['cardType'],
                    'currency' => $detailData['bank']['currency'],
                    'iban' => $detailData['bank']['iban'],
                ],
                'companyDetails' => [
                    'company' => $detailData['company']['name'],
                    'department' => $detailData['company']['department'],
                    'title' => $detailData['company']['title'],
                    'companyAddress' => $detailData['company']['address']['address'] . ', ' .
                        $detailData['company']['address']['city'] . ', ' .
                        $detailData['company']['address']['state'] . ', ' .
                        $detailData['company']['address']['postalCode'],
                ],
                'ein' => $detailData['ein'],
                'ssn' => $detailData['ssn'],
                'userAgent' => $detailData['userAgent'],
                'cryptoDetails' => [
                    'coin' => $detailData['crypto']['coin'],
                    'wallet' => $detailData['crypto']['wallet'],
                    'network' => $detailData['crypto']['network'],
                ],
                'role' => $detailData['role'],
            ];
        }, $details);

        return new JsonResponse($data);
    }
}