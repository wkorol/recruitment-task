<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\JsonResponse;
use App\Services\NewsRepository;
use PDOException;

readonly class NewsController
{
    public function __construct(private NewsRepository $newsRepository)
    {
    }

    public function getNewsById(int $id): JsonResponse
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!is_numeric($id)) {
                return new JsonResponse(['error' => 'Error while retrieving news, id is not a number'], 500);
            }

            try {
                $news = $this->newsRepository->getNewsById($id);

                if ($news === null) {
                    return new JsonResponse(['error' => 'News not found'], 404);
                }
            } catch (PDOException $e) {
                return new JsonResponse(['error' => $e->getMessage()], 500);
            }

            return new JsonResponse([$news], 200);
        }

        return JsonResponse::wrongRequestMethod($_SERVER['REQUEST_METHOD']);
    }

    public function deleteNewsById(int $id): JsonResponse
    {
       if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
           if (!is_numeric($id)) {
               return new JsonResponse(['error' => 'Error while deleting  news, id is not a number'], 500);
           }

           try {
               $this->newsRepository->deleteNewsById($id);
           } catch (PDOException $e) {
               return new JsonResponse(['error' => $e->getMessage()], 500);
           }
           return new JsonResponse(['message' => 'News deleted'], 200);
       }
        return JsonResponse::wrongRequestMethod($_SERVER['REQUEST_METHOD']);
    }

    public function getAllNewses(): JsonResponse
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $allNewses = $this->newsRepository->getAllNews();
            } catch (PDOException $e) {
                return new JsonResponse(['error' => $e->getMessage()], 500);
            }

            return new JsonResponse($allNewses, 200);
        }

        return JsonResponse::wrongRequestMethod($_SERVER['REQUEST_METHOD']);
    }

    public function createNews(): JsonResponse
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['title']) || !isset($input['description'])) {
            return new JsonResponse(['error' => 'Title or Description is empty'], 500);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $newsId = $this->newsRepository->createNews($input['title'], $input['description']);
            } catch (PDOException $e) {
                return new JsonResponse(['error' => $e->getMessage()], 500);
            }
            return new JsonResponse(['message' => 'News with id '. $newsId . ' has been created'], 200);
        }
        return JsonResponse::wrongRequestMethod($_SERVER['REQUEST_METHOD']);
    }

    public function updateNews(int $id): JsonResponse
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['title']) || !isset($input['description'])) {
            return new JsonResponse(['error' => 'Title or Description is empty'], 500);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            try {
                $newsId = $this->newsRepository->updateNews($id, $input['title'], $input['description']);
            } catch (PDOException $e) {
                return new JsonResponse(['error' => $e->getMessage()], 500);
            }
            if ($newsId !== 0) {
                return new JsonResponse(['message' => 'News with id '. $newsId . ' has been updated'], 200);
            }
            return new JsonResponse(['error' => 'Invalid news id'], 400);

        }

        return JsonResponse::wrongRequestMethod($_SERVER['REQUEST_METHOD']);
    }
}