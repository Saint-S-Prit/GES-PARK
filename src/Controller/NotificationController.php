<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    private $notifRepo;


    public function __construct(
        NotificationRepository $notifRepo


    ) {
        $this->notifRepo = $notifRepo;
    }
    /**
     * @Route(
     * path="/api/notification",
     * name="get_notification",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\NotificationController::getNotification",
     *  "__api_resource_class"=Notification::class,
     *  "__api_item_operation_name"="notification_read"
     * }
     */
    public function getNotification()
    {
        $notification = $this->notifRepo->findAll();
        return $this->json($notification, 200, [], ['groups' => 'notification_read']);
    }
}
