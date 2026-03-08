<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\User;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CourseService
{
    public function __construct(
        private readonly CourseRepository $repository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function getCourses(): array
    {
        return $this->repository->findAllSafe();
    }

    public function getCourseById(int $id): Course
    {
        $course = $this->repository->findById($id);
        if (!$course) {
            throw new NotFoundHttpException("Course not found");
        }
        return $course;
    }

    public function createCourse(array $data, ?User $currentUser): Course
    {
        $course = new Course();
        $course->setTitle($data['title'] ?? 'Course without title');
        $course->setContent($data['content'] ?? null);
        $course->setPrice($data['price'] ?? 0);
        $course->setDuration($data['duration'] ?? 0);

        $user = $currentUser ?? $this->entityManager->getRepository(User::class)->find($data['user_id'] ?? 0);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $course->setCreatedBy($user);

        return $this->repository->create($course);
    }

    public function updateCourse(int $id, array $data): Course
    {
        $course = $this->getCourseById($id);

        if (isset($data['title'])) $course->setTitle($data['title']);
        if (isset($data['content'])) $course->setContent($data['content']);
        if (isset($data['price'])) $course->setPrice($data['price']);
        if (isset($data['duration'])) $course->setDuration($data['duration']);

        return $this->repository->update($course, $data);
    }

    public function deleteCourse(int $id): void
    {
        $course = $this->getCourseById($id);
        $this->repository->delete($course);
    }
}
