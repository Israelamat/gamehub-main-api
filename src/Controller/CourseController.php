<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/course')]
final class CourseController extends AbstractController
{
    #[Route(name: 'app_course_index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll();
        return $this->json($courses, 200, [], ['groups' => 'course:read']);
    }

    #[Route('/new', name: 'app_course_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();

        $course = new Course();
        $course->setTitle($data['title'] ?? 'Course without title');
        $course->setContent($data['content'] ?? null);
        $course->setPrice($data['price'] ?? 0);
        $course->setDuration($data['duration'] ?? 0);

        if ($user = $this->getUser()) {
            $course->setCreatedBy($user);
        }

        $entityManager->persist($course);
        $entityManager->flush();

        return $this->json($course, 201, [], ['groups' => 'course:read']);
    }

    #[Route('/{id}', name: 'app_course_show', methods: ['GET'])]
    public function show(Course $course): Response
    {
        return $this->json($course, 200, [], ['groups' => 'course:read']);
    }

    #[Route('/{id}/edit', name: 'app_course_edit', methods: ['PUT'])]
    public function edit(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();

        if (isset($data['title'])) {
            $course->setTitle($data['title']);
        }
        if (isset($data['content'])) {
            $course->setContent($data['content']);
        }
        if (isset($data['duration'])) {
            $course->setDuration($data['duration']);
        }
        if (isset($data['price'])) {
            $course->setPrice($data['price']);
        }

        $entityManager->flush();

        return $this->json(['message' => 'Course updated'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_course_delete', methods: ['DELETE'])]
    public function delete(Course $course, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($course);
        $entityManager->flush();

        return $this->json(['message' => 'Curso deleted'], Response::HTTP_NO_CONTENT);
    }
}
