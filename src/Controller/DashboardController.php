<?php

namespace App\Controller;

use App\Repository\JarRepository;
use App\Repository\EmptyJarStatRepository;
use App\Repository\AuditLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(JarRepository $jarRepository, EmptyJarStatRepository $emptyRepository, AuditLogRepository $auditLogRepository): Response
    {
        $allJars = $jarRepository->findBy(
            [
                'status' => 'aktywny'
            ]
        );
        $emptyStats = $emptyRepository->findAll();
        $allLogs = $auditLogRepository->findAll();

        $popular = $jarRepository->getMostPopularContent();

        $latestJars = $jarRepository->findLatest(5);

        $latestLogs = $auditLogRepository->findBy([], ['createdAt' => 'DESC'], 4);

        return $this->render('dashboard/index.html.twig', [
            'jars' => $allJars,
            'jars_count' => count($allJars),
            'empty_stats' => count($emptyStats),
            'all_logs' => $allLogs,
            'popular_name' => $popular['name'] ?? 'Brak',
            'popular_amount' => $popular['amount'] ?? 0,
            'latest_jars' => $latestJars,
            'latest_logs' => $latestLogs,
        ]);
    }
}
