<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailController extends AbstractController
{
    #[Route('/api/contact', name: 'api_contact', methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer): JsonResponse
    {
        $adminAddress = $this->getParameter('app.admin_email');
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? null;
        $userEmail = $data['email'] ?? null;
        $subject = $data['subject'] ?? null;
        $messageContent = $data['message'] ?? null;

        // Validation
        if (!$name || !$userEmail || !$subject || !$messageContent) {
            return $this->json([
                'success' => false,
                'error' => 'All fields are required'
            ], 400);
        }

        try {
            $adminEmail = (new Email())
                ->from('noreply@gamehub.com')
                ->to($adminAddress)
                ->subject('New Contact Message: ' . $subject)
                ->text(
                    "Name: $name\n" .
                        "Email: $userEmail\n\n" .
                        "Message:\n$messageContent"
                );

            $userResponse = (new Email())
                ->from('noreply@gamehub.com')
                ->to($userEmail)
                ->subject('We have received your message')
                ->text(
                    "Hello $name,\n\n" .
                        "Thank you for reaching out. We have received your message:\n\n" .
                        "\"$messageContent\"\n\n" .
                        "We will get back to you shortly."
                );

            $mailer->send($adminEmail);
            $mailer->send($userResponse);

            return $this->json([
                'success' => true,
                'message' => 'Message sent successfully'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Could not send the email',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
