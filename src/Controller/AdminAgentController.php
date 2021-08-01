<?php

namespace App\Controller;

use App\Service\SendMessage;
use Doctrine\ORM\Mapping\Id;
use App\Repository\AdminAgentRepository;
use App\Repository\ProfileRepository;
use App\Service\UpdateEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminAgentController extends AbstractController
{
    private $encoder;
    private $denormalizer;
    private $sendMessage;
    private $mailer;
    private $agentRepo;
    private $updateService;
    private $profileRepo;


    public function __construct(
        UserPasswordEncoderInterface $encoder,
        DenormalizerInterface $denormalizer,
        SendMessage $sendMessage,
        \Swift_Mailer $mailer,
        AdminAgentRepository $agentRepo,
        UpdateEntity $updateService,
        ProfileRepository $profileRepo


    ) {
        $this->encoder = $encoder;
        $this->denormalizer = $denormalizer;
        $this->sendMessage = $sendMessage;
        $this->mailer = $mailer;
        $this->agentRepo = $agentRepo;
        $this->updateService = $updateService;
        $this->profileRepo = $profileRepo;
    }


    /**
     * @Route(
     * path="/api/agent",
     * name="agent_add",
     * methods={"POST"}),
     * defaults={
     *  "__controller="\App\AdminAgentController::AdminAdd",
     *  "__api_resource_class"=AdminAgent::class,
     *  "__api_collection_operation_name"="adminAgent_read"
     * }
     */
    public function AgentAdd(Request $request, EntityManagerInterface $entityManager)
    {
        // if (!$this->security->isGranted('ROLE_ADMINSYSTEME')) {
        //     return $this->json("seul les admins peuvent avoir accés a cette ressource", 400);
        // }
        //get data without file format
        $data = $request->request->all();

        //if file avatar is required
        if ($request->files->get("avatar")) {
            $data["avatar"] = fopen(($request->files->get("avatar")), "rb");
        }

        //set profile admin agent
        $profile = $this->profileRepo->findOneByName("ADMINAGENT");


        //denormalize to transform json data in a object AminSysteme  
        $adminAgent = $this->denormalizer->denormalize($data, 'App\Entity\AdminAgent');
        $adminAgent->setProfile($profile);

        //Générate code unique
        $codeFullName = strtoupper($adminAgent->getFirstname()[0] . '' . $adminAgent->getLastname()[0]);
        $codeDate = date('dmy');

        $codeUser = $codeFullName . '-' . $codeDate;
        $adminAgent->setCodeUser($codeUser);

        //use password Eencoder to hash password
        $adminAgent->setPassword($this->encoder->encodePassword($adminAgent, $adminAgent->getPassword()));

        //writte data valide in database

        $entityManager->persist($adminAgent);
        $entityManager->flush();

        //send massage to user;an accound is create form him
        $this->sendMessage->sendMessageActivatorAccount($this->mailer, $adminAgent);


        return new JsonResponse(["message" => "Compte admin agent est créé avec succés"]);
    }


    /**
     * @Route(
     * path="/api/agent",
     * name="agents_get",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\AdminAgentController::getAgents",
     *  "__api_resource_class"=AdminAgent::class,
     *  "__api_collection_operation_name"="adminAgent_read"
     * }
     */
    public function getAgents()
    {
        // if (!$this->security->isGranted('ROLE_ADMINSYSTEME')) {
        //     return $this->json("seul les admins peuvent avoir accés a cette ressource", 400);
        // }
        $users = $this->agentRepo->findAll();
        return $this->json($users, 200, [], ['groups' => 'adminAgent_read']);
    }


    /**
     * @Route(
     * path="/api/agent/{codeUser}",
     * name="get_agent_codeUser",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\AdminAgentController::getAgent",
     *  "__api_resource_class"=AdminAgent::class,
     *  "__api_item_operation_name"="adminAgent_read"
     * }
     */
    public function getAgent($codeUser)
    {
        // if (!$this->security->isGranted('ROLE_ADMINSYSTEME')) {
        //     return $this->json("seul les admins peuvent avoir accés a cette ressource", 400);
        // }

        $agent = $this->agentRepo->findOneByCodeUser($codeUser);
        if ($agent) {
            if ($agent->getStatus() === false) {
                return $this->json($agent, 200, [], ['groups' => 'adminAgent_read']);
            } else {
                return $this->json("cet admin est inactif ", 400);
            }
        } else {
            return $this->json("agent introuveable", 400);
        }
    }


    /**
     * @Route(
     * path="/api/agent/{codeUser}",
     * name="agents_delete_by_codeUser",
     * methods={"DELETE"}),
     * defaults={
     *  "__controller="\App\AdminAgentController::delete",
     *  "__api_resource_class"=AdminAgent::class,
     *  "__api_item_operation_name"="adminAgent_read"
     * }
     */
    public function delete($codeUser, EntityManagerInterface $entityManager)
    {
        $admin = $this->agentRepo->findOneByCodeUser($codeUser);
        if ($admin) {
            if ($admin->getStatus() === false) {
                $admin->setStatus(true);
                $entityManager->persist($admin);
                $entityManager->flush();
                $response = $this->json($admin, 200, [], ['groups' => 'adminAgent_read']);
            } else {
                $response = $this->json("cet admin est inactif ", 400);
            }
        } else {
            $response = $this->json("admin introuveable", 400);
        }
        return $response;
    }


    /**
     * @Route(
     * path="/api/agent/{codeUser}",
     * name="agent_update_by_codeUser",
     * methods={"PUT"}),
     * defaults={
     *  "__controller="\App\AdminAgentController::edit",
     *  "__api_resource_class"=AdminAgent::class,
     *  "__api_item_operation_name"="edite_adminAgent_read"
     * }
     */
    public function edit($codeUser, Request $request, string $fileName = null, EntityManagerInterface $entityManager)
    {

        // if (!$this->security->isGranted('ROLE_ADMINSYSTEME')) {
        //     return $this->json("seul les admins peuvent avoir accés a cette ressource", 400);
        // }

        $agent = $this->agentRepo->findOneByCodeUser($codeUser);
        if ($agent) {
            $data = $this->updateService->update($request, $fileName);

            foreach ($data as $key => $value) {
                if ($key !== 'profile') {
                    $method = 'set' . ucfirst($key);
                    if (method_exists($agent, $method)) {
                        if ($key == 'password') {
                            $agent->$method($this->encoder->encodePassword($agent, $value));
                        } else {
                            $agent->$method($value);
                        }
                    }
                }
            }


            $entityManager->persist($agent);
            $entityManager->flush();
            return $this->json($agent);
        }
    }
}
