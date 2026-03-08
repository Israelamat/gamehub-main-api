<?php

namespace App\Controller;

use App\Service\CourseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/course')]
final class CourseController extends AbstractController
{
    public function __construct(
        private readonly CourseService $courseService
    ) {}

    #[Route('', name: 'app_course_index', methods: ['GET'])]
    public function index(): Response
    {
        $courses = $this->courseService->getCourses();
        return $this->json($courses, 200, [], ['groups' => 'course:read']);
    }

    #[Route('', name: 'app_course_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $course = $this->courseService->createCourse(
            $request->toArray(),
            $this->getUser()
        );

        return $this->json(['message' => 'Course created', 'id' => $course->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_course_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $course = $this->courseService->getCourseById($id);
        return $this->json($course, 200, [], ['groups' => 'course:read']);
    }

    #[Route('/{id}', name: 'app_course_edit', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request): Response
    {
        $this->courseService->updateCourse($id, $request->toArray());
        return $this->json(['message' => 'Course updated']);
    }

    #[Route('/{id}', name: 'app_course_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $this->courseService->deleteCourse($id);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
